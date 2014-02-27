<?
watermark( urldecode($_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI']), $_SERVER['DOCUMENT_ROOT'].'/img/watermark.png', 5, true, 3);

#paste watermark into sourse image
#	@param $sourceFilename - sourse image filename
#	@param $watermarkFilename - watermark image filename (only png)
#	@param $pos - watermark position. Default - 5. (1...9)
#	@param $fitWatermark - fit watermark to image

function watermark($sourceFilename, $watermarkFilename, $pos = 5, $fitWatermark = false, $fitRatio=3)
{
	
	#set vars
	$data = array();

	#create source image if not exist
	if(!file_exists($sourceFilename))
	{
		$defaultImage = imagecreate(200, 200);
		imagealphablending($defaultImage, true);
		$data['background'] = imagecolorallocatealpha($defaultImage,231, 231, 231, 255);
		imagefilledrectangle($defaultImage, 0, 0, 200, 200,$data['background']);
		imagealphablending($defaultImage,true);
		imagepng($defaultImage, $sourceFilename, 9);
		imagedestroy($defaultImage);
		unset($defaultImage);
	}

	#create watermark image if not exist
	if(!file_exists($watermarkFilename))
	{
		$watermark = imagecreate(200, 20);
		imagealphablending($watermark, true);
		$data['textcolor'] = imagecolorallocate($watermark, 0, 0, 0);
		$data['background'] = imagecolorallocatealpha($watermark,255,255,255,127);
		imagefilledrectangle($watermark,0,0,485, 500,$data['background']);
		imagealphablending($watermark,true);
		imagestring($watermark, 2, 5, 5, $_SERVER['HTTP_HOST'], $data['textcolor'] );
		imagepng($watermark, $watermarkFilename, 9);
	}

	#get sourse image sizes
	list($data['sourceWidth'], $data['sourceHeight']) = getimagesize($sourceFilename);

	#get watermark image sizes
	list($data['watermarkWidth'], $data['watermarkHeight']) = getimagesize($watermarkFilename);	


	#create watermark
	$watermark = imagecreatefrompng($watermarkFilename);

	if( $fitWatermark )
	{	
		$data['watermarkNewWidth'] = $data['sourceWidth'] / $fitRatio;
		$data['watermarkNewHeight'] = (int)(($data['watermarkNewWidth'] * $data['watermarkHeight']) / $data['watermarkWidth']);
		$img = imagecreatetruecolor($data['sourceWidth'], $data['sourceHeight']);
		$alpha=imagecolorallocatealpha($img,255,255,255,0);
		imagefilledrectangle($img, 0, 0, $data['watermarkNewWidth'], $data['watermarkNewHeight'], $alpha);
		imagealphablending($img, false);
		imagesavealpha($img, true);
   	 	imagecopyresampled($img, $watermark,0,0,0,0, $data['watermarkNewWidth'], $data['watermarkNewHeight'], $data['watermarkWidth'], $data['watermarkHeight']);
    	$watermark = $img;
		$data['watermarkWidth'] = $data['watermarkNewWidth'];
		$data['watermarkHeight'] = $data['watermarkNewHeight'];
	}
	

	#get sourse image type
	$data['imageType'] = exif_imagetype( $sourceFilename );

    #watermark position
	#=======================#
	#		1	2	3		#
	#		4	5	6		#
	#		7	8	9		#
	#=======================#
	switch($pos)
	{
		#left-top
		case 1: 
			$data['offsetX'] = 0;
			$data['offsetY'] = 0;
		break;

		#top-center
		case 2: 
			$data['offsetX'] = ($data['sourceWidth']/2) - ($data['watermarkWidth']/2);
			$data['offsetY'] = 0;
		break;		

		#right-top
		case 3:
			$data['offsetX'] = $data['sourceWidth'] - $data['watermarkWidth']; 
			$data['offsetY'] = 0;
		break;

		#left-center
		case 4:
			$data['offsetX'] = 0; 
			$data['offsetY'] =  ($data['sourceHeight']/2) - ($data['watermarkHeight']/2);
		break;

		#center
		case 5:
			$data['offsetX'] = ($data['sourceWidth']/2) - ($data['watermarkWidth']/2);
			$data['offsetY'] = ($data['sourceHeight']/2) - ($data['watermarkHeight']/2);
		break;

		#right-center
		case 6:
			$data['offsetX'] = $data['sourceWidth'] - $data['watermarkWidth'];
			$data['offsetY'] = ($data['sourceHeight']/2) - ($data['watermarkHeight']/2);
		break;

		#left-bottom
		case 7:
			$data['offsetX'] = 0;
			$data['offsetY'] = $data['sourceHeight'] - $data['watermarkHeight'];
		break;
	
		#bottom-center
		case 8:
			$data['offsetX'] = ($data['sourceWidth']/2) - ($data['watermarkWidth']/2);
			$data['offsetY'] = $data['sourceHeight'] - $data['watermarkHeight'];
		break;
	
		#right-bottom
		case 9:
			$data['offsetX'] = $data['sourceWidth'] - $data['watermarkWidth']; 
			$data['offsetY'] = $data['sourceHeight'] - $data['watermarkHeight'];
		break;
		
		default: 
			$data['offsetX'] = 0;
			$data['offsetY'] = 0;
		break;
	}

	switch( $data['imageType'] )
	{
		case IMAGETYPE_JPEG:
			$outputImg = imagecreatetruecolor($data['sourceWidth'], $data['sourceHeight']);
			$sourceImg = imagecreatefromjpeg($sourceFilename);
			imagealphablending($outputImg, true);
			imagecopyresampled($outputImg, $sourceImg, 0, 0, 0, 0, $data['sourceWidth'], $data['sourceHeight'], $data['sourceWidth'], $data['sourceHeight']);
			imagecopy($outputImg, $watermark, $data['offsetX'], $data['offsetY'], 0, 0, $data['watermarkWidth'], $data['watermarkHeight']);			
			Header("Content-Type:image/jpeg");
			imagejpeg($outputImg, null, 100);
		break;

		case IMAGETYPE_PNG:
			$outputImg = imagecreatetruecolor($data['sourceWidth'], $data['sourceHeight']);
			$sourceImg = imagecreatefrompng($sourceFilename);
			imagealphablending($outputImg, true);
			imagecopyresampled($outputImg, $sourceImg, 0, 0, 0, 0, $data['sourceWidth'], $data['sourceHeight'], $data['sourceWidth'], $data['sourceHeight']);
			imagecopy($outputImg, $watermark, $data['offsetX'], $data['offsetY'], 0, 0, $data['watermarkWidth'], $data['watermarkHeight']);			
			Header("Content-Type:image/png");
			imagepng($outputImg, null, 9);
		break;
	}

	imagedestroy($outputImg);
	imagedestroy($watermark);
	unset($data);
	die();
}
?>