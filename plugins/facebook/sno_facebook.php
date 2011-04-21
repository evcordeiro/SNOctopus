<?php/* * * file: sno_facebook.php * Revision: 0.5 * authors: Fabio Elia, Lior Ben-kiki, Evan Cordeiro, * Thomas Norden, Royce Stubbs, Elmer Rodriguez * license: GPL v3 * This file is part of SNOctopus. * * SNOctopus is free software: you can redistribute it and/or modify * it under the terms of the GNU General Public License as published by * the Free Software Foundation, either version 3 of the License, or* (at your option) any later version. * * SNOctopus is distributed in the hope that it will be useful, * but WITHOUT ANY WARRANTY; without even the implied warranty of * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the * GNU General Public License for more details. * * You should have received a copy of the GNU General Public License * along with SNOctopus. If not, see http://www.gnu.org/licenses/ **/?><?php
class facebook{

	function postToAPI($feed_data = null,$credentials = null){
		
		if( $feed_data == null or $credentials == null )
		{
			return false;
		}
		
		/*test creds */
        $access_token = "155751477815713|26f65f46a701d2c731f16145-100002186135214|1dUOCa1o8YGI6TG9vFSzZBRojvw";
		$id = "167168166665156";
		
		/* real creds */
		/*
		$id = $credentials['id'];
		$access_token = $credentials['access_token'];
		*/
	    preg_match_all('!http://[a-z0-9\-\.\/]+\.(?:jpe?g|png|gif)!Ui' , strip_tags($feed_data['content'],'<img>'), $matches);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/" . (string)$id ."/feed");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		$data = array(
		    'access_token' => $access_token,
		    'message' => strip_tags($feed_data['content']),
		    'link' =>  strip_tags($feed_data['link']),
		    'picture' => $matches[0][0],
		    //'source' =>  strip_tags($information['link'])
		);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
	
		/* better error control needed here */
		return true;
	
	}
	
	function getUserInfo( $credentials = null)
	{
		if($credentials == null)
		{
			return false;
		}
	
		$graph_url = "https://graph.facebook.com/" . (string)($credentials['id'] . "?" . $credentials['access_token'];

		$user = json_decode(file_get_contents($graph_url));

		if(isset($user['error']))
		{
			return null;
		}
		
		/*= use ?type=small | normal | large*/
		$credentials['picture'] = "graph.facebook.com/" . $credentials['id'] . "/picture?type=small";
		$credentials['name'] = $user->name;
		$credentials['username'] = $user->username;
		
	
		return $credentials;
	
	}
	
	
}
?>
