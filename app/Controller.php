<?php

//! Base controller
class Controller {

	protected
		$db;
	protected
		$globals;
		
		

	//! HTTP route pre-processor
	function beforeroute($f3) {
		$db=$this->db;
		$f3->set('UPLOADS','/uploads/');
		

		/**	
		$f3->get('SESSION.user_id');
		
		$user=new DB\SQL\Mapper($db,'users');
		$user->load(array('id=?',$f3->get('SESSION.user_id')));
		if ($user->dry()) $f3->reroute('/login');
		
		
		$hive = $f3->hive();
		$tmp = explode('->',$hive['ROUTES'][$f3->get('PATTERN')][3][$hive['VERB']][0]);
		$class =  $tmp[0];
		$method = $tmp[1];
		if (in_array($method,$f3->get('permissions')[$f3->get('SESSION.usertype')]) || 
				strcmp($method,'notauthorized')==0) {
			//echo 'allowed';
		} else {
			//redirect to not allowed
			echo 'not allowed';
			$f3->reroute('/notauthorized');
		}**/
	}

	//! HTTP route post-processor
	function afterroute() {		
		$f3=Base::instance();
		// Render HTML layout
		if (!$f3->exists('title')) {
			$f3->set('title', $f3->get('site'));
		}
		$f3->set('foot','footer.htm');
		/**
		if ($f3->get('ajax')) echo Template::instance()->render('layoutajax.htm');
		else **/
		echo Template::instance()->render('layout.htm');
		/**
		$f3->set('SESSION.message','');
		$f3->set('SESSION.messagetype','');	
		**/
	}

	//! Instantiate class
	function __construct() {
		$f3=Base::instance();
		// Connect to the database
		$db=new DB\SQL(
			$f3->get('db')['dsn'],
			$f3->get('db')['user'],
			$f3->get('db')['pw'],
			array( \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION )
		);
		// Use database-managed sessions
		new DB\SQL\Session($db);
		// Save frequently used variables
		$this->db=$db;
		//$f3->set('globals',new DB\SQL\Mapper($this->db,'globalsession'));
		
	}

	
}

