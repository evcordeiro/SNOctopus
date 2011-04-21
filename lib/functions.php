<?php
function stripHTML($content){
	strip_tags($content,'<p><a>');
	$content = str_replace('&nbsp;', '', $content);  
	return $content;

}
?>
