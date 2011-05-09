<?php 
	session_start();
	require_once('../config.php');
	?>
			var username;

		    
			window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo APP_ID;?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
 
                FB.getLoginStatus(function(response) {
                    if (response.session) {
                        // logged in and connected user, someone you know
                        login();
                    }
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                FB.api('/me', function(response) {
                    document.getElementById('login').style.display = "block";
                    document.getElementById('login').innerHTML = response.name + " succsessfully logged in!";
                });
            }
            function logout(){
                document.getElementById('login').style.display = "none";
            }
 
            function fqlQuery(){
                FB.api('/me', function(response) {
                     var query = FB.Data.query('select name, hometown_location, sex, pic_square from user where uid={0}', response.id);
                     query.wait(function(rows) {
 
                       return rows[0].name;
                     });
                });
            }

            
 
            
           var fabio;
           var access;

            function fbswitch() {
            	FB.login(function(response) {
            				
	  			  var at = FB.getSession().access_token;
	  			  var uid = FB.getSession().uid;
 			 	
	              var credentials = new Array();
	              var credentials = {access_token: at, id: uid};
	              var name;
	              FB.api('/me', function(names) {
	              	name = names.name;	 
	              }); 
				 $.get("ajax.php", { option: "3", values: credentials }, function(data) {
				 	
				 		var html = "<tr><td><img src='http://graph.facebook.com/"+uid+"/picture'></td><td>"+name+"</td><td id='active' class='toggle_active' value='"+data+"' state='1' onmouseover='active();'>Active</td><td id=\"remove\" value='"+data+"' onmouseover='remove();'>Remove</td></tr>"
						alert(html);
						$(".tab_container .tab_content table > tbody:first").append(html);	 
            		
				});
				
				 	

			  
	            FB.logout(function(response) {});
	            }, {perms:"<?php echo PERMISSIONS;?>"});
	            FB.logout(function(response) {});
				


         	}

 