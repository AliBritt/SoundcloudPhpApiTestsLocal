<?php
// testing basic functions of soundcloud php api 

require 'Soundcloud.php' ;


//put this in seperate file
$soundcloud = new Services_Soundcloud('b2a3991985725a0f8ecca732a197f0fc' ,
				 '601b9a2b451a09fcaf93b5e2c70c9e66' ,
				 'http://sndcldtst.heliohost.org/SndCldTEst/') ;
				 
//check what this does
//$soundcloud->setDevelopment(FALSE);

//url from array of 
$authorizeUrl = $soundcloud->getAuthorizeUrl();

echo "<pre>";

echo "<a href='$authorizeUrl'> Connect with SoundCloud</a>" ;

try {
    $accessToken = $soundcloud->accessToken($_GET['code']);
	print_r($accessToken);
} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    exit($e->getMessage());
}



try {
    $me = json_decode($soundcloud->get('me'), true);
	print_r($me);
} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    exit($e->getMessage());
}


