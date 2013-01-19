<?php
    if (!empty($_POST["sortoption"]))
    $order = $_POST["sortoption"];
    
    $options = array("Date of Event", "Date Added (Newest First)");
?>
	
<!-- drop-down menu to select how to sort events-->
<div style="float:left;text-align:left;">
    <form name="sortby" class="form-inline" action="index.php" method="post" style="float:left; margin-bottom:0px; margin-left:10px;">
        <fieldset>
            <b style="font-size:xx-large;">Sort Events By:</b>
            <select name = "sortoption" style="margin-left:5px;" onchange=sortby.submit()>
                <?php
                    foreach($options as $option)
                    {
                        if ($order == $option)
                            print "<option selected>" . $option . "</option>";
                        else
                            print "<option>" . $option . "</option>";
                    }
                ?>
            </select>
        </fieldset>
    </form>
</div>
MOBILE
<!-- table to display upcoming events-->
<table class="table table-striped" style="overflow-y: auto; word-wrap: break-word; max-width: 32px; font-size:xx-large; line-height: 2.0">

	<!-- logistics to be displayed-->
    <thead>
        <tr>
            <th style="word-wrap: break-word; max-width: 100px;">Name of Event</th>
            <th style="word-wrap: break-word; max-width: 80px;">Date</th>
            <th style="word-wrap: break-word; max-width: 80px;">Time</th>
        </tr>
    </thead>

    <tbody>

    <?php
        if (empty($order) or $order == "Date of Event")
        {
            // query the database for events
            $rows = query("SELECT * FROM food_info ORDER BY year, month, day, ampm, hour, minute");
        }
        elseif ($order == "Date Added (Newest First)")
        {
            $rows = query("SELECT * FROM food_info ORDER BY added DESC");
        }
    ?>
          
          
        <!-- if there are no events in the database for some reason, display message -->  
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan = "4" style="text-align: center;">No upcoming events.</td>                
            </tr>
        <? endif ?>
        
        
        <!-- use the results of the SQL query to populate the table-->
        <?php if (!empty($rows)): ?>
        
            <?php foreach ($rows as $row): ?>

                <tr>
					<td style="word-wrap: break-word; max-width: 100px;">
						<a href=<?= "displayemail.php?id=" . $row["id"] ?> ><?= $row["title"] ?></a>
					</td>
                    <td style="word-wrap: break-word; max-width: 80px;">
                    	<?= $row["month"] . '/' . $row["day"] . '/' . $row["year"] ?>
                    </td>
                    <td style="word-wrap: break-word; max-width: 80px;">
                    	<?= $row["hour"] . ':' . $row["minute"] . ' ' . $row["ampm"] ?>
                    </td>
                </tr>
             <? endforeach; ?>
             
         <? endif ?>

    </tbody>

</table>
