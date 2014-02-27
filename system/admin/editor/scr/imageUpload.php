<?php

#return directory array
function search_file($dir, $ext)
{
	$result = array();
	$all_folder = array(rtrim($dir, '/'));
	while($folder = array_shift($all_folder))
	{
	$result = array_merge($result, glob($folder . $ext, GLOB_NOSORT));
		$next_folder = glob($folder . '/*', GLOB_ONLYDIR);
		$all_folder = array_merge($all_folder, $next_folder);
	}
	return $result;
}
 

 




	#root
	$root = $_SERVER['DOCUMENT_ROOT'];
	
	#path for original images
	$pathForFull = '/files/';
	
	#folder name
	$folder = 'images';

	$responseArray = array();
	$fullPathForFull = $root.$pathForFull.$folder.'/';

	$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
	
	if ($_FILES['file']['type'] == 'image/png'
	|| $_FILES['file']['type'] == 'image/jpg'
	|| $_FILES['file']['type'] == 'image/gif'
	|| $_FILES['file']['type'] == 'image/jpeg'
	|| $_FILES['file']['type'] == 'image/pjpeg')
	{
		#create directory if not exist
		if(!is_dir( $fullPathForFull) ) mkdir( $fullPathForFull, 777 );
		
		#get new file name	  
		$ext = explode(".", $_FILES['file']['name']);
		$ext = array_pop($ext);
		$ext = strtolower($ext); 
		
		$filename = uniqid().'.'.$ext;	  
   
		#move file
		move_uploaded_file($_FILES['file']['tmp_name'], $fullPathForFull.$filename);

		#response
		$responseArray = array(
			'filelink' => str_replace($root, '', $fullPathForFull.$filename)
		);

	}
	
	echo stripslashes(json_encode($responseArray));
	die();

