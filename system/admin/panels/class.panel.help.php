<?
@session_start();

class help extends Get{

	
	public function __construct()
	{
		//parent::__construct( );
		parent::init();
	}
	

	public function load()
	{
		if(!empty( $this->core->plugNamesArray ) ) $this->template->assign('plugNamesArray', $this->core->plugNamesArray);
		if(!empty( $this->core->registeredMmenuItemsArray ) ) $this->template->assign('registeredMmenuItemsArray', $this->core->registeredMmenuItemsArray);
		if(!empty( $this->core->modNamesArray ) ) $this->template->assign('modNamesArray', $this->core->modNamesArray);
		
		//print_r( $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['userprivileges'] );
		
	}
		
	public function render()
	{
		#display template
		$this->template->display('panel.help.tpl');
		
	}
	
	public function uploadFile( $files, $post )
	{
		return "";
	}
	
	
}
?>