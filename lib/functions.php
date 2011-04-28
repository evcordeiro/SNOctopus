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



?>
