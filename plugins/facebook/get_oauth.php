<?php 

    $app_id = "155751477815713";
    $app_secret = "1deb67ace222a0209d0c7783cd340821";
    $my_url = "http://sno.wamunity.com/build/plugins/facebook/get_oauth.php";
	$permissions = "publish_stream,offline_access,manage_pages";

	$code = $_REQUEST["code"];

	if( isset( $_GET['error_reason'] ))
	{
		/*
		pass back login timeout error msg (session or cookie?)
		
		or handle error message -here- then return
		
		return as ?oauthreturn
		*/
		/*session style*/
		session_name('snocto_go');
		session_start();
		$_SESSION['networkID'] = "facebook";
		$_SESSION['success'] = false;
		
		echo ("<a href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'>error recieved: continue</a>");
		/*
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return&network=facebook&success=false'</script>");
		*/
	}
	else if( !isset($_COOKIE['sno_info']) )
	{
		/*
		pass back login timeout error msg (session or cookie?)
		
		or handle error message -here- then return
		
		return as ?oauthreturn
		*/
		
		echo ("<a href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'>cookie unavailable: continue</a>");
		/*
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'</script>");
		*/
	}
	
	else{
	
		if(empty($code)) {
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . $app_id . "&redirect_uri=" . urlencode($my_url) . "&scope=" . $permissions;
	
			/*
			echo("<script> top.location.href='" . $dialog_url . "'</script>");
			*/
		echo("<a href='" . $dialog_url . "'>code received: submit</a>");
		
		}
	
		else{
	
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
				. $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret="
				. $app_secret . "&code=" . $code;

			$access_token = file_get_contents($token_url);
	
			$graph_url = "https://graph.facebook.com/me?" . $access_token;

			$user = json_decode(file_get_contents($graph_url));

			
			/*should prompt which to get, user->id? or user->accounts which yields below:
	
			{
			"data": [
					{
					"name": "SNOctopus",
					"category": "Software",
					"id": "167168166665156"
					},
					{
					"name": "SNOctopus",
					"category": "Application",
					"id": "155751477815713"
					}
					]
			}
	
			then selected outputs, credentials['id'] is simply id, the network identifier to send the db
			is a concatentation of user->username . " - " . user->accounts->name . ", " . user->accounts->category
			
			obviously the last two only if applicable. we are logged into the users account at this point, 
			so we can use the graph api. once we have this information, we can use curl to extract information
			in the future
	
			*/	
			$credentials['id'] = $user->id;
			$credentials['access_token'] = $access_token;
	
			echo ("<br>access_token: " . $access_token . "<br>");
			/*
			need to make sure access token is good
			or handle error message -here- then return
			
			*/
			$uinf = unserialize(base64_decode($_COOKIE['sno_info']));

			/*
			db_library_function_store_userinfo( $uinf['user_name'], "facebook", $credentials, $user->username );
			*/
	
			/*
			pass back success, error msg
			
			handle error message -here- then return
			
			return as ?oauthreturn
			*/
	
			echo ("<a href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'>access token response received: continue</a>");
	
			/*
			echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'</script>");
			*/
		}
	}
?>