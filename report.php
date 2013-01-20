<?php
/*********************************
*
* report.php
* Harvard Food Finder
*
* allows user to report event misinformation/abuse
*
********************************/

    $id = $_GET["id"];
    
    require("includes/config.php");
    
    // if form not yet submitted
    if($_SERVER["REQUEST_METHOD"] != "POST")
    {
        // gather list of current events in database
        $events = query("SELECT * FROM food_info ORDER BY title");
        if($events === false)
            apologize("Query failed. Please try again later");
        
        // show reporting form
        render("templates/reportform.php", array("events" => $events, "id" => $id));
    }
    //
    // form was submitted
    else
    {
        // check form was filled out correctly
        if($_POST["event"] == "Event Name")
            apologize("You must select an event to report.");
        else if(!isset($_POST["inlineRadio"]))
        	apologize("You must select what is wrong with the event.");
            
        else
        {
            
            // get id number for event 
            $event = query("SELECT * FROM food_info WHERE title=?", $_POST['event']);
                     
            if (empty($event))
            	apologize("There was an error reporting the event.");
            	
        	else
        	{            
				// insert into report sql table
				$query = query("INSERT INTO report (event_id, type, comments) VALUES (?, ?, ?)", $event[0]['id'], $_POST["inlineRadio"], $_POST["comments"]);
				
                // send an email to notify the webmasters
				$message = "A user reported the event " . $_POST["event"] . ", ID = " . $event[0]['id'] . ".\n\nThe comment was: " . $_POST["comments"];
				mail ("dianeyang0@gmail.com", "New Reported Event", $message);
				mail ("kim.soffen@gmail.com", "New Reported Event", $message);
				
				// let the user know the report was submitted
				success("Thank you for reporting the event.");
			}
        }
    }
?>
