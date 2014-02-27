<?php
@session_start();

if( !isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['userprivileges']['filemanager'] )){
	die('access denied');
}

error_reporting(0); // Set E_ALL for debuging

include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderConnector.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinder.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeDriver.class.php';
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
// Required for FTP connector support
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @return bool|null
 **/
function access($attr, $path, $data, $volume) {

	return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
		:  null;                                    // else elFinder decide it itself
}

$opts = array(
	// 'debug' => true,
	'roots' => array(
		array(
			'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
			'path'          => $_SERVER['DOCUMENT_ROOT'].'/',         // path to files (REQUIRED)
			'URL'           => '/', // URL to files (REQUIRED)
			'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
			'attributes'	=> array(
				array( 
					'pattern' => '/admin$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' 	=>	'/config$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/lib$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/mod$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/plug$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/plugins$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/tpl_compile$/',
					'read' 		=>	false,
					'write' 	=>	false,
					'hidden'	=>	true,
					'locked'	=>	false
				),
				array( 
					'pattern' => '/\.php$/',
					'write' => false,
					'read' 	=>	false,
					'locked' => true
				),
				array( 
					'pattern' => '/\.db$/',
					'write' => false,
					'read' 	=>	false,
					'locked' => false,
					'hidden' => true
				),
				array( 
					'pattern' => '/system$/',
					'write' => false,
					'read' 	=>	false,
					'locked' => false,
					'hidden' => true
				)
			)
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

