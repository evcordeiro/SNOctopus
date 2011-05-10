<?php

/* 
* 
* file: sno_twitter.php 
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


class twitter{

function postToAPI($information = null){
	require 'class-xhttp-php/class.xhttp.php';
	$consumer_key='87l3QJ3z5UYrGEI6njrekA';
	$consumer_secret_key='2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg';

	echo "posting...";

	session_name('snoOAuthTwitter');
	//session_start();

	xhttp::load('profile,oauth');
	$tumblr = new xhttp_profile();
	$tumblr->oauth($consumer_key, $consumer_secret_key);
	$tumblr->oauth_method('get'); 

	//stuff we will have to store in database for each user
	$_SESSION['user_id'] = 'norden.tom@gmail.com';
	$_SESSION['screen_name'] = 'tom_norden';
	$_SESSION['oauth_token'] = '267829590-38kCQPSk9LWsAj7aEMpEi5ifDtBtxWHHIsFLgsKU'; 
	$_SESSION['oauth_token_secret'] = 'nJdUtTY6nGjW76rMNgWNmZxDbDabQ8jupO4gKJUJsig';  
	$_SESSION['loggedin'] = true;
	$tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

	//data that makes up the post
	
	$data = array();

	/*$data['post'] = array(
	'type' => 'link',
	'name' => $information['title'],
	'url' => $information['link'],
	'description' => $information['content'],
	'generator' => 'SNOctopus',
	);*/

	$data['post']['status'] = 'a tweet from SNOctopus!';

	//post the datat to Tumblr
	$response = $tumblr->fetch('http://api.twitter.com/1/statuses/update.json', $data);
 
	var_dump($response);
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
