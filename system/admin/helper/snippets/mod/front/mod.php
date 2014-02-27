<?
$text="<?
class Mod".ucfirst($modName)." extends Mod{

	
	public function __construct()
	{
		parent::__construct();
	}


	public function start()
	{
		#paste your code here
		
		\$this->template->assign('module', array(
			'hello'	=>	'Hello from module ".$modNameRu."'
		));
		
	}
	
	public function render()
	{
		#paste your code here for view module
		
		\$this->template->display('default.tpl');
		
	}
	
	
	public function ajax( \$postArray = array() )
	{
		#paste your code here for ajax response
		echo 'I got an ajax response';
		var_dump(\$postArray);
	}	

	public function downloadFile( \$filename = '' )
	{
		//\$filename = \$this->root.'/files/file.txt';
		//\$this->core->downloadFile( \$filename, 'display file name.txt');
		return;
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