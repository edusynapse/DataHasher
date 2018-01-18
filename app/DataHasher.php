<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//! Front-end processor
class DataHasher extends Controller {

        function error($f3,$args) {
                echo "There was an error";

        }

	//! Display content page
	function showhome($f3,$args) {
		$db=$this->db;
		

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setCellValue('A1', 'Hello World !');

		$writer = new Xlsx($spreadsheet);
		//$writer->save('uploads/helloworld.xlsx');
					
		// Render HTML layout
		$f3->set('title', $f3->get('site'));

		$f3->set('head','header.htm');		
		$f3->set('inc','datahasher.htm');
		
		//echo Template::instance()->render('layout.htm');
	}


	function hashexcel($f3,$args) {
		$db=$this->db;
		$target_dir = "uploads/";
		$target_file = $target_dir . basename($_FILES["file"]["name"]);
		$uploadOk = 1;
		$extension = end(explode('.', $_FILES['file']['name']));
		// Check if image file is a actual image or fake image
		if($extension == 'xlsx') {
			//echo 'ok';
			move_uploaded_file( $_FILES['file']['tmp_name'], $target_file );
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($target_file );
    			$worksheet = $spreadsheet->getActiveSheet();
			$rownum = 1;
			$hash = [];
			$lastrow = false;
			//echo '<table>' . PHP_EOL;
			$lastcolumn = 0;
			foreach ($worksheet->getRowIterator() as $row) {
    				//echo '<tr>' . PHP_EOL;
    				$cellIterator = $row->getCellIterator();
				if ($rownum>1) {$cellIterator->setIterateOnlyExistingCells(FALSE);}
				else {$cellIterator->setIterateOnlyExistingCells(TRUE);}
							 // This loops through all cells,
                                                       //    even if a cell value is not set.
                                                       // By default, only cells that have a value
                                                       //    set will be iterated.
				$cellnum = 1;
    				foreach ($cellIterator as $cell) {
					$cellvalue = $cell->getValue();
					if ($lastcolumn > 0 && $cellnum > $lastcolumn && $rownum>1) break; 
					if (is_null($cell) && $cellnum == 1) {
						//last row
						$lastrow = true;
						break;
					}
					if ($rownum == 1) {
                                                //last column
						$lastcolumn = $cellnum;			
                                        }	
					if ($rownum==1) {
						if (strpos($cell->getValue(), '#') !== false) {
							$hash[$cellnum] = 1;
						} else {
							$hash[$cellnum] = 0;
						}
				
					
					} else {
						if ($hash[$cellnum] == 1) {
							$cell->setValue(md5($cell->getValue()));
						} 
					}
        				/**echo '<td>' .
             				$cell->getValue() .
             				'</td>' . PHP_EOL;**/
					$cellnum++;
    				}
    				//echo '</tr>' . PHP_EOL;
				$rownum++;
				if ($lastrow) break;
			}			
			//echo '</table>' . PHP_EOL;
			$writer = new Xlsx($spreadsheet);
                	$writer->save($target_file);
	
		}			
                $f3->set('title', $f3->get('site'));
		$f3->set('download', '/uploads/'.basename($_FILES["file"]["name"]));
                $f3->set('head','header.htm');
                $f3->set('inc','datahasherresult.htm');


	}
}
