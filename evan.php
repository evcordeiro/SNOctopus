<?php
require_once 'lib/access.class.php';
require_once 'lib/db/sno_db_interface.php';

$user = new flexibleAccess();
$bitly_url=array('http://bitly.com/fTqjGP','http://bitly.com/g9JN58','http://bitly.com/f56poA','http://bitly.com/fxjOw8','http://bitly.com/fzrlg1');
if ( $_GET['logout'] == 1 ) 
				$user->logout('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
if (!empty($_GET['activate'])){
	//This is the actual activation. User got the email and clicked on the special link we gave him/her
	$hash = $user->escape($_GET['activate']);
	$res = $user->query("SELECT `{$user->tbFields['active']}` FROM `{$user->dbTable}` WHERE `activation_hash` = '$hash' LIMIT 1",__LINE__);
	if ( $rec = mysql_fetch_array($res) ){
		if ( $rec[0] == 1 )
			$alert .= '<li>Your account is already activated</li>';
		else{
			//Activate the account:
			if ($user->query("UPDATE `{$user->dbTable}` SET `{$user->tbFields['active']}` = 1 WHERE `activation_hash` = '$hash' LIMIT 1", __LINE__))
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
	  while( mysql_num_rows($user->query("SELECT * FROM `{$user->dbTable}` WHERE `activation_hash` = '$hash' LIMIT 1"))==1)//We need a unique hash
	  	  $hash = $user->randomPass(100);
	  $email = $_POST['username'];
	  $data = array(
	  	'username' => $_POST['username'],
	  	'email' => $email,
	  	'password' => $_POST['pwd'],
	  	'activation_hash' => $hash,
	  	'active' => 0
	  );
	  $userID = $user->insertUser($data);//The method returns the userID of the new user or 0 if the user is not added
	  if ($userID==0)
	  	$alert .= '<li>User not registered. Account may already be registered.</li>';//user is allready registered or something like that
	  else {
	  	$alert .= '<li>User registered. Activate your account using the instructions in your mail</li>';
	  	//Here is a sample mail that user will get:
		$email = 'Activate your user account by visiting : '. $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] .'?activate='.$hash;
		mail($email, 'Activate your account', $email);
	  }
  }
}
//<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
?><?php				

/* This cookie is allows the UI to sync with the get_oauth plugin */
if ( $user->is_loaded() ){								
$userinfo['user_id'] = $user->userID;				
setcookie('sno_info', base64_encode(serialize($userinfo)), time()+600, "/", "sno.wamunity.com");	
		
?>
<html> 
    <head>
      <title>SNOctopus</title>
      	<link rel="stylesheet" href="default.php" type="text/css"> 
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
  <script src="http://code.jquery.com/jquery-git.js"></script>
		  <script type="text/javascript">
		  /*
		  ajax.php Option list
		  0 : New Feed [Fabio] 1 : Toggle Networkid / Feed active status  [Tom]
		  */
		  var $_GET = {};
			document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
			    function decode(s) {
			        return decodeURIComponent(s.split("+").join(" "));				}			
			    $_GET[decode(arguments[1])] = decode(arguments[2]);
			});
			/*
		     $('body').delay(3000).queue(function(next){
	          $.get( "verify.php",{ info: $_GET["info"] }, function(response){ 
	          $('.result').html(response); 
	          });
	          next();
	   		 });
			*/
			function newFeed(){
				var html = $.ajax({
				    url:'cd.php?url='+$('input[name="feed"]').val(),
				    async: false}).responseText;
				$('input[name="feed"]').removeClass("error");
				$('input[name="feed"]').removeClass("success");
			    if(html == 0)
			    	$('input[name="feed"]').addClass("error");
			    else if(html == 1)
			    	$('input[name="feed"]').addClass("success");
				/*
$.get("ajax.php", { option: "0", value[]:{ $('input[name="feed"]').val()}, arg2 }, function(data){
					$('#infobox').html(data);
				});*/
			}
			var cur = null;
			function getDetails(sno) {
						//alert( id +' ' + cur);
					if(cur == sno)
						//$('#infobox').toggle();
						$.get("getInfo.php", { id: sno}, function(data){
							$('#infobox').html(data);
						});
					cur = sno;
			 }
			$(".result li span").live('click', function(e) { 
			    e.preventDefault; 
			    this.blur(); 
			    return getDetails($(this).attr('sno')); 
			});
			$.get("feed_info.php", { 'user': <?php echo "'".base64_encode(json_encode($user->userData["user_id"]))."'";?> }, function(data){
				$('#infobox').html(data);
			});			
 		</script>		
			<?php
				}
			?>
    </head>
    <body>
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
				if ( !$user->is_loaded() && !isset($_GET['register']))
				{
					echo '<div id="login">
					<h1>Login</h1>
					<form method="post" action="'.$_SERVER['PHP_SELF'].'" />
					 <p><span>username: <input type="text" name="uname" /></span></p>
					 <p><span>password: <input type="password" name="pwd" /></span></p>
					 <p><span>Remember me? <input type="checkbox" name="remember" value="1" /></span></p>
					 <input type="button" value="Register" onclick="window.location.href=\'?register\'">  <input type="submit" value="login" />
					</form>';
					//Login stuff:
					if ( isset($_POST['uname']) && isset($_POST['pwd'])){
					  if ( !$user->login($_POST['uname'],$_POST['pwd'],$_POST['remember'] )){//Mention that we don't have to use addslashes as the class do the job
					    $alert .= '<li>Wrong username and/or password</li>';
					  }else{
					    //user is now loaded
					    echo "<script>window.setTimeout('location.replace(\"index.php\")', 100);</script>";
					    //header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
					  }
					}
					if(isset($alert))
						echo '<div id="error"><ul id="alert">'.$alert.'</ul></div>';
					echo '</div>';
				}else if(!$user->is_loaded() && isset($_GET['register'])){
					echo '<div id="login">
					<h1>Register</h1>
					<form method="post" action="'.$_SERVER['PHP_SELF'].'?register" />
					 <p><span>email: <input type="text" name="username" /></span></p>
					 <p><span>password:<input type="password" name="pwd" /></span></p>
 					 <p><span>retype password: <input type="password" name="rpwd" /></span></p>
					 <input type="submit" value="Register" /><input type="button" value="Login" onclick="window.location.href=\'index.php\'">
					</form>';
					if(isset($alert))
						echo '<div id="error"><ul id="alert">'.$alert.'</ul></div>';
					echo '</div>';								
				} else {
			?>
				<p>Welcome <b><?php 				echo $user->userData['username'];				/*								$userinfo['user_id'] = $user->user_id;				setcookie('sno_info', base64_encode(serialize($user_info)), time()+600, "/", "sno.wamunity.com");				*/				?></b></p>
						<br>											<form method="post" action="http://sno.wamunity.com/build/plugins/facebook/get_oauth.php">					 <input type="image" src="images/facebook.png"/>					</form>										<form method="post" action="http://sno.wamunity.com/build/plugins/twitter/get_oauth.php?init">					 <input type="image" src="images/twitter.png"/>					</form>										<form method="post" action="http://sno.wamunity.com/build/plugins/tumblr/get_oauth.php?init">					 <input type="image" src="images/tumblr.png"/>					</form>									
			           <div class="result">
							<form id="add_feed">
								<p><label>Add Feed:</label><input name="feed" size="50" type="text"></input> <input type="button" value="submit" onclick="newFeed()"></input></p>
							</form>
					   </div>
			<?php
				}
			?>			
			</div>
			<?php
					if ( $user->is_loaded() ){
			?>
			<div id="infobox"><center><img src="images/loading.gif"></center></div>			
			<div id="user networks">						
			<?php			
			echo "</center>";			
			echo "<pre>";			
			//print_r($user);			
			//print_r($user);			
			echo "<br>";			
			/*
			$stuff = sno_db_interface::getNetworkIdArrayFromUserId($user->user_id);			
			*/
			echo "<br>your networks:<br>";
			$stuff = sno_db_interface::resultArrayFromQuery("select * from networks where user_id='" . $user->userID . "'");
			
			print_r ($stuff);
			
			echo "</pre>";			
			echo "<center>";			
			?>			
			</div>			
			<?php } ?>
		</div>
		<div id="footer">
			SNOctopus Inc., &copy; 2011  All Rights Reserved
		</div>
     </body>
 </html> 
