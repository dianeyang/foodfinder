/***********************************************************************
 * scripts.js
 *
 * Computer Science 50
 * Problem Set 7
 *
 * Global JavaScript, if any.
 **********************************************************************/

/* changes the src of an image */
function changeimage(id, new_src)
{
	document.getElementById(id).src = new_src;
}


function changeheader(img, color1, color2)
{
	/* change logo image */
	document.getElementById('logo').src = img;
	
	/* change harvard color */
	document.getElementById('harvard').style.color = color1;
	
	/* change foodfinder color */
	document.getElementById('foodfinder').style.color = color2;
}

function loadmodal(id, contents, title)
{
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.open("GET",contents,true);
	xmlhttp.send();
	
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById(id).innerHTML=xmlhttp.responseText;
			document.getElementById('myModalLabel').innerHTML=title;
		}
	}
}