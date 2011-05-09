<?php
/* 
* 
* file: sno_bitly.php 
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


/*to use 
$bitly = new Bitly();

$shortUrl = $bitly->shortenUrl('longurl');

$longUrl = $bitly->expandUrl('shorturl');

$clicks = $bitly->('shorturl');  where $clicks['user'] are hash specific clicks and $clicks['global'] are longurl specific clicks

$referrer = $bitly->referrerUrl('shorturl');
foreach($referrer as $ref){
	$ref['referrer'];
	$ref['clicks'];
}

*/
/*for testing*/
/*
echo '<h1>Bitly</h1>';
$long = 'http://sno.wamunity.com/build/networks.php';
$bitly = new Bitly();
$short = $bitly->shortenUrl($long);
echo '<br>shortening: '.$long.'<br>'.$short.'<br>';
$expanded = $bitly->expandUrl($short);
echo '<br>expanding: '.$short.'<br>'.$expanded.'<br>';
$clicks = $bitly->clicksUrl($short);
echo '<br>user clicks: '.$clicks['user'].' global clicks:'.$clicks['global'].'<br>';
$referrer = $bitly->referrerUrl($short);
echo '<br>referrer: # of clicks';
echo '<ol>';
foreach($referrer as $ref){
	echo'<li>'.$ref['referrer'].': '.$ref['clicks'].'</li>';
}
echo '</ol>';
*/

class Bitly{
	private $break = "";
	private $api_version = "";
	private $format = "";
	private $login = "";
	private $apikey = "";	

	public function __construct() {
	$this->break = "\n";
	$this->api_version = "2.0.1";
	$this->format = "json";
	$this->login = 'tnorden';
	$this->apikey = 'R_e657b61f3df1c998041e07828de7d410';	
	}

function shortenUrl($url) {
	$shortened_url = "";
	$encoded_url = urlencode($url);
	$bitly_url = "http://api.bitly.com/v3/shorten?" . 
			"longUrl=" . $encoded_url . 
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey.
			"&format=" . $this->format; 
	$content = file_get_contents($bitly_url);

	try {
		$content = json_decode($content, true);
		if($content['status_code']!=200)
		{
			echo '<br>ERROR: stauts_code = '.$content['status_code'].'<br>';
		}else{
			$shortened_url = $content['data']['url'];
		}

	}
	catch (Exception $e) {
		echo "Caught exception: " . 
			$e->getMessage() . $break;
		exit;
	}

	return $shortened_url;
}
public function expandUrl($url) {
	$expanded_url = "";

	$hash = $this->parseBitlyUrl($url);

	$expanded_url = $this->expandUrlHash($hash);

	return $expanded_url;
}
public function clicksUrl($url){
	$hash = $this->parseBitlyUrl($url);
	$clicks = $this->clicksHash($hash);
	return $clicks;
}
public function referrerUrl($url){
	$hash = $this->parseBitlyUrl($url);
	$referrer = $this->referrerHash($hash);
	return $referrer;
}
private function parseBitlyUrl($url) {
	$parsed_url = parse_url($url);
	return trim($parsed_url['path'], "/");
}
public function expandUrlHash($hash) {
	$expanded_url = "";
	$bitly_url = "http://api.bitly.com/v3/expand?" . 
			"hash=" . $hash .
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey.
			"&format=" . $this->format; 

	$content = file_get_contents($bitly_url);

	try {
		$content = json_decode($content, true);
		if($content['status_code']!=200)
		{
			echo '<br>ERROR: stauts_code = '.$content['status_code'].'<br>';
		}else{
			$expanded_url = $content['data']['expand'][0]['long_url'];
		}
	}
	catch (Exception $e) {
		echo "Caught exception: " . 
			$e->getMessage() . $this->break;
		exit;
	}

	return $expanded_url;
}
public function clicksHash($hash){
	$clicks = array('user'=>"",'global'=>"");
	$bitly_url = "http://api.bitly.com/v3/clicks?" . 
			"hash=" . $hash.
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey.
			"&format=" . $this->format; 
	$content = file_get_contents($bitly_url);
	try{
		$content = json_decode($content, true);
		if($content['status_code']!=200)
		{
			echo '<br>ERROR: stauts_code = '.$content['status_code'].'<br>';
		}else{
			$clicks['user'] = $content['data']['clicks'][0]['user_clicks'];
			$clicks['global'] = $content['data']['clicks'][0]['global_clicks'];
		}

	}catch (Exception $e){
		echo "Caught exception: " . 
			$e->getMessage() . $this->break;
		exit;
	}
	return $clicks;
}
public function referrerHash($hash){
	$referrer = array("","");
	$bitly_url = "http://api.bitly.com/v3/referrers?" . 
			"hash=" . $hash.
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey.
			"&format=" . $this->format; 
	$content = file_get_contents($bitly_url);
	try{
		$content = json_decode($content, true);
		if($content['status_code']!=200)
		{
			echo '<br>ERROR: stauts_code = '.$content['status_code'].'<br>';
		}else{
			//print_r($content);			
			$referrer = $content['data']['referrers'];
		}

	}catch (Exception $e){
		echo "Caught exception: " . 
			$e->getMessage() . $this->break;
		exit;
	}

	/*clean up referrers
		searches url of referrers for common sources, combines like ones from known social networks (facebook, tumblr, twitter)
		all others are put under 'other'	
	*/
	$count = count($referrer);
	$twitter_loc = -1;
	$facebook_loc = -1;
	$tumblr_loc = -1;
	$other_loc = -1;
	while($count >= 0){
		$count--;
		if(stristr($referrer[$count]['referrer'],'twitter')){
			if($twitter_loc == -1){
				$twitter_loc = $count;
				$referrer[$twitter_loc]['referrer'] = 'twitter';
			}else{
				$referrer[$twitter_loc]['clicks'] += $referrer[$count]['clicks'];				
				unset($referrer[$count]);
			}
		}else if(stristr($referrer[$count]['referrer'],'facebook')){
			if($facebook_loc == -1){
				$facebook_loc = $count;
				$referrer[$facebook_loc]['referrer'] = 'facebook';
			}else{
				$referrer[$facebook_loc]['clicks'] += $referrer[$count]['clicks'];				
				unset($referrer[$count]);
			}
		}else if(stristr($referrer[$count]['referrer'],'tumblr')){
			if($tumblr_loc == -1){
				$tumblr_loc = $count;
				$referrer[$count]['referrer'] = 'tumblr';
			}else{
				$referrer[$tumblr_loc]['clicks'] += $referrer[$count]['clicks'];				
				unset($referrer[$count]);
			}
		}else{
			if($other_loc == -1){
				$other_loc = $count;
				$referrer[$other_loc]['referrer'] = 'other';
			}else{
				$referrer[$other_loc]['clicks'] += $referrer[$count]['clicks'];
				unset($referrer[$count]);
			}
		}
	}
	$referrer = array_values($referrer);
	sort($referrer);	
	return $referrer;	
}
}
?>
