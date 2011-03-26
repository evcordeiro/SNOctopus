<?php
/* 
* 
* file: logout.php
* Revision: 0.5
* authors: Fabio Elia, Lior Ben-kiki, Evan Cordeiro, 
* Thomas Norden, Royce Stubbs, Elmer Rodriguez 
* license: GPL v3 
* This file is part of SNOctopus. 
* 
* SNOctopus is free software: you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by 
* the Free Software Foundation, either version 3 of the License, or 
* (at your option) any later version. 
* 
* SNOctopus is distributed in the hope that it will be useful, 
* but WITHOUT ANY WARRANTY; without even the implied warranty of 
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the 
* GNU General Public License for more details. 
* 
* You should have received a copy of the GNU General Public License 
* along with SNOctopus. If not, see http://www.gnu.org/licenses/ 
*
*/
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	include("models/config.php");
	
	//Log the user out
	if(isUserLoggedIn()) $loggedInUser->userLogOut();

	if(!empty($websiteUrl)) 
	{
		$add_http = "";
		
		if(strpos($websiteUrl,"http://") === false)
		{
			$add_http = "http://";
		}
	
		header("Location: ".$add_http.$websiteUrl);
		die();
	}
	else
	{
		header("Location: http://".$_SERVER['HTTP_HOST']);
		die();
	}	
?>


