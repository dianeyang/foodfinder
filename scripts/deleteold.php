<?php
/*********************************
*
* deleteold.php
* Harvard Food Finder
*
* deletes old entries from the email and food_info sql tables 
* as well as the email text files
*
********************************/

	require_once dirname(__FILE__) . "/../includes/config.php";
	require_once dirname(__FILE__) . "/../includes/functions.php";
	
	// get current date
	$date = getdate();
	
	// array to track things to be deleted
	$delete;
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *
    * CLEAR OLD EVENTS FROM food_info AND emails
    *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	
	// find which events are old
	$rows = query("SELECT * FROM food_info ORDER BY year, month, day");
	foreach($rows as $row)
	{
		// event is old
		if($row['year'] < $date['year'])
		{
            echo "DELETED " . $row['title'] . ", id = " . $row['id'] . "\n";
			$delete[] = $row['id']; 
		}
		else if ($row['month'] < $date['mon'])
		{
            echo "DELETED " . $row['title'] . ", id = " . $row['id'] . "\n";
			$delete[] = $row['id'];
		}
		else if ($row['day'] < $date['mday'])
		{
            echo "DELETED " . $row['title'] . ", id = " . $row['id'] . "\n";
			$delete[] = $row['id'];
		}
			
		// event is not old, so no future events will be either
		else
			break;
	}
	
	// delete all old events from food_info
	if (!empty($delete))
	{
		foreach($delete as $del)
            
			query("DELETE FROM food_info WHERE id=?", $del);
	}
	
	// get remaining entries in food_info
	$insql = query("SELECT id FROM food_info");
	
	// put ids into 1-dimensional array
	foreach($insql as $id)
		$ids[] = $id['id'];		
	
	// delete from email sql
	$id2 = query("SELECT id FROM emails");
	foreach($id2 as $id)
	{
		if(!in_array($id['id'], $ids))
			query("DELETE FROM emails WHERE id=?", $id['id']);	
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *
    * CLEAR FILES FOR EVENTS THAT NO LONGER EXIST
    *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
		
	// delete html files
	$files1 = scandir('/nfs/home/groups/cs50-foodfinder/web/html_emails/');
	foreach ($files1 as $file) 
	{
		// get id # of file
		preg_match_all('/([\d]+)/', $file, $numb);
		
		// delete file if that id isn't in food_info
		if(!in_array($numb[0][0], $ids))
			unlink("/nfs/home/groups/cs50-foodfinder/web/html_emails/html_email" . $numb[0][0] . ".html");
	}

	// delete plaintext files
	$files2 = scandir('/nfs/home/groups/cs50-foodfinder/web/plaintext_emails/');
	foreach($files2 as $file) 
	{
		// get id # of file
		preg_match_all('/([\d]+)/', $file, $numb);
		
		// delete file if that id isn't in food_info
		if(!in_array($numb[0][0], $ids))
			unlink("/nfs/home/groups/cs50-foodfinder/web/plaintext_emails/plaintext_email" . $numb[0][0] . ".txt");
	}
	
	// delete image files
	$files3 = scandir('/nfs/home/groups/cs50-foodfinder/web/email_images/');
	foreach($files3 as $file) 
	{
		// get id # of file
		preg_match_all('/([\d]+)/', $file, $numb);
		
		// delete file if that id isn't in food_info
		// NOTE: there will be warnings b/c 2 of below files won't exist, but program still runs
		if(!in_array($numb[0][0], $ids))
		{
			unlink("/nfs/home/groups/cs50-foodfinder/web/email_images/email_image" . $numb[0][0] . ".jpg");
			unlink("/nfs/home/groups/cs50-foodfinder/web/email_images/email_image" . $numb[0][0] . ".gif");
			unlink("/nfs/home/groups/cs50-foodfinder/web/email_images/email_image" . $numb[0][0] . ".png");
		}
	}
    
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    *
    * CLEAR REPORTS FOR EVENTS THAT NO LONGER EXIST
    *
    * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    $reports = query("SELECT * FROM report");
    // for every report, check if corresponding event exists
    foreach($reports as $report)
    {
        $report_found = 0;
        // look for that event in food_info
        foreach($rows as $row)
        {
            // if you found the event, flag it as found and stop looking
            if($report['event_id'] == $row['id'])
            {
                $report_found = 1;
                break;
            }
        }
        // delete old reports from table
    	if ($report_found == 0)
    		query("DELETE FROM report WHERE event_id = ?", $report['event_id']);
    }
		
?>