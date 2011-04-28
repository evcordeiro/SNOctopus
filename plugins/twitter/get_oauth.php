<?php

require_once('config.php');
require_once('../../lib/functions.php');
require_once('../../lib/db/sno_db_interface.php');

$requestTokenURL = 'https://api.twitter.com/oauth/request_token';
$authorizeURL    = 'https://api.twitter.com/oauth/authorize';
$accessTokenURL  = 'https://api.twitter.com/oauth/access_token';

include_once 'class-xhttp-php/class.xhttp.php'; # uncomment if you don't use autoloading

xhttp::load('profile,oauth');
$twitter = new xhttp_profile();
$twitter->oauth(CONSUMER_KEY, CONSUMER_SECRET);
$twitter->oauth_method('get'); // For compatability, OAuth values are sent as GET data

if(isset($_GET['init'])) 
{
    setcookie("snotwitter", base64_encode(serialize(array())), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */
	echo ("<script>top.location.href='?signin'</script>");
	die();
}

/* Get cookie data */
$locals = unserialize(base64_decode($_COOKIE['snotwitter']));

if(isset($_GET['signin']) and !$locals['loggedin']) {

	# STEP 2: Application gets a Request Token from Twitter
    $data = array();
    $data['post']['oauth_callback'] = OAUTH_CALLBACK;
    $response = $twitter->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
     $locals['oauth_token']        = $var['oauth_token'];
     $locals['oauth_token_secret'] = $var['oauth_token_secret'];
	
	setcookie("snotwitter", base64_encode(serialize($locals)), time()+120, "/", "sno.wamunity.com"); 
	echo("<script> top.location.href='" . $authorizeURL . "?oauth_token=" . $locals['oauth_token'] . "'</script>");   
    die();
		
      # STEP 4: (Hidden from Application)
      # User gets redirected to Tumblr.
      # Tumblr asks if she wants to allow the application to have access to her account.s
      # She clicks on the "Allow" button.

    } else {
        eat_cookie();
		postErrorMessage( implode($response) );
		die();
    }
}

# STEP 5: User gets redirected back to the application. Some GET variables are set by Twitter
if($_GET['oauth_token'] == $locals['oauth_token'] and $_GET['oauth_verifier'] and !$locals['loggedin']) {

    # STEP 6: Application contacts Twitter to exchange Request Token for an Access Token.
	$data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $twitter->set_token($locals['oauth_token'], $locals['oauth_token_secret']);
    $response = $twitter->fetch($accessTokenURL, $data);

    if($response['successful']) {

       # STEP 7: Application now has access to the user's data,
       # for reading protected entries, sending a post updates.
       $var = xhttp::toQueryArray($response['body']);

        $credentials['user_id'] = $var['user_id'];
        $credentials['screen_name'] = $var['screen_name'];
        $credentials['oauth_token'] = $var['oauth_token'];
        $credentials['oauth_token_secret'] = $var['oauth_token_secret'];
        $locals['loggedin'] = true;
		
		if(!isset($_COOKIE['sno_info']))
		{
			eat_cookie();
			postErrorMessage("Not logged into SNOCtopus");
			die();
		}
		
		$uinf = unserialize(base64_decode($_COOKIE['sno_info']));
		
		sno_db_interface::setNewNetwork($uinf['user_name'], "twitter" , $credentials['screen_name'], $credentials, true );
		
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

function eat_cookie()
{
	setcookie("snotwitter", "", time()-1000, "/", "sno.wamunity.com");  /* deleat cookie */
}

function update_cookie()
{
	setcookie("snotwitter", base64_encode(serialize($locals)), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */
}

?>