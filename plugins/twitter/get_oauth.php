<?php
$consumer_token  = '87l3QJ3z5UYrGEI6njrekA';
$consumer_secret = '2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg';
$callbackURL     = 'http://sno.wamunity.com/build/plugins/twitter/get_oauth.php';

$requestTokenURL = 'https://api.twitter.com/oauth/request_token';
$authorizeURL    = 'https://api.twitter.com/oauth/authorize';
$accessTokenURL  = 'https://api.twitter.com/oauth/access_token';

require 'class-xhttp-php/class.xhttp.php'; 
session_name('snocto_go');
session_start();

$_SESSION['networkID'] = 'twitter';
$_SESSION['success'] = false;

xhttp::load('profile,oauth');
$tumblr = new xhttp_profile();
$tumblr->oauth($consumer_token, $consumer_secret);
$tumblr->oauth_method('get'); 


if(isset($_GET['signin']) and !$_SESSION['twitter_loggedin']) {

   $data = array();
    $data['post']['oauth_callback'] = $callbackURL;
    $response = $tumblr->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
        $_SESSION['oauth_token']        = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];
		
		$_SESSION['twitter_loggedin'] = true; /* Handles user denies -- But Twitter doesn't return on user denies */
		
		header('Location: '.$authorizeURL.'?oauth_token='.$_SESSION['oauth_token'], true, 303);
        die();

    } else {
        
        $_SESSION['error_code'] = $response['body'];
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");
    }
}

if($_GET['oauth_token'] == $_SESSION['oauth_token'] and $_GET['oauth_verifier'] and !$_SESSION['twitter_loggedin']) {

   $data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $response = $tumblr->fetch($accessTokenURL, $data);

    if($response['successful']) {

       $var = xhttp::toQueryArray($response['body']);

        $_SESSION['oauth_token'] = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];
        $_SESSION['twitter_loggedin'] = true;
		$_SESSION['networkID'] = 'twitter';
		$_SESSION['success'] = true;

    } else {
        $_SESSION['error_code'] = $response['body'];
		echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");
    }
}

if($_SESSION['twitter_loggedin']) { 
echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");
} 

else 
{
echo("<script> top.location.href='?signin'</script>");
} 
?>

