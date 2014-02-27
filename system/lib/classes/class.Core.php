<?
class Core
{
	public $adminDir = '/system/admin';

	public $adminPath = '/system/admin/';

	public $tempDir = '/system/admin/temp';

	public $tempPath = '/system/admin/temp/';

	#template page prefix
	public $htmlBodyTemplatePrefix = 'body.';

	#template body page prefix
	public $htmlPageTemplatePrefix = 'page.';

	#load modules flag
	public $blockModules = false;
	
	#load content flag
	public $blockContent = false;
	
	#words array
	public $wordsArray = array();
	
	#main uri
	public $uri = '';
	
	#main original uri
	public $requestUri = '';
		
	#main uri
	public $uriArray = array();
	
	#main original uri
	public $requestUriArray = array();
	
	#page id after loadPage()
	public $pageId = 0;
	
	#config class
	public $config;
	
	#validate class
	public $validate;
	
	#mysql class
	public $db;
	
	#root dir
	public $root = '';
	
	#site host
	public $host = '';
	
	#array of all less files after loadCssJsFiles()
	public $lessFilesArray = array();
		
	#array of all css files after loadCssJsFiles()
	public $cssFilesArray = array();
	
	#array of all css files after loadCssJsFiles()
	public $jsFilesArray = array();
	
	#main buffer array to page after loadPage()
	public $pageBufferArray = array();
	
	#debug mode
	public $debug = true;
	
	#microtime if debug is true
	public $microtime = 0;
	
	#module content array
	public $modBuffer = array();
	
	#plugin content array
	public $plugBuffer = array();
	
	#headers array
	public $headersArray = array();
	
	#main menu array after  mmenuLoad()
	public $mmenuArray = array();
		
	#gzip compression flag
	public $gzip = false;	
	
	#gzip compression level value
	public $gzipLevel = 3;
	
	#flag. Enable air mode from admin panel
	public $editedFromAdmin = false;
	
	#main buffer
	public $buffer = '';
	


	public function __construct()
	{
		$this->db = $GLOBALS['db'];


		
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->host = $_SERVER['HTTP_HOST'];
		$this->config = $GLOBALS['config'];
		
		$this->debug = $this->config->debug;
		
		if($this->debug)
		{
			$this->microtime = microtime(true);
		}	
		$this->validate = $GLOBALS['validate'];
		
		$this->uri = $_SERVER['REQUEST_URI'];
		
		$this->route();

		if($this->getRedirect( $this->requestUri )!==false) $this->redirect($this->getRedirect( $this->requestUri ));

	}

	public function route()
	{
		$this->uri = preg_replace('/\/{1,}/', '/', $this->uri);
		$this->uri = preg_replace('/\/{0,}$/', '', $this->uri);
		$this->uri .='/';	

		#replace protected links
		if( $this->config->multylang)
		{
			foreach( $this->config->multylang_bd as $prefix => $otherData )
			{
				$this->uri = preg_replace('/^\/'.$prefix.'\//','/',$this->uri);
			}
			
		}		
		#if is air mode from iframe
		if( preg_match('/\\/edited\\/[0-9]+\\/{0,1}$/', $this->uri) ) $this->editedFromAdmin = true;
	
		$this->requestUri = $this->uri;

		#replace protected links
		foreach( $this->config->protectedLinks as $link )
		{
			if ( $link=='download' ) continue; 
			$this->uri = preg_replace('/\/{1}'.$link.'\/{1,1}(.*)/','/',$this->uri);
		}
		
		$this->requestUriArray = explode("/", $this->requestUri);
		$this->uriArray = explode("/", $this->uri);
		
		#check the download URI
		if( isset( $this->uriArray[4] ) && $this->uriArray[1] == 'download' )
		{
			if( $this->uriArray[2] == 'plug' )
			{
				$this->plugDownload($this->uriArray[3], $this->uriArray[4]);

			}elseif( $this->uriArray[2] == 'mod'){
				$this->modDownload($this->uriArray[3], $this->uriArray[4]);
			}
		}

	}

	public function getRedirect( $url )
	{
		$res = $this->db->prepare("SELECT `to_url` FROM `redirects` WHERE `from_url`=:uri OR `from_url`=:uris LIMIT 1");
		$res->bindValue(':uri', $url);
		$res->bindValue(':uris', substr($url, 0, -1) );
		$res->execute();
		
		$resArray = $res->fetch();
		if( !empty($resArray) )
		{
			$resArray['to_url'] = preg_replace('/^(^http[s]{0,1}\:\/\/){0,1}/', 'http://', $resArray['to_url']);
			return $resArray['to_url'];
		}
		return false;
	}

	public function modDownload( $modName, $filename )
	{

		if(!file_exists( $this->root.'/mod/'.$modName) )  return;
		
		include_once $this->root.'/mod/'.$modName.'/front/mod.php';
		eval('$mod = new Mod'.ucfirst($modName).'(); ');
		$mod->db = $this->db;
		$mod->core = $this;
		$mod->img = new img();
		$mod->config = $this->config;
		$mod->root = $this->root;


		if( method_exists( $mod, 'downloadFile' )  )
		{
			$mod->downloadFile( $filename );
		}
		unset ($mod);
	}

	public function plugDownload($plugName, $filename)
	{
		if(!file_exists( $this->root.'/plug/'.$plugName) )  return;

		ob_start();
		include_once $this->root.'/plug/'.$plugName.'/front/plug.php';
		eval('$plug = new Plug'.ucfirst($plugName).'(); ');
		$plug->db = $this->db;
		$plug->core = $this;
		$plug->img = new img();
		$plug->config = $this->config;
		$plug->root = $this->root;

		if( method_exists( $plug, 'downloadFile' )  )
		{
			$plug->downloadFile( $filename );
		}
		unset ($plug);
	}
	
	public function downloadFile( $filename, $attachmentFilename = '' )
	{
  		if( !file_exists( $filename ) ) die('file not exists '.$filename); //return;

  		if($attachmentFilename == '') $attachmentFilename = basename($filename);

		if (ob_get_level())  ob_end_clean();

		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $attachmentFilename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($filename));

		if ($fd = fopen($filename, 'rb')) 
		{
			while (!feof($fd)) print fread($fd, 1024);
		}
		fclose($fd);
		die();
	}

	#load bread crumbs and puts them in an array $this->pageBufferArray['breadcrumbs']
	public function getBreadcrumbs( $arr = array())
	{
		
		if( $this->pageId==1 ) return;

		if( empty($arr) )
		{
		 	$this->pageBufferArray['breadcrumbs'][] = array(
				'url'	=>	$this->mmenuArray[1]['url'],
				'name'	=>	$this->mmenuArray[1]['name']
		 	);
			$arr = $this->mmenuArray;
		}

		foreach( $arr as $id => $data )
		{
			$act = ($data['active']=='act') ? 'true' : 'false';
			
			if( $data['active']=='act' ) 
			{
				$this->pageBufferArray['breadcrumbs'][] = array(
					'url'	=>	$data['url'],
					'name'	=>	$data['name']
				);
			}
	
			if( isset( $data['childNodes'] ) )
			{
				$this->getBreadcrumbs( $data['childNodes'] );
			}
		}
	}


	public function init()
	{
		#load words
		$this->loadWords();
		
		#load page data
		$this->loadPage();

		#main menu loas
		$this->mmenuLoad();

		#get breadcrumbs
		$this->getBreadcrumbs();

		#load plugins
		$this->loadPlugins();
		
		#load modules
		if( !$this->blockModules ) $this->loadModules();

		if( $this->editedFromAdmin ) 
		{
			array_push( $this->jsFilesArray, $this->adminDir.'/editor/js/c-elfinder.min.js');
			array_push( $this->jsFilesArray, $this->adminDir.'/editor/js/a-jquery-ui.min.js');
			array_push( $this->jsFilesArray, $this->adminDir.'/js/added/codemirror.core.js');
			array_push( $this->jsFilesArray, $this->adminDir.'/js/airmode/airmode.js');
			
			array_push( $this->cssFilesArray, $this->adminDir.'/css/airmode/airmode.css');
			array_push( $this->cssFilesArray, $this->adminDir.'/editor/css/elfinder.min.css');
			array_push( $this->cssFilesArray, $this->adminDir.'/editor/css/elfinder.theme.css');
			array_push( $this->cssFilesArray, $this->adminDir.'/editor/css/jquery-ui.css');
			array_push( $this->cssFilesArray, $this->adminDir.'/css/f-codemirror.css');
		}

	}
	
	public function mobileDetect()
	{
		#mobile detect
		if( $this->config->mobileVersionExist )
		{
			$mobule = new mobile();
			
			#if is mobile and not tablet
			if( $mobule->isMobile() && !$mobule->isTablet() )
			{
				#if mobile version of the site is on a subdomain
				if($this->config->mobileVersionOnSubdomain)
				{
					@header("location: ".$this->config->protocol."://".$this->config->mobileVersionSubdomainName.".".$_SERVER['HTTP_HOST'].$this->requestUri); 
					die();
		
				#if mobile version of the site is on an another domain	
				}else{
					@header("location: ".$this->config->mobileVersionOtherDomainName); 
					die();
				}
			}
		}
	}
	
	public static function templatePasteWord( $params )
	{
		$wordKey = $params['name'];

		#if debug mode then add not existing words to db
		if( $GLOBALS['config']->debug )
		{
			if(!isset($GLOBALS['core']->wordsArray[ $wordKey ]))
			{
				$GLOBALS['core']->addWord($wordKey, $wordKey, $wordKey);
			}
		}
	
		if( isset($GLOBALS['core']->wordsArray[ $wordKey ]) )
		{
			if( $GLOBALS['core']->editedFromAdmin ) return '<airtag class="air-mode-helper" data-word-key="'.$wordKey.'" type="air-mode-helper-word">'.$GLOBALS['core']->wordsArray[ $wordKey ].'</airtag>'; else return $GLOBALS['core']->wordsArray[ $wordKey ];
		}
	}
	
	public static function templateInsertModule($params)
	{
		$region = $params['name'];

		if( !isset($GLOBALS['core']->modBuffer[ $region ])  ) return;
		
		$resultBuffer = '';
		foreach($GLOBALS['core']->modBuffer[ $region ] as $modId => $modArray)
		{
			if( $GLOBALS['core']->editedFromAdmin )
			{
				$resultBuffer .= '<div data-mod-id="'.$modArray['id'].'" data-mod-name="'.$modArray['name'].'" class="air-mode-helper" type="air-mode-helper-module">'.$modArray['content'].'</div>';
			}else{
				$resultBuffer .= $modArray['content'];
			}
			
		}
		return $resultBuffer;

	}
	
	public static function templateInsertPlugin($params)
	{
		$plugName = $params['name'];
		if( !isset($GLOBALS['core']->plugBuffer[ $plugName ])  ) return;
		$resultBuffer = '';
		$resultBuffer .= $GLOBALS['core']->plugBuffer[ $plugName ];
		return $resultBuffer;
	}

	public function compileTemplate()
	{
		global $template;
		
		if( $this->blockContent )
		{
			$this->pageBufferArray['h1'] = '';
			$this->pageBufferArray['content'] = '';
		}
		#extend smarty template function for paste words
		#in template set {pasteWord name="WORD_KEY"}
		$template->register_function("pasteWord", array('core', 'templatePasteWord'), true);
		
		#extend smarty template function for insert modules in region
		#in template set {region name="REGION_NAME" runame="RUSSIAN REGION NAME"}
		$template->register_function("region", array('core', 'templateInsertModule'), true);
		
		#extend smarty template function for insert plugins in region
		#in template set {plugin name="PLUGIN NAME"}
		$template->register_function("plugin", array('core', 'templateInsertPlugin'), true);

		#unite js or not
		if( $this->pageBufferArray['unite_js'] != '1' ) $template->assign('metaBufferJsArray', $this->jsFilesArray); else $_SESSION[ $_SERVER['HTTP_HOST'] ]['metaBufferJsArray'] = $this->jsFilesArray;
		
		#unite css or not
		if( $this->pageBufferArray['unite_css'] != '1') $template->assign('metaBufferCssArray', $this->cssFilesArray); else $_SESSION[ $_SERVER['HTTP_HOST'] ]['metaBufferCssArray'] = $this->cssFilesArray;

		#compile edited from admin air mode flag
		$template->assign('editedFromAdmin', $this->editedFromAdmin);
		
		#compile originalVersionDomainName template
		$template->assign('originalVersionDomainName', $this->config->originalVersionDomainName);
		
		#compile meta less template
		$template->assign('metaBufferLessArray', $this->lessFilesArray);

		#compile meta data to page
		$template->assign('page', $this->pageBufferArray);
		
		#compile main menu template
		$template->assign('mmenuArray', $this->mmenuArray);
		
		#compile breadcrumbs template
		if(isset($this->pageBufferArray['breadcrumbs']))
		{
			#clear last breadcrabs url
			unset($this->pageBufferArray['breadcrumbs'][ count($this->pageBufferArray['breadcrumbs']) - 1 ]['url']);
			
			$template->assign('breadcrumbs', $this->pageBufferArray['breadcrumbs']);
		}
			

		#compile project name
		$template->assign('projectName', $this->config->projectName);

		#set body file template
		$template->assign('body', $this->htmlBodyTemplatePrefix.$this->pageBufferArray['template'].'.tpl');
		
		#set buffer
		$this->buffer = $template->fetch($this->htmlPageTemplatePrefix.$this->pageBufferArray['template'].'.tpl');

		#secur email address
		$pattern = "/@/";
		$this->buffer = preg_replace($pattern, '<thecat></thecat>', $this->buffer);
		
		#if is admin air mode
		if( $this->editedFromAdmin ) $this->buffer .= '<script type="text/javascript">window.editedFromAdmin = true;</script>';

		#if gzip is enabled
		if($this->gzip && function_exists( "gzencode" ) )
		{
			$_SESSION[ $_SERVER['HTTP_HOST'] ]['gzip'] = $this->gzipLevel;
			$this->buffer = gzencode( $this->buffer, $this->gzipLevel );
			$this->headersArray[] = "Content-Encoding: gzip";
			$this->headersArray[] = "Vary: Accept-Encoding";
			$this->headersArray[] = "Content-Length: ".strlen($this->buffer);
		}else{
			if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['gzip'] ) ) unset($_SESSION[ $_SERVER['HTTP_HOST'] ]['gzip']);
		}
		
	}
	
	public function render()
	{
		echo $this->buffer;
	}
	
	/* load all words */
	public function loadWords()
	{
		$res = $this->db->prepare("SELECT `word_key`, `word_value` FROM `words`");
		$res->execute();
		$resArray = $res->fetchAll();
		if( !empty( $resArray ) )
		{
			$pseudos = array(
				'%date%'	=>	date("d.m.Y")
			);

			foreach( $resArray as &$data )
			{
				$this->wordsArray[ $data['word_key'] ] = htmlspecialchars( $data['word_value'] );
			}
		}
	}
	
	/* add new word to DB */
	public function addWord($wordKey, $wordValue, $wordDesc)
	{
		$res = $this->db->prepare("SELECT `word_key` FROM `words` WHERE `word_key`=:word_key ");
		$res->bindValue(':word_key', $wordKey, PDO::PARAM_STR);
		$res->execute();
		$resArray = $res->fetch();
		if( !empty( $resArray ) ) return;
		
		$res = $this->db->prepare("INSERT INTO `words` SET `word_key`=:word_key, `word_value`=:word_value, `word_desc`=:word_desc");
		$res->bindValue(':word_key', $wordKey, PDO::PARAM_STR);
		$res->bindValue(':word_value', $wordValue, PDO::PARAM_STR);
		$res->bindValue(':word_desc', $wordDesc, PDO::PARAM_STR);
		$res->execute();
	}
	
	
	public function redirect( $url )
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ".$url);
		die();
	}
	
	#load $cssCacheFilesArray and $jsCacheFilesArray and $lessCacheFilesArray
	#@param $dirName STRING  directody name where having 'css' or 'js' folder
	public function loadCssJsFiles( $pathName='', $inTheEnd=false )
	{
		$types=array('css', 'less', 'js');
		
		if( empty($types) || $pathName=='' ) return;
		
		foreach( $types as $type )
		{
			$folder=( $type=='less' ) ? 'css' : $type;
			
			if( !is_dir( $this->root.$pathName.$folder.'/' ) ) continue;
			
			$dirArray = scandir( $this->root.$pathName.$folder.'/' );
			
			foreach($dirArray as $file)
			{
				if( $file=='.' || $file=='..' ) continue;
		
				if( preg_match('/^[^-].*?\.'.$type.'$/', $file) )
				{
					switch( $type )
					{
						case 'css': 
							if( $inTheEnd ) array_unshift( $this->cssFilesArray, $pathName.$folder.'/'.$file );
							else $this->cssFilesArray[] = $pathName.$folder.'/'.$file; 
						break;
						
						case 'less':	
							if( $inTheEnd ) array_unshift( $this->lessFilesArray, $pathName.$folder.'/'.$file); 
							else $this->lessFilesArray[] = $pathName.$folder.'/'.$file; 
						break;
						
						case 'js': 		
							if( $inTheEnd ) array_unshift( $this->jsFilesArray, $pathName.$folder.'/'.$file);
							else $this->jsFilesArray[] = $pathName.$folder.'/'.$file;
						break;
					}
				}
			}
		}
	}
	
	
	#load all modules from current page
	public function loadModules()
	{
		$query = "
			SELECT 
				`m`.`id`,
				`m`.`mod_name`,
				`m`.`region`
			FROM 
				`modules` AS `m`
			WHERE
				`page_id`=:page_id	
			ORDER BY
				`mod_range`
		";
		$res = $this->db->prepare( $query );
		$res->bindValue(':page_id', $this->pageId, PDO::PARAM_INT);
		$res->execute();
		if( !$res ) return;
		$modArray = $res->fetchAll();
		foreach( $modArray as $dataArray )
		{
			$this->loadModule( $dataArray['mod_name'], $dataArray['id'],  $dataArray['region'] );
		}

	}
	
	#load all plugins from project
	public function loadPlugins()
	{
		global $template;
		
		$dirArray = scandir( $this->root.'/plug/');

		foreach($dirArray as $dirName)
		{
			if( !is_dir( $this->root.'/plug/'.$dirName) || $dirName=='.' || $dirName=='..' || preg_match( '/^[-]/', $dirName ) || !file_exists( $this->root.'/plug/'.$dirName.'/front/' ) ) continue;
			
			$this->loadCssJsFiles('/plug/'.$dirName.'/front/');
			
			if( !file_exists( $this->root.'/plug/'.$dirName.'/front/plug.php' ) ) continue;
			
			$template->template_dir = $this->root.'/plug/'.$dirName.'/front/tpl/';
			$template->compile_dir = $this->root.'/plug/'.$dirName.'/front/tpl_compile/';
			$template->cache_dir = $this->root.'/plug/'.$dirName.'/front/cache';		
			
			include_once $this->root.'/plug/'.$dirName.'/front/plug.php';
			ob_start();
			eval('$plug = new Plug'.ucfirst($dirName).'(); ');
			
			$plug->start();
			$plug->render();
			unset ($plug);
			$this->plugBuffer[ $dirName ] = ob_get_clean();
			
			$template->template_dir = $this->root.'/tpl/';
			$template->compile_dir = $this->root.'/tpl_compile/';
			$template->cache_dir = $this->root.'/cache/';
			
		}
	}

	#load module
	#@param - module name
	#@param - region name
	public function loadModule( $modName, $modId, $regionName )
	{
		
		global $template;

		$template->template_dir = $this->root.'/mod/'.$modName.'/front/tpl/';
		$template->compile_dir = $this->root.'/mod/'.$modName.'/front/tpl_compile/';
		$template->cache_dir = $this->root.'/mod/'.$modName.'/front/cache';		

		ob_start();
		include_once $this->root.'/mod/'.$modName.'/front/mod.php';
		eval('$mod = new Mod'.ucfirst($modName).'();');
		$mod->modId = $modId;
		$mod->start();
		$mod->render();
		
		$this->modBuffer[ $regionName ][$modId] = array(
			'name'			=>	$modName,
			'id'			=>	$modId,
			'content'		=> ob_get_clean()
		);
		unset ($mod);
		
		$this->loadCssJsFiles('/mod/'.$modName.'/front/');
		
 		$template->template_dir = $this->root.'/tpl/';
		$template->compile_dir = $this->root.'/tpl_compile/';
		$template->cache_dir = $this->root.'/cache/';
	}
	
	
	#error 404 page not found
	public function error404()
	{
		#set headers
		$this->headersArray[] = 'Status: 404 Not Found';
		$this->headersArray[] = 'HTTP/1.1 404 Not Found';
		$this->headersArray[] = 'Connection: Close';

		#set page template file
		$this->pageBufferArray['template'] = '404';

		#set html prefixes
		$this->htmlPageTemplatePrefix = 'error.';
	}	

	#site closed
	public function siteClosed( $closeType )
	{
		$this->headersArray[] = 'Status: 503 Service Temporarily Unavailable';
		$this->headersArray[] = 'HTTP/1.1 503 Service Temporarily Unavailable';
		$this->headersArray[] = 'Connection: Close';		
		
		#set page template file
		switch( (int)$closeType )
		{
			case 1:
				$this->pageBufferArray['template'] = 'maintenance';
			break;
			
			case 2:
				$this->pageBufferArray['template'] = 'reconstruction';
			break;
			
			case 3:
				$this->pageBufferArray['template'] = 'produpdate';
			break;
		}

		#set html prefixes
		$this->htmlBodyTemplatePrefix = 'block.';
		
		$this->htmlPageTemplatePrefix = 'block.';

	}
	
	#error from engine
	public function errorEngine( $message='' )
	{
		die("Error from engine: ".$message);
	}
	
	#send headers
	public function sendHeaders()
	{
/*		if( isset($this->pageBufferArray['expires_date']) )
		{
			$date=new DateTime( $this->pageBufferArray['expires_date'] );	
			$this->headersArray[] = "Expires: ".$date->format("D, d M Y H:i:s")." GMT";	
			unset($date);
		}

		if( isset($this->pageBufferArray['edit_date']) )
		{
			$date=new DateTime( $this->pageBufferArray['edit_date'] );
			$this->headersArray[] = "Last-Modified:".$date->format("D, d M Y H:i:s")." GMT";
		}	

*/		if( empty($this->headersArray) ) return;
		
		foreach( $this->headersArray as $headerValue )
		{
			header( $headerValue );
		}
	}
	
	#load page data
	public function loadPage()
	{
		
		$this->pageBufferArray['unite_js'] = '0'; 
		$this->pageBufferArray['unite_css'] = '0';
		$this->pageBufferArray['closed'] = '0';

		$query = "
			SELECT 
				`p`.*,
				`s`.`closed`,
				`s`.`gzip`,
				`s`.`gzip_level`,
				`s`.`super_meta`,
				`s`.`timezone`,
				`s`.`unite_js`,
				`s`.`unite_css`,
				`s`.`global_meta_title_prefix`,
				`s`.`invert_title_prefix`
			FROM
				`parts` AS `p`
			LEFT JOIN
				`settings` AS `s`
			ON 
				`s`.`id`=:set
			WHERE 
				`p`.`url`=:uri
			AND
				`p`.`off`=:off
			LIMIT 1
		";
		
		$res = $this->db->prepare( $query );
		$res->bindValue(':set', '1', PDO::PARAM_INT);
		$res->bindValue(':uri', $this->uri, PDO::PARAM_STR);
		$res->bindValue(':off', '0', PDO::PARAM_INT);
		$res->execute();

		$row = $res->fetch();
		
		if(!$row && !$this->editedFromAdmin) $this->error404();
		
		if( $row )
		{
			#page ID
			$this->pageId = $row['id'];

			#gzip
			$this->gzip = ( $row['gzip']=='1' ) ? true : false;
			
			#gzip level
			$this->gzipLevel = ( $this->gzip ) ? $row['gzip_level'] : 0;
			
			if( function_exists("date_default_timezone_set") && $row['timezone']!='0' && $row['timezone']!='' ) date_default_timezone_set( $row['timezone'] );
			
			if( $row['closed']!='0' )
			{
				$exception_ip = false;
				
				$res = $this->db->query("SELECT `ip` FROM `exception_ip` ");
				$resArray = $res->fetchAll();
				
				if( !empty( $resArray ) )
				{
					
					$myIp = $this->ip();
					foreach( $resArray as $dataArray )
					{
						if( $myIp==$dataArray['ip'] )
						{
							$exception_ip = true;
							break;
						}
					}
				}
				
				if( !$exception_ip && !$this->editedFromAdmin )
				{ 
					$this->pageBufferArray['closed'] = $row['closed'];
					$this->siteClosed( $row['closed'] );
				}
			}

			$this->pageBufferArray['id']						= $this->pageId;
			$this->pageBufferArray['charset']					= 'utf-8';
			$this->pageBufferArray['author'	]					= 'kunano';
			$this->pageBufferArray['copyright']					= 'kunano';
			$this->pageBufferArray['language']					= $row['meta_lang'];
			$this->pageBufferArray['keywords']					= $row['meta_keywords'];
			$this->pageBufferArray['description']				= $row['meta_description'];
			$this->pageBufferArray['title']						= $row['meta_title'];
			$this->pageBufferArray['invert_title_prefix']		= $row['invert_title_prefix'];
			$this->pageBufferArray['global_meta_title_prefix']	= $row['global_meta_title_prefix'];
			$this->pageBufferArray['extra_meta']				= $row['extra_meta'];
			$this->pageBufferArray['meta_robots_all']			= $row['meta_robots_all'];
			$this->pageBufferArray['meta_robots_noindex']		= $row['meta_robots_noindex'];
			$this->pageBufferArray['meta_robots_nofollow']		= $row['meta_robots_nofollow'];
			$this->pageBufferArray['meta_robots_noarchive']		= $row['meta_robots_noarchive'];
			$this->pageBufferArray['quick_desc']				= $row['quick_desc'];
			$this->pageBufferArray['h1']						= $row['title'];
			$this->pageBufferArray['content']					= $row['content'];
			$this->pageBufferArray['super_meta']				= $row['super_meta'];
			$this->pageBufferArray['skype_block']				= $row['skype_block'];
			$this->pageBufferArray['unite_js']					= $row['unite_js'];
			$this->pageBufferArray['unite_css']					= $row['unite_css'];
			$this->pageBufferArray['expires_date']				= $row['expires_date'];
			$this->pageBufferArray['edit_date']					= $row['edit_date'];

			
			$this->pageBufferArray['title'] = ( $this->pageBufferArray['invert_title_prefix']=='0' ) ? $this->pageBufferArray['global_meta_title_prefix'].$this->pageBufferArray['title'] : $this->pageBufferArray['title'].$this->pageBufferArray['global_meta_title_prefix'];

			if( $this->pageBufferArray['closed']=='0' ) 
			{
				$this->pageBufferArray['template'] = $row['template'];

				switch( $row['cache_control'] )
				{
					case 'empty': $this->headersArray[] = "Cache-Control: "; break;
					case 'no-cache': $this->headersArray[] = "Cache-Control: no-cache"; break;
					case 'a-no-cache': $this->headersArray[] = "Cache-Control: no-store, no-cache"; break;
					case 'b-no-cache': $this->headersArray[] = "Cache-Control: no-store, no-cache, must-revalidate"; break;
					case 'c-no-cache': $this->headersArray[] = "Cache-Control: no-store, no-cache, must-revalidate, max-age=o"; break;
					case 'd-no-cache': $this->headersArray[] = "Cache-Control: no-store, no-cache, max-age=o"; break;
				}
			
				switch( $row['pragma'] )
				{
					case 'empty': $this->headersArray[] = "Pragma: "; break;
					case 'no-cache': $this->headersArray[] = "Pragma: no-cache"; break;
					case 'public': $this->headersArray[] = "Pragma: public"; break;
				}


				$date=new DateTime( $this->pageBufferArray['expires_date'] );	
				$this->headersArray[] = "Expires: ".$date->format("D, d M Y H:i:s")." GMT";	
				unset($date);
	
				$date=new DateTime( $this->pageBufferArray['edit_date'] );
				$this->headersArray[] = "Last-Modified:".$date->format("D, d M Y H:i:s")." GMT";
				unset($date);

				$this->headersArray[] = "Status: 200 OK";
				$this->headersArray[] = "HTTP/1.1 200 OK";
				$this->headersArray[] = "Accept-Language:".$row['meta_lang'].";q=0.8";
				$this->headersArray[] = "Connection: keep-alive";

			}
		}
		
		if($this->editedFromAdmin)
		{
			$this->pageBufferArray['content'] = '<iscontent>'.$this->pageBufferArray['content'].'</iscontent>';
			$this->pageBufferArray['h1'] = '<istitle>'.$this->pageBufferArray['h1'].'</istitle>';
		}

		$this->loadCssJsFiles('/');

		$this->headersArray[] = "X-Developer: DHS Nonprofit project 2010";
		$this->headersArray[] = "X-Powered-By: KUNANO";
	}

	public function ip()
	{
		$ip = '';
		
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) and filter_var(strtok(@$_SERVER['HTTP_X_FORWARDED_FOR'],','),FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=strtok($_SERVER['HTTP_X_FORWARDED_FOR'],','); 
		} 
		
		elseif(isset($_SERVER['GEOIP_ADDR']) and filter_var(@$_SERVER['GEOIP_ADDR'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['GEOIP_ADDR']; 
		}
		
		elseif(isset($_SERVER['HTTP_X_REAL_IP']) and filter_var(@$_SERVER['HTTP_X_REAL_IP'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['HTTP_X_REAL_IP']; 
		} 
		
		elseif(isset($_SERVER['HTTP_CLIENT_IP']) and filter_var(@$_SERVER['HTTP_CLIENT_IP'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['HTTP_CLIENT_IP']; 
		}
		
		elseif(isset($_SERVER['REMOTE_ADDR']) and filter_var(@$_SERVER['REMOTE_ADDR'],FILTER_VALIDATE_IP,FILTER_FLAG_IPV4)!==FALSE){ 
		$ip=$_SERVER['REMOTE_ADDR']; 
		}else{ 
		$ip='0.0.0.0'; 
		}
		
		return $ip;
	}

	#clear direcory
	public function clearDir($path, $suicide=false)
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


	#load main menu
	public function mmenuLoad()
	{
		#load data
		$query = "SELECT `id`, `pid`, `name`, `icon`, `quick_desc`, `url`, `target`, `page_range` FROM `parts` WHERE `off`=:off AND `in_menu`=:in_menu ORDER BY `pid` DESC, `page_range`";
		$res = $this->db->prepare( $query );
		$res->bindValue(':off', '0', PDO::PARAM_STR);
		$res->bindValue(':in_menu', '1', PDO::PARAM_INT);
		$res->execute();
		
		if(!$res) { $this->errorEngine("Failed to load main menu in MmenuLoad method from Core class"); }
		
		$tableArray = $res->fetchAll();

		#compile tree array
 		foreach ($tableArray as $row)
		{	
			$tree[$row['id']] = array( 
				'id'			=>	$row['id'],
				'pid'			=>	$row['pid'],
				'name'			=>	$row['name'], 
				'quick_desc'	=>	$row['quick_desc'], 
				'url'			=>	$this->config->getUrlPref().$row['url'],
				'target'		=>	$row['target'],
				'page_range'	=>	$row['page_range'],
				'active'		=>	( $this->pageId==$row['id'] ) ? 'act' : ''
			);
			
			if( $row['icon'] != '' && file_exists( $this->root.'/files/images/pages/'.$row['id'].'/'.$row['icon'] ) ) $tree[$row['id']]['icon'] =  '/files/images/pages/'.$row['id'].'/'.$row['icon'];
			
			
			if( $this->editedFromAdmin ) $tree[$row['id']]['inAirAdminMode'] = 'true';
		} 

		#paste childs items to parent and set acive class
		foreach($tree as $id => $arr)
		{
			#if PID is 0 and marker is true then set active class to main parent item and kill marker
			if( $arr['pid']=='0' && isset($actMarker) && $actMarker && isset($markPid))
			{
				$actMarker = false;
				$tree[ $markPid ]['active'] = 'act';
			}	

			if( $arr['pid']!=0 )
			{
				
				#if pid is 0 and marker is exist true then set active class to parent item
				if( $arr['pid']=='0' && isset($actMarker) && $actMarker )
				{
					$actMarker = false;
				}					
				
				#if marker is not exists then create marker and set markerPid
				if($arr['active']=='act' && !isset($actMarker))
				{
					$actMarker = true;
					$markPid = $arr['pid'];
				}	
				
				#if marker exist and id is markerPid then set markerPid and set active class to parent item
				if( isset( $actMarker ) && $actMarker && isset($markPid) && $id == $markPid )
				{

					$tree[ $id ]['active'] = 'act';
					$markPid = $arr['pid'];
				}				

				if( $this->config->mmenu['subSections'] )
				{
					$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
				}

				unset( $tree[ $id ] );
				
			}
		}
		
		$this->mmenuArray = $tree;
		
	}
	
	/* example $this->dateToRus("d F Y", strtotime('12-05-2004')) */
	public function dateToRus() {
	    $translate = array(
	    "am" => "дп",
	    "pm" => "пп",
	    "AM" => "ДП",
	    "PM" => "ПП",
	    "Monday" => "Понедельник",
	    "Mon" => "Пн",
	    "Tuesday" => "Вторник",
	    "Tue" => "Вт",
	    "Wednesday" => "Среда",
	    "Wed" => "Ср",
	    "Thursday" => "Четверг",
	    "Thu" => "Чт",
	    "Friday" => "Пятница",
	    "Fri" => "Пт",
	    "Saturday" => "Суббота",
	    "Sat" => "Сб",
	    "Sunday" => "Воскресенье",
	    "Sun" => "Вс",
	    "January" => "Января",
	    "Jan" => "Янв",
	    "February" => "Февраля",
	    "Feb" => "Фев",
	    "March" => "Марта",
	    "Mar" => "Мар",
	    "April" => "Апреля",
	    "Apr" => "Апр",
	    "May" => "Мая",
	    "May" => "Мая",
	    "June" => "Июня",
	    "Jun" => "Июн",
	    "July" => "Июля",
	    "Jul" => "Июл",
	    "August" => "Августа",
	    "Aug" => "Авг",
	    "September" => "Сентября",
	    "Sep" => "Сен",
	    "October" => "Октября",
	    "Oct" => "Окт",
	    "November" => "Ноября",
	    "Nov" => "Ноя",
	    "December" => "Декабря",
	    "Dec" => "Дек",
	    "st" => "ое",
	    "nd" => "ое",
	    "rd" => "е",
	    "th" => "ое"
	    );
	    
	    if (func_num_args() > 1) {
	        $timestamp = func_get_arg(1);
	        return strtr(date(func_get_arg(0), $timestamp), $translate);
	    } else {
	        return strtr(date(func_get_arg(0)), $translate);
	    }
	}

	/*
	 * @author Alexander Makarov
	 * @link http://rmcreative.ru/
	 * @param String $markup
	 */
	public function bbcodeToHtml($markup){
	    $markup = str_replace('&', '&', $markup);
	    $markup = str_replace('<', '<', $markup);
	    $markup = str_replace('>', '>', $markup);
	    $preg = array(    
	          // Text arrtibutes
	          '~\[s\](.*?)\[\/s\]~si'        => '<del>$1</del>',
	          '~\[b\](.*?)\[\/b\]~si'                 => '<strong>$1</strong>',
	          '~\[i\](.*?)\[\/i\]~si'                 => '<em>$1</em>',
	          '~\[u\](.*?)\[\/u\]~si'                 => '<u>$1</u>',
	          '~\[color=(.*?)\](.*?)\[\/color\]~si'   => '<span style="color:$1">$2</span>',
	          '~\[size=(.*?)\](.*?)\[\/size\]~si'     => '<span style="font-size:$1%">$2</span>',
	          
	          //align
	          '~\[leftfloat\](.*?)\[\/leftfloat\]~si' => '<div style="float: left">$1</div>',
	          '~\[rightfloat\](.*?)\[\/rightfloat\]~si' => '<div style="float: right">$1</div>',
	          '~\[center\](.*?)\[\/center\]~si'       => '<div style="text-align: center">$1</div>',
	          '~\[left\](.*?)\[\/left\]~si'           => '<div style="text-align: left">$1</div>',
	          '~\[right\](.*?)\[\/right\]~si'         => '<div style="text-align: right">$1</div>',
	          //headers
	          '~\[h1\](.*?)\[\/h1\]~si'               => '<h3>$1</h3>',
	          '~\[h2\](.*?)\[\/h2\]~si'               => '<h4>$1</h4>',
	          '~\[h3\](.*?)\[\/h3\]~si'               => '<h5>$1</h5>',
	          '~\[h4\](.*?)\[\/h1\]~si'               => '<h6>$1</h6>',
	          '~\[h5\](.*?)\[\/h2\]~si'               => '<h6>$1</h6>',
	          '~\[h6\](.*?)\[\/h3\]~si'               => '<h6>$1</h6>',
	
	          // [code=language][/code]
	          '~\[code\](.*?)\[\/code\]~si'              => '<pre><code class="no-highlight">$1</code></pre>',         
	          '~\[code=(.*?)\](.*?)\[\/code\]~si'     => '<pre><code class="$1">$2</code></pre>',               
	
	          // email with indexing prevention & @ replacement
	          '~\[email\](.*?)\[\/email\]~sei'         => "'<a rel=\"noindex\" href=\"mailto:'.str_replace('@', '.at.','$1').'\">'.str_replace('@', '.at.','$1').'</a>'",
	          '~\[email=(.*?)\](.*?)\[\/email\]~sei'   => "'<a rel=\"noindex\" href=\"mailto:'.str_replace('@', '.at.','$1').'\">$2</a>'",
	          
	          // links
	          '~\[url\]www\.(.*?)\[\/url\]~si'        => '<a href="http://www.$1">$1</a>',
	          '~\[url\](.*?)\[\/url\]~si'             => '<a href="$1">$1</a>',
	          '~\[url=(.*?)?\](.*?)\[\/url\]~si'      => '<a href="$1">$2</a>',
	          // images
    		  '~\[img\](.*?)\[\/img\]~si'             => '<img src="$1" alt="$1"/>',
    		  '~\[img width=(.*?),height=(.*?)\](.*?)\[\/img\]~si' => '<img src="$3" alt="$3" style="width: $1px; height: $2px"/>',
    		  // quoting
    		  '~\[quote\](.*?)\[\/quote\]~si'         => '<span class="quote">$1</span>',
    		  '~\[quote=(?:"|"|\')?(.*?)["\']?(?:"|"|\')?\](.*?)\[\/quote\]~si'   => '<span class="quote"><strong class="src">$1:</strong>$2</span>',

    		  //new line to <br>
    		  '~\n~' => '<br/>',

    		  //smiles
    		  '~\:\)~' 		=> '<img src="/img/wysibb/smiles/smile.gif"/>',
    		  '~\:\(~' 		=> '<img src="/img/wysibb/smiles/sad.gif"/>',
    		  '~\:D~' 		=> '<img src="/img/wysibb/smiles/biggrin.gif"/>',
    		  '~\;\)~' 		=> '<img src="/img/wysibb/smiles/wink.gif"/>',
    		  '~\:good\:~' 	=> '<img src="/img/wysibb/smiles/good.gif"/>',
    		  '~\:bad\:~' 	=> '<img src="/img/wysibb/smiles/bad.gif"/>',
    		  '~\:shock\:~' => '<img src="/img/wysibb/smiles/chok.gif"/>',
    		  '~\:angry\:~' => '<img src="/img/wysibb/smiles/aggressive.gif"/>',
    		  '~\:bo\:~'  => '<img src="/img/wysibb/smiles/bo.gif"/>',
    		  '~\:lol\:~'  => '<img src="/img/wysibb/smiles/lol.gif"/>',
    		  '~\:kiss\:~'  => '<img src="/img/wysibb/smiles/kiss.gif"/>',
    		  '~\:rofl\:~'  => '<img src="/img/wysibb/smiles/rofl.gif"/>',

    		  //reply block
    		  '~\[reply\](.*?)\[\/reply\]~si' => '<span class="reply_users">$1</span>',
    		  
    		  //replace block
    		  '~\[replace\](.*?)\[\/replace\]~si' => '<span class="replace_comment_mess">$1</span>',

    		  //security
    		  '~script~'  => 'sсriрt'
  		);
  
  		return preg_replace(array_keys($preg), array_values($preg), $markup);
	}

	public function declOfNum($number, $titles)
	{
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
	}

	#get folder name by id
	#@param $id - for example, a item ID
	public function getFolderById($id)
	{
		return intval($id / 50);
	}

}
?>