<?php

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


	setlocale(LC_ALL, 'en_US.UTF8');

	function clearUTF($s)
	{
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




?>