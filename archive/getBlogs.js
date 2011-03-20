/*****************
*
*       file:           getBlogs.js
*		Revision:		0.1
*       authors:        Fabio Elia, Lior Ben-kiki, Evan Cordeiro,
*						Thomas Norden, Royce Stubbs, Elmer Rodriguez 
*       license:		GPL v3 
*
******************/

function showRSS(str)
{
	if (str.length==0)  
	{   
		document.getElementById("rssOutput").innerHTML="";  
		return;  
	}

	if (window.XMLHttpRequest)  
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari  
		xmlhttp=new XMLHttpRequest();  
	}

	else  
	{
		// code for IE6, IE5  
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");  
	}

	xmlhttp.onreadystatechange=function()  
	{  
		if (xmlhttp.readyState==4 && xmlhttp.status==200)    
		{    
			document.getElementById("rssOutput").innerHTML=xmlhttp.responseText;    
		}  
	}

	xmlhttp.open("GET","getrss.php?q="+str,true);
	xmlhttp.send();
}

function showAtom(str)
{
	alert("stub");
}
