<?php
/*********************************
*
* parse.php
* Harvard Food Finder
*
* pulls logistical information out of email
*
********************************/

	echo "start\n";
	require_once dirname(__FILE__) . "/../includes/config.php";

	 // temp: prevent notice errors
	 error_reporting(E_ALL ^ E_NOTICE);
	
	 define("MAX_STRING", 1048576);
	 
	 // get all emails currently in database    
	 $rows = query("SELECT * FROM emails WHERE freefood = 1 AND parsed = 0");

	 // go through each email that was downloaded
	 foreach($rows as $row)
	 {
		// set all variables to null
		$date_info = NULL;
		$time_info = NULL;
		$place_info = NULL;
		$rsvp = NULL;
		
		
		// open email file
		$email = fopen($row["plaintext_path"],'r');
		if ($email == NULL)
			exit();	
		// signify haven't read any words yet
		$oldword = false;
		$rsvp = "No";

		// go through entire email
		while($s = fread($email,MAX_STRING)) 
		{
			
			// split into lines 
			$lines = explode("\n", $text);
			
			// if line has to do w/ email header delete line
			foreach($lines as $line)
			{
				if(strpos($line, '---')!== false || strpos($line, 'Date:')!== false ||
				strpos($line, 'Subject:')!== false || strpos($line, 'To:')!== false ||
				strpos($line, 'CC:')!== false || strpos($line, 'BCC:')!== false)
					 $s = str_replace($line, "", $s);
			}
			
			// separate string into its words for parsing
			$words = preg_split('/\s+/',$s,-1,PREG_SPLIT_NO_EMPTY); 
			
			// add additional item to array so can check last word 
			$words[] = NULL;
		
			foreach($words as $word)
			{
				
				// remove punctuation marks and make lowercase ** get rid of 1st char punct
				$word = trim($word, '?.*!=/)(-:');
				$word = ltrim($word, '?.*!=/)(-:');
				$word = strtolower($word);
				
				// adjust for end of array
				if ($word === end($words))
					$word = $oldword;      
				
				// initialize tracking variables
				$is_date = $is_time = 0;   
				
				// search date dictionary if not found yet                      
				if ($date_info['m'] == NULL)
					$is_date = search_date($oldword, $word);
	
				// only look for time if time info hasnt been found already
				if ($time_info['h'] == NULL && $is_date == 0)
					$is_time = search_time($oldword, $word);
		 
				// only look for place if place info hasnt been found already
				if ($time_info['b'] == NULL && $is_date == false && $is_time == false)
					search_place($oldword, $word);
				
				// check for rsvp
				if ($rsvp == "No" && ($word == "rsvp" || $word == "lottery") && $oldword != "no")
					$rsvp = "Yes";
											   
				// keep track of past word
				$oldword = $word;
			}
	   }
	
	   // capitalize building name
	   $place_info["b"] = ucwords($place_info["b"]);
         

	   // check that event not already in database and add  
	   $check = query("SELECT * FROM food_info WHERE month = ? AND day = ? AND hour = ? AND 
	   minute = ? AND ampm = ? AND building = ?", $date_info['m'], $date_info['d'], 
	   $time_info['h'], $time_info['m'], $time_info['ap'], $place_info['b']);
	   
	   if (empty($check) && $date_info['m'] != NULL)
		{
			$query = query("INSERT INTO food_info (id, added, title, month, day, year, hour, minute, 
			ampm, building, room, rsvp) VALUES (?, now(), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			$row["id"], $row["subject"], $date_info['m'], $date_info['d'], $date_info['y'], $time_info['h'],
			$time_info['m'], $time_info['ap'], $place_info['b'], $place_info['r'], $rsvp);
		 }
	
	// update in email database that email has been parsed
	query("UPDATE emails SET parsed=1 WHERE id=?", $row['id']);
    echo "PARSED " . $row['title'] . ", id = " . $row['id'] . ".\n";

	}  

    // find date within email parse
    function search_date($oldword, $word)
    {
        global $date_info;
        
        // array to account for month spillover
        $month_days = array(1 => 31, 2 => 28, 3 => 31,4 => 30, 5 => 31, 6 => 30, 7 => 31,
        8 => 31,9 => 30,10 => 31,11 => 30,12 => 31,);
        
        // array to correspond month to number
        $month_numb = array('april' => 4,'aug' => 8,'august' => 8,'dec' => 12,'december' => 12,
        'feb' => 2,'february' => 2,'jan' => 1,'january' => 1,'july' => 7,'june' => 6,
        'march' => 3,'may' => 5,'nov' => 11,'november' => 11,'oct' => 10,'october' => 10,
        'sep' => 9,'sept' => 9,'september' => 9,);
        
        // array of month words
        $month_dict = array('april', 'aug', 'august','dec','december','feb','february',
        'jan','january','july','june','march','may','nov','november','oct','october','sep',
        'sept','september',);
        
        // array of words that go with "this ___"
        $this_dict = array('afternoon','evening','fri','friday','mon','monday','morning',
        'sat','saturday','sun','sunday','tues','tuesday','thurs','thursday','wed','wednesday',);
        
        // array of words that go w/ timestamp
        $stamp_dict = array('today','tomorrow','tonight',);
        
        // array of date words
        $date_dict = array(
        'eighteenth','eighth','eleventh','fifteenth','fifth','first','fourteenth','fourth',
        'nineteenth','ninth','second','seventeenth','seventh','sixteenth','sixth','tenth',
        'third','thirteenth','thirtieth','thirty-first','twelfth','twentieth','twenty-eighth',
        'twenty-fifth','twenty-first','twenty-fourth','twenty-ninth','twenty-second',
        'twenty-seventh','twenty-sixth','twenty-third',);
        
        // array to convert date words to numbers
        $date_numb = array(
        'eighteenth' => 18,'eighth' => 8,'eleventh' => 11,'fifteenth' => 15,'fifth' => 5,
        'first' => 1,'fourteenth' => 14,'fourth' => 4,'nineteenth' => 19,'ninth' => 9,'
        second' => 2,'seventeenth' => 17,'seventh' => 7,'sixteenth' => 16,'sixth' => 6,
        'tenth' => 10,'third' => 3,'thirteenth' => 13,'thirtieth' => 30,'thirty-first' => 31,
        'twelfth' => 12,'twentieth' => 20,'twenty-eighth'=> 28,'twenty-fifth' => 25,
        'twenty-first'=> 21,'twenty-fourth' => 24,'twenty-ninth' => 29,'twenty-second' => 22,
        'twenty-seventh' => 27,'twenty-sixth' => 26,'twenty-third' => 23,);
        
        //find the date if in month day form
        if (in_array($oldword, $month_dict))
        {
            // store old word as month
            $date_info['m'] = $month_numb[$oldword];
            
            // store next word as day, if number or in word dictionary
            if (in_array($word, $date_dict))
            {
                $date_info['d'] = $date_numb[$word];     
            }
            else if (preg_match('#[0-9]#',$word))
            {
                preg_match("|\d+|", $word, $m);
                $date_info['d'] = $m[0]; 
            }
        }
        
        // find date based on this/next + something
        else if ($oldword == 'this' || $oldword == 'on' || $oldword == 'next')
        {
            if(in_array($word, $this_dict))
            {
                // get current date
                $today = getdate();
                if ($today == NULL)
                   return false; 
                
                // set up day # array
                $event_day = array('sun' => 0,'sunday' => 0,'mon' => 1,'monday' => 1,'tues' => 2,
                'tuesday' => 2,'wed' => 3,'wednesday' => 3,'thurs' => 4,'thursday' => 4,
                'fri' => 5,'friday' => 5,'sat' => 6,'saturday' => 6,);
                
                // case for morning/afternoon/evening CHECK IF WILL === NULL
                
                if ($event_day[$word] === NULL)
                    $change = 0;
                else   
                    $change = ($event_day[$word] - $today['wday']);
                    
                // adjust for if event is earlier day in week than today
                if ($change < 0)
                    $change += 7;
                    
                // adjust for if next week
                if ($oldword == 'next')
                    $change += 7;
                    
                // find diff ($change) and adjust $day
                $today['mday']+= $change;
                
                // account for spillover into next month
                if ($today['mday'] > $month_days[$today['mon']])
                {
                    $today['mday'] %= $month_days[$today['mon']];
                    $today['mon']++;
                }
                
                // set month and day of event
                $date_info['m'] = $today['mon'];
                $date_info['d'] = $today['mday']; 
            }
        }
        
        // deal w/ words that involve timestamp. ex: 'tonight'
        else if (in_array($word, $stamp_dict))
        {
            // get date
            $day = getdate(); 
            if ($day == NULL)
                return false;
                
            // adjust if word is tomorrow not today
            if ($word == 'tomorrow')
                $day['mday']++;
                
            // adjust for spillover into next month
            if ($day['mday'] > $month_days[$day['mon']])
            {
                $day['mday'] %= $month_days[$day['mon']];
                $day['mon']++;
            }
            
            // put in month/day info for event
            $date_info['m'] = $day['mon'];
            $date_info['d'] = $day['mday'];
        }
        
        //deal w/ fully # dates 
        else if ((preg_match("#([0-9]+){1,2}\/([0-9]+){1,2}#i", $word)) || 
                (preg_match("#([0-9]+){1,2}\.([0-9]+){1,2}#i", $word)))
        {
            // check if month is 1 or 2 digits long
            if (is_numeric($word[1]))
            {
                $date_info['m'] = $word[0] . $word[1];
                
                // check if day is 1 or 2 digits long
                if (is_numeric($word[4]))
                {
                    $date_info['d'] = $word[3] . $word[4];
                }
                else
                {
                    $date_info['d'] = $word[3];
            	}
            }
            else
            {
                $date_info['m'] = $word[0];
                
                // check if day is 1 or 2 digits long
                if (preg_match('#[0-9]#',$word[3]))
                    $date_info['d'] = $word[2] . $word[3];
                else
                    $date_info['d'] = $word[2];
            }
        }
        
        // decide year
        if ($date_info['m'] != NULL)
        {
            $date = getdate();
            if($date['mon'] == 12 && $date_info['m'] == 1)
                $date_info['y'] = $date['year'] + 1;
            else if ($date_info['m'] != NULL)
                $date_info['y'] = $date['year'];
        }
        
        // check #s are valid
        if ($date_info['m'] > 12 || $date_info['m'] < 1 || $date_info['d'] < 1 || $date_info['d'] > 31 || 
            !preg_match("/^[0-9]+$/", $date_info['m']) || !preg_match("/^[0-9]+$/", $date_info['d']))
        {
            $date_info['m'] = NULL;
            $date_info['d'] = NULL;
            $date_info['y'] = NULL;
        }
        
        if ($date_info['m'] != NULL)
        {
            return true;
        }
        else
            return false;
    }
    
    // find time within email parse
    function search_time($oldword, $word)
    {         
         global $time_info;
         
          // if 'pm' or 'am' is in the word
          if ((strpos($word, 'pm')) !== false || (strpos($word, 'am')) !== false
          || (strpos($word, 'a.m')) !== false || (strpos($word, 'p.m')) !== false)
          {
              // store am/pm
              if ((strpos($word, 'pm')) !== false || (strpos($word, 'p.m')) !== false)
                  $time_info['ap'] = 'pm';
              else
                  $time_info['ap'] = 'am';  
          
              // if the number and am/pm are same string: eg 1:30pm
              if (preg_match('#[0-9]#',$word))
              {
                  $word = preg_replace('/[^0-9:]/', "", $word);
                  
                  // if number contains : (ie hours and minutes)
                  if (strstr($word, ':'))
                  {
                      $temp = explode(":", $word, 2);
                      $time_info['h'] = $temp[0];
                      $time_info['m'] = $temp[1][0] . $temp[1][1];
                  }
                  
                  // if number is only hour (eg 1pm)
                  else
                  {
                      $time_info['h'] = $word;
                      $time_info['m'] = '00';
                  }
              }
              
              // if the number is different from am/pm (eg 1:30 pm)
              else if (preg_match('#[0-9]#',$word))
              {
                  // if number contains : (ie hours and minutes)
                  if (strstr($word, ':'))
                  {
                      $temp = explode(":", $word, 2);
                      $time_info['h'] = $temp[0];
                      $time_info['m'] = $temp[1][0] . $temp[1][1];
                  }
                  
                  // if number contains only hours
                  else
                  {
                      $time_info['h'] = $word;
                      $time_info['m'] = '00';
                  }
              }
          }
          
          // if written in format 1:30 w/ no other information
          else if (preg_match('#[0-9]#',$oldword) && strstr($oldword, ':'))
          {
              $temp = explode(":", $oldword, 2);
              $time_info['h'] = $temp[0];
              $time_info['m'] = $temp[1][0] . $temp[1][1];

              // betting that 11 means am, else means pm (since no info given)
              if ($time_info['h'] == 11)
                  $time_info['ap'] = 'am';
              else
                  $time_info['ap'] = 'pm';
          } 
          
          // account for '6-7' etc
          else if (preg_match("/^\d{1,2}(\:\d{2})?\-\d{1,2}(\:\d{2})?/", $oldword))
          {
              $temp2 = explode("-", $oldword);
              $time_info['h'] = $temp2[0];
              $time_info['m'] = '00';
              	              
              // determine am/pm based on guess
              if ($time_info['h'] == 11)
                  $time_info['ap'] = 'am';
              else
                  $time_info['ap'] = 'pm';
          }
          
          
          // accounts for 'at 7' etc
          else if (($oldword == 'at') && ($word >= 1) && ($word <= 12))
          {
              $time_info['h'] = $word;
              $time_info['m'] = '00';
              
              // determine am/pm based on guess
              if ($time_info['h'] == 11)
                  $time_info['ap'] = 'am';
              else
                  $time_info['ap'] = 'pm';
          }
          // checks that #s are valid
          if ($time_info['h'] > 12 || $time_info['h'] < 1 || $time_info['m'] < 0 || $time_info['m'] > 59 || 
              !preg_match("/^[0-9]+$/", $time_info['h']) || !preg_match("/^[0-9]+$/", $time_info['m']))
          {
              $time_info['h'] = NULL;
              $time_info['m'] = NULL;
              $time_info['ap'] = NULL;
          }
          
          if ($time_info['h'] != NULL)
              return true;
          else
              return false;
    }
    
    // find location within email parse
    function search_place($oldword, $word)
    {  
        global $place_info;
        
        // array of building words
        $place_dict = array('adams','annenberg','apley','barker','boylston','cabot','canaday',
        'currier','dunster','eliot','emerson','fong','grays','greenough','hollis','holworthy',
        'hurlbut','iop','kirkland','lev','leverett','lionel','lowell','mather','matthews',
        'maxwell', 'maxwell-dwrokin', 'mower','northwest','pennypacker','phoho','phorzheimer',
        'quincy','sever','soch','straus','stoughton','thayer','ticknor','weld','wigglesworth',
        'winthrop', 'womens');
          
        // array of room words
        $room_dict = array('basement', 'common','dhall','dining','jcr','junior','scr','senior',);
        
        // check if 1st word is a building
        if (in_array($oldword, $place_dict))
        {
            $place_info['b'] = $oldword;
            
            // check if next word is a room
            if (in_array($word, $room_dict) || preg_match('#[0-9]#',$word))
                $place_info['r'] = $word;
                
            return true;
        }
        
        // special case for science center
        if ($oldword == 'science' && $word == 'center')
            $place_info['b'] = "science center";
        
        // no match made
        return false;
    }

// citiations: line 48 http://www.java-samples.com/showtutorial.php?tutorialid=982
// line 172, 174, 273, 292, 322, 324, 343, 363, 406, 441:
//     http://www.webdeveloper.com/forum/showthread.php?165159-PREG_MATCH-for-numbers-only
// lines 250-252 http://www.regular-expressions.info/dates.html
// line 378 http://stackoverflow.com/questions/1574911/single-dash-phone-number-regex-validation 
//     AND  http://www.webdeveloper.com/forum/showthread.php?165159-PREG_MATCH-for-numbers-only

?>
