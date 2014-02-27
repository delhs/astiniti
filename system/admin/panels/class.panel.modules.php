<?
@session_start();

class modules extends Get{
	
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
		$allModulesArray = array();
		
		#load all modules array
		$dirArray = scandir( $this->root.'/mod/' );

		foreach($dirArray as $dirName)
		{
			if( !is_dir($this->root.'/mod/'.$dirName) || $dirName=='.' || $dirName=='..' || preg_match( '/^[-]/', $dirName ) ) continue;
			include_once $this->root.'/mod/'.$dirName.'/back/mod.php';
			eval('$mod_'.$dirName.' = new Mod'.ucfirst($dirName).'(); ');
			eval('$modName = $mod_'.$dirName.'->modName;');
			eval('$modNameRu = $mod_'.$dirName.'->modNameRu;');
			$allModulesArray[ $dirName ]['mod_name'] = $modName;
			$allModulesArray[ $dirName ]['mod_name_ru'] = $modNameRu;
		}
	
		
		#load modules of this page
		$res = $this->db->prepare("SELECT * FROM `modules` WHERE `page_id`=:page_id ORDER BY `mod_range`");
		$res->bindValue(':page_id', $this->pageId);
		$res->execute();
		$resArray = $res->fetchAll();
		
		#compile $allModulesArray
		foreach($resArray as &$data)
		{
			$data['mod_name_ru'] = $allModulesArray[ $data['mod_name'] ]['mod_name_ru'];
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
		$this->template->register_function("region", array('modules', 'addTemplateRegion'), true);
		$this->template->register_function("plugin", array('modules', 'nullMethod'), true);
		$this->template->register_function("pasteWord", array('modules', 'nullMethod'), true);
		
		#set body file template
		$this->template->assign('body', 'body.'.$result['template'].'.tpl');
		
		#fetch template
		$this->template->fetch( $pageTemplateFile );

		#compile all regions from page template file
		if( isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray']) ) $this->template->assign('allRegionsArray', $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray']);
		unset( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['tempRegionArray'] );
		
		#return smarty config
		$this->template->template_dir = $this->root.'/system/admin/tpl/';
		
		#compile attached modules array
		if( !empty( $resArray ) ) $this->template->assign('attachedModArray', $resArray);

		#compile all modules array
		if( !empty( $allModulesArray ) ) $this->template->assign('allModulesArray', $allModulesArray);			

		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.modules.tpl');
	}
	
	public function save( $post )
	{
		switch( $post['action'] )
		{
			case 'addModule' :
				if( !isset( $this->userPrivilege['modules_added'] )){
					die('access denied');
				}
				$this->log('Модули', 'Подключение модуля "'.$post['moduleName'].'" к разделу', $this->pageId );
				
				$res = $this->db->prepare("INSERT INTO `modules` SET `page_id`=:page_id, `mod_name`=:mod_name, `mod_range`=:mod_range");
				$res->bindValue(':page_id', $this->pageId);
				$res->bindValue(':mod_name', $post['moduleName']);
				$res->bindValue(':mod_range', '999');
				$res->execute();
				die( $this->db->lastInsertId() );
			break;
			
			case 'rangeChange':
				if( !isset( $this->userPrivilege['modules_range'] )){
					die('access denied');
				}
				$this->log('Модули', 'Изменение порядка следования модулей', $this->pageId );
			
				unset($post['action']);
				$res = $this->db->prepare("UPDATE `modules` SET `mod_range`=:mod_range WHERE `id`=:id LIMIT 1");
				foreach($post as $mod_range => $id)
				{
					$res->bindValue(':mod_range', $mod_range);
					$res->bindValue(':id', $id);
					$res->execute();
				} 
			break;
			
			case 'regionChange':
				if( !isset( $this->userPrivilege['modules_region'] )){
					die('access denied');
				}
				$this->log('Модули', 'Изменение региона модуля', $this->pageId );
			
				$res = $this->db->prepare("UPDATE `modules` SET `region`=:region WHERE `id`=:id LIMIT 1");
				$res->bindValue(':region', $post['regionName']);
				$res->bindValue(':id', $post['moduleId']);
				$res->execute();
			break;
			
			case 'deleteModule':
				if( !isset( $this->userPrivilege['modules_remove'] )){
					die('access denied');
				}
				$this->log('Модули', 'Отключение модуля "'.$post['moduleName'].'" от раздела', $this->pageId );
			
				$modName = $post['moduleName'];
				include_once $this->root.'/mod/'.$modName.'/back/mod.php';
				eval(' $mod_'.$modName.' = new Mod'.ucfirst($modName).'(); ');
				eval(' $mod_'.$modName.' ->delete( "'.$post['moduleId'].'", "'.$this->pageId.'" ); ');
				$res = $this->db->prepare("DELETE FROM `modules` WHERE `id`=:id LIMIT 1");
				$res->bindValue(':id', $post['moduleId']);
				$res->execute();
			break;
			
			case 'viewModule':
				if( !isset( $this->userPrivilege['modules_edit'] )){
					die('access denied');
				}
				ob_start();
				$modName = $post['moduleName'];
				
				$this->template->template_dir = $this->root.'/mod/'.$modName.'/back/tpl/';
				$this->template->compile_dir = $this->root.'/mod/'.$modName.'/back/tpl_compile/';
				$this->template->cache_dir = $this->root.'/mod/'.$modName.'/back/cache';	
				
 				
				include_once $this->root.'/mod/'.$modName.'/back/mod.php';
				eval(' $mod = new Mod'.ucfirst($modName).'(); ');
				if( $this->config->debug ) $mod->install();
				$mod->modId = $post['moduleId'];
				$mod->pageId = $this->pageId;
				$mod->start();
				$mod->render();


				$this->template->template_dir = $this->root.'/system/admin/tpl/';
				$this->template->compile_dir = $this->root.'/system/admin/tpl_compile/';
				$this->template->cache_dir = $this->root.'/system/admin/cache';	
			
				$this->template->assign('xModuleArray', array(
					'nameRu'	=>	$mod->modNameRu,
					'buffer'	=>	ob_get_clean(),
					'id'	=> $mod->modId
				));
				
				$this->template->clear_compiled_tpl( 'panel.modules.view.tpl' );
				$this->template->display('panel.modules.view.tpl');
				
				unset($mod); 
				
			break;
		}
	}
	
	
}
?>