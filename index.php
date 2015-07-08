<?php
// testing basic functions of soundcloud php api 

session_start();
// sort out session_destroy();


require 'Soundcloud.php' ;


//put this in seperate file
//creates new instance of class and calls contructor that takes vars from soundcloud api .com
$soundcloud = new Services_Soundcloud('b2a3991985725a0f8ecca732a197f0fc' ,
				 '601b9a2b451a09fcaf93b5e2c70c9e66' ,
				 'http://scapi.rrshost.in/test/') ;
		
		
				 
//check what this does
$soundcloud->setDevelopment(FALSE);



/*
 * url from array of??
returns url to soundcloud.com/connect containing client id and secret, responce type=code and
redirect uri set in services() 
after link followed and user signed into soundcloud(Oauth2), 'code' is in url
*/
$authorizeUrl = $soundcloud->getAuthorizeUrl();



echo "<pre>";

echo "<a href='$authorizeUrl'> Connect with SoundCloud</a>" ;

//echo "<a href= 'http://scapi.rrshost.in/test/sessDes.php'>Destroy Session</a> ";



try {
		/*
		 * check if SESSION is set to access token returned from sc
		 * will only work if code is returned in URL
		 */
		if(!isset($_SESSION['toke'])){
			
			/*Obtain 'code' in order to request a access token. input this into function. 
			 * returns array which includes access token*/
			
			$accessToken = $soundcloud->accessToken($_GET['code']);
			
			//print_r($accessToken);
			//set SESSION to access token(from accesstoken array)
			$_SESSION['toke'] = $accessToken['access_token'];
			
			/*why use session at all? 
			 * why not skip straight to defining class property accessToken to our 'access_token' from array
			 * ie $soundcloud->setAccessToken = $accessToken['access_token'];
			 * would have to run this code every time though
			 */
			
			
	
		}
		/*if the session already has the access token
		 */
		else{
			
			//print_r($_session['token']);
			//print_r($_SESSION) ;
			
			/*set our classes accesstoken property to the session data(set after following initial
			connect to soundcloud link) this will happen on refresh of page
			 */
			$soundcloud->setAccessToken($_SESSION['toke']);
			//print_r (" function = " . $soundcloud->setAccessToken . "<br/>");
		}
	/* 
	invalid_http_responce_code exception_(a custom exception) is thrown from _request() which
	does HTTP request using cURL
	($e)is an object containing the exception information
	*/
}  catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
	/*
	getMessage() is a method of Exception()
	returns string set in custom exception
	*/
    exit($e->getMessage());
}
/*1st time through the below code works. second time it doesnt.
 *  on first run session is not set, get code is used and then session set.
 *  second time session is set and accesstoken property is assigned to session data.
 */
try {
	/*
	json_decode returns ass aray
	$soundcloud->get() returns a _request()
	*/
    $me = json_decode($soundcloud->get('me'), true);
	//print_r($me);
	//create var with userid returned from $me array
	//sc->get takes tracks as url and the array with user id as parameters
	$tracks = json_decode($soundcloud->get('tracks' , array('user_id' =>'177803')), true);
	//print_r($tracks);
	
} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    exit($e->getMessage());

}

//search by genre and bpm

// find all tracks with the genre 'punk' that have a tempo greater than 120 bpm.
$tracksSearchGenre = json_decode($soundcloud->get('tracks', array('genres' => 'techno', 'bpm[from]' => 110)), true);

//print_r($tracksSearchGenre);


//search for a user

$username = 'ali britt';

$tracksSearchQ = json_decode($soundcloud->get('tracks', array('q' => $username)), true);

print_r($tracksSearchQ );



//Embedding a SoundCloud Widget
/*
$soundcloud->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));

try{
	$track_url = 'https://soundcloud.com/alibritt/o-bi-o-ba';
	$embed_info = json_decode($soundcloud->get('oembed', array('url' => $track_url)));

	// render the html for the player widget
	print $embed_info->html;
	
}catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
    exit($e->getMessage());

}
 * */
// post comment
/*try {
	/* sc->post takes tracks/trackid/comments as url and array comment body as parameter
	 * this is different to git docs
	 * works fine without json_decode
	 */
/*	$commment = json_decode($soundcloud->post('tracks/196549988/comments', array(
	'comment[body]' => 'posting comment from soundcloud api wrap')
		)
	);
		
}catch (Services_Soundcloud_Invalid_Http_Responce_code_exception $e) {
	exit($e->getMessage());
}*/
//session_destroy();
?>