<!-- NOT MOBILE-->
<?php
    require_once dirname(__FILE__) . "/../includes/gmail.php";
   
   
    if (!empty($_POST["sortoption"]))
    $order = $_POST["sortoption"];
    
    $options = array("Date of Event", "Date Added (Newest First)");
    echo $style;
?>

<div style="float:left;">
    <h3 style="line-height:1; margin:0;">Upcoming Events</h3>
</div>

<!-- buttons to select how to sort events-->
<div style="float:right;text-align:left;">
	<b>Order By:</b>
	<form class="order" action="index.php" method="post" >
	<input id="ebtn" class="orderbtn" type="submit" value="Date of Event">	
    </form>
    |
	<form class="order" action="index.php" method="post" >
	<input type="hidden" name="adddate">
	<input id="abtn" class="orderbtn" type="submit" value="Date Added (Newest First)">
	
	</form>
</div>

<!-- table to display upcoming events-->
<table class="table" style="overflow-y: auto;">

	<!-- logistics to be displayed-->
    


 	<tbody>

          
    <!-- if there are no events in the database for some reason, display message -->  
    <?php if (empty($rows)): ?>
        <tr>
            <td colspan = "6" style="text-align: center;">No upcoming events.</td>                
        </tr>
    <? endif ?>
        
        
    <!-- use the results of the SQL query to populate the table-->
     <?php
         if(!empty($rows))
         {
            foreach ($rows as $row)
            {
                print ("<tr>");
				
				$gcal = makegcal($row, 1);
                //$datetimeinfo = '<td class="gcalbox" onclick="window.open(\'http://www.google.com\',\'Width=100%\');"><span class="date datetime2">' . $row["month"] . "/" . $row["day"] . '</span><br/>';
				
				$datetimeinfo = '<td class="gcalbox" onclick="window.open(\'' . $gcal . '\',\'Width=100%\');"><span class="date datetime2">' . $row["month"] . "/" . $row["day"] . '<br/></span>';
               
                // print time based on info available
                if($row["hour"] != NULL && $row["hour2"] != NULL)
                    $datetimeinfo = $datetimeinfo . '<span class="time datetime2">' . $row["hour"] . ":" . $row["minute"] . " " . $row["ampm"] . " - <br/>" . $row["hour2"] . ":" . $row["minute2"] . " " . $row["ampm2"] . '</span>';
                else if($row["hour"] != NULL)
                    $datetimeinfo = $datetimeinfo . '<span class="time datetime2">' . $row["hour"] . ":" . $row["minute"] . " " . $row["ampm"] . '</span>';
                
                print($datetimeinfo);
                print('<span class="gcallink">Add to Google Calendar</span></td>');
                    
                    
                    

                $linkstring = '<span class="eventtitle"><a href="#myModal" data-toggle="modal" onclick="loadmodal(\'./html_emails/html_email' . $row["id"] . '.html\', \'' . $row["title"] . '\')">';
                print('<td>' . $linkstring .  $row["title"] . '</a></span>');
                
                if($row["rsvp"] == 'Yes')
                	print('<span class="rsvp"> RSVP Required </span>');    
                
                print('<br/>' . $row["building"] . " " . $row["room"]);
                
                print('<td style="white-space:nowrap;">');	
    ?>
		
<ul class="nav" id="moreoptions">
    <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="height:10px; margin-bottom:5px;">Share <b class="caret"></b></a>
        <ul class="dropdown-menu" id="moreoptions-dropdown">

        	<?php
                                
                print('<li><a href="javascript:void()">');
               // print('<span>Share:</span><br/>');
				print("<span class='st_facebook_large' st_url='http://www.hcs.harvard.edu/cs50-foodfinder/displayemail.php?id=" . $row['id'] . "' displayText='Facebook'></span>");
 				print("<span class='st_twitter_large' st_url='http://www.hcs.harvard.edu/cs50-foodfinder/displayemail.php?id=" . $row['id'] . "' st_title='" . $row['title'] . "' st_via='' displayText='Tweet'></span>");
				print("<span class='st_googleplus_large' st_url='http://www.hcs.harvard.edu/cs50-foodfinder/displayemail.php?id=" .$row['id'] . "' displayText='Google +'></span>");
				print('</a></li>');
                
                //$gcal = makegcal($row);
                //print('<li>' . $gcal . '</li>');
                
               // print('<hr>');

               // print('<li><a href="#myModal" data-toggle="modal" onclick="loadmodal(\'displaycontents\', \'./templates/reportform.php\', \'Report\')">Report</a></li></ul></li></ul>');
		 	}
		 }

?>

    </tbody>

</table>