<?
require 'vendor/autoload.php';

$f3 = \Base::instance();

// Initialize CMS
$f3->config('app/config.ini');

// Define routes
$f3->config('app/routes.ini');

$f3->set('DEBUG',3);

ini_set('display_errors', 1);

$f3->run();
?>
