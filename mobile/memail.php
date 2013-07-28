<?php
	require('mheader.php');
    require('mgmail.php');
	require('mincludes/functions.php');
?>	
	
        <div class="sharingoptions">
                <?php
                    $id = $_GET['id'];
                    
                    $row = mquery("SELECT * FROM food_info WHERE id = ?", $id);
                    if(empty($row))
                        mapologize("Query failed. Please try again later.");
                    
                    print("<table class='shareoptions'><tr><td style='border-right: 1px solid white;'><span class='addtogcal'>" . makegcal($row[0]) . "</span></td>");
                    
                    // attempt at share buttons
                    print('<td style="border-left: 1px solid #ccc;"><span style="color:#fff; font-weight:700; text-transform:uppercase;">Share:<br/></span>');
                    print("<span class='st_facebook_large' st_url='http://www.harvardfoodfinder.com/displayemail.php?id=" . $id . "' displayText='Facebook'></span>");
                    print("<span class='st_twitter_large' st_url='http://www.harvardfoodfinder.com/displayemail.php?id=" . $id . "' st_title='" . $row[0]['title'] . "' st_via='' displayText='Tweet'></span>");
                    print("<span class='st_googleplus_large' st_url='http://www.harvardfoodfinder.com/displayemail.php?id=" .$id . "' displayText='Google +'></span></td></tr></table>");
                ?>   
        </div>
		<div id="main" role="main" class="container">
		    <?php
                require(dirname(__FILE__) . "/../html_emails/html_email" . $id . ".html");		
			
			?>
		
		</div>

<!-- back button -->
<a href="http://www.hcs.harvard.edu/cs50-foodfinder/mobile/index.php">
    <div class="backbutton" type="menu" rel="EDIT MENU">		
            << Back to Event Listings
    </div>
</a>

<?php
	require('mfooter.php');
?>