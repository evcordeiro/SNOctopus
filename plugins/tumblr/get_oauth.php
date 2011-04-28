<?php

require_once('config.php');
require_once('../../lib/functions.php');;
require_once('../../lib/db/sno_db_interface.php');


$requestTokenURL = 'http://www.tumblr.com/oauth/request_token';
$authorizeURL    = 'http://www.tumblr.com/oauth/authorize';
$accessTokenURL  = 'http://www.tumblr.com/oauth/access_token';

require 'class-xhttp-php/class.xhttp.php'; # uncomment if you don't use autoloading

xhttp::load('profile,oauth');
$tumblr = new xhttp_profile();
$tumblr->oauth(CONSUMER_TOKEN, CONSUMER_SECRET);
$tumblr->oauth_method('get'); // For compatability, OAuth values are sent as GET data

if(isset($_GET['init'])) 
{
    setcookie("snotumblr", base64_encode(serialize(array())), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */
	/*echo ("<script>top.location.href='?signin'</script>");*/
	header('Location: ?signin');
	die();
}

/* Get cookie data */
$locals = unserialize(base64_decode($_COOKIE['snotumblr']));


if(isset($_GET['signin']) and !$locals['loggedin']) {

	# STEP 2: Application gets a Request Token from Tumblr
    $data = array();
    $data['post']['oauth_callback'] = CALLBACK_URL;
    $response = $tumblr->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
     $locals['oauth_token']        = $var['oauth_token'];
     $locals['oauth_token_secret'] = $var['oauth_token_secret'];
	
		setcookie("snotumblr", base64_encode(serialize($locals)), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */
	/*echo("<script> top.location.href='" . $authorizeURL . "?oauth_token=" . $locals['oauth_token'] . "'</script>");   */
	$urll = $authorizeURL . "?oauth_token=" . $locals['oauth_token'];
	header('Location: ' . $urll);
    die();
		
      # STEP 4: (Hidden from Application)
      # User gets redirected to Tumblr.
      # Tumblr asks if she wants to allow the application to have access to her account.
      # She clicks on the "Allow" button.

    } else {
        eat_cookie();
		postErrorMessage( implode($response) );
		die();
    }
}

# STEP 5: User gets redirected back to the application. Some GET variables are set by Tumblr
if($_GET['oauth_token'] == $locals['oauth_token'] and $_GET['oauth_verifier'] and !$locals['loggedin']) {

    # STEP 6: Application contacts Tumblr to exchange Request Token for an Access Token.
	$data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $tumblr->set_token($locals['oauth_token'], $locals['oauth_token_secret']);
    $response = $tumblr->fetch($accessTokenURL, $data);

    if($response['successful']) {

       # STEP 7: Application now has access to the user's data,
       # for reading protected entries, sending a post updates.
       $var = xhttp::toQueryArray($response['body']);

        $credentials['oauth_token'] = $var['oauth_token'];
        $credentials['oauth_token_secret'] = $var['oauth_token_secret'];
        $locals['loggedin'] = true;
		
		$data = null;
		$tumblr->set_token($credentials['oauth_token'], $credentials['oauth_token_secret']);
		$response = $tumblr->fetch('http://www.tumblr.com/api/authenticate', $data);
		$userinfo['name'] = $response['profile']['name'];
		
		if(!isset($_COOKIE['sno_info']))
		{
			eat_cookie();
			postErrorMessage("Not logged into SNOCtopus");
			die();
		}
		
		$uinf = unserialize(base64_decode($_COOKIE['sno_info']));
	
		/* Do we need this typecast */
		sno_db_interface::setNewNetwork((int)($uinf['user_name']), "tumblr" , $response['profile']['name'] , $credentials, 1 );
			
		/*debug*/
		eat_cookie();
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/index.php'</script>");
		die();
		
    } else {
		eat_cookie();
		postErrorMessage( implode($response) );
        die();
    }
}
/*
if(isset($_POST['postlink']) and $_SESSION['loggedin']) {

    # Set access token
   $tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

    $data = array();
    $data['post'] = array(
      'type'   => 'link',
      'name'   => 'Authenticating with Tumblr using OAuth | sudocode',
      'url'    => 'http://sudocode.net/article/351/authenticating-with-tumblr-using-oauth-in-php',
      'description' => $_POST['description'],
      'generator' => 'sudocode.net',
      );

    $response = $tumblr->fetch('http://www.tumblr.com/api/write', $data);

    if($response['successful']) {
        echo "Update successful!<br><br>";
    } else {
        echo "Update failed. {$response[body]}<br><br>";
    }
}
*/

postErrorMessage("User Denied Access");

function eat_cookie()
{
	setcookie("snotumblr", "", time()-1000, "/", "sno.wamunity.com");  /* deleat cookie */
}

function update_cookie()
{
	setcookie("snotumblr", base64_encode(serialize($locals)), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */
}

?>