<?php

//start session
session_start();

// soundcloud api wrapper
require 'Soundcloud.php';


// credentials for new instance of services soundcloud
require 'credScUser.php';


//create new instance
$scUser = new Services_Soundcloud(CLIENT_ID , CLIENT_SECRET , REDIRECT_URI) ;


//get oauth code
$authorizeUrl = $scUser->getAuthorizeUrl();

echo "<pre>";

echo "<a href='$authorizeUrl'> Connect with SoundCloud</a><br>";

//set session to token returned from array
try {
		
		//check if SESSION is set to access token returned from sc. will only work if code is returned in URL
		 
		if(!isset($_SESSION['token'])){
			
			/*Obtain 'code' in order to request a access token. input this into function. 
			 * returns array which includes access token*/
			
			$accessToken = $scUser->accessToken($_GET['code']);
			
			//set SESSION to access token(from accesstoken array)
			$_SESSION['token'] = $accessToken['access_token'];
			
		}
		
		//if the session already has the access token
		 
		else{
			
			/*set our classes accesstoken property to the session data(set after following initial
			connect to soundcloud link) this will happen on refresh of page
			 */
			$scUser->setAccessToken($_SESSION['token']);
		}
	
}  catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
	
    exit($e->getMessage());
}


//run search 

$username = 'ali britt';

$tracksSearchQ = json_decode($scUser->get('tracks', array('q' => $username)), true);
//print_r($tracksSearchQ);
//print_r($tracksSearchQ[0]['permalink_url']);

//select trackurls(permalink url) from returned array
//cycle through top level array
foreach($tracksSearchQ as $trackInfo){
	//cycle through track info. array 
	foreach($trackInfo as $key=>$value){
	 // echo "$key . 'is' . $value . '<br>'";
	 
		//and identify urls
		if ($key == 'permalink_url'){
			//not sure what this does
			$scUser->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
			
			//echo "$key . 'is' . $value . '<br>'";
			try{
				
				//set var to array urls
				$track_url = $value;
				//print_r($track_url) ;
				echo "<br>";
				//$embed_info = json_decode($scUser->get('oembed', array('url' => $track_url)));
				
				//embed tracks using url and oembed code
				$embed_info = json_decode($scUser->get('oembed', 
					array('url' => $track_url , 'maxheight' => '150', 'maxwidth' => '400')));
				 
			
				// render the html for the player widget
				print $embed_info->html;
				echo "<br>";
	
			//print_r($embed_info);
			
			}catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			    exit($e->getMessage());
					}
		}
		
		else{
			//echo "nope <br> ";
			
		}

	
	}
 
} 
	//print_r($url);
//}


//Embedding a SoundCloud Widget for all results
//use [permalink_url] => returned from $tracksSearch
/*
$scUser->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));

try{
	$track_url = $tracksSearchQ[0]['permalink_url'];//'https://soundcloud.com/alibritt/o-bi-o-ba';
	//print_r($track_url) ;
	echo "<br>";
	//$embed_info = json_decode($scUser->get('oembed', array('url' => $track_url)));
	
	$embed_info = json_decode($scUser->get('oembed', 
		array('url' => $track_url , 'maxheight' => '150', 'maxwidth' => '400')));
	 

	// render the html for the player widget
	print $embed_info->html;
	echo "<br>";
	
	//print_r($embed_info);
	
}catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    exit($e->getMessage());

}
*/

?>