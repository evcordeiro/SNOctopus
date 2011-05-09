<?php

/* 
* 
* file: functions.php 
* Revision: 1.0 
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

/* Miscellaneous Facebook Plugin Methods */

function postErrorMessage($error_message = NULL)
{
	echo ("<font size='14'>Sorry, there was an error processing your request</font><br>");
	echo ("Error Message: " . $error_message . "<br><br><br><br>");
	echo ("<font size='12'><a href='http://sno.wamunity.com/build/index.php'>Return to SNOctopus</a></font>");
}



?>
