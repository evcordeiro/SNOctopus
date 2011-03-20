<?php

/* 
* 
* file: sno_tumblr.php 
* Revision: 0.5 
* authors: Fabio Elia, Lior Ben-kiki, Evan Cordeiro, 
* Thomas Norden, Royce Stubbs, Elmer Rodriguez 
* license: GPL v3 
* This file is part of SNOctopus. 
* 
* SNOctopus is free software: you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by 
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version. 
* 
* SNOctopus is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* 
* You should have received a copy of the GNU General Public License 
* along with SNOctopus. If not, see http://www.gnu.org/licenses/ 
*
*/


require 'class-xhttp-php/class.xhttp.php';
class tumblr{

function postToAPI($information = null){
/*
	session_name('snoOAuthTumblr');
	session_start();*/

	xhttp::load('profile,oauth');
	$tumblr = new xhttp_profile();
	$tumblr->oauth($consumer_key, $consumer_secret_key);
	$tumblr->oauth_method('get'); 

	//stuff we will have to store in database for each user
	$_SESSION['user_id'] = 'norden.tom@gmail.com';
	$_SESSION['screen_name'] = 'tnorden';
	$_SESSION['oauth_token'] = '7AWBUeaUFEKK5pbPgOVRZBLtxJ4x1seS8bar1JY83X3pHn6rJj'; 
	$_SESSION['oauth_token_secret'] = 'C9us5fF3sHmVc8wqOXyt8Uvya4ZIL4rnGg1pceM4jvkD9BHpl2';  
	$_SESSION['loggedin'] = true;
	$tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

	//data that makes up the post
	
	$data = array();

	$data['post'] = array(
	'type' => 'link',
	'name' => $information['title'],
	'url' => $information['link'],
	'description' => $information['content'],
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

}

}
?>
