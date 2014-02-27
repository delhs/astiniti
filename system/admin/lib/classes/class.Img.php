<?

		/*
		1	IMAGETYPE_GIF
		2	IMAGETYPE_JPEG
		3	IMAGETYPE_PNG
		4	IMAGETYPE_SWF
		5	IMAGETYPE_PSD
		6	IMAGETYPE_BMP
		7	IMAGETYPE_TIFF_II (порядок байт intel)
		8	IMAGETYPE_TIFF_MM (порядок байт motorola)
		9	IMAGETYPE_JPC
		10	IMAGETYPE_JP2
		11	IMAGETYPE_JPX
		12	IMAGETYPE_JB2
		13	IMAGETYPE_SWC
		14	IMAGETYPE_IFF
		15	IMAGETYPE_WBMP
		16	IMAGETYPE_XBM
		17	IMAGETYPE_ICO */

class Img
{
	
	public $source = null;
	public $output = null;
	public $errorReport = null;
	
	private $root = '';
	private $type = null;
	
	
	
	public function __construct()
	{
		$this->root = $_SERVER['DOCUMENT_ROOT'];

	}
	
	#check file, is image?
	public function isImg( $fileName )
	{
		$type = exif_imagetype( $fileName ) or $this->errorReport = 'Ошибка расширения. Расширение &laquo;extension=php_exif.dll&raquo; не инициализировано.';
		
		if( $type != IMAGETYPE_JPEG	&&	$type != IMAGETYPE_PNG && $type != IMAGETYPE_WBMP && $type != IMAGETYPE_GIF )
		{
			$this->errorReport = 'Некорректный тип файла.';
			return false;
		}
		return true;
	}
	
	#resize image
	public function resize( $newWidth, $newHeight=null, $alpha=true )
	{

		if( $this->source==null ){ $this->errorReport = 'Исходный файл не выбран'; return false; }
		if( $this->output==null ){ $this->errorReport = 'Конечный файл не указан'; return false; }
		if( !file_exists($this->source) ){ $this->errorReport = 'Исходный файл не существует'; return false; }


		$this->type = exif_imagetype( $this->source ) or $this->errorReport = 'Ошибка расширения. Расширение &laquo;extension=php_exif.dll&raquo; не инициализировано.';
		if( $this->type != IMAGETYPE_JPEG	&&	$this->type != IMAGETYPE_PNG &&	$this->type != IMAGETYPE_WBMP && $this->type != IMAGETYPE_GIF )
		{
			$this->errorReport = 'Некорректный тип файла.';
		}
		
		list($width_orig, $height_orig) = getimagesize( $this->source );
		if( $newHeight==null )$newHeight = (int)(($newWidth * $height_orig) / $width_orig);
		
		$img = imagecreatetruecolor($newWidth, $newHeight);			
			
			
		switch( $this->type )
		{
			case IMAGETYPE_JPEG:
						$current_image = imagecreatefromjpeg( $this->source );
						imagecopyresampled($img, $current_image, 0, 0, 0, 0, $newWidth, $newHeight, $width_orig, $height_orig);
						imagejpeg($img, $this->output, 100);
						break;
						
			case IMAGETYPE_PNG:
						if( $alpha )
						{
							imageAlphaBlending($img, false);
							imageSaveAlpha($img, true);
						}
						$current_image = imagecreatefrompng( $this->source );
						imagecopyresampled($img, $current_image, 0, 0, 0, 0, $newWidth, $newHeight, $width_orig, $height_orig);
						imagepng($img, $this->output, 9);
						break;
						
			case IMAGETYPE_WBMP:
						$current_image = imagecreatefromwbmp( $this->source );
						imagecopyresampled($img, $current_image, 0, 0, 0, 0, $newWidth, $newHeight, $width_orig, $height_orig);
						imagewbmp($img, $this->output);
						break;	
						
			case IMAGETYPE_GIF:
						$current_image = imagecreatefromgif( $this->source );
						imagecopyresampled($img, $current_image, 0, 0, 0, 0, $newWidth, $newHeight, $width_orig, $height_orig);
						imagegif($img, $this->output);
						break;	
		}
		return true;
	}
	
	#crop image
	public function crop( $x1, $y1, $crop_width, $crop_height, $alpha=true )
	{					
		if( $this->source==null ){ $this->errorReport = 'Исходный файл не выбран'; return false; }
		if( $this->output==null ){ $this->errorReport = 'Конечный файл не указан'; return false; }
		if( !file_exists($this->source) ){ $this->errorReport = 'Исходный файл не существует'; return false; }
		
		$this->type = exif_imagetype( $this->source ) or $this->errorReport = 'Ошибка расширения. Расширение &laquo;extension=php_exif.dll&raquo; не инициализировано.';
		if( $this->type != IMAGETYPE_JPEG	&&	$this->type != IMAGETYPE_PNG &&	$this->type != IMAGETYPE_WBMP && $this->type != IMAGETYPE_GIF )
		{
			$this->errorReport = 'Некорректный тип файла.';
		}
		
		$img = imagecreatetruecolor($crop_width, $crop_height);	

		switch( $this->type )
		{
			case IMAGETYPE_JPEG:
						$current_image = imagecreatefromjpeg( $this->source );
						if( !imagecopy($img, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height) ) { $this->errorReport = 'Ошибка'; return false;};	
						imagejpeg($img, $this->output, 100);
						break;
						
			case IMAGETYPE_PNG:
						if( $alpha )
						{
							imageAlphaBlending($img, false);
							imageSaveAlpha($img, true);
						}
						$current_image = imagecreatefrompng( $this->source );
						imagecopy($img, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height);	
						imagepng($img, $this->output, 9);
						break;
						
			case IMAGETYPE_WBMP:
						$current_image = imagecreatefromwbmp( $this->source );
						imagecopy($img, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height);	
						imagewbmp($img, $this->output);
						break;	
						
			case IMAGETYPE_GIF:
						$current_image = imagecreatefromgif( $this->source );
						imagecopy($img, $current_image, 0, 0, $x1, $y1, $crop_width, $crop_height);	
						imagegif($img, $this->output);
						break;	
		}
		return true;
	}
	
	#copy image
	public function copy()
	{
		if( $this->source==null ){ $this->errorReport = 'Исходный файл не выбран'; return false; }
		if( $this->output==null ){ $this->errorReport = 'Конечный файл не указан'; return false; }
		if( !file_exists($this->source) ){ $this->errorReport = 'Исходный файл не существует'; return false; }
		if( copy( $this->source, $this->output ) ){
			return true;
		}else{
			return false;
		};
	}	
	
	public function move()
	{
		if( $this->source==null ){ $this->errorReport = 'Исходный файл не выбран'; return false; }
		if( $this->output==null ){ $this->errorReport = 'OКонечный файл не указан'; return false; }
		if( !file_exists($this->source) ){ $this->errorReport = 'Исходный файл не существует'; return false; }
		if( rename( $this->source, $this->output ) ){
			return true;
		}else{
			return false;
		};
	}

}

?>