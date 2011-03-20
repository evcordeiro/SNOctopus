<?php/* * * file: sno_facebook.php * Revision: 0.5 * authors: Fabio Elia, Lior Ben-kiki, Evan Cordeiro, * Thomas Norden, Royce Stubbs, Elmer Rodriguez * license: GPL v3 * This file is part of SNOctopus. * * SNOctopus is free software: you can redistribute it and/or modify * it under the terms of the GNU General Public License as published by * the Free Software Foundation, either version 3 of the License, or* (at your option) any later version. * * SNOctopus is distributed in the hope that it will be useful, * but WITHOUT ANY WARRANTY; without even the implied warranty of * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the * GNU General Public License for more details. * * You should have received a copy of the GNU General Public License * along with SNOctopus. If not, see http://www.gnu.org/licenses/ **/?><?php
class facebook{

	function postToAPI($information = null,$cred = null){
		//$access_token = $cred['token'];
		$access_token = "155751477815713%7Cc7fe28c8cc1fad8003168b7b-1181677758%7Cy6ekZvTIwCw4G_M2Yh_IkDEqzYE";

	
	      preg_match_all('!http://[a-z0-9\-\.\/]+\.(?:jpe?g|png|gif)!Ui' , strip_tags($information['content'],'<img>'), $matches);

		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/me/feed");
		curl_setopt($ch, CURLOPT_URL, "https://graph.facebook.com/167168166665156/feed");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);

		$data = array(
		    'access_token' => $access_token,
		    'message' => strip_tags($information['content']),
		    'link' =>  strip_tags($information['link']),
		    'picture' => $matches[0][0],
		    //'source' =>  strip_tags($information['link'])
		);
		var_dump($data);	
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		echo "<br>";
		var_dump($info);
	}
}
?>
