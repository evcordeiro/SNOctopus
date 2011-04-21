<?php

$consumer_token  = 'nTu0OIggfxbJXuJ1NShuB2Mr2ce7WBjXkM74rhTVRoWXCryEQ5';
$consumer_secret = 'QFsyJxnri7elEOzpzzR5dmtndQfGLYDb1FSMPkzVR5f1nkCGGE';
$callbackURL     = 'http://sno.wamunity.com/build/plugins/tumblr/get_oauth.php?signin';

$requestTokenURL = 'http://www.tumblr.com/oauth/request_token';
$authorizeURL    = 'http://www.tumblr.com/oauth/authorize';
$accessTokenURL  = 'http://www.tumblr.com/oauth/access_token';

require 'class-xhttp-php/class.xhttp.php'; # uncomment if you don't use autoloading

session_name('sno_tumblr_oauth');
session_start();

xhttp::load('profile,oauth');
$tumblr = new xhttp_profile();
$tumblr->oauth($consumer_token, $consumer_secret);
$tumblr->oauth_method('get'); // For compatability, OAuth values are sent as GET data


if(isset($_REQUEST['signin'])) {

	$_SESSION = array();

    # STEP 2: Application gets a Request Token from Tumblr
   $data = array();
    $data['post']['oauth_callback'] = $callbackURL;
    $response = $tumblr->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
        $_SESSION['oauth_token']        = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];

		$_SESSION['tumblr_loggedin'] = true; /* To handle user denied requests */
        # STEP 3: Application redirects the user to Tumblr for authorization.
       header('Location: '.$authorizeURL.'?oauth_token='.$_SESSION['oauth_token'], true, 303);
       die();
      # STEP 4: (Hidden from Application)
      # User gets redirected to Tumblr.
      # Tumblr asks if she wants to allow the application to have access to her account.
      # She clicks on the "Allow" button.

    } else {
        
		echo ("<br> Tumblr Authentication for SNOctopus has failed with error:<br>" . $response['body']);
		
		echo ("<a href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'>Return to SNOctopus</a>");
		/*
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");
		*/
    }
}

# STEP 5: User gets redirected back to the application. Some GET variables are set by Tumblr
if($_GET['oauth_token'] == $_SESSION['oauth_token'] and $_GET['oauth_verifier'] and !$_SESSION['tumblr_loggedin']) {

    # STEP 6: Application contacts Tumblr to exchange Request Token for an Access Token.
   $data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $response = $tumblr->fetch($accessTokenURL, $data);

    if($response['successful']) {

       # STEP 7: Application now has access to the user's data,
       # for reading protected entries, sending a post updates.
       $var = xhttp::toQueryArray($response['body']);

        $creds['user_id'] = $var['user_id'];
        $creds['screen_name'] = $var['screen_name'];
        $creds['oauth_token'] = $var['oauth_token'];
        $creds['oauth_token_secret'] = $var['oauth_token_secret'];
        $_SESSION['tumblr_loggedin'] = true;
		
		/*
			db_library_function_store_userinfo( $uinf['user_name'], "tumblr", $creds, $creds['screen_name'] );
		*/
		
    } else {
        
		echo ("<br> Tumblr Authentication for SNOctopus has failed with error:<br>" . $response['body']);
		
		echo ("<a href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'>Return to SNOctopus</a>");
		
		/*
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php?oauth_return'</script>");
		*/
    }
}

?>
