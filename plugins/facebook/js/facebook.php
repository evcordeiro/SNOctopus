<?php 
	session_start();
	require_once('../config.php');
	?>

			var username;
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
			window.fbAsyncInit = function() {

                FB.init({
                	appId: '<?php echo APP_ID;?>', 
                	status: true, 
                	cookie: true, 
                	xfbml: true
                	});
 				
                /* All the events registered */
               FB.logout(function(response) {
					  // user is now logged out
					});
				FB.Event.subscribe('auth.login', function(response) {
				  // do something with response.session
				  var at = FB.getSession().access_token;
	  			  var uid = FB.getSession().uid;
				  var credentials = new Array();
	              var credentials = {access_token: at, id: uid};
				  var name;
	              FB.api('/me', function(names) {
	              	name = names.username;	 
	              
				  $.get("ajax.php", { option: "3", values: credentials }, function(data) {
			 	 		var html = "<tr><td><img src='http://graph.facebook.com/"+uid+"/picture'></td><td class='name'>"+name+"</td><td id='active' class='toggle_active' value='"+data+"' state='1' onmouseover='active();'>Active</td><td id=\"remove\" value='"+data+"' onmouseover='remove();'>Remove</td></tr>"
						$(".tab_container .tab_content table.facebook > tbody:first").append(html);	 
					});
			  	  }); 
				  
				  
				  
				  FB.logout(function(response) {
					  // user is now logged out
					});
					
				  });
				
                FB.logout(function(response) {
				  // user is now logged out
				});
 
                
            };



 