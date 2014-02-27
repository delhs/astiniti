<?
class Plug{
	
	public $template;
	
	public $validate;
	
	public $core;
	
	public $img;
	
	public $config;
	
	public $db;
	
	public $pageId = 0;
	
	public $root = 0;

	public $host = 0;
	
	public function __construct()
	{
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->host = $_SERVER['HTTP_HOST'];
		
		if( !isset($GLOBALS['core']) ) return;
		
		$this->db = $GLOBALS['db'];
		$this->template = $GLOBALS['template'];
		$this->validate = $GLOBALS['validate'];
		$this->core = $GLOBALS['core'];
		$this->img = $GLOBALS['img'];
		$this->config = $GLOBALS['config'];
		$this->pageId = $this->core->pageId;

		
		#extend smarty template function for paste words
		#in template set {pasteWord name="WORD_KEY"}
		$this->template->register_function("pasteWord", array('core', 'templatePasteWord'), true);		
		
	}

	
	public function upload( $files, $post )
	{
		
		
		$errorMessage = '';
		
		#if upload is successfuly
		if( $files[ $post['xfilename'] ]['error']=='0' )
		{
			
			#create temporary directory if not exist
			if(!is_dir($this->root.'/temp')) mkdir($this->root.'/temp', 0777);
			
			#set output filename
			$outputFile = ($files[ $post['xfilename'] ]['name']);
			
			#set file external
			$tmpArr = explode(".", $outputFile);
			$fileExternal = strtolower(array_pop($tmpArr));
			unset($tmpArr);		
			
			#set output full filename
			$outputFile = uniqid().'.'.$fileExternal;
			
			#move file to temp directory
			if( move_uploaded_file( $files[ $post['xfilename'] ]['tmp_name'], $this->root.'/temp/'.$outputFile ))
			{
				
				if( !empty( $files ) )
				{
					foreach( $files as &$dataArray )
					{
						$dataArray['inname'] = $dataArray['name'];
						$dataArray['name'] = $outputFile;
						$dataArray['tmp_name'] = $this->root.'/temp/'.$outputFile;
					}
				}			
				
			}else{
				$errorMessage = 'Ошибка загрузки файла. Попробуйте другой файл.';
			}
		}	
	
		if( method_exists( $this, 'uploadFile' )  )
		{
			$errorMessage = $this->uploadFile( $files, $post );
		}
		
		die( '<script type="text/javascript">window.parent.engine.plug.uploadResponse( "'.$outputFile.'", "'.$errorMessage.'");</script>');
				
		
	}	
}
?>