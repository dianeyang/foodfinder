<div>
	<p class="lead">
		Report abuse, incorrect information, or an event that's not actually free food.
	</p>
</div>
<div>
    <form action="report.php" method="post" class="form-horizontal">
        <fieldset>
            
            <!-- form with options based on what events there are -->
            <div class="form-inline">
            	Event name:
				<select name = "event" class="span5">  
					<option>Event Name</option>  
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
            <div id="radios" class="form-inline" style="margin:10px;"> Problem:
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio1" value="notfreefood"> Not Free Food
				</label>
				<label class="radio inline">
					<input type="radio" name="inlineRadio" id="inlineRadio2" value="wronginfo"> Incorrect Information
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
            <div class="control-group" style="margin:15px;">
                <button type="submit" class="btn">Submit</button>
            </div>
            
        </fieldset>
    </form>
</div>

<!-- back button -->
<a href="/cs50-foodfinder";>Back to Event Listings</a>
