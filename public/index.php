<?php
/**
 * This be the main Kobro-Script.
 * 
 * @copyright Dr. Kobros Foundation!
 * @author Devadutt Chattopadhyay
 * @author Rajanigandha Balasubramanium
 * @author Lalitchandra Pakalomattam
 *   
 */

//ROOT Path modified to point to the real root of the application
$root = realpath(dirname(__FILE__) . "/../");
define('ROOT', $root);

// Register the new autoload
require_once ROOT .'/app/autoload.php';

// Register Autoload function
spl_autoload_register("kobroAutoload");

require_once ROOT . '/vendor/autoload.php';

/* Mighty KobroCMS be implemented with fantastic patterns! */

try {
	$app = KobroCms::getInstance();
	echo $app->run();
} catch(Exception $e) {
		
	echo "<h1>KobroCMS Fatal Error</h1>";
	
	echo "<em>" . $e . "</em>";

	// We kobros developers be very clever: we hide stack trace from customer if not devel mode!
	if($app->config['mode'] == 'development') {
		print "<pre>";
		print_r($e->getTrace());
		print "</pre>";
	}
	
}

// We done!