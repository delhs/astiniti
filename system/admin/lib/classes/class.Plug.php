<?
class Plug{
	
	public $plugName = '';
	
	public $template;
	
	public $core;
	
	public $img;
	
	public $config;
	
	public $db;

	public $pageId = 0;
	
	public $root = 0;

	public $host = 0;
	
	public $validate = 0;
	
	public function __construct()
	{
		$this->root = $_SERVER['DOCUMENT_ROOT'];
		$this->host = $_SERVER['HTTP_HOST'];
		
		if( !isset($GLOBALS['core']) ) return;
		
		$this->db = $GLOBALS['db'];
		$this->template = $GLOBALS['template'];
		$this->core = $GLOBALS['core'];
		$this->img = $GLOBALS['img'];
		$this->config = $GLOBALS['config'];
		$this->validate = $GLOBALS['validate'];
		$this->pageId = $this->core->pageId;
	}

	
	public function upload( $files, $post )
	{
		
		
		$errorMessage = '';
		
		#if upload is successfuly
		if( $files[ $post['xfilename'] ]['error']=='0' )
		{
			
			#create temporary directory if not exist
			if(!is_dir($this->root.$this->core->tempDir)) mkdir($this->root.$this->core->tempDir, 0777);
			
			#set output filename
			$outputFile = ($files[ $post['xfilename'] ]['name']);
			
			#set file external
			$tmpArr = explode(".", $outputFile);
			$fileExternal = strtolower(array_pop($tmpArr));
			unset($tmpArr);	
			
			#set output full filename
			$outputFile = uniqid().'.'.$fileExternal;
			
			#move file to temp directory
			if( move_uploaded_file( $files[ $post['xfilename'] ]['tmp_name'], $this->root.$this->core->tempPath.$outputFile ))
			{
				
				if( !empty( $files ) )
				{
					foreach( $files as &$dataArray )
					{
						$dataArray['inname'] = $dataArray['name'];
						$dataArray['name'] = $outputFile;
						$dataArray['tmp_name'] = $this->root.$this->core->tempPath.$outputFile;
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
		
		die( '<script type="text/javascript">window.parent.admin.plug.uploadResponse("'.$outputFile.'", "'.$errorMessage.'");</script>');
				
		
	}
}
?>