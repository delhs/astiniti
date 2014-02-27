<?
class Core
{
	public $adminDir = '/system/admin';

	public $adminPath = '/system/admin/';

	public $tempDir = '/system/admin/temp';
	
	public $tempPath = '/system/admin/temp/';

	#main uri
	public $uri = '';
	
	#page id after loadPage()
	public $pageId = 0;
	
	#main original uri
	public $requestUri = '';
	
	#headers array
	public $headersArray = array();
	
	#config class
	public $config;
	
	#mysql class
	public $db;
	
	#root dir
	public $root = '';
	
	#site host
	public $host = '';
	
	public $cssFilesArray = array();
	
	public $jsFilesArray = array();
	
	public $loadedJsFilesArray = array();
	
	#theme names array
	public $themesArray = array();
	
	#main buffer array to page after loadPage()
	public $pageBufferArray = array();
	
	#plugins names list array
	public $plugNamesArray = array();
	
	#modules names list array
	public $modNamesArray = array();
	
	#debug mode
	public $debug = true;
	
	#microtime if debug is true
	public $microtime = 0;
	
	#module content array
	public $modBuffer = array();
		
	#plugin content array
	public $plugBuffer = array();
	
	#main menu array after  mmenuLoad()
	public $mmenuArray = array();
	
	#user uid
	public $uid = '';
	
	#user privilegies array
	public $userPrivilege = array();
	
	#plugins that have registered themselves in the main menu
	public $registeredMmenuItemsArray = array();
	
	public function __construct()
	{
		
		$this->headersArray[] = "Status: 200 OK";
		$this->headersArray[] = "HTTP/1.1 200 OK";
		$this->headersArray[] = "Accept-Language: ru;q=0.8";
		$this->headersArray[] = "Connection: keep-alive";		
		$this->headersArray[] = "X-Developer: DHS Nonprofit project 2010";
		$this->headersArray[] = "X-Powered-By: KUNANO";
		$this->headersArray[] = "X-Version: 4.1";
		
		if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] ) ) $this->uid =  $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'];
		
		if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['userprivileges'] ) )$this->userPrivilege = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['userprivileges'];
		
		$this->db = $GLOBALS['db'];
		
		if($this->debug)
		{
			$this->microtime = microtime(true);
		}	
		
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->host = $_SERVER['HTTP_HOST'];
		$this->config = $GLOBALS['config'];
		
		$this->debug = $this->config->debug;
		
		
		$this->uri = $_SERVER['REQUEST_URI'];
		$this->uri = preg_replace('/\/{1,}/', '/', $this->uri);
		$this->uri = preg_replace('/\/{0,}$/', '', $this->uri);
		$this->uri .='/';	
	
		$this->requestUri = $this->uri;

		foreach( $this->config->protectedLinks as $link )
		{
			$this->uri = preg_replace('/\/{1}'.$link.'\/{1,1}(.*)/','/',$this->uri);
		}
		
		$this->loadPluginsNames();
		
		$this->loadModulesNames();

		#main menu load
		$this->mmenuLoad();
		
	}

	/* return url address from page id */
	public function getUrlFromPageId( $id )
	{
		$res = $this->db->prepare( "SELECT `url` FROM `parts` WHERE `id`=:id LIMIT 1" );
		$res->bindValue(':id', $id, PDO::PARAM_INT);
		$res->execute();
		$row = $res->fetch();
		if(!$row) return 0;
		return $row['url'];	
	}
	
	/* return page id from url address */
	public function getPageIdFromUrl( $url  )
	{
		
		$url = trim($url);
		
		if( $url=="/" || $url=="" ) return '1';
		
		$url = preg_replace('/\n\r\s/', '', $url);
		
		$url = preg_replace('/^'.$this->config->protocol.':\/\//', '', $url);
		$url = preg_replace('/^'.$this->host.'/', '', $url);
		
		if( $url=="/" || $url=="" ) return '1';

		$url = preg_replace('/\/{1,}/', '/', $url);
		$url = preg_replace('/\/{0,}$/', '', $url);
		$url .='/';

		foreach( $this->config->protectedLinks as $link )
		{
			$url = preg_replace('/\/{1}'.$link.'\/{1,1}(.*)/','/',$url);
		}

		$url = trim($url);
		
		if( $url=="/" || $url=="" ) return '1';
			
		$res = $this->db->prepare( "SELECT `id`	FROM `parts` WHERE `url`=:url" );
		$res->bindValue(':url', $url, PDO::PARAM_STR);
		$res->execute();
		$row = $res->fetch();
		if(!$row) return '0';

		return $row['id'];
	}
	
	public function init()
	{

		#pack css files from css dir to css array
		$this->cssFilesArray = $this->loadFilenames('/system/admin/css/', 'css');
	
		#pack js files from editor dir to loaded array
		$tmpArray = $this->loadFilenames('/system/admin/editor/css/', 'css');
		$this->cssFilesArray = array_merge ( $this->cssFilesArray , $tmpArray );
		

		#pack js files from added dir to loaded array
		$this->loadedJsFilesArray = $this->loadFilenames('/system/admin/js/added/', 'js');
		
		#pack js files from editor dir to loaded array
		$tmpArray = $this->loadFilenames('/system/admin/editor/js/', 'js');
		$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );
		
		#pack js files from editor dir to loaded array
		$tmpArray = $this->loadFilenames('/system/admin/js/core/', 'js');
		$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );
		
		
		#pack js files from js dir to js array
		$this->jsFilesArray = $this->loadFilenames('/system/admin/js/', 'js');
		
		#get themes names
		$this->themesArray = $this->loadFilenames('/system/admin/css/themes/');
		if( !empty($this->themesArray) )
		{
			foreach( $this->themesArray as $index => &$value )
			{
				if ( !preg_match('/^-/', basename($value))  ){
					$value = basename($value);
				}else{
					unset($this->themesArray[$index]);
				}
			}
		}
	
		
		
		
		if( !empty( $this->plugNamesArray ) )
		{
			foreach( $this->plugNamesArray as $data )
			{
				$tmpArray = $this->loadFilenames('/plug/'.$data['plug_name'].'/back/css/', 'css');
				$this->cssFilesArray = array_merge ( $this->cssFilesArray , $tmpArray );
				
				$tmpArray = $this->loadFilenames('/plug/'.$data['plug_name'].'/back/js/', 'js');
				$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );
			}
		}

		if( !empty( $this->registeredMmenuItemsArray ) && !empty( $this->registeredMmenuItemsArray[0] ) )
		{
			foreach( $this->registeredMmenuItemsArray as $data )
			{
				$tmpArray = $this->loadFilenames('/plug/'.$data['dataAction'].'/back/css/', 'css');
				$this->cssFilesArray = array_merge ( $this->cssFilesArray , $tmpArray );
				
				$tmpArray = $this->loadFilenames('/plug/'.$data['dataAction'].'/back/js/', 'js');
				$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );
			}
		}
		
		
		if( isset( $this->modNamesArray ) && !empty( $this->modNamesArray ) )
		{
		
			foreach( $this->modNamesArray as $data )
			{
				$tmpArray = $this->loadFilenames('/mod/'.$data['mod_name'].'/back/css/', 'css');
				$this->cssFilesArray = array_merge ( $this->cssFilesArray , $tmpArray );
				
				$tmpArray = $this->loadFilenames('/mod/'.$data['mod_name'].'/back/js/', 'js');
				$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );
			}
		}
		
		
		
		
		
	}
	
	public function loadFilenames( $pathName='', $type='', $inTheEnd=false )
	{
		if(  $pathName=='' || !is_dir( $this->root.$pathName ) ) return;
		
		$resArray = array();
		$dirArray = scandir( $this->root.$pathName );
		
		foreach($dirArray as $file)
		{
			if( $file=='.' || $file=='..' ) continue;
		
			if( $type!='' && preg_match('/^[^-].*?\.'.$type.'$/', $file) )
			{
				if( $inTheEnd ) array_unshift( $resArray, $pathName.$file);
				else $resArray[] = $pathName.$file;
			}else if( $type=='' ){
				if( $inTheEnd ) array_unshift( $resArray, $pathName.$file);
				else $resArray[] = $pathName.$file;
			}
		}

		return $resArray;		
	}
	
	
	
	
	
	
	
	#write event into log
	public function log( $panel_name, $comment, $page_id=0 )
	{
		if( $this->uid == 'developer') return;
		
		$res = $this->db->prepare("INSERT INTO `adm_log` SET `log_date`=:date, `log_time`=:time, `panel_name`=:panel_name, `page_id`=:page_id, `uid`=:uid, `log_comment`=:comment ");
		$res->bindValue(':date', date("Y-m-d"));
		$res->bindValue(':time', date("H:i:s"));
		$res->bindValue(':panel_name', $panel_name);
		$res->bindValue(':page_id', $page_id);
		$res->bindValue(':uid', $this->uid);
		$res->bindValue(':comment', $comment); 
		$res->execute();
		
		$this->db->query("DELETE FROM `adm_log` WHERE `log_date` < DATE_SUB( NOW(),INTERVAL 30 DAY)");
		
	}
	

	#load all modules from current page
	public function loadModules()
	{
		$query = "
			SELECT 
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
			$this->loadModule( $dataArray['mod_name'], $dataArray['region'] );
		}
	}
	

	#load all plugins names from project
	public function loadPluginsNames()
	{
		$dirArray = scandir( $this->root.'/plug/');

		foreach($dirArray as $dirName)
		{
			if( !is_dir($this->root.'/plug/'.$dirName) || $dirName=='.' || $dirName=='..' || preg_match( '/^[-]/', $dirName ) || !file_exists( $this->root.'/plug/'.$dirName.'/back/plug.php' ) ) continue;
	
			ob_start();
			include_once $this->root.'/plug/'.$dirName.'/back/plug.php';
			eval('$plug = new Plug'.ucfirst($dirName).'(); ');
			
			if( method_exists( $plug, "registerMmenuItem") )
			{
				$this->registeredMmenuItemsArray[] = $plug->registerMmenuItem();
				unset( $plug );
				ob_clean();
				continue;
			}
			$this->plugNamesArray[ $dirName ]['plug_name'] = $plug->plugName;
			$this->plugNamesArray[ $dirName ]['plug_name_ru'] = $plug->plugNameRu;
			unset( $plug );
			ob_clean();
		}
	}	

	#load all modules names from project
	public function loadModulesNames()
	{
		$dirArray = scandir( $this->root.'/mod/');
		foreach($dirArray as $dirName)
		{
			if( !is_dir( $this->root.'/mod/'.$dirName) || $dirName=='.' || $dirName=='..' || preg_match( '/^[-]/', $dirName ) ) continue;
			
			include_once $this->root.'/mod/'.$dirName.'/back/mod.php';
			eval('$mod = new Mod'.ucfirst($dirName).'(); ');
			$this->modNamesArray[ $dirName ]['mod_name'] = $mod->modName;
			$this->modNamesArray[ $dirName ]['mod_name_ru'] = $mod->modNameRu;
			unset( $mod );
		}
		
	}

	
	
	#load module
	#@param - module name
	#@param - region name
	public function loadModule( $modName, $regionName )
	{
		global $template;

		$template->template_dir = $this->root.'/mod/'.$modName.'/back/tpl/';
		$template->compile_dir = $this->root.'/mod/'.$modName.'/back/tpl_compile/';
		$template->cache_dir = $this->root.'/mod/'.$modName.'/back/cache';		

		ob_start();
		include $this->root.'/mod/'.$modName.'/back/mod.php';
		eval(' $mod = new Mod'.ucfirst($modName).'(); ');
		if( $this->config->debug ) $mod->install();
		$mod->start();
		$mod->render();
		
		$this->modBuffer[ $regionName ][] = ob_get_clean();
		
		//$this->loadCssJsFiles('/mod/'.$modName.'/back/');
		
		//$tmpArray = $this->loadFilenames('/mod/'.$modName.'/back/css/', 'css');
		//$this->cssFilesArray = array_merge ( $this->cssFilesArray , $tmpArray );
		//
		//$tmpArray = $this->loadFilenames('/mod/'.$modName.'/back/js/', 'js');
		//$this->loadedJsFilesArray = array_merge ( $this->loadedJsFilesArray , $tmpArray );

 		$template->template_dir = $this->root.'/tpl/';
		$template->compile_dir = $this->root.'/tpl_compile/';
		$template->cache_dir = $this->root.'/cache/';
	}
	
	#send headers
	public function sendHeaders()
	{
		if( empty($this->headersArray) ) return;
		
		foreach( $this->headersArray as $headerValue )
		{
			@header( $headerValue );
		}
		
	}
	
	
	#error from engine
	public function errorEngine( $message='' )
	{
		die("Error from engine: ".$message);
	}
	
	
	#load M main menu
	public function mmenuLoad()
	{
		$flag = false;
		#load data
		$query = "SELECT `id`, `pid`, `name`, `url`, `target`, `page_range`, `off`, `in_menu` FROM `parts` ORDER BY `pid` DESC ,`page_range`";
		$res = $this->db->prepare( $query );
		$res->execute();
		
		if(!$res) { $this->errorEngine("Failed to load main menu in MmenuLoad method from Controller class"); }
		
		$tableArray = $res->fetchAll();

		$pageId = ( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] ) ) ? $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] : 1;

		#compile tree array
 		foreach ($tableArray as $row)
		{	

			$tree[$row['id']] = array( 
				'id'=>$row['id'],
				'pid'=>$row['pid'],
				'name'=>$row['name'], 
				'url'=>$row['url'],
				'target'=>$row['target'],
				'page_range'=>$row['page_range'],
				'off'=>$row['off'],
				'in_menu'=>$row['in_menu'],
				'active'=>( $pageId==$row['id'] ) ? 'act' : ''
			);
		} 
		
		#paste childs items to parent
		
		foreach($tree as $id => $arr)
		{
			if( $arr['pid']!=0 )
			{
				#paste childs items to parent
				$tree[ $arr['pid'] ]['childNodes'][ $id ] = $tree[ $id ];
				unset( $tree[ $id ] );
			}
			if ($arr['pid']==0) continue;
			if( $pageId == $tree[ $arr['pid'] ]['id'] ) $tree[ $arr['pid'] ]['active'] = 'act';
			
		}
		
		$this->mmenuArray = $tree;
		
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
  
	public function getIp()
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
	    //return $markup;
	    $markup = str_replace('&', '&', $markup);
	    $markup = str_replace('<', '<', $markup);
	    $markup = str_replace('>', '>', $markup);
	    $preg = array(    
	          // Text arrtibutes
	          '~\[s\](.*?)\[\/s\]~si'      			  => '<del>$1</del>',
	          '~\[b\](.*?)\[\/b\]~si'                 => '<strong>$1</strong>',
	          '~\[i\](.*?)\[\/i\]~si'                 => '<em>$1</em>',
	          '~\[u\](.*?)\[\/u\]~si'                 => '<u>$1</u>',

	          '~\[color=(.*?)\]~si'						=>	'<span style="color:$1">',
	          '~\[\/color\]~si'							=>	'</span>',

	          '~\[size=(.*?)\]~si'						=>	'<span style="font-size:$1%">',
	          '~\[\/size\]~si'							=>	'</span>',


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

}
?>