<?php
require_once("functions.php");
/*****************
*
*   file:           index.php
*	Revision: 	0.5
*   authors:        Fabio Elia, Lior Ben-kiki, Evan Cordeiro,
*					Thomas Norden, Royce Stubbs, Elmer Rodriguez 
*   license: 	GPL v3 
*	This file is part of SNOctopus.
*
*    SNOctopus is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    SNOctopus is distributed in the hsope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with SNOctopus.  If not, see <http://www.gnu.org/licenses/>.
 ******************/

/** Begin of RSS Retrieval **/ 
$feed1 = "http://www.citizenschools.org/feed/atom/";
$feed2 =  "http://snoctopus.blogspot.com/rss.xml"; 

parseFeed($feed1);
parseFeed($feed2);

function parseFeed($feed)
{

$xmlstr = file_get_contents($feed);
$sitemap = simplexml_load_string($xmlstr);

echo "<pre>";
echo "<br><b>Available Plugins:</b><br>";
print_r($sitemap	);
echo "</pre>";

/** Parse some XML **/
$source = $sitemap->generator; // Which blog is it? Wordpress? Blogspot? ./?

$tagList = $sitemap->entry[0]->category;
$tags = array();
foreach ($tagList as $tag) {
	$tags[] = $tag["term"];
}

//print_r($sitemap->generator);
/** End of RSS Retrieval **/ 


/** Begin Retrieval of Plugins **/ 

// Get the list of plugins
if ($handle = opendir('./plugins/')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
		$list[] = $file;
        }
    }
    closedir($handle);
}
//Print list of possible plugins

echo "<pre>";
echo "<br><b>Available Plugins:</b><br>";
print_r($list);
echo "</pre>";

/** End Retrieval of Plugins **/ 


/** Begin Commit to Plugins **/ 


// ATM this will only retrieve the last post. It will not check if we have already retrieved it.
// The commented for loop will allow to get all the posts. We could then parse it for the newest ones.
$count = 0;

//foreach($sitemap->entry as $key => $value){
	$information['title'] = $sitemap->entry[$count]->title;
	$information['content'] =  stripHTML($sitemap->entry[$count]->content);
	$information['link'] = $sitemap->entry[$count]->link[4][0][0]['href'];
	$information['timestamp'] = $sitemap->entry[$count]->published;
	$information['blogTime'] =  $sitemap->updated;
	$information['tags'] = $tags;
	$information['bitlyURL'] = "http://sample.com";
	$information['bitlyHash'] = "xxxxxxxxxxxx";



	foreach($list as $service ){
		// only post to our services with the sno_prefix.
		$service_file = './plugins/'.$service.'/sno_'.$service.'.php';
		if(file_exists($service_file)){
			require_once($service_file);
			$obj = new $service;
			
			echo ("<br><b>Posting to: ". $service . "<br><b>Output:</b><br>");
			$obj->postToAPI($information);
		}
	}
	
	echo ("<a href='http://snoctop.us/index.php'>http://snoctop.us/index.php</a><br>");
	echo ("<a href='http://tnorden.tumblr.com/'>http://tnorden.tumblr.com/</a><br>");
	echo ("<a href='http://snoctopus.blogspot.com/2011/03/httpsno.html'>http://snoctopus.blogspot.com/2011/03/httpsno.html</a><br>");
	echo ("<a href='http://twitter.com/search?q=snoctopus'>http://twitter.com/tom_norden</a><br>");
	echo ("<a href='http://www.facebook.com/pages/SNOctopus/167168166665156?sk=wall'>http://www.facebook.com/pages/SNOctopus/167168166665156?sk=wall</a><br>");
}
	//$count++;
//}

 /** End Commit to Plugins **/ 
 
 
 


?>

