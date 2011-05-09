<?php
/* * * file: sno_facebook.php * Revision: 0.5 * authors: Fabio Elia, Lior Ben-kiki, Evan Cordeiro, * Thomas Norden, Royce Stubbs, Elmer Rodriguez * license: GPL v3 * This file is part of SNOctopus. * * SNOctopus is free software: you can redistribute it and/or modify * it under the terms of the GNU General Public License as published by * the Free Software Foundation, either version 3 of the License, or* (at your option) any later version. * * SNOctopus is distributed in the hope that it will be useful, * but WITHOUT ANY WARRANTY; without even the implied warranty of * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the * GNU General Public License for more details. * * You should have received a copy of the GNU General Public License * along with SNOctopus. If not, see http://www.gnu.org/licenses/ **/?><?php

//require_once('../../lib/functions.php');

class facebook{

	function postToAPI($feed_data = null,$credentials = null){
		
		
		if( $feed_data == null or $credentials == null )
		{
			return false;
		}
		
		
		/*debug only*/
		if($credentials == null)
		{
			$credentials['access_token'] = "155751477815713|26f65f46a701d2c731f16145-100002186135214|1dUOCa1o8YGI6TG9vFSzZBRojvw";
			$credentials['id'] = "167168166665156";
		}

		/* fancy parsing stuff here: */
		
	    preg_match_all('!http://[a-z0-9\-\.\/]+\.(?:jpe?g|png|gif)!Ui' , strip_tags($feed_data['content'],'<img>'), $matches);
		
		$data = array(
		    'access_token' => $credentials['access_token'],
		    'message' => strip_tags($feed_data['content']),
		    'link' =>  strip_tags($feed_data['link']),
		    'picture' => $matches[0][0],
		    //'source' =>  strip_tags($information['link'])
		);
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/" . (string)($credentials['id']) ."/feed");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
	
		/* 
		$result will be false on fail, 
		on success will be some sort of array, as CURLOPT_RETURNTRANSFER is set
		*/
		return $result;
	
	}
	
	function getUserInfo( $credentials = null)
	{
		if($credentials == null)
		{
			return false;
		}
	
		$graph_url = "https://graph.facebook.com/" . (string)($credentials['id']) . "?" . $credentials['access_token'];

		$user = json_decode(file_get_contents($graph_url));

		if(isset($user['error']))
		{
			return null;
		}
		
		/*= use ?type=small | normal | large*/
		$userinfo['picture'] = "graph.facebook.com/" . $credentials['id'] . "/picture?type=small";
		$userinfo['name'] = $user->name;
		$userinfo['username'] = $user->username;

		return $userinfo;
	
	}
	
	
}
?>
