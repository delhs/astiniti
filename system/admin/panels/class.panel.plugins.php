<?
@session_start();

class plugins extends Get{
	
	private $viewAllPlugins = true;
	
	public function __construct()
	{
		parent::init();
	}
	
	
	public function load()
	{
		if( $_POST[ 'get' ]!='undefined' )
		{
			$this->viewPlugin( $_POST['get']['action'] );
			
			return;
		}

		if( empty( $this->core->plugNamesArray ) ) return;
		
		$this->template->assign('plugNamesArray',  $this->core->plugNamesArray);
		
	}
		
	public function render()
	{
		#display template
		if( $this->viewAllPlugins ) $this->template->display('panel.plugins.tpl');
	}
	
	public function viewPlugin( $plugName )
	{
		if( !isset( $this->userPrivilege['plugins_edit'] )){
			die('access denied');
		}
		$this->viewAllPlugins = false;
		ob_start();
		$this->template->template_dir = $this->root.'/plug/'.$plugName.'/back/tpl/';
		$this->template->compile_dir = $this->root.'/plug/'.$plugName.'/back/tpl_compile/';
		$this->template->cache_dir = $this->root.'/plug/'.$plugName.'/back/cache';	
		
 		
		include_once $this->root.'/plug/'.$plugName.'/back/plug.php';
		eval(' $plug = new Plug'.ucfirst($plugName).'(); ');
		if( $this->config->debug ) $plug->install();
		$plug->pageId = $this->pageId;
		$plug->start();
		$plug->render();


		$this->template->template_dir = $this->root.'/system/admin/tpl/';
		$this->template->compile_dir = $this->root.'/system/admin/tpl_compile/';
		$this->template->cache_dir = $this->root.'/system/admin/cache';	
		
		$this->template->assign('xPluginArray', array(
			'nameRu'	=>	$plug->plugNameRu,
			'buffer'	=>	ob_get_clean()
		));
		
		$this->template->clear_compiled_tpl( 'panel.plugins.view.tpl' );
		$this->template->display('panel.plugins.view.tpl');
		
		unset($plug); 	
	}
	
	public function save( $post )
	{
	
		switch( $post['action'] )
		{
			case 'viewPlugin':
				ob_start();
				$plugName = $post['pluginName'];
				$this->template->template_dir = $this->root.'/plug/'.$modName.'/back/tpl/';
				$this->template->compile_dir = $this->root.'/plug/'.$modName.'/back/tpl_compile/';
				$this->template->cache_dir = $this->root.'/plug/'.$modName.'/back/cache';	
				
 				
				include_once $this->root.'/plug/'.$modName.'/back/plug.php';
				eval(' $plug = new Plug'.ucfirst($modName).'(); ');
				$plug->pageId = $this->pageId;
				$plug->start();
				$plug->render();


				$this->template->template_dir = $this->root.'/system/admin/tpl/';
				$this->template->compile_dir = $this->root.'/system/admin/tpl_compile/';
				$this->template->cache_dir = $this->root.'/system/admin/cache';	
			
				$this->template->assign('xPluginArray', array(
					'nameRu'	=>	$plug->plugNameRu,
					'buffer'	=>	ob_get_clean()
				));
				
				$this->template->clear_compiled_tpl( 'panel.plugins.view.tpl' );
				$this->template->display('panel.plugins.view.tpl');
				
				unset($plug); 
				
			break;
		}
	}
}
?>