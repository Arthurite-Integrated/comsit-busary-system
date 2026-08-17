<?php
/**
 * Image resize while uploading
 * @author Resalat Haque
 * @link http://www.w3bees.com/2013/03/resize-image-while-upload-using-php.html
 */
 
/**
 * Image resize
 * @param int $width
 * @param int $height
 */
 
function resize($width, $height, $a,$b){
	/* Get original image x y*/
	if($b=='pix')
	list($w, $h) = getimagesize($_FILES['photoimg_pix']['tmp_name']);elseif($b=='sign')
	list($w, $h) = getimagesize($_FILES['photoimg_sign']['tmp_name']);
	/* calculate new image size with ratio */
	$ratio = max($width/$w, $height/$h);
	$h = ceil($height / $ratio);
	$x = ($w - $width / $ratio) / 2;
	$w = ceil($width / $ratio);
	/* new file name */
		$path = 'pictures/'.$a.'.jpg';
	//$path = 'uploads/'.$_FILES['photoimg']['name'];
	//$path = 'putme_pictures/'.$_FILES['photoimg']['name'];
	/* read binary data from image file */
	if($b=='pix')
	$imgString = file_get_contents($_FILES['photoimg_pix']['tmp_name']);
	else if($b=='sign')
	$imgString = file_get_contents($_FILES['photoimg_sign']['tmp_name']);
	/* create image from string */
	$image = imagecreatefromstring($imgString);
	$tmp = imagecreatetruecolor($width, $height);
	imagecopyresampled($tmp, $image,
  	0, 0,
  	$x, 0,
  	$width, $height,
  	$w, $h);
	if($b=='pix'){
		//$t = ($_FILES['photoimg_pix']['type']);
	
	switch ($_FILES['photoimg_pix']['type']) 
	//switch $t
	{
		case 'image/jpeg':
			imagejpeg($tmp, $path, 100);
			break;
					default:
			exit;
			break;
	}

	}
	else if($b=='sign'){
		$t = ($_FILES['photoimg_sign']['type']);
		
			switch ($_FILES['photoimg_sign']['type']) 
	//switch $t
	{
		case 'image/jpeg':
			imagejpeg($tmp, $path, 100);
			break;
					default:
			exit;
			break;
	}

		
		}
	/* Save image */
	/*	case 'image/png':
			imagepng($tmp, $path, 0);
			break;
		case 'image/gif':
			imagegif($tmp, $path);
			break;*/

//	echo $path; exit;
	return $path;
	/* cleanup memory */
	imagedestroy($image);
	imagedestroy($tmp);
}
?>