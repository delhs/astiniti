<?
@session_start();

class Helper extends Get{

	protected $root = '';
	
	protected $pluginFolders = array(
		0 	=> '/front/',
		1 	=> '/front/tpl/',
		2 	=> '/front/cache/',
		3 	=> '/front/tpl_compile/',
		4 	=> '/front/js/',
		5 	=> '/front/css/',
		6 	=> '/back/',
		7 	=> '/back/tpl/',
		8 	=> '/back/cache/',
		9 	=> '/back/tpl_compile/',
		10 	=> '/back/js/',
		11 	=> '/back/css/'
	);	

	protected $moduleFolders = array(
		0 	=> '/front/',
		1 	=> '/front/tpl/',
		2 	=> '/front/cache/',
		3 	=> '/front/tpl_compile/',
		4 	=> '/front/js/',
		5 	=> '/front/css/',
		6 	=> '/back/',
		7 	=> '/back/tpl/',
		8 	=> '/back/cache/',
		9 	=> '/back/tpl_compile/',
		10 	=> '/back/js/',
		11 	=> '/back/css/'
	);

	protected $snippetsPlugPath = '/system/admin/helper/snippets/plug/';
	protected $snippetsModPath = '/system/admin/helper/snippets/mod/';

	public function __construct()
	{
		parent::init();
	}

	protected function trimUTF8BOM($data){ 
		if(substr($data, 0, 3) == pack('CCC', 239, 187, 191)) {
			return substr($data, 3);
		}
		return $data;
	}	

	public function load()
	{
 		if( !isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']) || $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] != 'developer') die('access denied');
	}
		
	public function render()
	{
		#display template
		if( !isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']) || $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] != 'developer') die('access denied');
		
		if(!isset( $_POST['get']['action'] )) return;

		$this->template->assign('developer', 'developer');

		switch( $_POST['get']['action'] )
		{
			case 'createplugin':
				$this->template->assign('createplugin', 'createplugin');
			break;

			case 'createmodule':
				$this->template->assign('createmodule', 'createmodule');
			break;
		}

		$this->template->display('panel.helper.tpl');
	}
	
	
	public function save( $post )
	{
		if( !isset($_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid']) || $_SESSION[ $_SERVER['HTTP_HOST'] ]['admin']['uid'] != 'developer') die('access denied');

		$validate = new Validate();
		$validate->addToValidate('name', $post['name'], 'notnull');
		$validate->addToValidate('class_name', $post['class_name'], 'notnull');
		if( $validate->validate() )
		{
			$post['class_name'] = preg_replace('/[^a-zA-Z0-0_]+/', '', $post['class_name']);
			$post['class_name'] = lcfirst($post['class_name']);
		}

		switch($post['action'])
		{
			case 'createplugin': 
				if( is_dir($this->root.'/plug/'.$post['class_name']) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['class_name'] = 'Данное имя уже используется. Задайте другое имя для плагина';
					die( json_encode($tmpArray) );
				}
				$this->createPlugin($post['class_name'], $post['name']); 
			break;

			case 'createmodule': 
				if( is_dir($this->root.'/mod/'.$post['class_name']) )
				{
					$tmpArray = json_decode($validate->error, true);
					$tmpArray['validate'] = 'error';
					$tmpArray['class_name'] = 'Данное имя уже используется. Задайте другое имя для модуля';
					die( json_encode($tmpArray) );
				}
				$this->createModule($post['class_name'], $post['name']); 
			break;
		}

		die( $validate->error );
	}
	
	
	protected function createPlugin($plugName, $plugNameRu)
	{
		
		foreach( $this->pluginFolders as $path )
		{
			mkdir( $this->root.'/plug/'.$plugName.$path, 0777, true );
		}
				
		#create plug.php		
		$handle = fopen($this->root.'/plug/'.$plugName.'/front/plug.php', 'x');
		include_once $this->root.$this->snippetsPlugPath.'front/plug.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
				
				
		#create default.tpl		
		$handle = fopen($this->root.'/plug/'.$plugName.'/front/tpl/default.tpl', 'x');
		include_once $this->root.$this->snippetsPlugPath.'front/tpl/default.tpl.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 	
		
		
		#create default.css		
		$handle = fopen($this->root.'/plug/'.$plugName.'/front/css/default.css', 'x');
		include_once $this->root.$this->snippetsPlugPath.'front/css/default.css.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 		
				
				
		#create default.js		
		$handle = fopen($this->root.'/plug/'.$plugName.'/front/js/default.js', 'x');
		include_once $this->root.$this->snippetsPlugPath.'front/js/default.js.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
		
		#create plug.php from back	
		$handle = fopen($this->root.'/plug/'.$plugName.'/back/plug.php', 'x');
		include_once $this->root.$this->snippetsPlugPath.'back/plug.php';
		$text = $this->trimUTF8BOM($text);			
		fputs($handle, $text); 
		fclose($handle); 
		
		#create default.tpl	from back			
		$handle = fopen($this->root.'/plug/'.$plugName.'/back/tpl/default.tpl', 'x');
		include_once $this->root.$this->snippetsPlugPath.'back/tpl/default.tpl.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
		
		#create default.js from back		
		$handle = fopen($this->root.'/plug/'.$plugName.'/back/js/default.js', 'x');
		include_once $this->root.$this->snippetsPlugPath.'back/js/default.js.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
	}

	protected function createModule($modName, $modNameRu)
	{
		foreach( $this->moduleFolders as $path )
		{
			mkdir( $this->root.'/mod/'.$modName.$path, 0777, true );
		}

		#create mod.php		
		$handle = fopen($this->root.'/mod/'.$modName.'/front/mod.php', 'x');
		include_once $this->root.$this->snippetsModPath.'front/mod.php';
		$text = $this->trimUTF8BOM($text); 
		fputs($handle, $text); 
		fclose($handle); 
				
				
		#create default.tpl		
		$handle = fopen($this->root.'/mod/'.$modName.'/front/tpl/default.tpl', 'x');
		include_once $this->root.$this->snippetsModPath.'front/tpl/default.tpl.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 	
		
		
		#create default.css		
		$handle = fopen($this->root.'/mod/'.$modName.'/front/css/default.css', 'x');
		include_once $this->root.$this->snippetsModPath.'front/css/default.css.php';
		$text = $this->trimUTF8BOM($text); 
		fputs($handle, $text); 
		fclose($handle); 		
				
				
		#create default.js		
		$handle = fopen($this->root.'/mod/'.$modName.'/front/js/default.js', 'x');
		include_once $this->root.$this->snippetsModPath.'front/js/default.js.php';
		$text = $this->trimUTF8BOM($text); 
		fputs($handle, $text); 
		fclose($handle); 
		
		
		
		#create mod.php	from back	
		$handle = fopen($this->root.'/mod/'.$modName.'/back/mod.php', 'x');
		include_once $this->root.$this->snippetsModPath.'back/mod.php';
		$text = $this->trimUTF8BOM($text); 
		fputs($handle, $text); 
		fclose($handle); 
		
		#create default.tpl	from back			
		$handle = fopen($this->root.'/mod/'.$modName.'/back/tpl/default.tpl', 'x');
		include_once $this->root.$this->snippetsModPath.'back/tpl/default.tpl.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
		
		#create default.js from back		
		$handle = fopen($this->root.'/mod/'.$modName.'/back/js/default.js', 'x');
		include_once $this->root.$this->snippetsModPath.'back/js/default.js.php';
		$text = $this->trimUTF8BOM($text);
		fputs($handle, $text); 
		fclose($handle); 
	}
	
}
?>