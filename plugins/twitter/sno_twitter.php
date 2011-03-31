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


/*require '../../bitly/sno_bitly.php';

require 'class-xhttp-php/class.xhttp.php';

$info = array();
$info['user_id']= 'norden.tom@gmail.com';
$info['screen_name']= 'tnorden';
$info['oauth_token']= '267829590-38kCQPSk9LWsAj7aEMpEi5ifDtBtxWHHIsFLgsKU';
$info['oauth_token_secret']= 'nJdUtTY6nGjW76rMNgWNmZxDbDabQ8jupO4gKJUJsig';
$info['title']= '*TITLE*';
$info['link']= 'http://www.nor-dev.com';//'http://sno.wamunity.com/test/ui';
$info['content']= '*CONTENT*';
echo '<h1>twitter Test</h1>';
$twitter = new twitter;
$twitter->postToAPI($info);*/

class twitter{

function postToAPI($information){
	$consumer_key='87l3QJ3z5UYrGEI6njrekA';
	$consumer_secret_key='2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg';

	echo "posting...";

	xhttp::load('profile,oauth');
	$tumblr = new xhttp_profile();
	$tumblr->oauth($consumer_key, $consumer_secret_key);
	$tumblr->oauth_method('get'); 

	if(!$information['oauth_token'])
	{
		$information['oauth_token']= '267829590-38kCQPSk9LWsAj7aEMpEi5ifDtBtxWHHIsFLgsKU';
		$information['oauth_token_secret']= 'nJdUtTY6nGjW76rMNgWNmZxDbDabQ8jupO4gKJUJsig';

	}

	$tumblr->set_token($information['oauth_token'], $information['oauth_token_secret']);

	//data that makes up the post
	
	$data = array();
	//if there is no link post title
	if($information['bitlyURL']){
		$data['post']['status'] = $information['bitlyURL'];
	}else{
		$data['post']['status'] = $information['title'];
	}
	//post the datat to Twitter
	$response = $tumblr->fetch('http://api.twitter.com/1/statuses/update.json', $data);
 
	//verify the post was successful
	if($response['successful']) 
	{
		echo "Update successful!<br><br>";
	} 
	else 
	{
		echo "Cannot duplicate post.<br>";
	}
	return response;

}

}
?>
