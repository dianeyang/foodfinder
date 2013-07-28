<?php
/*********************************
*
* report.php
* Harvard Food Finder
*
* allows user to report event misinformation/abuse
*
********************************/

    
    require("mincludes/config.php");
    
    // if form not yet submitted
    if($_SERVER["REQUEST_METHOD"] != "POST")
    {
        // gather list of current events in database
        $events = mquery("SELECT * FROM food_info ORDER BY title");
        if($events === false)
            mapologize("Query failed. Please try again later");
        
        // show reporting form
        mrender("mtemplates/reportform.php", array("events" => $events));
    }
    
    // form was submitted
    else
    {
        // check form was filled out correctly
        if(!isset($_POST["inlineRadio"]))
        	mapologize("You must select what is wrong with the event.");
            
        else
        {
            
            // get id number for event 
            $event = mquery("SELECT * FROM food_info WHERE title=?", $_POST['event']);

            if(empty($event))
            	$event[0]['id'] = 0;
             
			// insert into report sql table
			$query = mquery("INSERT INTO report (event_id, type, comments) VALUES (?, ?, ?)", $event[0]['id'], $_POST["inlineRadio"], $_POST["comments"]);
			
			// send an email to notify the webmasters
			$message = "A user reported the event " . $_POST["event"] . ", ID = " . $event[0]['id'] . ".\n\nThe comment was: " . $_POST["comments"];
			mail ("dianeyang0@gmail.com", "New Reported Event", $message);
			mail ("kim.soffen@gmail.com", "New Reported Event", $message);
			
			// let the user know the report was submitted
			msuccess("Thank you for reporting the event.");
			
        }
    }
?>
