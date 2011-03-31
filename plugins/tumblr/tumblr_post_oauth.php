<?php

	//Specific for the App
	$consumer_key = 'nTu0OIggfxbJXuJ1NShuB2Mr2ce7WBjXkM74rhTVRoWXCryEQ5';
	$consumer_secret_key = 'QFsyJxnri7elEOzpzzR5dmtndQfGLYDb1FSMPkzVR5f1nkCGGE';

	require 'class-xhttp-php/class.xhttp.php';

	session_name('snoOAuthTumblr');
	session_start();

	xhttp::load('profile,oauth');
	$tumblr = new xhttp_profile();
	$tumblr->oauth($consumer_key, $consumer_secret_key);
	$tumblr->oauth_method('get'); 

	//stuff we will have to store in database for each user
	$_SESSION['user_id'] = 'norden.***@gmail.com';
	$_SESSION['screen_name'] = '***rden';
	$_SESSION['oauth_token'] = '7AWBUeaUFEKK5pbPgOVRZBLtxJ4x1seS8bar1JY83X3pHn6rJj'; 
	$_SESSION['oauth_token_secret'] = 'C9us5fF3sHmVc8wqOXyt8Uvya4ZIL4rnGg1pceM4jvkD9BHpl2';  
	$_SESSION['loggedin'] = true;


	$tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

	//data that makes up the post
	$data = array();
	$data['post'] = array(
	'type' => 'link',
	'name' => 'TITLE OF BLOG HERE',
	'url' => 'LINK TO BLOG HERE',
	'description' => 'PUT STUFF ABOUT THE BLOG HERE',
	'generator' => 'SNOctopus',
	);

	//post the datat to Tumblr
	$response = $tumblr->fetch('http://www.tumblr.com/api/write', $data);

	//verify the post was successful
	if($response['successful']) {
	echo "Update successful!<br><br>";
	} else {
	echo "Update failed. {$response[body]}<br><br>";
	}
	return response;
?>


