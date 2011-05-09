<?php 
	session_start();
	require_once('../config.php');
?>


	
     twttr.anywhere(function (T) {
        if (T.isConnected()) {
	      currentUser = T.currentUser;
	      screenName = currentUser.data('screen_name');
	     // alert(screenName);
          $("#twttr-anywhere-button").append('<button id="signout" type="button">Sign out of Twitter</button>');
          $("#signout").bind("click", function () {
            twttr.anywhere.signOut();
            location.reload();
          });
        } else {
        	T("#twttr-anywhere-button").connectButton({
            authComplete: function(user, bridge_code) {
              $.post('./plugins/twitter/js/convert.php', {'bridge_code': bridge_code}, function(data){
              });
            
            }
          });
        	
       
        }
      });