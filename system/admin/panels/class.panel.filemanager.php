<?
@session_start();

class filemanager extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		if( !isset( $this->userPrivilege['filemanager'] )){
			die('access denied');
		}
		
		if( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']=='developer' )
		{
			$url = '/system/admin/editor/php/connector.ring0.php';
		}else{
			$url = '/system/admin/editor/php/connector.root.php';
		}
		
		$this->template->assign('url', $url);
		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.filemanager.tpl');
	}
	
}
?>