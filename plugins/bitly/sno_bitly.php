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


//to use $shortUrl = shortenUrl('longurl');

/*for testing
echo '<h1>Bitly</h1>';
$long = 'http://sno.wamunity.com/build/networks.php';
$short = shortenUrl($long);
echo $long.'<br>'.$short;
*/

function shortenUrl($url) {
	$break = "\n";
	$api_version = "2.0.1";
	$format = "json";
	$login = 'tnorden';
	$apikey = 'R_e657b61f3df1c998041e07828de7d410';	
	$shortened_url = "";
	$encoded_url = urlencode($url);
	$bitly_url = "http://api.bit.ly/shorten?" . 
			"version=" .$api_version . 
			"&format=" . $format . 
			"&longUrl=" . $encoded_url . 
			"&login=" . $login . 
			"&apiKey=" . $apikey;

	$content = file_get_contents($bitly_url);

	try {
		$shortened_url = parseContent($content, $url);

	}
	catch (Exception $e) {
		echo "Caught exception: " . 
			$e->getMessage() . $break;
		exit;
	}

	return $shortened_url;
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

?>
