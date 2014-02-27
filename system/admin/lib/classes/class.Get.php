<?
@session_start();

class Get{

	protected $core;
	protected $template;
	protected $img;
	protected $db;
	protected $pageId = 0;
	protected $panel = '';
	protected $root = '';
	protected $post = array();
	protected $config;
	protected $uid;
	protected $userPrivilege;
	
	#switch methods
	public function __construct(){
		
		$this->init();
		
		if( $this->post['get']=='undefined' || is_array($this->post['get']) )
		{
			$post = ( $this->post['get']!='undefined' ) ? $this->post['get'] : null;
			$this->getPanel( $this->post['panelName'], $post );
			return;
		}
		
		
		switch( $this->post['get'] )
		{
			case 'init':
				if( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['init'] ) ) die();
				
				$this->core->init();
				$retStr = $this->core->loadedJsFilesArray;
				$retStr[] = $this->config->currentLang;
				$retStr[] = (bool)$this->config->multylang;
				$retStr[] = $this->config->getUrlPref();
				$retStr[] = (!empty($this->core->themesArray)) ? json_encode($this->core->themesArray): 'NULL';
				$retStr[] = (isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'])) ? $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] : 'NULL';
				$retStr[] = (isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username'])) ? $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['username'] : 'NULL';
		
				$retStr = json_encode( $retStr );
				
				$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['init'] = 'true';
				die( $retStr );
			break;
			
			#check server connection
			case 'connection':
				if( !isset($auth) )
				{
					$auth = new auth();
				}
				$auth->updateUserPrivilegies();
				$autorization =  ( isset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] ) ) ? true : false;

				$responseArray = array(
					'response'		=>	(bool)true,
					'serverDate'	=>	(string)date("d-m-Y"),
					'serverTime'	=>	(string)date("H:i:s"),
					'ip'			=>	(string)$this->core->getIp(),
					'autorization'	=>	(bool)$autorization
				);
				die( json_encode( $responseArray ) );
				
			break;
			
			#set current page id
			case 'setId' :		
				$res = $this->db->prepare("SELECT `url`, `name` FROM `parts` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $this->post['action']);
				$res->execute();
				$resArray = $res->fetch();
				if( $resArray!==false )
				{
					$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'] = $this->post['action'];
					$respArray = array(
						'id'	=>	$this->post['action'],
						'url'	=>	$this->config->getUrlPref().$resArray['url'],
						'name'	=>	$resArray['name']
					);
					die( json_encode( $respArray ) );
				}
			break;
			
			#set language and multylang_bd
			case 'setLang':
				$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['multylang_bd'] = $this->post['action'];
			break;
			
			#get panel			
			case 'undefined' :	
				$this->getPanel( $this->panel );
			break;	

			#get main menu
			case 'mainMenu' :	
				$this->template->assign('mmenuArray', $this->core->mmenuArray);
				if( !empty( $this->core->modNamesArray ) ) $this->template->assign('modNamesArray', $this->core->modNamesArray);
				if( !empty( $this->core->plugNamesArray ) ) $this->template->assign('plugNamesArray', $this->core->plugNamesArray);
				if( !empty( $this->core->registeredMmenuItemsArray ) )$this->template->assign('registeredMmenuItemsArray', $this->core->registeredMmenuItemsArray);
				if( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] == 'developer' ) $this->template->assign('developer', 'developer');
				$this->template->display('main_menu.tpl');
			break;			
		}
	
	}
	
	public function log( $panel_name, $comment, $page_id=0 )
	{
		$this->core->log( $panel_name, $comment, $page_id );
	}
	
	#just set vars
	public function init()
	{
		$this->pageId = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['page_id'];
		$this->panel = $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['panel'];
		$this->post = $_POST;
		$this->core = $GLOBALS['core'];
		$this->template = $GLOBALS['template'];
		$this->img = $GLOBALS['img'];
		$this->db = $GLOBALS['db'];
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->config = $this->core->config;
		$this->uid = $this->core->uid;
		$this->userPrivilege = $this->core->userPrivilege;
	}
	
	
	#loading panels
	protected function getPanel( $panelName, $post=null )
	{
		
		if(file_exists( $this->root.$this->core->adminDir.'/panels/class.panel.'.$panelName.'.php' ))
		{
			$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['panel'] = $panelName;

			include $this->root.$this->core->adminDir.'/panels/class.panel.'.$panelName.'.php';
			eval("\$panel = new ".$panelName."();");
			$panel->post = ( $post!=null ) ? $post : array();
			$panel->load();
			$panel->render();
		}else{
			echo 'class "class.panel.'.$panelName.' from path "'.$this->core->adminDir.'/panels/" is not exists';
		}
	}
	
	public function upload( $files, $post )
	{

		$errorMessage = '';
		
		#if upload is successfuly
		if( $files[ $post['xfilename'] ]['error']=='0' )
		{
			
			#create temporary directory if not exist
			if(!is_dir($this->root.$this->core->tempDir)) mkdir($this->root.$this->core->tempDir, 0777);
			
			#set output filename
			$outputFile = ($files[ $post['xfilename'] ]['name']);
			
			#set file external
			$tmpArr = explode(".", $outputFile);
			$fileExternal = strtolower(array_pop($tmpArr));
			unset($tmpArr);	 
			
			#set output full filename
			$outputFile = uniqid().'.'.$fileExternal;
			
			#move file to temp directory
			if( move_uploaded_file( $files[ $post['xfilename'] ]['tmp_name'], $this->root.$this->core->tempPath.$outputFile ))
			{
				
				if( !empty( $files ) )
				{
					foreach( $files as &$dataArray )
					{
						$dataArray['name'] = $outputFile;
						$dataArray['tmp_name'] = $this->root.$this->core->tempPath.$outputFile;
					}
				}			
				
			}else{
				$errorMessage = 'Ошибка загрузки файла. Попробуйте другой файл.';
			}
		}	
	
		
		if( method_exists( $this, 'uploadFile' )  )
		{
			$errorMessage = $this->uploadFile( $files, $post );
		}
		
		die( '<script type="text/javascript">window.parent.admin.uploadResponse( "'.$outputFile.'", "'.$errorMessage.'");</script>');
				
		
	}
}
?>