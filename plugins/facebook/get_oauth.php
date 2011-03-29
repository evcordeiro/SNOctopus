<?php 

    $app_id = "155751477815713";
    $app_secret = "1deb67ace222a0209d0c7783cd340821";
    $my_url = "http://sno.wamunity.com/build/plugins/facebook/graph.php";
	$permissions = "publish_stream,offline_access,manage_pages";

    $code = $_REQUEST["code"];
	
	$auth_url = "
	https://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . $my_url . "&scope=" . $permissions . "&response_type=token";
	
	/*echo("<a href=".$auth_url.">click to login</a>");*/
	/*fopen($auth_url);*//*http_post_data ?*/
	/*
	$fields = array(
    
	'client_id' => $app_id,
    'redirect_uri' => $my_url,
	'scope' => $permissions
	
	);

$response = http_post_fields("https://www.facebook.com/dialog/oauth", $fields);
 PECL not installed
*/
echo ("<FORM action=". $auth_url ." method=post>")
?>