<?php 

	require_once('config.php');
	require_once('../../lib/functions.php');
	require_once('../../lib/db/sno_db_interface.php');
	
	$code = $_REQUEST["code"];

	/* need to check referring url */
	
	if( isset( $_GET['error_reason'] ))
	{
		postErrorMessage($_GET['error_reason']);
		die();
	}
	else if( !isset($_COOKIE['sno_info']) )
	{
		postErrorMessage("Not logged in to SNOctopus");
		die();
	}
	
	else{
		if(empty($code)) {
			$dialog_url = "http://www.facebook.com/dialog/oauth?client_id=" 
            . APP_ID . "&redirect_uri=" . urlencode(CALLBACK_URL) . "&scope=" . PERMISSIONS;
	
			echo("<script> top.location.href='" . $dialog_url . "'</script>");	
		}
	
		else{
	
			$token_url = "https://graph.facebook.com/oauth/access_token?client_id="
				. APP_ID . "&redirect_uri=" . urlencode(CALLBACK_URL) . "&client_secret="
				. APP_SECRET . "&code=" . $code;

			$access_token = file_get_contents($token_url);

			$graph_url = "https://graph.facebook.com/me?" . $access_token;

			$user = json_decode(file_get_contents($graph_url));

			/*
			need to check $user for error in return
			*/
			
			
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
			so we can use the graph api. once we have this information, we can extract information
			in the future
	
			*/	
			$credentials['id'] = $user->id;
			// here we force the token format as a string to avoid the annoying tendency of php to switch to
			// switch to scientific notation, also we clip the 'access_token=' off of the beginning
			$credentials['access_token'] = substr($access_token, 13);
				
			$uinf = unserialize(base64_decode($_COOKIE['sno_info']));

			sno_db_interface::setNewNetwork($uinf['user_id'], "facebook" , $user->name, $credentials, true );
			
			//echo ($uinf['user_id'] . "facebook   " . $user->username . "  " . " credentials "  . " true" );

			echo("<script> top.location.href='http://www.sno.wamunity.com/build/index.php'</script>");
			
			die();
		}
	}
?>