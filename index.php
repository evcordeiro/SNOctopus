<?php
require_once 'lib/access.class.php';
require_once 'lib/db/sno_db_interface.php';
require_once 'config.php';

$fbid = '155751477815713';
$fbperms = 'publish_stream,offline_access,manage_pages';
$user = new flexibleAccess();

if ( $_GET['logout'] == 1 ) 
   $user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);

if (!empty($_GET['activate'])){
    //This is the actual activation. User got the email and clicked on the special link we gave him/her
    $hash = $user->escape($_GET['activate']);
    $res = $user->query("SELECT `{$user->tbFields['active']}` " 
                        . "FROM `{$user->dbTable}` "
                        . "WHERE `activation_hash` = '$hash' LIMIT 1",
                        __LINE__);

    if ( $rec = mysql_fetch_array($res) ){
       if ( $rec[0] == 1 )
          $alert .= '<li>Your account is already activated</li>';
       else{
           //Activate the account:
           if ($user->query("UPDATE `{$user->dbTable}` "
                            . "SET `{$user->tbFields['active']}` = 1 "
                            . "WHERE `activation_hash` = '$hash' LIMIT 1", 
                            __LINE__))
               $alert .= '<li>Account activated. You may login now</li>';
            else
                $alert .= '<li>Unexpected error. Please contact an administrator</li>';
        }
    }else{
        $alert .= '<li>User account does not exists</li>';
    }
}

if (!empty($_POST['username']) && isset($_GET['register']) ){
        
    $errors = false;
        
    // Email Validation
    if(!$user->validEmail($_POST['username']) ){
        $alert .= "<li>Please enter a valid email address</li>";
        $errors = true;
    }
    // Password Match
    if($_POST['pwd'] != $_POST['rpwd'] ){
        $alert .= "<li>Passwords do not match</li>";
        $errors = true;
    }
    // Password Strength
    if (!preg_match("#.*^(?=.{6,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$#", $_POST['pwd'])){
        $alert .= "<li>Password must be 6 characters minimum, mixed case and alphanumeric </li>";
        $errors = true; 
    }
          
          
    if(!$errors){
        //Register user:
        //Get an activation hash and mail it to the user
        $hash = $user->randomPass(100);
        while( mysql_num_rows($user->query("SELECT * " 
                                           . "FROM `{$user->dbTable}` "
                                           . "WHERE `activation_hash` = '$hash' "
                                           . "LIMIT 1")) == 1) { //We need a unique hash
                  $hash = $user->randomPass(100);
        }
        $email = $_POST['username'];
        $data = array(
            'username' => $_POST['username'],
            'email' => $email,
            'password' => $_POST['pwd'],
            'activation_hash' => $hash,
            'active' => 0
        );
        
        // The method returns the userID of the new user or 0 if the user is not added:
        $userID = $user->insertUser($data);
        
        if ($userID == 0)
            //user is allready registered or something like that
            $alert .= '<li>User not registered. Account may already be registered.</li>';
        else {
            $alert .= '<li>User registered. Activate your account using the instructions in your mail</li>';
            //Here is a sample mail that user will get:
            $email = 'Activate your user account by visiting : '
                   . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] 
                   .'?activate='.$hash;
            mail($email, 'Activate your account', $email);
        }
  }
}

?>

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

     <head>
        <title>Stuff is cool</title>
      	<link rel="stylesheet" href="default.php" type="text/css"> 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<?php
    if ( $user->is_loaded() ){
?>
        <div id="fb-root"></div>

	 <script src="http://platform.twitter.com/anywhere.js?id=<?php echo TWITTER_CONSUMER_KEY; ?>&v=1" type="text/javascript"></script>
	 <script type="text/javascript" src="js/functions.js"></script>
    	 <script type="text/javascript" src="plugins/facebook/js/facebook.php"></script>
    	 <script type="text/javascript" src="plugins/twitter/js/twitter.php"></script>
<?php
     }
?>
			
    </head>
    <body>
        <div id="fb-root"></div>
        <script type="text/javascript" src="plugins/facebook/js/facebook.php"></script>
        
        <div id="top_bar" class="align_right">
                
<?php
    if ( $user->is_loaded() ){
        echo '<a href="'.$_SERVER['PHP_SELF'].'?logout=1">logout</a>';
    }
?>
        </div>
        <div id="content">
            <div id="form">

<?php
    if ( !$user->is_loaded() && !isset($_GET['register'])) {
?>

                <div id="login">
                    <h1>Login</h1>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" />
                    <p><span>username: <input type="text" name="uname" /></span></p>
                    <p><span>password: <input type="password" name="pwd" /></span></p>
                    <p><span>Remember me? <input type="checkbox" name="remember" value="1" /></span></p>
                    <input type="button" value="Register" onclick="window.location.href=\'?register\'">
                    <input type="submit" value="login" />
                    </form>

<?php        
    //Login stuff:
    if ( isset($_POST['uname']) && isset($_POST['pwd'])) {

        //  Mention that we don't have to use addslashes as the class do the job
        if (!$user->login($_POST['uname'],$_POST['pwd'],$_POST['remember'] )) { 
            $alert .= '<li>Wrong username and/or password</li>';
        }else{
            // user is now loaded
            echo "<script>window.setTimeout('location.replace(\"index.php\")', 100);</script>";
            // header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
        }
    }
    if(isset($alert))
        echo '<div id="error"><ul id="alert">'.$alert.'</ul></div>';
     
    echo '</div>';
                                        
    } else if(!$user->is_loaded() && isset($_GET['register'])) {
?>                                
                <div id="login">
                <h1>Register</h1>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>?register" />
                <p><span>email: <input type="text" name="username" /></span></p>
                <p><span>password:<input type="password" name="pwd" /></span></p>
                <p><span>retype password: <input type="password" name="rpwd" /></span></p>
                                        
                <input type="submit" value="Register" />
                <input type="button" value="Login" onclick="window.location.href=\'index.php\'">
                </form>

<?php        
    if(isset($alert))
        echo '<div id="error"><ul id="alert">'.$alert.'</ul></div>';
    echo '</div>';
                                                                
    } else {
?>
                <p>Welcome <b><?php echo $user->userData['username'];?></b></p>
                                        
                <br>
                <ul class="tabs">
                    <li><a href="#tab1"><img src="images/facebook.png" /></a></li>
                    <li><a href="#tab2"><img src="images/twitter.png" /></a></li>
                    <li><a href="#tab3"><img src="images/tumblr.png" /></a></li>
                </ul>

<?php 
    $accounts = sno_db_interface::getNetworkIdArrayFromUserId($user->userID); 

    foreach($accounts as $account){                                                                     
        if ($account['network_name'] == "facebook"){
            $facebook[] = $account;
        } else if($account['network_name'] == "twitter"){
            $twitter[] = $account;
        } else if($account['network_name'] == "tumblr")
            $tumblr[] = $account;             
    }
?>               



<div class="tab_container">
    <div id="tab1" class="tab_content">
        <table class='facebook auto_margin'>
            <tbody>
<?php 
    if($facebook != NULL){
        foreach($facebook as $account) {
            $creds = unserialize(base64_decode($account['credentials']));							
            $pic = "http://graph.facebook.com/{$creds['id']}/picture";
            echo "<tr>";
											
            echo "<td><img src=\"{$pic}\"></td>";
            echo "<td class='name'>";
            echo $account['network_label'];
            echo "</td>";
?>
			<td id="active" 
			   class="<?php echo ($account['active_state'] ==1) ? 'toggle_active' : 'toggle_inactive'; ?> option"  
			   value="<?php echo $account['network_id'];?>" 
			   state="<?php echo $account['active_state'];?>"> 
			   <?php echo ($account['active_state'] ==1) ? 'Active' : 'Inactive'; ?>
			 </td>
			<td id="remove" class='option' value="<?php echo $account['network_id'];?> "> 
			   Remove
			</td>	
		<?php
			
			echo "</tr>";
  			}
  		}

?>
</tbody>
</table>
<fb:login-button>Add another Facebook account</fb:login-button>

</div>
		<div id="tab2" class="tab_content">
		<span id="twttr-anywhere-button"></span>
		
			<table class='twitter auto_margin'><tbody>
<?php 
if($twitter != NULL){
foreach($twitter as $account){
//http://graph.facebook.com/royce.stubbs/picture
$creds = unserialize(base64_decode($account['credentials']));

$pic = "http://img.tweetimag.es/i/{$creds['id']}";
echo "<tr>";

echo "<td><img src=\"{$pic}\"></td>";
echo "<td class='name'>";
echo $account['network_label'];
echo "</td>";
?>
			<td id="active" 
			    class="<?php echo ($account['active_state'] ==1) ? 'toggle_active' : 'toggle_inactive'; ?> option"  
			    value="<?php echo $account['network_id'];?>" 
			    state="<?php echo $account['active_state'];?>"> 
			<?php echo ($account['active_state'] ==1) ? 'Active' : 'Inactive'; ?>
			</td>
			<td id="remove" class='option' value="<?php echo $account['network_id'];?> "> 
			    Remove
			</td>	
			<?php
			
			echo "</tr>";
			}
			}
			//var_dump($accounts);
			?>
			</tbody>
		    </table>
		</div>

		    <div id="tab3" class="tab_content">
		    	asdfasd adsf asd f
		    </div>						
	</div>
        <div class="result">
           <p>
               <label>Add Feed:</label>
               <input name="feed" size="35" type="text"></input> 
               <input type="button" value="submit" id="button" onclick="newFeed()"></input>
           </p>
        </div>
                                                
<?php
    }
?>                      
        </div>
<?php
    if ($user->is_loaded()) {
?>
        <div id="infobox"><center><img src="images/loading.gif"></center></div>
<?php } ?>
        </div>
                
        <div id="footer">
            SNOctopus Inc., &copy; 2011  All Rights Reserved
        </div>
      
     </body>
 </html> 