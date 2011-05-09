<?php
session_start();

// Download TwitterOAuth from http://github.com/abraham/twitteroauth.
require_once('../twitteroauth/twitteroauth.php');
require_once('../config.php');
require_once('../../../lib/db/sno_db_interface.php');

// Get consumer keys from http://dev.twitter.com/apps.
$consumer_key = CONSUMER_KEY;
$consumer_secret = CONSUMER_SECRET;
$oauth_bridge_code = $_REQUEST['bridge_code'];

// Build a TwitterOAuth object.
$connection = new TwitterOAuth(
  $consumer_key,
  $consumer_secret
);

// Request an access_token with an oauth_bridge_code.
$request = $connection->oAuthRequest('https://api.twitter.com/oauth/access_token', 'POST', array('oauth_bridge_code' => $oauth_bridge_code));

// If the access_token request succeeds...
if (200 === $connection->http_code){
  // Parse the access_token string.
  $token = OAuthUtil::parse_parameters($request);
  // Build new TwitterOAuth object with users access_token.
  $connection = new TwitterOAuth(
    $consumer_key,
    $consumer_secret,
    $token['oauth_token'],
    $token['oauth_token_secret']
  );
  $user_id = $_SESSION['SNOctopus'];
  $credentials['oauth_token'] = $connection->token->key;
  $credentials['oauth_token_secret'] = $connection->token->secret;
  $content = $connection->get('account/verify_credentials');
  $username = $content->screen_name;

  sno_db_interface::setNewNetwork($user_id, "twitter" , $username, $credentials, true );
  var_dump($credentials);
  // Call verify_credentials to make sure access_tokens are valid.
} else {
  $content = 'Oops.... something went wrong.';
}

//print_r($content);

?>