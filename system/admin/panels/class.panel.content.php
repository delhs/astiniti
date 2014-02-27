<?
@session_start();

class content extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	
	public static function addTemplateRegion( $params )
	{
		$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray'][ $params['name'] ]['name'] = $params['name'];
		$_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray'][ $params['name'] ]['runame'] = $params['runame'];
	}
	
	public static function nullMethod( $params )
	{
		return;
	}
	

	public function load()
	{
		
		#check modules exist
		$modulesExists = false;
		$dirArray = scandir( $this->root.'/mod/' );
		foreach($dirArray as $dirName)
		{
			if( $dirName=='.' || $dirName=='..' ) continue;
			$modulesExists = true;
			break;
		}
		#assign modules exist flag
		if( $modulesExists ) $this->template->assign('modulesExists', '1' );
		
		
		
		#load modules of this page
		$res = $this->db->prepare("SELECT * FROM `modules` WHERE `page_id`=:page_id ORDER BY `mod_range`");
		$res->bindValue(':page_id', $this->pageId);
		$res->execute();
		$resArray = $res->fetchAll();
		
		#compile attached modules
		foreach($resArray as &$data)
		{
			$modName = $data['mod_name'];
			include_once $this->root.'/mod/'.$modName.'/back/mod.php';
			eval('$mod_'.$modName.' = new Mod'.ucfirst($modName).'(); ');
			eval('$modName = $mod_'.$modName.'->modName;');
			eval('$modNameRu = $mod_'.$modName.'->modNameRu;');
			$data['mod_name_ru'] = $modNameRu;
		}
		
		#get this page template file
		$res = $this->db->prepare("SELECT `template` FROM `parts` WHERE `id`=:id");
		$res->bindValue(':id', $this->pageId);
		$res->execute();
		$result = $res->fetch();
		$pageTemplateFile = ( file_exists($this->root.'/tpl/page'.$result['template'].'.tpl') )? 'page'.$result['template'].'.tpl' : 'page.default.tpl';
		
		#set smarty config
		$this->template->template_dir = $this->root.'/tpl/';
		#clear compile template file
		
		$this->template->clear_compiled_tpl( $pageTemplateFile );
		$this->template->clear_compiled_tpl( 'body.'.$result['template'].'.tpl' );
		
		#redeclare extend smarty template function for insert modules in region
		$this->template->register_function("region", array('content', 'addTemplateRegion'), true);
		$this->template->register_function("plugin", array('content', 'nullMethod'), true);
		$this->template->register_function("pasteWord", array('content', 'nullMethod'), true);
		
		#set body file template
		$this->template->assign('body', 'body.'.$result['template'].'.tpl');
		
		#fetch template file
		$this->template->fetch( $pageTemplateFile );

		
		#compile all regions from page template file
		if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray']) ) $this->template->assign('allRegionsArray', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray']);
		unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray'] );
		
		#return smarty config
		$this->template->template_dir = $this->root.'/system/admin/tpl/';
		
		#compile attached modules array
		if( !empty( $resArray ) ) $this->template->assign('attachedModArray', $resArray);
		
		
		
		#load page content data
		$res = $this->db->prepare("SELECT `name`, `title`, `content` FROM `parts` WHERE `id`=:id LIMIT 1");
		$res->bindValue(':id', $this->pageId);
		$res->execute();
		$dataArray = $res->fetch();

		
		#assign template page
		$this->template->assign('page', $dataArray );
		
	}
		
	public function render()
	{	
		#display template
		$this->template->display('panel.content.tpl');
	}
	
	#save method
	public function save( $post )
	{
		#security
		if( !isset( $this->userPrivilege['content_edit'] )){
			die('access denied');
		}
		
		
		
		#if is air mode and get page url event
		if( isset( $post['airMode'] ) && isset( $post['id'] ) && isset($post['action']) && $post['action']=='getUrl' )
		{
			die( $this->core->getUrlFromPageId( $post['id'] ) );
		}	
		
		#if is air mode and get page id event
		if( isset( $post['airMode'] ) && isset( $post['url'] ) && isset($post['action']) && $post['action']=='getId' )
		{
			die( $this->core->getPageIdFromUrl( $post['url'] ) );
		}	
		
		#if is air mode and save only page title event
		if( isset( $post['airMode'] ) && isset($post['action']) && $post['action']=='saveTitle' )
		{
			#write to log
			$this->log('Контент', 'Изменение заголовка раздела', $this->pageId);
			
			#save
			$res = $this->db->prepare("UPDATE `parts` SET `title`=:title WHERE `id`=:id LIMIT 1 ");
			$res->bindValue(':title', $post['title']);
			$res->bindValue(':id', $this->pageId);
			$res->execute();
			die();
		}
		
		
		#if is air mode and save content event
		if( isset( $post['airMode'] ) && isset( $post['url'] ) && isset( $post['content'] ) )
		{
			$this->pageId = $this->core->getPageIdFromUrl( $post['url'] ) ;
			
			$res = $this->db->prepare("UPDATE `parts` SET `content`=:content WHERE `id`=:id LIMIT 1 ");
			$res->bindValue(':content', $post['content']);
			$res->bindValue(':id', $this->pageId);
			$res->execute();
			
			$this->log('Контент', 'Изменение контента раздела', $this->pageId);
			die();

		}
		 
		
		#write to log
		$this->log('Контент', 'Изменение контента раздела', $this->pageId);
		
		#if save data
		$res = $this->db->prepare("UPDATE `parts` SET `title`=:title, `content`=:content WHERE `id`=:id LIMIT 1 ");
		$res->bindValue(':title', $post['title']);
		$res->bindValue(':content', $post['content']);
		$res->bindValue(':id', $this->pageId);
		$res->execute();

		

	}
	
	
}
?>