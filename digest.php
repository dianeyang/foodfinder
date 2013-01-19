<?php
/*********************************
*
* digest.php
* Harvard Food Finder
*
* allows user to subscribe or unsubscribe to daily digest email
*
********************************/
	require("includes/config.php");

	// if form not yet submitted
    if($_SERVER["REQUEST_METHOD"] != "POST")
    	render("templates/digestform.php", array("title" => "Daily Digest Email"));

	// form was submitted
	else
	{		

		// user is trying to subscribe
		if($_POST["action"] == "subscribe")
		{
		
			if(empty($_POST["sub"]))
			apologize("Please enter your email to subscribe.");
			
			// make sure email is valid format (contains @ and . and no spaces)
			if(strpos($_POST["sub"], '@') === false || strpos($_POST["sub"], '.') === false 
			|| strpos($_POST["sub"], ' ') !== false)
				apologize("Invalid email format.");
			
			// make sure email not already in database
			if(query("SELECT * FROM dailydigest WHERE email = ?", $_POST["sub"]))
				apologize("Email already signed up for Daily Digest.");
			
			// add email to list, send email confirmation, and show confirmation on screen
			query("INSERT INTO dailydigest (email) VALUES (?)", $_POST["sub"]);
			confirmation($_POST["sub"]);
			success("You have successfully subscribed to Daily Digest Emails.
					We have sent a confirmation email to " . $_POST["sub"]);
		}
		
		// user is trying to unsubscribe
		elseif($_POST["action"] == "unsubscribe")
		{
		
			if(empty($_POST["unsub"]))
				apologize("Please enter your email to unsubscribe.");
			
			// a funny prank
			if ($_POST["unsub"] == 'rogerhuang@college.harvard.edu')
				apologize("Good try Roger.");
			
			// user is in database, so unsubscribe
			if(query("SELECT * FROM dailydigest WHERE email = ?", $_POST["unsub"]))
			{
				query("DELETE FROM dailydigest WHERE email = ?", $_POST["unsub"]);
				success("You have successfully unsubscribed from Daily Digest Emails.");
			}
			
			// user is not in database
			else
				apologize("Your email was not found in the database.");
				
		}

	}

?>