<?php
/*********************************
*
* add.php
* Harvard Food Finder
*
* allows user to manually add an event to webiste
*
********************************/

    require("mincludes/config.php");
    
    // array of days in month to account for spillover
    $month_day = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31,);
        
    // associate weekday #s to days
    $week_numb = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday',);
        
    // associate month #s to months
    $month_numb = array('0', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
    'November', 'December',);

    // if form not yet submitted
    if($_SERVER["REQUEST_METHOD"] != "POST")
    {   
        // get current date
        $today = getdate();
        if ($today == NULL)
            mapologize("Error. Try again later.");
            
        // get dates for the next 2 weeks
        for ($i = 0; $i < 14; $i++)
        {
            $days[$i]["wday"] = ($today["wday"] + $i) % 7;
            $days[$i]["mon"] = $today["mon"];
            $days[$i]["mday"] = $today["mday"] + $i; 
            $days[$i]["year"] = $today["year"]; 
            
            // account for month spillover
            if ($days[$i]["mday"] > $month_day[$today["mon"]])
            {
                $days[$i]["mon"]++;
                $days[$i]["mday"] -= $month_day[$today["mon"]];
            }
            
            // account for year spillover
            if ($days[$i]["mon"] > 12)
            {
                $days[$i]["mon"] -= 12;
                $days[$i]["year"]++;
            }
            
            // convert to written out date form
            $days[$i]["mon"] = $month_numb[$days[$i]["mon"]];
            $days[$i]["wday"] = $week_numb[$days[$i]["wday"]];
            
            // concatonate to make final date
            $date[$i] = $days[$i]["wday"] . ", " . $days[$i]["mon"] . " " . $days[$i]["mday"] . ", " . $days[$i]["year"];
        }
        
        // show add form
        mrender("mtemplates/addform.php", array("dates" => $date, "title" => "Add Event"));
    }
     
    // form was submitted
    else
    {
    	// if the submit button was hit
		if ($_POST["action"] == "submit")
		{
			// check form submission is valid
			if ($_POST["date"] == "Date")
				mapologize("You must select a date."); 
			else if (empty($_POST["title"]))
				mapologize("You must give a title.");
			else if ($_POST["hour"] == "Hour")
				mapologize("You must select a time."); 
			else if ($_POST["minute"] == "Min")
				mapologize("You must select a time.");
			else if ($_POST["ampm"] == "AM/PM")
				mapologize("You must select a time."); 
			else if (empty($_POST["building"]))
				mapologize("You must select a building."); 
				
			// form was filled out correctly
			else
			{
				// make sure no bad words
				if(!preg_match('/\b(\w*test\w*)\b/', $_POST['comments'], $matches) && !preg_match('/\b(\w*haha\w*)\b/', $_POST['comments'], $matches)
				&& !preg_match('/\b(\w*fuck\w*)\b/', $_POST['comments'], $matches) && !preg_match('/\b(\w*shit\w*)\b/', $_POST['comments'], $matches)
				&& !preg_match('/\b(\w*bitch\w*)\b/', $_POST['comments'], $matches) && !preg_match('/\b(\w*dyke\w*)\b/', $_POST['comments'], $matches)
				&& !preg_match('/\b(\w*fag\w*)\b/', $_POST['comments'], $matches) && !preg_match('/\b(\w*cunt\w*)\b/', $_POST['comments'], $matches)
				&& !preg_match('/\b(\w*ass\w*)\b/', $_POST['comments'], $matches)
				&& !preg_match('/\b(\w*test\w*)\b/', $_POST['title'], $matches) && !preg_match('/\b(\w*haha\w*)\b/', $_POST['title'], $matches)
				&& !preg_match('/\b(\w*fuck\w*)\b/', $_POST['title'], $matches) && !preg_match('/\b(\w*shit\w*)\b/', $_POST['title'], $matches)
				&& !preg_match('/\b(\w*bitch\w*)\b/', $_POST['title'], $matches) && !preg_match('/\b(\w*dyke\w*)\b/', $_POST['title'], $matches)
				&& !preg_match('/\b(\w*fag\w*)\b/', $_POST['title'], $matches) && !preg_match('/\b(\w*cunt\w*)\b/', $_POST['title'], $matches)
				&& !preg_match('/\b(\w*ass\w*)\b/', $_POST['title'], $matches))
				{
				
					// pull components out of date
					$words = preg_split('/\s+/',$_POST["date"],-1,PREG_SPLIT_NO_EMPTY);
					$month = array_search($words[1], $month_numb);
					$day = intval($words[2]);
					$year = $words[3];
					
					// capitalize building name
					$_POST["building"] = ucwords($_POST["building"]);
		
					// decide if rsvp req'd
					if(isset($_POST['rsvp']))
						$rsvp = "Yes";
					else
						$rsvp = "No";
		
					// get next available id #
					mquery("INSERT INTO emails (id) VALUES (NULL)");
					$ids = mquery("SELECT * FROM emails ORDER BY id DESC");
					$id1 = $ids[0]['id'];
		
					// make file to keep comments
					$name = "../html_emails/html_email" . $id1 . ".html";
					$file = fopen($name, 'w');
					if ($file == NULL)
						exit();
					$formatted = str_replace("\n", "<br/>", $_POST["comments"]);
					fwrite($file, "<div>". $formatted . "</div>");
					
					
					// update row on email sql table
					mquery("UPDATE emails SET subject=?, html_path=?, parsed=1, freefood=1 WHERE id=?", 
					$_POST["title"],$name, $id1);
					fclose($file);
		
		
					// add event to database if not there already
					if (!mquery("SELECT * FROM food_info WHERE month = ? AND day = ? AND hour = ? AND 
					minute = ? AND ampm = ? AND building = ? AND room = ?", $month, $day, $_POST["hour"], 
					$_POST["minute"], $_POST["ampm"], $_POST["building"], $_POST["room"]))
					{
						mquery("INSERT INTO food_info (id, title, month, day, year, hour, minute, ampm, building, 
						room, rsvp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $id1, $_POST["title"], 
						$month, $day, $year, $_POST["hour"], $_POST["minute"], $_POST["ampm"], $_POST["building"], 
						$_POST["room"], $rsvp);
					}
					
					// email the webmasters
					$subject = "New Event Added";
					$message = "A user added an event " . $_POST["title"] . ", ID = " . $id1 . ".\n\nThe comment was: " . $_POST["comments"];
					
					mail ("dianeyang0@gmail.com", $subject, $message);
					mail ("kim.soffen@gmail.com", $subject, $message);
		
					// let the user know the event was added
					msuccess("Thank you for submitting your event.");
				}
				
				else
					mapologize("Your event was rejected by our automatic moderator. If you believe this was
					a mistake, please email event information to harvardfoodfinder@gmail.com");
			}
		}
		
		// if the preview button was hit
		elseif ($_POST["action"] == "preview")
		{
			$formatted = str_replace("\n", "<br/>", $_POST["comments"]);
			mrender("mtemplates/preview.php", array("title" => $_POST["title"], "comments" => $formatted));
		}
    }

// citations: line 78 http://www.java-samples.com/showtutorial.php?tutorialid=982

?>
