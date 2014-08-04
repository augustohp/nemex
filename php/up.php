<?php
	define('NEMEX_PATH', '../');

	include(NEMEX_PATH.'auth.php');
	include_once(NEMEX_PATH.'php/functions.php');

	function imagecreatefromjpegexif($filename) {	
        $img = imagecreatefromjpeg($filename);
        $exif = exif_read_data($filename);
        if ($img && $exif && isset($exif['Orientation']))
        {
            $ort = $exif['Orientation'];
            echo "orientation: ".$ort." \n";
            if ($ort == 6 || $ort == 5)
                $img = imagerotate($img, 270, null);
            if ($ort == 3 || $ort == 4)
                $img = imagerotate($img, 180, null);
            if ($ort == 8 || $ort == 7)
                $img = imagerotate($img, 90, null);

            if ($ort == 5 || $ort == 4 || $ort == 7)
                imageflip($img, IMG_FLIP_HORIZONTAL);
        }
        return $img;
    }


	$filehash = substr(randomHash(), 12);

	$project = $_POST['project'];


	$path_parts = pathinfo($_FILES['file']['name']);
	$count = count($_FILES['file']['name']);
	$extension = strtolower($path_parts['extension']);


	$uploadedfile = $_FILES['file']['tmp_name'];



	if($extension=="jpg" || $extension=="jpeg") 
		$src = imagecreatefromjpegexif($uploadedfile);
	else if($extension=="png") 
		$src = imagecreatefrompng($uploadedfile);
	else if($extension=="gif")
		$src = imagecreatefromgif($uploadedfile);
	else
		exit();



	$exif = exif_read_data($uploadedfile);

	$width = $exif['COMPUTED']['Width'];
	$height = $exif['COMPUTED']['Height'];

	$ext = 	strtolower($path_parts['extension']);
	$name = time().'-'.$filehash.'.'.$ext;
	$filename = NEMEX_PATH.'projects/'.$project.'/'.$name;

	if( !$exif) {
		list($width, $height, $type, $attr) = getimagesize($_FILES['file']['tmp_name']);
	}	

	if($width >= 800) {
		$newwidth = 800;
		
		
		if( $exif['Orientation'] == 5 || $exif['Orientation'] == 6 || $exif['Orientation'] == 8)
			$newheight = intval( $newwidth / ($height/$width)  );	
		else 
			$newheight = intval( ($height/$width) * $newwidth );
		
		
		$tmp=imagecreatetruecolor($newwidth,$newheight);

		// preserve transparency
		if($extension == "gif" or $extension == "png"){
		    imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
		    imagealphablending($tmp, false);
		    imagesavealpha($tmp, true);
		}


		if( $exif['Orientation'] == 5 || $exif['Orientation'] == 6 || $exif['Orientation'] == 8)	
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $height, $width);
		else 
			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight, $width, $height);

		
		switch($extension){
		    case 'bmp': imagewbmp($tmp, $filename); break;
		    case 'gif': imagegif($tmp, $filename); break;
		    case 'jpg': imagejpeg($tmp, $filename); break;
		    case 'jpeg': imagejpeg($tmp, $filename); break;
		    case 'png': imagepng($tmp, $filename); break;
		}

		imagedestroy($tmp);
		$filepath = NEMEX_PATH.'projects/'.$project.'/big/'.$name;
		move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
	}
	else {
		$ext = 	strtolower($path_parts['extension']);
		$name = time().'-'.$filehash.'.'.$ext;
		$filepath = NEMEX_PATH.'projects/'.$project.'/'.$name;
		
		if(move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
			copy($filepath, NEMEX_PATH.'projects/'.$project.'/big/'.$name);
		}


	}

	imagedestroy($src);
	
?>