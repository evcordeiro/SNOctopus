<?php
require_once('config.php');
//require_once('../../lib/functions.php');
require_once('class-xhttp-php/class.xhttp.php');



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

public function postToAPI($feed_data = NULL, $credentials = NULL){
	
	xhttp::load('profile,oauth');
	$twitter = new xhttp_profile();
	$key ='87l3QJ3z5UYrGEI6njrekA';
	$sec ='2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg';
	$twitter->oauth($key, $sec);
	$twitter->oauth_method('get'); 

	/*debug only*/
	if(!$credentials['oauth_token'])
	{
		$credentials['oauth_token']= '267829590-38kCQPSk9LWsAj7aEMpEi5ifDtBtxWHHIsFLgsKU';
		$credentials['oauth_token_secret']= 'nJdUtTY6nGjW76rMNgWNmZxDbDabQ8jupO4gKJUJsig';

	}

	$twitter->set_token($credentials['oauth_token'], $credentials['oauth_token_secret']);

	/* Begin parsing stuff */
	
	$data = array();
	//if there is no link post title
	if($feed_data['bitlyURL']){
		$data['post']['status'] = $feed_data['bitlyURL'];
	}else{
		$data['post']['status'] = $feed_data['title'];
	}
		echo "posting to ".$network_label."'s twitter";
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	
	/* End parsing stuff */
	
	//post the data to Twitter
	$response = $twitter->fetch('https://api.twitter.com/1/statuses/update.json', $data);
 
	/*debug only*/
	if($response['successful']) 
	{
		return true;
	} 
	else 
	{
		return false;
	}
	/*deprecated*/
	return response;

}

public function getUserInfo( $credentials = null)
	{
	xhttp::load('profile,oauth');
	$twitter = new xhttp_profile();
	$twitter->oauth(CONSUMER_KEY, CONSUMER_SECRET);
	$twitter->oauth_method('get'); 

	/*debug only*/
	if(!$credentials['oauth_token'])
	{
		$credentials['oauth_token']= '267829590-38kCQPSk9LWsAj7aEMpEi5ifDtBtxWHHIsFLgsKU';
		$credentials['oauth_token_secret']= 'nJdUtTY6nGjW76rMNgWNmZxDbDabQ8jupO4gKJUJsig';

	}

	$twitter->set_token($credentials['oauth_token'], $credentials['oauth_token_secret']);

	$data = null;

	//post the data to Twitter
	$response = $twitter->fetch('http://api.twitter.com/1/account/verify_credentials.json', $data);
 

	if($response['successful']) 
	{
		$userinfo['name'] = $response['body']['name'];
		$userinfo['screen_name'] = $response['body']['screen_name'];
		$userinfo['id'] = $response['body']['id'];
		$userinfo['picture'] = striplashes($response['body']['profile_image_url']);
		
		return $userinfo;
	} 
	else 
	{
		return false;
	}

	}
}
?>
