     twttr.anywhere(function (T) {
        if (T.isConnected()) {
	      var currentUser = T.currentUser;
	      var screenName = currentUser.data('screen_name');
	     // alert(screenName);
               twttr.anywhere.signOut();

        } else {
        	T("#twttr-anywhere-button").connectButton({
            authComplete: function(user, bridge_code) {

            	var currentUser = T.currentUser;

	      		var screenName = currentUser.data('screen_name');

              $.post('./plugins/twitter/convert.php', {'bridge_code': bridge_code}, function(data){
			 	 	var html = "<tr><td><img src='http://img.tweetimag.es/i/"+screenName+"'></td><td class='name'>"+screenName+"</td><td id='active' class='toggle_active' value='"+data+"' state='1' onmouseover='active();'>Active</td><td id=\"remove\" value='"+data+"' onmouseover='remove();'>Remove</td></tr>"

$(".tab_container .tab_content table.twitter > tbody:first").append(html);	 
					
              });
               twttr.anywhere.signOut();
            }
          });
        }
      });
     
