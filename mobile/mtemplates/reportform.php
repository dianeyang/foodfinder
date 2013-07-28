<div class="container">
	<p class="lead">
		Report abuse, incorrect info, or an event that's not actually free food.
	</p>

    <form action="mreport.php" method="post" class="form-horizontal">
        <fieldset>
            
            <!-- form with options based on what events there are -->
            <div class="form-inline">
				<select name = "event" class="span5">  
					<option>Event Name</option>
					<option>General Comments</option> 
					<?php
						foreach($events as $event)
						{
							if ($event["id"] == $id)
								print("<option selected=\"selected\">" . $event["title"] . "</option>");
							else
								print("<option>" . $event["title"] . "</option>");
						}
							
					?>
				</select>
            </div>
            
            <!-- radio buttons for type of problem-->
            <div id="radios" class="form-inline" style="margin:10px;"> <span style="vertical-align:sub;">Problem:</span>
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio1" value="notfreefood"> Not Free Food
				</label>
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio2" value="wronginfo"> Incorrect Info
				</label>
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio3" value="abuse"> Abuse
				</label>
                <label class="radio inline">
                <input type="radio" name="inlineRadio" id="inlineRadio3" value="duplicate"> Duplicate Event
                </label>
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio3" value="other"> Other
				</label>
			</div>
            
            <br\><br\>
            
            <!-- spot for submitter to write what's wrong w/ event -->
            <div>
                <textarea name="comments" class="span6" rows="8" placeholder="Additional Comments"></textarea>
            </div>
            
            <!-- submit button -->
            <div class="control-group" style="margin:15px; text-align:center;">
                <button type="submit" class="btn" style="width:150px;">Submit</button>
            </div>
            
        </fieldset>
    </form>
</div>

<!-- back button -->
<div class="backbutton">
<a href="http://m.harvardfoodfinder.com"><< Back to Event Listings</a>
</div>