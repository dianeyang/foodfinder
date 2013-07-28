<?php

/****************************************************************************
 *
 * senddigest.php
 * sends a daily email to users who have subscribed to Daily Digest emails
 *
 ****************************************************************************/
 
 // referenced http://www.w3schools.com/php/func_mail_mail.asp
 // to learn how to send HTML emails, borrowed lines 65-67

	require_once dirname(__FILE__) . "/../includes/config.php";
	require_once dirname(__FILE__) . "/../includes/functions.php";
	
	// set subject of email
	$date = getdate();
	$subject = 'Daily Digest: ' . $date["mon"] . "/" . $date["mday"] . "/" . $date["year"];
	
	// prepare the tags to start and end an html document
	$htmlstart = "<html>
				<head>
				<title>Daily Digest" . $date["mon"] . "/" . $date["mday"] . "/" . $date["year"] .
				"</title>
				</head>
				<body>";
				
	$htmlend = "</body>
				</html>";
    
    // prepare the image header
    $image = "<div><a href=\"http://www.harvardfoodfinder.com\"><img src=\"http://hcs.harvard.edu/cs50-foodfinder/html/img/dailydigestheader.png\"></a></div><br/>";
    
    // prepare the footer message
    $footer = "You are receiving this email because you have subscribed to Daily Digest emails from
    <a href=\"http://hcs.harvard.edu/cs50-foodfinder/\">Harvard Food Finder</a>.
    If you would like to unsubscribe, please click <a href=\"http://hcs.harvard.edu/cs50-foodfinder/digest.php\">
    here</a>.";
	
	// generate message
	$events = query("SELECT * FROM food_info WHERE day = ? AND month = ? ORDER BY ampm, hour, minute", $date["mday"], $date["mon"]);
    print_r($events);
	// events found
	if (!empty($events))
	{
		// set intro to email, default events, and footer
		$message1 = "Good morning! The free food events for today are listed below. Click to see more information.";
		
		// go through each event today
		// convert sql entry to readable English form
		
		foreach($events as &$event)
		{
			$entry = '<li>' . '<a href=\'http://www.hcs.harvard.edu/cs50-foodfinder/displayemail.php?id=' . $event['id'] . '\'>'
					 . $event['title'] . ': ' . $event['hour'] . ':' . $event['minute'] . ' ' . $event['ampm'];	 
			if($event['hour2'] != NULL)
				$entry = $entry . ' - ' . $event['hour2'] . ':' . $event['minute2'] . ' ' . $event['ampm'];
			if($event["building"] != NULL)
				$entry = $entry. ' at ' . $event['building'] . ' ' . $event['room'];
			$entry = $entry . '</a>'. '<br/><br/></li>';
			$row[] = $entry;
		}
		
		// put all event rows into 1 message
		foreach($row as $ro)
			$message2 = $message2 . $ro;
			
		// add to intro, message, and html start & end tags
		$message = $htmlstart. $image. $message1 . "<br/><ul>" . $message2 . "</ul>" . $footer . $htmlend;
	
		echo '2';
		// set headers for email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: <harvardfoodfinder@gmail.com>' . "\r\n";
		
		// send to everyone on list 
		$users = query("SELECT * FROM dailydigest");
		foreach($users as $user)
			mail($user["email"], $subject, $message, $headers);
	}
		
?>
