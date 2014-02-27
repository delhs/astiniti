<?

//$fp = fopen("filename.php","wb");
//fwrite($fp,pack("CCC",0xef,0xbb,0xbf));die();


$_SERVER['REQUEST_URI'] = preg_replace('/\/admin\/helper\//','/',$_SERVER['REQUEST_URI']);



$uri = $_SERVER['REQUEST_URI'];
$root = $_SERVER['DOCUMENT_ROOT'];





$arr = explode('/', $uri);

$method = (isset( $arr[1] )) ? $arr[1] : NULL;
$action = (isset( $arr[2] )) ? $arr[2] : NULL;

if( $uri!='/' && ($method==NULL || $action==NULL) )
{
	die('404');
}


#connect to db
include_once $root.'/admin/helper/lib/classes/class.MySql.php';
$db = new MySql();


#install DB tables if not exists
include_once $root.'/admin/helper/lib/classes/class.installer.php';
$installer = new installer();
$installer->install();

function trimUTF8BOM($data){ 
    if(substr($data, 0, 3) == pack('CCC', 239, 187, 191)) {
        return substr($data, 3);
    }
    return $data;
}

if( $method=='create' )
{
	if($action=='module')
	{
		if($_POST)
		{
			$modName = $_POST['name'];
			$modNameRu = $_POST['runame'];
			
			mkdir( $root.'/mod/'.$modName, 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/tpl/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/cache/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/tpl_compile/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/js/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/css/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/front/scr/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/tpl/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/cache/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/tpl_compile/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/js/', 0777 );
			mkdir( $root.'/mod/'.$modName.'/back/css/', 0777 );
			
					
			#create mod.php		
			$handle = fopen($root.'/mod/'.$modName.'/front/mod.php', 'x');
			include_once $root.'/admin/helper/snippets/mod/front/mod.php';
			$text = trimUTF8BOM($text); 
			fputs($handle, $text); 
			fclose($handle); 
					
					
			#create default.tpl		
			$handle = fopen($root.'/mod/'.$modName.'/front/tpl/default.tpl', 'x');
			include_once $root.'/admin/helper/snippets/mod/front/tpl/default.tpl.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 	
			
			
			#create default.css		
			$handle = fopen($root.'/mod/'.$modName.'/front/css/default.css', 'x');
			include_once $root.'/admin/helper/snippets/mod/front/css/default.css.php';
			$text = trimUTF8BOM($text); 
			fputs($handle, $text); 
			fclose($handle); 		
					
					
			#create default.js		
			$handle = fopen($root.'/mod/'.$modName.'/front/js/default.js', 'x');
			include_once $root.'/admin/helper/snippets/mod/front/js/default.js.php';
			$text = trimUTF8BOM($text); 
			fputs($handle, $text); 
			fclose($handle); 
			
			
			
			#create mod.php	from back	
			$handle = fopen($root.'/mod/'.$modName.'/back/mod.php', 'x');
			include_once $root.'/admin/helper/snippets/mod/back/mod.php';
			$text = trimUTF8BOM($text); 
			fputs($handle, $text); 
			fclose($handle); 
			
			#create default.tpl	from back			
			$handle = fopen($root.'/mod/'.$modName.'/back/tpl/default.tpl', 'x');
			include_once $root.'/admin/helper/snippets/mod/back/tpl/default.tpl.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
			
			#create default.js from back		
			$handle = fopen($root.'/mod/'.$modName.'/back/js/default.js', 'x');
			include_once $root.'/admin/helper/snippets/mod/back/js/default.js.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
			
			header('location: /admin/helper/modules/view/');
			die();		
			
			
		}else{
		
			?>
			<h1>Create module</h1>
			
			<p><a href="/admin/helper/">Назад</a></p>
			<p></p>
			
			<form name="createModule" method="post" action="/admin/helper/<?=$method.'/'.$action.'/'?>">
			
				<br/>
				<p>Имя модуля (латиница без пробелов и спец. символов)</p>
				<input type="text" name="name" value=""  />
						
				<p>Имя модуля (русскоязычное название)</p>
				<input type="text" name="runame" value=""  />
	
				<p></p>
				<p>
					<input type="submit" name="submit" value="Отправить" />
				</p>
				
				
			</form>
			<?
		}
	}
	
	
	if($action=='plugin')
	{
		if($_POST)
		{
			$plugName = $_POST['name'];
			$plugNameRu = $_POST['runame'];
			
			mkdir( $root.'/plug/'.$plugName, 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/tpl/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/cache/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/tpl_compile/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/js/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/front/css/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/tpl/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/cache/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/tpl_compile/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/js/', 0777 );
			mkdir( $root.'/plug/'.$plugName.'/back/css/', 0777 );
			
					
			#create plug.php		
			$handle = fopen($root.'/plug/'.$plugName.'/front/plug.php', 'x');
			include_once $root.'/admin/helper/snippets/plug/front/plug.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
					
					
			#create default.tpl		
			$handle = fopen($root.'/plug/'.$plugName.'/front/tpl/default.tpl', 'x');
			include_once $root.'/admin/helper/snippets/plug/front/tpl/default.tpl.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 	
			
			
			#create default.css		
			$handle = fopen($root.'/plug/'.$plugName.'/front/css/default.css', 'x');
			include_once $root.'/admin/helper/snippets/plug/front/css/default.css.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 		
					
					
			#create default.js		
			$handle = fopen($root.'/plug/'.$plugName.'/front/js/default.js', 'x');
			include_once $root.'/admin/helper/snippets/plug/front/js/default.js.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
			
			
			
			#create plug.php from back	
			$handle = fopen($root.'/plug/'.$plugName.'/back/plug.php', 'x');
			include_once $root.'/admin/helper/snippets/plug/back/plug.php';
			$text = trimUTF8BOM($text);			
			fputs($handle, $text); 
			fclose($handle); 
			
			#create default.tpl	from back			
			$handle = fopen($root.'/plug/'.$plugName.'/back/tpl/default.tpl', 'x');
			include_once $root.'/admin/helper/snippets/plug/back/tpl/default.tpl.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
			
			#create default.js from back		
			$handle = fopen($root.'/plug/'.$plugName.'/back/js/default.js', 'x');
			include_once $root.'/admin/helper/snippets/plug/back/js/default.js.php';
			$text = trimUTF8BOM($text);
			fputs($handle, $text); 
			fclose($handle); 
			
			header('location: /admin/helper/plugins/view/');
			die();		
			
			
		}else{
		
			?>
			<h1>Create plugin</h1>
			
			<p><a href="/admin/helper/">Назад</a></p>
			<p></p>
			
			<form name="createPlugin" method="post" action="/admin/helper/<?=$method.'/'.$action.'/'?>">
			
				<br/>
				<p>Имя плагина (латиница без пробелов и спец. символов)</p>
				<input type="text" name="name" value=""  />
						
				<p>Имя плагина (русскоязычное название)</p>
				<input type="text" name="runame" value=""  />
	
				<p></p>
				<p>
					<input type="submit" name="submit" value="Отправить" />
				</p>
				
				
			</form>
			<?
		}
	}	
	
}


if( $method=='modules' )
{
	if($action=='view')
	{
		?>
			<h1>Module viewer</h1>
			<p><a href="/admin/helper/">Назад</a></p>
			<p></p>
		<?
		die();
	}
}

if( $method=='plugins' )
{
	if($action=='view')
	{
		?>
			<h1>plugin viewer</h1>
			<p><a href="/admin/helper/">Назад</a></p>
			<p></p>
		<?
		die();
	}
}



?>
<h1>Helper</h1>


<a href="/admin/helper/create/module/">Create module</a>
<br/>
<a href="/admin/helper/create/plugin/">Create plugin</a>

<?die();?>