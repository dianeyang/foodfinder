<?php
	
	// not mobile
	// configuration
	require("includes/config.php");
	  
	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		if(isset($_POST['adddate'])) 
		{
			$rows = query("SELECT * FROM food_info ORDER BY added DESC");
			$style = '<style> #abtn{color: #000; font-weight: 700;} 
				  #ebtn{color: #f00; font-weight: 400} </style>';
		}
		else
		{ 
			$rows = query("SELECT * FROM food_info ORDER BY year, month, day, ampm, hour, minute");
			$style = '<style> #ebtn{color: #000; font-weight: 700;} 
				  #abtn{color: #f00; font-weight: 400} </style>';
		}
	}
	
	else
	{    
		  $rows = query("SELECT * FROM food_info ORDER BY year, month, day, ampm, hour, minute");
		  $style = '<style> #ebtn{color: #000; font-weight: 700;} 
				  #abtn{color: #f00; font-weight: 400} </style>';
	}

          
	render("templates/eventslist.php", array("title" => "Events List", "rows" => $rows, "style" => $style));

?>

