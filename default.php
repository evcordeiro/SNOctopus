@CHARSET "ISO-8859-1";
			body{
				margin:0px;
				padding:0px;
				background-color:lightblue;
				font-family: Gill Sans, Verdana;
			}
			#top_bar{
				height: 123px;
				padding:5px 10 5 5;
				padding-right:10px;
				background:#145f69 url('images/logo/minilogo.png') no-repeat left top;
				background: url('images/logo/minilogo.png') no-repeat left top, -moz-linear-gradient(60% 100% 90deg, #000000, #145f69) ;
				background: url('images/logo/minilogo.png') no-repeat left top, -webkit-gradient(linear, 0% 0%, 0% 100%, from(#145f69), to(#000000));
				border-bottom: 4px solid #eee;
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
			#infobox{	
				background-color:#2f7d89;
				-moz-border-radius-bottomright: 15px;
				border-bottom-right-radius: 15px;
				-moz-border-radius-bottomleft: 15px;
				border-bottom-left-radius: 15px;
				border: 4px solid white;
				color: #000000;
				margin: auto;
				margin-top:-20px;
				padding:50px;
				text-align:center;
				width: 520px;
			}
			#form{
				background-color:#145f69;
				<?php 
				$side = array("","left","right");
				$base = array("","top","bottom");
				$i = rand(0, 2);
				$j = rand(0, 2);
				echo "background:#145f69 url('images/logo/octopus-color.jpg') no-repeat $side[$i] $base[$j];";
				?>
			 	-moz-border-radius: 15px; border-radius: 15px;
				border: 4px solid white;
				color:white;
				margin-top:50px;
				margin: auto;
				padding:50px;
				text-align:center;
				width: 520px;
			}
			#form p{
				font-family: Gill Sans, Verdana;
				color:#ffffff;
				line-height: 40px;
			}
			#form a{
				color:#ececec;
			}
			#content{
				padding:20px;
				text-align:center;
			}
			ul{
				list-style: none;
			}
			.align_center{	
				margin: 0px auto;
				text-align:center;
				
			}
			.align_left{	
				text-align:left;
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
				padding:20px;
				text-align:center;
			}
			#issue, .result ol > li, #networks > li {
			 	padding:8px;
			 	color:#000000;
			 	background-color:#ececec;
			 	-moz-border-radius: 5px; border-radius: 5px;
			 	filter:alpha(opacity=80); opacity:0.8;
			 	display: -moz-inline-stack; display: inline-block;*display: inline;
			    vertical-align: top;
			    margin: 2px;
			    font-family: Verdana; font-size: 18px;

			 }
			 #networks > li .toggle_active, .result ol > li #active  {
			    font-family: Verdana; font-size: 18px;
			 	padding:8px;
				background-color: #9FF33D;
				margin: -8px;
				-moz-border-radius: 5px; border-radius: 5px;
			}
			.result ol > li #inactive, .feed_details ul > li .toggle_inactive  {
				font-style:italic;
			 	padding:8px;
				background-color: #F5001D;
				margin: -8px;
				-moz-border-radius: 5px; border-radius: 5px;
			}

			/* Login Errors */
			
			#error li{
			    list-style: none;
			    margin-top:5px;
			 	padding:5px;
			 	color: #ffffff;
			 	width: auto;
			 	font-size: 12px;
			 	border-bottom: 1px dashed #FF7373;
			 	background-color:#BF3030;
			 	-moz-border-radius: 5px; border-radius: 5px;
			 	filter:alpha(opacity=80); opacity:0.8;
			 }
			 #login{
			 	text-align:right;
			 	margin:0 20 0 0;
			 }

			 #login p > span {
				padding: 10;
				background-color:#000000;
				filter:alpha(opacity=80); opacity:0.8;
				-moz-border-radius: 5px; border-radius: 5px;
			 }
			 .float_left{
			 	float: left;
			 }
			
	
			.feed_list_id{
				background: 2f7d89;				
			}
			.error{
				background-color:#FF0000;
			}
			.success{
				background-color:#00FF00;
			}
			#button{
				background-color:#ececec;
			}
			.feed_details{
				background-color:#92C4D1;
				color:#2E2F2F;
				-moz-border-radius: 5px; border-radius: 5px;
				margin-top:-15px;
				padding: 15px;
			}
			.feed{
				padding: 5px;
				align: left;
				background: Gainsboro;
				-moz-border-radius: 5px; border-radius: 5px;
				z-index:10;
			}
			#refresh{
				margin-top:-15px;
				padding-bottom:15px;
			}
			
			ul.tabs {
				margin: 0;
				padding: 0;
				float: left;
				list-style: none;
				height: 60px; /*--Set height of tabs--*/
				border-bottom: 1px solid #999;
				border-left: 1px solid #999;
				width: 100%;
			}
			ul.tabs li {
				float: left;
				margin: 0;
				padding: 0;
				height: 61px; /*--Subtract 1px from the height of the unordered list--*/
				line-height: 31px; /*--Vertically aligns the text within the tab--*/
				border: 1px solid #999;
				border-left: none;
				margin-bottom: -1px; /*--Pull the list item down 1px--*/
				overflow: hidden;
				position: relative;
				background: #e0e0e0;
			}
			ul.tabs li a {
				text-decoration: none;
				color: #000;
				display: block;
				padding: 0 20px;
				border: 1px solid #fff; /*--Gives the bevel look with a 1px white border inside the list item--*/
				outline: none;
			}
			ul.tabs li a:hover {
				background: #ccc;
			}
			html ul.tabs li.active, html ul.tabs li.active a:hover  { /*--Makes sure that the active tab does not listen to the hover properties--*/
				background: lightblue;
				border-bottom: 1px solid lightblue; 
                        /*--Makes the active tab look like it's connected with its content--*/
			}
			
                        .tab_container a{
				color: #ccc;
			}
	
			.tab_container {
				border: 1px solid #999;
				text-align:left;
				border-top: none;
				color:#000000;
				overflow: hidden;
				clear: both;
				float: left; 
				width: 100%;
				background-color:lightblue;
			}

			.tab_content,.tab_content > a,.tab_content > a {
				text-align:center;
				padding: 20px;
				font-size: 1.2em;
			}
			div .tab_content a{
				color:#000;
			}
			.tab_container td{
				font-size:12px;
				background-color:#92C4D1;
				margin:5px;
			}
                         .tab_container td.name {
                                width:  70%;
                                text-align:center;
                        }
                        .tab_container td.option {
                                width:  50px;
                        }
			.tab_container td.toggle_active{
				text-align:center;
				background-color: #66FF66;				
			}
			.tab_container td.toggle_inactive{
				text-align:center;
				background-color: #CC9999;				
			}

.auto_margin {
    margin: 0 auto;
}

