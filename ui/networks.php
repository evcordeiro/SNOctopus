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
	<a href="">Login/Logout<a/><a href="">Accounts</a>
</div>
<div id="content">
	<div id="form">
		<p>Welcome <b>Bob</b>
			
				<br><a href="../plugins/facebook/get_oauth.php"><img height="80px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><a href="../plugins/twitter/get_oauth.php"><img src="../images/twitter.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><a href="../plugins/tumblr/get_oauth.php"><img src="../images/tumblr.png"></a><img height="40px" src="http://findicons.com/files/icons/719/crystal_clear_actions/64/button_ok.png">
				<br><img src="images/digg.png"><img  height="40px" src="http://upload.wikimedia.org/wikipedia/commons/thumb/6/61/Crystal_128_error.png/50px-Crystal_128_error.png">
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


