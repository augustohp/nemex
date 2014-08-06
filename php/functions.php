<?php

	function debug($d = '', $title = '') {
		if (error_reporting()) {
			if ($title) {
				debug('<b>'.print_r($title, true).":</b>\n");
			}
			echo '<pre>' . print_r($d, true) . "</pre>\n";
		}
	}

	function randomHash() {
	    return md5(uniqid(rand(), true).microtime().microtime());
	}


	/**
	 * Find the position of the Xth occurrence of a substring in a string
	 * @param $haystack
	 * @param $needle
	 * @param $number integer > 0
	 * @return int
	 */
	function strposX($haystack, $needle, $number){
	    if($number == '1'){
	        return strpos($haystack, $needle);
	    }elseif($number > '1'){
	        return strpos($haystack, $needle, strposX($haystack, $needle, $number - 1) + strlen($needle));
	    }else{
	        return error_log('Error: Value for parameter $number is out of range');
	    }
	}



	function clearUTF($s)
	{
        if (false === extension_loaded('iconv')) {
            return utf8_decode($s);
        }

	    $r = '';
	    $s1 = iconv('UTF-8', 'ASCII//TRANSLIT', $s);
	    for ($i = 0; $i < strlen($s1); $i++)
	    {
	        $ch1 = $s1[$i];
	        $ch2 = mb_substr($s, $i, 1);

	        $r .= $ch1=='?'?$ch2:$ch1;
	    }
	    return $r;
	}


    function normalizeFilePath($path)
    {
        return str_replace(array('/', '\\'), DS, $path);
    }

function redirect($urlPath)
{
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['PHP_SELF']);
    $urlPath = ($path == '/') ? $urlPath : $path.'/'.$urlPath;
    $urlPath = str_replace('//', '/', $urlPath);

    $destinationUrl = sprintf('//%s/%s', $hostname, $urlPath);
    header('Location: '.$destinationUrl);
    exit;
}

