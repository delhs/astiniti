<?
class page extends Get{

	
	public function __construct()
	{
		parent::init();
	}
	

	public function load()
	{
		#compile partitions menu
		$this->template->assign('partitions', $this->core->mmenuArray);
	}
		
	public function render()
	{
		#display template
		$this->template->display('pages.tpl');
	}

}
?>