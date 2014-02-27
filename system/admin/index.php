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

#extension=php_curl.dll
#extension=php_exif.dll
#extension=php_gd2.dll
		


	

//print_r(get_loaded_extensions());
	
if (!extension_loaded('gd')) {
  echo "Ошибка расширения. Расширение &laquo;php_gd2.dll&raquo; не инициализировано.<br/>";
  echo "Попытка запустить расширение &laquo;php_gd2.dll&raquo;...<br/>";
  @dl('php_gd2.dll') or die('Не удалось запустить расширение &laquo;php_gd2.dll&raquo;. Дальнейшая работа невозможна.');
}
	
if (!extension_loaded('exif')) {
  echo "Ошибка расширения. Расширение &laquo;extension=php_exif.dll&raquo; не инициализировано.";
  echo "Попытка запустить расширение &laquo;php_exif.dll&raquo;...<br/>";
  @dl('php_exif.dll') or die('Не удалось запустить расширение &laquo;php_exif.dll&raquo;. Дальнейшая работа невозможна.');
}
	
if (!extension_loaded('curl')) {
  echo "Ошибка расширения. Расширение &laquo;php_curl.dll&raquo; не инициализировано.";
  echo "Попытка запустить расширение &laquo;php_curl.dll&raquo;...<br/>";
  @dl('php_curl.dll') or die('Не удалось запустить расширение &laquo;php_curl.dll&raquo;. Дальнейшая работа невозможна.');
}
	
	
	
@session_start();

#set marker to admin
if( !isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin'] ) )
{
	$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['panel'] = 'parts';
	$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] = '1';
}

if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['init']) ) unset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['init']);


#if is helper then load only helper
if( preg_match( '/\/system\/admin\/helper\//', $_SERVER['REQUEST_URI'] ) )
{
	include_once $_SERVER['DOCUMENT_ROOT'].'/system/admin/helper/helper.php';
	die();
}

$_SERVER['REQUEST_URI'] = preg_replace('/\/system\/admin\//','/',$_SERVER['REQUEST_URI']);

function clearDir($path, $suicide=false)
{
	if(file_exists($path) && is_dir($path))
	{
		$dirHandle = opendir($path);
		while (false !== ($file = readdir($dirHandle))) 
		{
			if ($file!='.' && $file!='..')
			{
				$tmpPath=$path.'/'.$file;
				chmod($tmpPath, 0777);
				
				if (is_dir($tmpPath))
	  			{
					clearDir($tmpPath, true);
			   	} 
	  			else 
	  			{ 
	  				if(file_exists($tmpPath))
					{
	  					unlink($tmpPath);
					}
	  			}
			}
		}
		closedir($dirHandle);
		
		if($suicide && file_exists($path))
		{
			rmdir($path);
		}
	}
}

#set autoload
function __autoload($className)
{
	$fname = $_SERVER['DOCUMENT_ROOT'].'/system/admin/lib/classes/class.'.$className.'.php';
	
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
}

#mysql connct
try{
	$db = new PDO('mysql:host='.$config->dbhost.';dbname='.$config->dbname, $config->dbuser, $config->dbpassword);

}catch(PDOException $e){
	die("Error. Connecting to database is failed");
}

$res = $db->prepare('SET NAMES "utf8"');
$res->execute();

#autoinstallation
$installer = new Installer();
$installer->installAll();

#image class
$img = new Img();


#authorize class
$auth = new Auth();

#main controller
$core = new Core();

#validator
$validate = new Validate();


#template class (This is Smarty v. 2.0)
include_once $_SERVER['DOCUMENT_ROOT'].'/system/admin/lib/classes/smarty/Smarty.class.php';
$template = new Smarty();
$template->template_dir = $_SERVER['DOCUMENT_ROOT'].'/system/admin/tpl/';
$template->compile_dir = $_SERVER['DOCUMENT_ROOT'].'/system/admin/tpl_compile/';
$template->cahing = false;
$template->error_reporting = ( $config->debug ) ? NULL : true;
#assign basic values
$template->assign('hostname', $_SERVER['HTTP_HOST']);


#if is ajax request "login"
if(isset( $_POST['login'] ))
{
	$uid = $auth->login( $_POST['login'], $_POST['password'] );
	
	if( $uid==NULL ){
		if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']) ) unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] );
		if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username']) ) unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username'] );
		die('NULL');
	}else{
		$respArr = array(
			'uid'			=>	$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'],
			'username'		=>	$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username']
		);
		die(json_encode( $respArr ));
	}
}

#if is ajax request "logout"
if(isset( $_POST['logout'] ))
{
	if(isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']  ))
	{
		unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']  );
		unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username']  );
		if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['developer'] ) ) unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['developer']  );
		unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['userprivileges']  );
	}
}


#if is ajax request "get" for the get loaded scripts
if( isset($_POST['get']) && $_POST['get']==='init' )
{
	$get = new Get();
	die();	
}


if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{	
	if(isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] ) && $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] !='developer')
	{
		$res = $db->prepare(" SELECT `id` FROM `adm_users` WHERE `uid`=:uid  LIMIT 1");
		$res->bindValue(':uid', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']);
		$res->execute();
		$ret = $res->fetch();
		if( $ret===false ) die('NULL');
	}
}


#if is ajax request "mod_name" or "plug_name" or "panel_name" for load module or plugin or load panel
if(isset( $_POST['mod_name'] ) || isset( $_POST['plug_name'] ) || isset( $_POST['panel_name'] ) )
{
	$ajax = new Ajax( $_POST, $_FILES );
	die();
}


#if is ajax request "get" for load panel from  admin.loadPanel()
if( isset($_POST['get']) )
{
	$get = new Get();
	die();	
}

if(!@isset($_SERVER['HTTP_X_REQUESTED_WITH']) && @empty($_SERVER['HTTP_X_REQUESTED_WITH']) && @strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest')
{
	clearDir($_SERVER['DOCUMENT_ROOT'].'/system/admin/temp');
}

#main controller
$core->init();

#compile hostname template
$template->assign('hostname', $_SERVER['HTTP_HOST']);

#compile hostname template
$template->assign('protocol', $config->protocol);

#compile current page ID template
$template->assign('pageId', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id']);

#compile meta less template
if( isset($core->lessFilesArray) ) $template->assign('metaBufferLessArray', $core->lessFilesArray);

if( isset($core->jsFilesArray) ) $template->assign('jsFilesArray', $core->jsFilesArray);

if( isset($core->cssFilesArray) ) $template->assign('cssFilesArray',$core->cssFilesArray);


#compile debug template value
$template->assign('debug', $config->debug);

#compile multylang data array
if( $config->multylang )
{
	$template->assign('multylang', array(
		'currentLang'	=>	$config->currentLang,
		'urlPref'		=>	$config->getUrlPref(),
		'prefixes'		=>	$config->prefixes
	));
}

#compile projectName template value
$template->assign('projectName', $config->projectName);

#compile plugins menu template
$template->assign('plugNamesArray', $core->plugNamesArray);

#compile plugins menu template
$template->assign('modNamesArray', $core->modNamesArray);

#compile main menu template
$template->assign('mmenuArray', $core->mmenuArray);

#send headers
$core->sendHeaders();

#display template
$template->display('default.tpl');

unset( $db );	
unset( $img );	
unset( $security );	
unset( $config );	
unset( $installer );	
unset( $auth );	
unset( $core );	
unset( $validate );	
unset( $template );	

die();
?>