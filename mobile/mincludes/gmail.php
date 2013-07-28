<?php

// GCAL LINK; source = http://support.google.com/calendar/bin/answer.py?hl=en&answer=1186917#individual

function makegcal($row)
{
	// format event name and place
	$gcalname = preg_replace("/\s/", "%20", $row["title"]);
	$gcalplace = preg_replace("/\s/", "%20", $row["building"] . " " . $row["room"]);
	
	// format date
	if (strlen($row["month"]) == 1)
		$row["month"] = '0' . $row["month"];
	if (strlen($row["day"]) == 1)
		$row["day"] = '0' . $row["day"];
	$gcaldate = $row["year"] . $row["month"] . $row["day"];
	
	// format time  
	// if time given
	if($row["hour"] != NULL)
	{
		// put in military time
		if($row["ampm"] == 'pm')
			$row["hour"] += 12;
		if($row["ampm2"] == 'pm')
			$row['hour2'] += 12;
		
		// deal w null fields -> default to 1 hr event
		if($row["hour2"] == NULL)
		{
			$row["hour2"] = $row["hour"] + 1;
			$row["minute2"] = $row["minute"];
		}
		
		// account for GMT
		$row["hour"] += 5;
		$row["hour2"] += 5;
					
		// account for daylight savings
		if($gcaldate > (($row["year"] * 10000) + 310) && $gcaldate < (($row["year"] * 10000) + 1103))
		{
			$row["hour"]--;
			$row["hour2"]--;
		}
	
		if($row["hour"] > 23)
		{
			$row["hour"] -= 24;
			$gcaldate++;
			
			// account for month and year spillover
			$daycount = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			$daycount2 = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			
			// account for leap year
			if($row["year"] % 4 == 0)
				$counter = $daycount2;
			else
				$counter = $daycount;
			
			// account for month spillover
			if(($row["day"]++) > $counter[(int)$row["month"]])
			{
				$gcaldate -= $counter($row["month"]);
				
				// account for year spillover
				if($row["month"] == 12)
				{
					$gcaldate += 10000;
					$gcaldate -= 1100;
				}
				else
					$gcaldate += 100;
			}
		}
		
		$gcaldate2 = $gcaldate; 
		
		
		if($row["hour2"] > 23)
		{
			$row["hour2"] -= 24;
			
			
			$gcaldate2++;
			
			// account for month and year spillover
			$daycount = array(0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			$daycount2 = array(0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
			
			// account for leap year
			if($row["year"] % 4 == 0)
				$counter = $daycount2;
			else
				$counter = $daycount;
			
			// account for month spillover
			if(($row["day"]++) > $counter[(int)$row["month"]])
			{
				$gcaldate2 -= $counter($row["month"]);
				
				// account for year spillover
				if($row["month"] == 12)
				{
					$gcaldate2 += 10000;
					$gcaldate2 -= 1100;
				}
				else
					$gcaldate2 += 100;
			}
		}
		
		// correct numb of digits
		if(strlen($row["hour"]) == 1)
			$row["hour"] = '0' . $row["hour"];
		if(strlen($row["minute"]) == 1)
			$row["minute"] = '0' . $row["minute"];
		if(strlen($row["hour2"]) == 1)
			$row["hour2"] = '0' . $row["hour2"];
		if(strlen($row["minute2"]) == 1)
			$row["minute2"] = '0' . $row["minute2"];
			
		// construct final date format
		$gcaltime1 = 'T' . $row["hour"] . $row["minute"] . '00Z';
		$gcaltime2 = 'T' . $row["hour2"] . $row["minute2"] . '00Z';
	}
	
	// time not given
	else
	{
		$gcaltime1 = $gcaltime2 = "";
		$gcaldate2 = $gcaldate + 1;
	}	
	
	
	return ('<a href="http://www.google.com/calendar/event?action=TEMPLATE&text=' . 
	$gcalname . '&dates=' . $gcaldate . $gcaltime1 . '/' . $gcaldate2 . $gcaltime2 . '&details=
	&location=' . $gcalplace .'&trp=true&sprop=http%3A%2F%2Fwww.hcs.harvard.edu%2Fcs50-foodfinder%2
	F&sprop=name:Harvard%20Food%20Finder" target="_blank">Add to G-Cal</a>');
}

?>