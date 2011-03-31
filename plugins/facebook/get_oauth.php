<?php 

    $app_id = "155751477815713";
    $app_secret = "1deb67ace222a0209d0c7783cd340821";
    $my_url = "http://sno.wamunity.com";///build/plugins/facebook/graph.php";
	$permissions = "publish_stream,offline_access,manage_pages";

    $code = $_REQUEST["code"];
	
	$auth_url = "
	https://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . $my_url . "&scope=" . $permissions . "&response_type=token";
	
	$code = $_REQUEST["code"];

    if(empty($code)) {
        $dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($my_url);

        echo("<script> top.location.href='" . $dialog_url . "'</script>");
    }

    $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
        . $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
        . $app_secret . "&code=" . $code;

    $access_token = file_get_contents($token_url);

    $graph_url = "https://graph.facebook.com/me?" . $access_token;

    $user = json_decode(file_get_contents($graph_url));

    echo("Hello " . $user->name);
?>