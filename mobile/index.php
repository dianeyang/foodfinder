<?php
	require('mheader.php');
?>


          <div id="1807519" class="edit" type="blog" rel="EDIT BLOG">
			<div id="main" role="main" class="t-boutique-p-blogdate">
				<ul class="news date">
                                                
		<?php
			// generate events list
			require(dirname(__FILE__) . "/../includes/functions.php");
			$rows = query("SELECT * FROM food_info ORDER BY year, month, day, ampm, hour, minute");
			
			// convert month numb to abbrev
			$monthnumb = array(0, 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'); 
			
			foreach($rows as $row)
			{
				print('<li><a href="memail.php?id=' .$row['id'] . '">');
				print('<time><span class="d">' . $row['day'] . '</span><span class="m">' . $monthnumb[$row['month']] . '</span><span class="y">' . $row['year'] . '</span></time>');
				print('<h3 class="title3 eventtitle">' . $row['title'] . '</h3><br/>');
				
				if($row["hour"] != NULL && $row["hour2"] != NULL)
                    print('<h3 class="title3 eventdetails">' . $row["hour"] . ":" . $row["minute"] . " " . $row["ampm"] . " - " . $row["hour2"] . ":" . $row["minute2"] . " " . $row["ampm2"] . '</h3>');
                else if($row["hour"] != NULL)
                    print('<h3 class="title3 eventdetails">' . $row["hour"] . ":" . $row["minute"] . " " . $row["ampm"] . '</h3>');
                
                if($row["building"] != NULL)
                	print('<br/><h3 class="title3 eventdetails">' . $row["building"] . ' ' . $row["room"] . '</h3></a>');
				
				
				
			}
		?>
			  
			</div>

		</div>		
<?php
	require('mfooter.php');
?>