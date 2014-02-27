<?php
#automate

#set global vars
if( !isset($_SERVER['HTTP_HOST']) ) $_SERVER['HTTP_HOST'] = 'tdunion';
if( !isset($_SERVER['DOCUMENT_ROOT']) ) $_SERVER['DOCUMENT_ROOT'] = preg_replace('/\\/automate$/', '', dirname(__FILE__));
if( !isset($_SERVER['REQUEST_URI']) ) $_SERVER['REQUEST_URI'] = '';
$buffer = "";

#set autoload
function __autoload($className)
{
	$fname = $_SERVER['DOCUMENT_ROOT'].'/lib/classes/class.'.$className.'.php';
	
	if(file_exists($fname)) include_once($fname);

}

#security class
$security = new security();

#protect server global var
$_SERVER = $security->SafeData( $_SERVER );

#config class
$config = new config();

if( $config->debug )
{
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
}else{
	ini_set('display_errors', 'Off');
	error_reporting(0);
}

#image class
$img = new img();

#mysql connct
try{
	$db = new PDO('mysql:host='.$config->dbhost.';dbname='.$config->dbname, $config->dbuser, $config->dbpassword);

}catch (PDOException $e){
	die("Error. Connecting to database is failed\n");
}

$res = $db->prepare('SET NAMES "utf8"');
$res->execute();

#validator
$validate = class Validate();

#main controller
$core = new Core();


#set timezone
$res = $db->query("SELECT `timezone` FROM `settings` ");
$resArray = $res->fetch();
if( function_exists("date_default_timezone_set") && $resArray['timezone']!='0' && $resArray['timezone']!='' ) date_default_timezone_set( $resArray['timezone'] );





#GET TASKS
$res = $db->query("SELECT * FROM `automate` ");
if(!$res)
{
	$db->query("
			CREATE TABLE IF NOT EXISTS `automate` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `in_day` int(11) NOT NULL DEFAULT '0',
			  `in_month` int(11) NOT NULL DEFAULT '0',
			  `in_year` int(11) NOT NULL DEFAULT '0',
			  `in_hour` int(11) NOT NULL DEFAULT '0',
			  `in_minute` int(11) NOT NULL DEFAULT '0',
			  `filename` varchar(255) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1;
		");
	die();
}

$resArray = $res->fetchAll();


if( !empty( $resArray ) )
{
	foreach( $resArray as $task )
	{
		if( $task['in_year']!=0 && $task['in_year']!=date('Y') ) continue;
		if( $task['in_month']!=0 && $task['in_month']!=date('m') ) continue;
		if( $task['in_day']!=0 && $task['in_day']!=date('d') ) continue;
		if( $task['in_hour']!=0 && $task['in_hour']!=date('H') ) continue;
		if( $task['in_minute']!=0 && $task['in_minute']!=date('i') ) continue;


		if( file_exists( $_SERVER['DOCUMENT_ROOT'].$task['filename'] ) )
		{
			include $_SERVER['DOCUMENT_ROOT'].$task['filename'];
			$buffer .= "\n".date("Y-m-d H:i:s")." RUN FILE '".$task['filename']."'\n";
		}
	}
}

if( $config->debug && $buffer!="" )
{
	$buffer = "----------------------action----------------------".$buffer."--------------------end action--------------------\n\n";
	$handle = fopen( $_SERVER['DOCUMENT_ROOT'].'/automate/automate.log.txt', "a+");
	fwrite( $handle, $buffer );
	fclose( $handle );	
}


die();
?>