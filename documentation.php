<?php
	$title = "Documentation";
	require("includes/config.php");
	require("templates/header2.php");
?>

<div style="text-align:left">

	<div id="jumpto" style="text-align:center">
		<p class="lead"><a id="start">Food Finder's Documentation</a></p>
		<ul class="nav nav-pills">
		    <li class="active"><a href="#">Jump to:</a></li>
		    <li><a href="#main">Main Page</a></li>
		    <li><a href="#add">Manual Add</a></li>
		    <li><a href="#digest">Daily Digest</a></li>
		    <li><a href="#report">Report</a></li>
		</ul>
	</div>
	
	<p class="lead"><a id="main">Main Page:</a></p>
	<p>
	Our website can be found at www.hcs.harvard.edu/cs50-foodfinder.
	On the main page you can find a list of all the logistical information for the upcoming 
	events that have been deemed free food by our algorithm. They are listed in chronological 
	order, and each event is listed with its name, date, time, location, whether an rsvp is 
	required, and a spot to report abuse or a mistake related to the event. Each event title 
	is linked to a page that contains the entire text of the email from which the event was 
	generated. This way, if you click on an event, you can get more complete information about 
	the event than is included on the front page table.
	</p>
	<p>
	There is also a navigation bar on top which includes a link to manually 
	add an event and to receive Daily Digest emails, both of which are described below.  
	</p>

	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>

	<p class="lead"><a id="add">Manual Add:</a></p>
	<p>
	This function would be used when the user knows about a free food event that is not 
	registered on the website. This would occur if either our algorithm didn't identify 
	the email about that event as free food (because it did not contain enough key words, 
	etc) or the email account from which we download emails isn't on a mailing list that 
	the event got sent to. In either case, when you reach this page, you see a form that 
	includes, just like the main table, a spot for the event title, date, time, location, 
	and rsvp requirement. It also contains an "additional information" section, which will 
	act as the equivalent to the full email text (if the event was sent to us in an email), 
	so it will display when the event title is clicked. If the user fills this out completely 
	(with a few exceptions- room and comments) and hit the submit button it will be added to 
	the main page's table.
	</p>
		
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>

	<p class="lead"><a id="digest">Daily Digest:</a></p>
	<p>
	If the user signs up for Daily Digest emails, they will receive an email every morning 
	at 5am that includes a chronological list of all the free food events that are occurring 
	that day. On the Daily Digest page on the website, the user can subscribe (by typing their 
	email into the first blank and hitting subscribe), in which case it is checked that their 
	email has a valid format and isn't already receiving Daily Digest emails, and they are added 
	to the list. Conversely, the user can also use this page to unsubscribe from the email list 
	by typing their email into the 2nd text box labeled unsubscribe, and (if their email matches 
	one that's on the email list) they will be unsubscribed.
	</p>
		
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
	<p class="lead"><a id="report">Report:</a></p>
	<p>
	If the user clicks 'report' next to one of the entries, they will be sent to another
	page to fill in information about what's wrong with that event. The event they selected will
	be automatically filled in (but they can change it if they clicked the wrong report button), 
	and they will be asked for what type of error occurred and for details on the mistake. If
	this form is filled out correctly, the report is put into our database so we can decide
	how to fix the problem. 
	</p>
	
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
</div>

<?php
	require("templates/footer.php");
?>