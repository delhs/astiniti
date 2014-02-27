<?
class PlugAjax extends Plug{

	public function __construct()
	{
		parent::__construct();
	}

	public function start()
	{
		#load all js files
		$dirArray = scandir( $this->root.'/mod/');
		
		foreach($dirArray as $dirName)
		{
			if( !is_dir( $this->root.'/mod/'.$dirName) || $dirName=='.' || $dirName=='..' || preg_match( '/^[-]/', $dirName ) || !file_exists( $this->root.'/mod/'.$dirName.'/front/' ) ) continue;
			$this->core->loadCssJsFiles('/mod/'.$dirName.'/front/');
		}

	}
	
	public function render()
	{
		#paste your code here for view plugin
		$this->template->display('default.tpl');
		
	}
	
	public function ajax( $postArray = array() )
	{
		$postArray['url'] = str_replace($this->core->config->protocol.'://'.$this->core->host, '', $postArray['url']);

		#set vars
		$_SERVER['REQUEST_URI'] = $postArray['url'];
		
		#set vars
		$this->core->uri = $postArray['url'];

		#reoting
		$this->core->route();

		#check to redirect
		if($this->core->getRedirect( $this->core->requestUri )!==false)
		{
			$this->core->buffer = '301 '.$this->core->getRedirect( $this->core->requestUri );
			$this->core->render();
			return;	
		}

		#main Core initialization
		$this->core->init();

		#main Core check to mobile
		$this->core->mobileDetect();

		#compile page meta data
		$metaArray = array(
			'title'			=>	(isset($this->core->pageBufferArray['title'])) ? $this->core->pageBufferArray['title'] : '',
			'description'	=>	(isset($this->core->pageBufferArray['description'])) ? $this->core->pageBufferArray['description'] : '',
			'keywords'		=>	(isset($this->core->pageBufferArray['keywords'])) ? $this->core->pageBufferArray['keywords'] : '',
			'extra_meta'	=>	(isset($this->core->pageBufferArray['extra_meta'])) ? $this->core->pageBufferArray['extra_meta'] : '',
			'super_meta'	=>	(isset($this->core->pageBufferArray['super_meta'])) ? $this->core->pageBufferArray['super_meta'] : ''
		);

		if(!isset( $this->core->pageBufferArray['content'] ))
		{
			$this->core->buffer = '404';
		}else{

			if( $this->core->pageBufferArray['closed'] != '0')
			{
				$this->core->buffer = '503 '.$this->core->pageBufferArray['closed'];

			}else{
				$this->core->pageBufferArray['content'] .= '<script type="text/javascript">plug_ajax.meta='.json_encode( $metaArray ).'</script>';
				
				#main Core compile full template
				$this->core->compileTemplate();
	
				#main Core compile body template
				$this->core->buffer = $this->template->fetch($this->core->htmlBodyTemplatePrefix.$this->core->pageBufferArray['template'].'.tpl');		

			}

		}

		#main Core display body template
		$this->core->render();

	}
	
}
?>