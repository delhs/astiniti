<?
$text="<?
class Mod".ucfirst($modName)." extends Mod{

	public \$modName = '".$modName."';
	public \$modNameRu = '".$modNameRu."';
	
	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		ob_start();
		\$this->ajax(array('action'=>'get_tpl_one'));
		\$this->template->assign('welcomescreen', ob_get_clean());
	}
	
	public function render()
	{
		#paste your code here for view module
		
		\$this->template->display('default.tpl');

	}
	
	#@param \$moduleId - removed module id
	#@param \$fromPageId - page from which the module is removed
	public function delete( \$moduleId, \$fromPageId )
	{
		//echo 'remve module id is '.\$moduleId.' from page id is '.\$fromPageId;
		#this method is initialized when the module was removed in admin panel
	}
	
	public function install()
	{
		#installing module
	}
	
	public function ajax( \$postArray = array() )
	{
		switch( \$postArray['action'] )
		{
			case 'get_tpl_one':
				\$this->template->display('get_tpl_one.tpl');
			break;
		}
	}

	#get folder name by id
	#@param \$id - for example, a item ID
	public function getFolderById(\$id)
	{
		return intval(\$id / 50);
	}
}

?>";  
?>