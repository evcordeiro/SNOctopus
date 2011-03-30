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
*/
/*for testing
echo '<h1>Bitly</h1>';
$long = 'http://sno.wamunity.com/build/networks.php';
$bitly = new Bitly();
$short = $bitly->shortenUrl($long);
echo 'shortening: '.$long.'<br>'.$short.'<br>';
$expanded = $bitly->expandUrl($short);
echo 'expanding: '.$short.'<br>'.$expanded;
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
	$bitly_url = "http://api.bit.ly/shorten?" . 
			"version=" .$this->api_version . 
			"&format=" . $this->format . 
			"&longUrl=" . $encoded_url . 
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey;

	$content = file_get_contents($bitly_url);

	try {
		$shortened_url = $this->parseContent($content, $url);

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

	$expanded_url = $this->expandUrlByHash($hash);

	return $expanded_url;
}
private function parseBitlyUrl($url) {
	$parsed_url = parse_url($url);
	return trim($parsed_url['path'], "/");
}
public function expandUrlByHash($hash) {
	$expanded_url = "";
	$bitly_url = "http://api.bit.ly/expand?" . 
			"version=" . $this->api_version . 
			"&format=" . $this->format . 
			"&hash=" . $hash . 
			"&login=" . $this->login . 
			"&apiKey=" . $this->apikey;

	$content = file_get_contents($bitly_url);

	try {
		$expanded_url = $this->parseContent($content, $hash);
	}
	catch (Exception $e) {
		echo "Caught exception: " . 
			$e->getMessage() . $this->break;
		exit;
	}

	return $expanded_url;
}

function parseContent($content, $key) {
	$content = json_decode($content, true);

	if ($content['errorCode'] != 0 || 
	    $content['statusCode'] != "OK") {
		throw new Exception($content['statusCode'] . ": " . 
				$content['errorCode'] . " " . 
				$content['errorMessage']);
	}

	if (isset($content['results'][$key]['longUrl'])) {
		return $content['results'][$key]['longUrl'];
	}
	else if (isset($content['results'][$key]['shortUrl'])) {
		return $content['results'][$key]['shortUrl'];
	}
	else {
		throw new Exception("ERROR. URL not found: " . $key);
	}
}
}
?>
