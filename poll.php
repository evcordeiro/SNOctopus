<?php
require_once("lib/functions.php");
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


require_once 'lib/db/sno_db_interface.php';
require_once 'bitly/sno_bitly.php';

 // GET ACTIVE MAPS AND NETWORK IDS
$query = "SELECT * FROM maps
			LEFT JOIN networks ON networks.network_id = maps.network_id
			WHERE maps.active_state = 1";
$pdoStatement = sno_db_interface::executePreparedQueryN($query,array('1'));
$activeFeeds = $pdoStatement->fetchAll();	
	


foreach($activeFeeds as $feed){
	echo "<pre>";
	print_r($feed);
	echo "</pre>";
	echo "</br></br>Feed: ". $feed['feed_url'];
	echo "<BR>posting to ".$feed['network_label']."'s ".$feed['network_name']."<BR>";
	// GET ALL POSTS 
	$query = "SELECT publish_date FROM posts WHERE feed_url = ? and network_id = ?";
	$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($feed['feed_url'],$feed['network_id']));
	$activeNetworks = $pdoStatement->fetchAll();	
	
	
	
	if(empty($activeNetworks)){
		//FIND EMPTY POSTS AND CHECK IF THEY ARE ACTIVE
		$query = "SELECT active_state from maps where feed_url = ? and network_id = ?";
		$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($feed['feed_url'],$feed['network_id']));
		$activeMap = $pdoStatement->fetchAll();	
		if($activeMap[0]['active_state']){
			//var_dump(unserialize(base64_decode($feed['credentials'])));
			$creds = unserialize(base64_decode($feed['credentials']));
 			parseFeed($feed['feed_url'], $feed['network_name'], $creds, $feed['network_id'],getPublishDate($feed['feed_url']));// $feed['credentials']);	
		} 
		// if parseFeed Successful then lets update the db			 		
			
	}else{
		
		$query = "SELECT active_state, publish_date from posts 
					LEFT JOIN maps ON posts.feed_url = maps.feed_url and posts.network_id = maps.network_id
					WHERE maps.feed_url = ? and maps.network_id = ? and STRCMP(posts.publish_date, ?)
					ORDER BY publish_date DESC
					";
		$query = "SELECT MAX(publish_date) as publish_date,post_id FROM posts 
					WHERE feed_url = ?
					GROUP BY feed_url, network_id";
		$pdoStatement = sno_db_interface::executePreparedQueryN($query,array($feed['feed_url']));
		$activeMap = $pdoStatement->fetchAll();	
		
				
		//Check if most recent post in db matches is older then blog's post
		echo "time1:".strtotime($activeMap[0]['publish_date'])."<BR>";
		echo $feed['feed_url'];
		echo "time2:".strtotime(getPublishDate($feed['feed_url']))."<BR>";
		if(strtotime($activeMap[0]['publish_date']) < strtotime(getPublishDate($feed['feed_url']))){
			echo "loop";
			if($feed['active_state'] == "1"){
				echo "here";
	 			parseFeed($feed['feed_url'], $feed['network_name'], null, $feed['network_id'],strtotime(getPublishDate($feed['feed_url'])));//, $feed['credentials']);	
			}
			// if parseFeed Successful then lets update the db			 		

		}
		
	}


}


function getPublishDate($feed){
	$xmlstr = file_get_contents($feed);
	$sitemap = simplexml_load_string($xmlstr);
	return $sitemap->entry[0]->published;
}

function parseFeed($feed, $network, $credentials, $network_id, $pub_date){
	echo "parsing";
	$xmlstr = file_get_contents($feed);
	$sitemap = simplexml_load_string($xmlstr);

	
	/** Parse some XML **/
	$source = $sitemap->generator; // Which blog is it? Wordpress? Blogspot? ./?
	
	$tagList = $sitemap->entry[0]->category;
	$tags = array();
	foreach ($tagList as $tag) {
		$tags[] = $tag["term"];
	}
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
		$bitly = new Bitly();
		$shortUrl = $bitly->shortenUrl($information['link']);	
		echo "link: ".$information['link'];	
		$information['bitlyURL'] = $shortUrl;
		$information['bitlyHash'] = "xxxxxxxxxxxx";
		
		echo "<BR>updating database<BR>";
		$result = sno_db_interface::setNewPost($feed,$network_id,$pub_date,$information['bitlyURL']);			
		echo "<br>database result: ".$result."<br>";
		$network = strtolower($network);
		$service_file = './plugins/'.$network.'/sno_'.$network.'.php';
		
		if(file_exists($service_file)){
				require_once($service_file);
				$obj = new $network;
				
				echo ("<br><b>Posting to: ".$network."</b><br><b>Output:</b> ");
				$response = $obj->postToAPI($information, $credentials);		
				if($response){
					echo("<b>posted to ".$network."</b>");
				}else{
					echo("<b>ERROR: could not post to ".$network."</b>");
				}
		}else{
			//service does not exist
		}


}
	//$count++;
//}

 /** End Commit to Plugins **/ 
 
 
 


?>

