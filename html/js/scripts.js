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

function loadmodal(contents, title, id)
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
			changemodalcontents(xmlhttp.responseText, title);
		}
	}
}

function changemodalcontents(contents, title)
{
    // title & contents
    document.getElementById('displaycontents').innerHTML= contents;
	document.getElementById('myModalLabel').innerHTML=title;
	 
	 // do below ike innerhtml but set whole thing. keep stlight options line of code
	 
	document.getElementById('st_facebook').setAttribute('st_url', 'http://www.cnn.com');
	//document.getElementbyId("st_twitter").setAttribute("st_url", 'http://www.harvardfoodfinder.com/displayemail.php?id=' + id);	
	//document.getElementbyId("st_twitter").setAttribute("st_title", title);	
	//document.getElementbyId("st_gplus").setAttribute("st_url", 'http://www.harvardfoodfinder.com/displayemail.php?id=' + id);	
	stLight.options({publisher: "ab7a537c-4e64-44bf-8173-89e85480e101"});
}

function gcallink(cell, contents)
{
    cell.innerHTML='<span class="addtogcal"><a href="http://www.time.com">' + contents + '</a></span>';
}