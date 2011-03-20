<?php
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
