<?php

class DesComponent {
	var $key = 'swkj1237';

	function encrypt($string) {

		$ivArray=array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
		$iv=null;
		foreach ($ivArray as $element)
			$iv.=CHR($element);


 		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );  
       $string = $this->pkcs5Pad ( $string, $size );  

		$data =  mcrypt_encrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $iv);

		$data = base64_encode($data);
		return $data;
	}

	function decrypt($string) {

		$ivArray=array(0x12, 0x34, 0x56, 0x78, 0x90, 0xAB, 0xCD, 0xEF);
		$iv=null;
		foreach ($ivArray as $element)
			$iv.=CHR($element);

		$string = base64_decode($string);
		//echo("****");
		//echo($string);
		//echo("****");
		$result =  mcrypt_decrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $iv);
   $result = $this->pkcs5Unpad( $result );  

		return $result;
	}
	
	
	 function pkcs5Pad($text, $blocksize)  
    {  
        $pad = $blocksize - (strlen ( $text ) % $blocksize);  
        return $text . str_repeat ( chr ( $pad ), $pad );  
    }  
  
    function pkcs5Unpad($text)  
    {  
        $pad = ord ( $text {strlen ( $text ) - 1} );  
        if ($pad > strlen ( $text ))  
            return false;  
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)  
            return false;  
        return substr ( $text, 0, - 1 * $pad );  
    }  
	
}


$des = new DesComponent();
echo ($test = $des->encrypt("{
	'signature' = 'ApOjfJTD1a0SxUc4sfs3UnIGCzLSWjy1eDZxvYabRMW2EFNB+RhqS4tN0r9g+eX98Zvd2Dg5OkycLE4ZwoFS74jl3MclWR2Ix3Kwx5GY3I2XHmeOqOFjeuW6i7qUW2Te3piIiVj3YXtF55lFdLhj+ZbQeRYUwWS4GysQUcQNIM/sAAADVzCCA1MwggI7oAMCAQICCGUUkU3ZWAS1MA0GCSqGSIb3DQEBBQUAMH8xCzAJBgNVBAYTAlVTMRMwEQYDVQQKDApBcHBsZSBJbmMuMSYwJAYDVQQLDB1BcHBsZSBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTEzMDEGA1UEAwwqQXBwbGUgaVR1bmVzIFN0b3JlIENlcnRpZmljYXRpb24gQXV0aG9yaXR5MB4XDTA5MDYxNTIyMDU1NloXDTE0MDYxNDIyMDU1NlowZDEjMCEGA1UEAwwaUHVyY2hhc2VSZWNlaXB0Q2VydGlmaWNhdGUxGzAZBgNVBAsMEkFwcGxlIGlUdW5lcyBTdG9yZTETMBEGA1UECgwKQXBwbGUgSW5jLjELMAkGA1UEBhMCVVMwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAMrRjF2ct4IrSdiTChaI0g8pwv/cmHs8p/RwV/rt/91XKVhNl4XIBimKjQQNfgHsDs6yju++DrKJE7uKsphMddKYfFE5rGXsAdBEjBwRIxexTevx3HLEFGAt1moKx509dhxtiIdDgJv2YaVs49B0uJvNdy6SMqNNLHsDLzDS9oZHAgMBAAGjcjBwMAwGA1UdEwEB/wQCMAAwHwYDVR0jBBgwFoAUNh3o4p2C0gEYtTJrDtdDC5FYQzowDgYDVR0PAQH/BAQDAgeAMB0GA1UdDgQWBBSpg4PyGUjFPhJXCBTMzaN+mV8k9TAQBgoqhkiG92NkBgUBBAIFADANBgkqhkiG9w0BAQUFAAOCAQEAEaSbPjtmN4C/IB3QEpK32RxacCDXdVXAeVReS5FaZxc+t88pQP93BiAxvdW/3eTSMGY5FbeAYL3etqP5gm8wrFojX0ikyVRStQ+/AQ0KEjtqB07kLs9QUe8czR8UGfdM1EumV/UgvDd4NwNYxLQMg4WTQfgkQQVy8GXZwVHgbE/UC6Y7053pGXBk51NPM3woxhd3gSRLvXj+loHsStcTEqe9pBDpmG5+sk4tw+GK3GMeEN5/+e1QT9np/Kl1nj+aBw7C0xsy0bFnaAd1cSS6xdory/CUvM6gtKsmnOOdqTesbp0bs8sn6Wqs0C9dgcxRHuOMZ2tm8npLUm7argOSzQ==';
	'purchase-info' = 'ewoJIm9yaWdpbmFsLXB1cmNoYXNlLWRhdGUtcHN0IiA9ICIyMDEzLTEyLTE5IDAwOjMwOjM2IEFtZXJpY2EvTG9zX0FuZ2VsZXMiOwoJInVuaXF1ZS1pZGVudGlmaWVyIiA9ICJiMDBlZDhiNTY3NTIyZjViM2Q5MTcyMTNhMGYxNGM1NzJlOTgzMTk5IjsKCSJvcmlnaW5hbC10cmFuc2FjdGlvbi1pZCIgPSAiMTAwMDAwMDA5Njk1MTIxMyI7CgkiYnZycyIgPSAiMS4wIjsKCSJ0cmFuc2FjdGlvbi1pZCIgPSAiMTAwMDAwMDA5Njk1MTIxMyI7CgkicXVhbnRpdHkiID0gIjEiOwoJIm9yaWdpbmFsLXB1cmNoYXNlLWRhdGUtbXMiID0gIjEzODc0NDE4MzY2ODIiOwoJInVuaXF1ZS12ZW5kb3ItaWRlbnRpZmllciIgPSAiRjFBQzVFRTUtM0U5Qi00MEZDLUE3RTctOUY4RDVFQTZBM0VDIjsKCSJwcm9kdWN0LWlkIiA9ICJjb20uemt5ei5zaXh0eSI7CgkiaXRlbS1pZCIgPSAiNzcyNjIwODA3IjsKCSJiaWQiID0gImNvbS56a3l6LnNwZWVkIjsKCSJwdXJjaGFzZS1kYXRlLW1zIiA9ICIxMzg3NDQxODM2NjgyIjsKCSJwdXJjaGFzZS1kYXRlIiA9ICIyMDEzLTEyLTE5IDA4OjMwOjM2IEV0Yy9HTVQiOwoJInB1cmNoYXNlLWRhdGUtcHN0IiA9ICIyMDEzLTEyLTE5IDAwOjMwOjM2IEFtZXJpY2EvTG9zX0FuZ2VsZXMiOwoJIm9yaWdpbmFsLXB1cmNoYXNlLWRhdGUiID0gIjIwMTMtMTItMTkgMDg6MzA6MzYgRXRjL0dNVCI7Cn0=';
	'environment' = 'Sandbox';
	'pod' = '100';
	'signing-status' = '0';
}
"));
echo "<br />";
//echo ($des->decrypt($test));

function is_utf8($string) { 
return preg_match('%^(?: 
[\x09\x0A\x0D\x20-\x7E] # ASCII 
| [\xC2-\xDF][\x80-\xBF] # non-overlong 2-byte 
| \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs 
| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte 
| \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates 
| \xF0[\x90-\xBF][\x80-\xBF]{2} # planes 1-3 
| [\xF1-\xF3][\x80-\xBF]{3} # planes 4-15 
| \xF4[\x80-\x8F][\x80-\xBF]{2} # plane 16 
)*$%xs', $string); 
}
?>