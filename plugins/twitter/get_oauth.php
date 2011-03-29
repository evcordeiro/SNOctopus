<?php
$consumer_token  = '87l3QJ3z5UYrGEI6njrekA';
$consumer_secret = '2wiFiQ79tjTBPVHC6mo6dDtIUhfPQDdfPYZTFOGg';
$callbackURL     = 'http://sno.wamunity.com/build/plugins/twitter/get_oauth.php';

$requestTokenURL = 'https://api.twitter.com/oauth/request_token';
$authorizeURL    = 'https://api.twitter.com/oauth/authorize';
$accessTokenURL  = 'https://api.twitter.com/oauth/access_token';

require 'class-xhttp-php/class.xhttp.php'; 
session_name('twitteroauth');
session_start();

xhttp::load('profile,oauth');
$tumblr = new xhttp_profile();
$tumblr->oauth($consumer_token, $consumer_secret);
$tumblr->oauth_method('get'); 
if(isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    echo 'You were logged out.<br><br>';
}

if(isset($_GET['signin']) and !$_SESSION['loggedin']) {

   $data = array();
    $data['post']['oauth_callback'] = $callbackURL;
    $response = $tumblr->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
        $_SESSION['oauth_token']        = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];

       header('Location: '.$authorizeURL.'?oauth_token='.$_SESSION['oauth_token'], true, 303);
        die();

    } else {
        echo 'Could not get token.<br><br>';
    }
}

if($_GET['oauth_token'] == $_SESSION['oauth_token'] and $_GET['oauth_verifier'] and !$_SESSION['loggedin']) {

   $data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $response = $tumblr->fetch($accessTokenURL, $data);

    if($response['successful']) {

       $var = xhttp::toQueryArray($response['body']);

        $_SESSION['oauth_token'] = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];
        $_SESSION['loggedin'] = true;

    } else {
        echo 'Unable to sign you in with Twitter. Please try again later.<br><br>';
        echo $response['body'];
    }
}

if($_SESSION['loggedin']) { ?>

<strong>oauth_token</strong>: <?php echo $_SESSION['oauth_token']; ?><br />
<strong>oauth_token_secret</strong>: <?php echo $_SESSION['oauth_token_secret']; ?><br /><br />
<a href="?logout">Log out</a>


<?php } else {
?>
<a href="?signin">Sign in with Twitter</a>
<?php } ?>
