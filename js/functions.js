		  /*
		  ajax.php Option list
		  0 : New Feed [Fabio]
		  1 : Toggle Networkid / Feed active status  [Tom]
		  
		  
		  */
			function newFeed(){
				var html = new Array(); 
				html = $.ajax({
				    url:'ajax.php?option=2&values='+$('input[name="feed"]').val(),
				    async: false}).responseText;
				    
				var response = html;
					
				if(response == "1"){
					$('input[name="feed"]').removeClass("error");
					$('input[name="feed"]').addClass("success");
				} else {
					$('input[name="feed"]').removeClass("success");
					$('input[name="feed"]').addClass("error");
				}

			}
			

 			$(document).ready(function() {
				$.get("feed_info.php", function(data){
					$('#infobox').html(data);
				});
				
				//When page loads...
				$(".tab_content").hide(); //Hide all content
				$("ul.tabs li:first").addClass("active").show(); //Activate first tab
				$(".tab_content:first").show(); //Show first tab content
			
				//On Click Event
				$("ul.tabs li").click(function() {
			
					$("ul.tabs li").removeClass("active"); //Remove any "active" class
					$(this).addClass("active"); //Add "active" class to selected tab
					$(".tab_content").hide(); //Hide all tab content
			
					var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
					$(activeTab).fadeIn(); //Fade in the active ID content
					return false;
				});		
				
				$(".tab_container .tab_content td#active").click(function(){
					var obj = $(this);	
					$.get("ajax.php",{ 'option' : 4, 'values': $(this).attr("value"),'state': $(this).attr("state")}, function(data){
						obj.attr("state", data);
						if(data == 1){
							obj.toggleClass("toggle_active toggle_inactive");
							obj.text("Active");
						} else {
							obj.toggleClass("toggle_inactive toggle_active");
							obj.text("Inactive");
						}				
					});
				});
				$(".tab_container .tab_content td#remove").click(function(){
					var obj = $(this);	
					$.get("ajax.php",{ 'option' : 5, 'values': $(this).attr("value")}, function(data){	
						obj.parent("tr").remove();					
						//$(".tab_container .tab_content tr."+obj.attr("value")).html("");		
					});
				});
			
					

	
			});
			function remove(){	
				$(".tab_container .tab_content td#remove").click(function(){
					var obj = $(this);	
					$.get("ajax.php",{ 'option' : 5, 'values': $(this).attr("value")}, function(data){	
						obj.parent("tr").remove();					
						//$(".tab_container .tab_content tr."+obj.attr("value")).html("");		
					});
				});
			}
			function active(){	
				$(".tab_container .tab_content td#active").click(function(){
					var obj = $(this);	
					$.get("ajax.php",{ 'option' : 4, 'values': $(this).attr("value"),'state': $(this).attr("state")}, function(data){
						obj.attr("state", data);
						if(data == 1){
							obj.toggleClass("toggle_active toggle_inactive");
							obj.text("Active");
						} else {
							obj.toggleClass("toggle_inactive toggle_active");
							obj.text("Inactive");
						}				
					});
				});
			}
	
		
