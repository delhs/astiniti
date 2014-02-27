<?
/*
The MIT License (MIT)

Copyright (c) 2013 kunano.ru

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

@session_start();
	


	
#set autoload
function __autoload($className)
{
	$fname = $_SERVER['DOCUMENT_ROOT'].'/system/lib/classes/class.'.$className.'.php';
	
	if(file_exists($fname)) include_once($fname);
}

#security class
$security = new Security();

#protect server global var
$_SERVER = $security->SafeData( $_SERVER );

#config class
$config = new Config();

if( $config->debug )
{
	ini_set('display_errors', 'On');
	error_reporting(E_ALL | E_STRICT);
}else{
	ini_set('display_errors', 'Off');
	error_reporting(0);
}

#image class
$img = new Img();

#mysql connct
try{
	$db = new PDO('mysql:host='.$config->dbhost.';dbname='.$config->dbname, $config->dbuser, $config->dbpassword);

}catch (PDOException $e){
	die("Error. Connecting to database is failed");
}

$res = $db->prepare('SET NAMES "utf8"');
$res->execute();


#validator
$validate = new Validate();

#main controller
$core = new Core();


#template class (This is Smarty v. 2.0)
include_once $_SERVER['DOCUMENT_ROOT'].'/system/lib/classes/smarty/Smarty.class.php';
$template = new Smarty();

$template->template_dir = $_SERVER['DOCUMENT_ROOT'].'/tpl/';
$template->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/tpl_compile/';
$template->cache_dir = $_SERVER['DOCUMENT_ROOT'].'/cache/';
$template->cahing = false;

$template->security = true;
$template->security_settings['PHP_TAGS'] = false;
$template->error_reporting = ( $config->debug ) ? NULL : true;
 

 
 
#if is ajax request "mod_name" or "plug_name" for load module or plugin
if(isset( $_POST['mod_name'] ) || isset( $_POST['plug_name'] ))
{
	$ajax = new Ajax( $_POST, $_FILES );
	die();
}

#main controller initialization
$core->init();

#main controller check to mobile
$core->mobileDetect();

#main controller compile full template
$core->compileTemplate();

#send headers
$core->sendHeaders();

#main controller display full template
$core->render();
?>