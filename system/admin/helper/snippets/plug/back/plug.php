<?
$text="<?
class Plug".ucfirst($plugName)." extends Plug{

	public \$plugName = '".$plugName."';
	public \$plugNameRu = '".$plugNameRu."';
	
	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		#paste your code here
		ob_start();
		\$this->ajax(array('action'=>'get_tpl_one'));
		\$this->template->assign('welcomescreen', ob_get_clean());
		
	}
	
	public function render()
	{
		#paste your code here for view plugin
		
		\$this->template->display('default.tpl');

	}
	
	public function install()
	{
		#installing plugin
	}
	
	public function ajax( \$postArray = array() )
	{
		#paste your code here for ajax response
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