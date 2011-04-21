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
/*$info = array();
$info['oauth_token']= '7AWBUeaUFEKK5pbPgOVRZBLtxJ4x1seS8bar1JY83X3pHn6rJj';
$info['oauth_token_secret']= 'C9us5fF3sHmVc8wqOXyt8Uvya4ZIL4rnGg1pceM4jvkD9BHpl2';
$info['title']= '*TITLE*';
$info['link']= 'http://www.nor-dev.com';//'http://sno.wamunity.com/test/ui';
$info['content']= '*CONTENT*';
echo '<h1>tumblr Test</h1>';
$tumblr = new tumblr;
$tumblr->postToAPI($info);*/

class tumblr{

function postToAPI($information = null){
	$consumer_key='nTu0OIggfxbJXuJ1NShuB2Mr2ce7WBjXkM74rhTVRoWXCryEQ5';
	$consumer_secret_key='QFsyJxnri7elEOzpzzR5dmtndQfGLYDb1FSMPkzVR5f1nkCGGE';
	xhttp::load('profile,oauth');
	$tumblr = new xhttp_profile();
	$tumblr->oauth($consumer_key, $consumer_secret_key);
	$tumblr->oauth_method('get'); 

	if(!$information['oauth_token'])
	{
		$information['oauth_token']= '7AWBUeaUFEKK5pbPgOVRZBLtxJ4x1seS8bar1JY83X3pHn6rJj';
		$information['oauth_token_secret']= 'C9us5fF3sHmVc8wqOXyt8Uvya4ZIL4rnGg1pceM4jvkD9BHpl2';

	}
	$tumblr->set_token($information['oauth_token'], $information['oauth_token_secret']);

	//data that makes up the post
	
	$data = array();

	$data['post'] = array(
	'type' => 'regular',
	'title' => $information['title'],
	'body' => preg_replace("/[^a-zA-Z0-9\s]/", "", strip_tags(stripHTML($information['content'])))
	//'body' => html_entity_decode( strip_tags(stripHTML($information['content'])))
	);

	//echo "unmod content:<br>";
	//echo "<pre>" . $information['content'] . "</pre>";
	//echo "<br><br>striptags htmlentititydecode<br><br>";
	
	//$cont = htmlentities($information['content'], ENT_QUOTES | ENT_IGNORE );
	//echo $cont;
	//echo (strip_tags(html_entity_decode($cont, ENT_QUOTES)));
	/*
	echo (strip_tags(html_entity_decode($information['content'], ENT_NOQUOTES, 'ISO-8859-1')));
	echo (strip_tags(html_entity_decode($information['content'], ENT_QUOTES, 'ISO-8859-15')));
	echo (strip_tags(html_entity_decode($information['content'], ENT_COMPAT, 'UTF-8')));
	*/
	
	//post the datat to Tumblr
	$response = $tumblr->fetch('http://www.tumblr.com/api/write', $data);
 
	//verify the post was successful
	if($response['successful']) {
	echo "Update successful!<br><br>";
	} else {
	echo "Update failed. {$response[body]}<br><br>";
		print_r($information['content']);
	}
	return response;

}

}
?>
