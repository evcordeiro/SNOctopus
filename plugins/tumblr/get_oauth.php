<?php
$consumer_token  = 'nTu0OIggfxbJXuJ1NShuB2Mr2ce7WBjXkM74rhTVRoWXCryEQ5';
$consumer_secret = 'QFsyJxnri7elEOzpzzR5dmtndQfGLYDb1FSMPkzVR5f1nkCGGE';
$callbackURL     = 'http://sno.wamunity.com/build/plugins/tumblr/get_oauth.php';

$requestTokenURL = 'http://www.tumblr.com/oauth/request_token';
$authorizeURL    = 'http://www.tumblr.com/oauth/authorize';
$accessTokenURL  = 'http://www.tumblr.com/oauth/access_token';

require 'class-xhttp-php/class.xhttp.php'; # uncomment if you don't use autoloading

session_name('tumblroauth');
session_start();

xhttp::load('profile,oauth');
$tumblr = new xhttp_profile();
$tumblr->oauth($consumer_token, $consumer_secret);
$tumblr->oauth_method('get'); // For compatability, OAuth values are sent as GET data

if(isset($_GET['logout'])) {
    $_SESSION = array();
    session_destroy();
    echo 'You were logged out.<br><br>';
}

if(isset($_GET['signin']) and !$_SESSION['loggedin']) {

    # STEP 2: Application gets a Request Token from Tumblr
   $data = array();
    $data['post']['oauth_callback'] = $callbackURL;
    $response = $tumblr->fetch($requestTokenURL, $data);

    if($response['successful']) {
        $var = xhttp::toQueryArray($response['body']);
        $_SESSION['oauth_token']        = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];

        # STEP 3: Application redirects the user to Tumblr for authorization.
       header('Location: '.$authorizeURL.'?oauth_token='.$_SESSION['oauth_token'], true, 303);
        die();
      # STEP 4: (Hidden from Application)
      # User gets redirected to Tumblr.
      # Tumblr asks if she wants to allow the application to have access to her account.
      # She clicks on the "Allow" button.

    } else {
        echo 'Could not get token.<br><br>';
    }
}

# STEP 5: User gets redirected back to the application. Some GET variables are set by Tumblr
if($_GET['oauth_token'] == $_SESSION['oauth_token'] and $_GET['oauth_verifier'] and !$_SESSION['loggedin']) {

    # STEP 6: Application contacts Tumblr to exchange Request Token for an Access Token.
   $data = array();
    $data['post']['oauth_verifier'] = $_GET['oauth_verifier'];

    $tumblr->set_token($_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    $response = $tumblr->fetch($accessTokenURL, $data);

    if($response['successful']) {

       # STEP 7: Application now has access to the user's data,
       # for reading protected entries, sending a post updates.
       $var = xhttp::toQueryArray($response['body']);

        $_SESSION['user_id'] = $var['user_id'];
        $_SESSION['screen_name'] = $var['screen_name'];
        $_SESSION['oauth_token'] = $var['oauth_token'];
        $_SESSION['oauth_token_secret'] = $var['oauth_token_secret'];
        $_SESSION['loggedin'] = true;

    } else {
        echo 'Unable to sign you in with Twitter. Please try again later.<br><br>';
        echo $response['body'];
    }
}

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

if($_SESSION['loggedin']) { ?>

<strong>oauth_token</strong>: <?php echo $_SESSION['oauth_token']; ?><br />
<strong>oauth_token_secret</strong>: <?php echo $_SESSION['oauth_token_secret']; ?><br /><br />
<a href="?logout">Log out</a>


<?php } else {
  # STEP 1: User goes to a web application that she wants to use. She clicks on the "Sign in with Tumblr" button. 
?>
<a href="?signin">Sign in with Tumblr</a>
<?php } ?>
