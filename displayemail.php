<?php
    
    // pick which email to display
    $id = $_GET['id'];
	
	require("includes/config.php");
	
	if (file_exists("/nfs/home/groups/cs50-foodfinder/web/html_emails/html_email" . $id . ".html"))	
	{	
		// show email on page
		$link = "html_emails/html_email" . $id . ".html";
		
		$row = query("SELECT * FROM food_info WHERE id = ?", $id);
		
		$title = $row[0]["title"];
		
		// render header
		require("templates/header.php");
		
		if (!empty($row[0]["title"]))
		{
			print '<p class="lead">' . $row[0]["title"] . '</p>';
		}

		// render template
		require("$link");

		// render footer
		require("templates/footer.php");
    }
    
    else
    	apologize("This event has been deleted.");

?>