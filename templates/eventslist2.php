<?php
    if (!empty($_POST["sortoption"]))
    $order = $_POST["sortoption"];
    
    $options = array("Date of Event", "Date Added (Newest First)");
    ?>

<!-- drop-down menu to select how to sort events-->
<div style="float:left;text-align:left;">
<form name="sortby" class="form-inline" action="index.php" method="post" style="float:left; margin-bottom:0px; margin-left:10px;">
<fieldset>
<b>Sort Events By:</b>
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

<!-- table to display upcoming events-->
<table class="table table-striped" style="overflow-y: auto;">

<!-- logistics to be displayed-->
<thead>
<tr>
<th>Name of Event</th>
<th>Date</th>
<th>Time</th>
<th>Location</th>
<th>RSVP Required?</th>
<th>Report Abuse/Mistake</th>
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
<td colspan = "6" style="text-align: center;">No upcoming events.</td>
</tr>
<? endif ?>


<!-- use the results of the SQL query to populate the table-->
<?php if (!empty($rows)): ?>

<?php foreach ($rows as $row): ?>

<tr>
<td><a href="#myModal" data-toggle="modal" onclick="loadmodal('displaycontents', <?='\'./html_emails/html_email' . $row["id"] . '.html\'' ?>, <?='\'' . $row["title"] . '\'' ?>)"><?= $row["title"] ?></a></td>
<td><?= $row["month"] . '/' . $row["day"] . '/' . $row["year"] ?></td>
<td><?= $row["hour"] . ':' . $row["minute"] . ' ' . $row["ampm"] ?></td>
<td><?= $row["building"] . ' ' . $row["room"] ?></td>
<td><?= $row["rsvp"] ?></td>
<td><a href="#myModal" data-toggle="modal" onclick="loadmodal('displaycontents', <?='\'./templates/reportform.php?id=' . $row["id"] . '\'' ?>, 'Report')">
<button class="btn">Report</button></a></td>
</tr>
<? endforeach; ?>

<? endif ?>

</tbody>

</table>