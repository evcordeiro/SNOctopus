<?php
function stripHTML($content){
	strip_tags($content,'<p><a>');
	$content = str_replace('&nbsp;', '', $content);  
	return $content;

}



function postErrorMessage($error_message = NULL)
{
	echo ("<font size='14' align='center'>Sorry, there was an error processing your request</font><br>");
	echo ("<font size='12' align='center'>Error Message: " . $error_message . "<br><br><br><br>");
	echo ("<font size='12' align='center'><a href='http://sno.wamunity.com/build/index.php'>Return to SNOctopus</a></font>");
	die();
}

function fixURL($url){
	if(substr($url, 0, 7) != "http://" )
		$url = "http://".$url;	
	return $url;
}

function findFeedURL($url){
	// { "/rss.xml" , ""}
	$url = fixURL($url);
	if(substr($url, -1) == "/")
		$url = substr($url, 0, -1);
		
	$extenstions = array ("/rss.xml", "/feed/atom/", "/blog/rss_2.0/");	
	
	if(filter_var($url, FILTER_VALIDATE_URL)){
		foreach($extenstions as $extenstion){
			try{
				$headers = get_headers($url.$extenstion, 1);
			}catch(Exception $e){
				//return 0;
			}
			if($headers[0] == "HTTP/1.1 200 OK")
				return array('verify' => "1", 'url' => $url.$extenstion);
		}
	}
	return array('verify' => "0", 'url' => $url);
}


?>
