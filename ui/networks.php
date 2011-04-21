<?php

if(isset($_GET['logout']))
{

setcookie("sno_info", "", time()-3600, "/", "sno.wamunity.com");   /* delete cookie */
echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");

}

if(isset($_GET['login']))
{
/* do login stuff */


$user_info['user_name'] = "Papa Smurf";
$user_info['login_time'] = time();
setcookie("sno_info", base64_encode(serialize($user_info)), time()+120, "/", "sno.wamunity.com");  /* expire in 2 minutes */

/* Now reload page to have access to cookie info */

echo("<script> top.location.href='http://www.sno.wamunity.com/build/ui/networks.php'</script>");
}

if(isset($_GET['oauth_return']))
{

/*we are handling this as a session or a cookie?*/

/*db calls and checks out user, needs to validate user logged in????
so session is better, just grab the cookie[username]????
*/
echo ("Returning...<br>");

}


/*debug*/
	if( isset($_COOKIE['sno_info']) )
	{
		if (unserialize(base64_decode($_COOKIE['sno_info'])) == false)
		{
			echo "Not logged in";
		}
		else
		{
			$uinf = unserialize(base64_decode($_COOKIE['sno_info']));
			echo ("Welcome " . $uinf['user_name'] . "<br>" );
			echo ("You were logged in at " . date('F j, Y, g:i a', $uinf['login_time']) . "<br>");
		}
	
	}
	else
	{
		echo "Not logged in";
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head profile="http://gmpg.org/xfn/11">

<title>SNOctopus</title>
<style type="text/css">
body{
	margin:0px;
	padding:0px;
	background-color:lightblue;
	font-family: Gill Sans, Verdana;
}
#top_bar{
	height: 25px;
	padding:5px;
	padding-right:10px;
	background-color: #145f69;
	border-bottom: 2px solid #eee;
}
#top_bar a{
	padding-right:20px;
	color: #ffffff;
	font-family: Gill Sans, Verdana;
	font-size: 11px;
	line-height: 25px;
	text-transform: uppercase;
	letter-spacing: 2px;
	font-weight: bold;
}
#form{
	width: 520px;
	margin: auto;
	margin-top:50px;
	text-align:left;
	background-color:#145f69;
	border: 4px solid white;
	-moz-border-radius: 15px;
	border-radius: 15px;
	padding:50px;
}
#form p{
	font-family: Gill Sans, Verdana;
	color:#ffffff;
	line-height: 40px;
}
#content{
	 
	padding:20px;
	text-align:center;
}
.align_right{	
	text-align:right;
}
#footer{
	color:#145f69;
	font-family: Century Gothic, sans-serif;
	font-size: 11px;
	letter-spacing: 1.5px;
	line-height: 70px;
}
</style>
  <script type="text/javascript" language="javascript">   
        function createDiv()
        {
            var divTag = document.createElement("div");
          
            divTag.id = "feeds";
                       
             
            divTag.className ="dynamicDiv";
             
            divTag.innerHTML = '<br>Feed: <input type="textbox" size="25"> Tags: <input type="textbox" size="15"><input type="button" value="Add" onclick="createDiv();">';


             
            document.getElementById("feeds").appendChild(divTag);
        }
 
    </script>
</head>
<body >

<div id="top_bar" class="align_right">
	<a href="?login">Login<a/><a href="?logout">Logout<a/><a href="">Accounts</a>
</div>
<div id="content">
	<div id="form">
		<p>Welcome			
				<br><a href="../plugins/facebook/get_oauth.php"><img src="../images/facebook.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><a href="../plugins/twitter/get_oauth.php"><img src="../images/twitter.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><a href="../plugins/tumblr/get_oauth.php?signin"><img src="../images/tumblr.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><a href="../plugins/digg/get_oauth.php"><img src="../images/digg.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<div id="feeds">
				<br>Feed: <input type="textbox" size="25"> Tags: <input type="textbox" size="15"><input type="button" value="Add" onclick="createDiv();"></div>

		</p>
	</div>
	<div id="footer">
		SNOctopus Inc., ©2011  All Rights Reserved
	</div>
</div>


</body>
</html>
