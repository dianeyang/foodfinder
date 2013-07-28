<div class="container">
	<!-- title/instructions for page-->
	<p class="lead">
		Know of a free food event we missed? Enter the info here!
	</p>
	<p>
		Alternatively, forward a publicity email to <a href="mailto:harvardfoodfinder@gmail.com">harvardfoodfinder@gmail.com</a>, and it should get added the next time we run our algorithm.
	</p>
	
	<form action="madd.php" method="post" class="form-inline">
		
		<!-- form that includes time/date/location extra info -->
		<fieldset>
			<div class="control-group">
					<input name="title" placeholder="Event Title" type="text" class="span6"/>
			</div>
				
			<div class="control-group">
			    <!-- options for date within next 2 weeks -->
				<select name = "date" placeholder = "Date" class="spandate" style="display:inline; width:60%;">  
					   <option>Date</option>  
						<?php
							foreach($dates as $date)
							print("<option>" . $date . "</option>");
						?>
				</select>
				
			    <!-- checkbox for rsvp -->
				RSVP Required?
				<input type="checkbox" name=rsvp value="1">
			</div>
				
			<div class="control-group">
				 at 
	
    			<!-- options for hour -->
				<select name="hour" placeholder="Time" class="spanhr" style="display:inline; width:30%;">  
				   <option>Hour</option>  
				   <option>1</option>
				   <option>2</option>
				   <option>3</option>
				   <option>4</option>
				   <option>5</option>
				   <option>6</option>
				   <option>7</option>
				   <option>8</option>
				   <option>9</option>
				   <option>10</option>
				   <option>11</option>
				   <option>12</option>
				</select>
				
				:
				
				<!-- options for minutes -->
				<select name="minute" class="spanmin" style="display:inline; width:30%;">  
					<option>Min</option>  
					<option>00</option>
					<option>15</option>
					<option>30</option>
					<option>45</option>
				</select>
					
				<!-- options for am/pm -->
				<select name="ampm" class="spanampm" style="display:inline; width:30%;">
					<option>AM/PM</option>
					<option>am</option>
					<option>pm</option>
				</select>
			</div>
				
			<!-- place to write in building and room -->
			<div class="control-group">
				<input name="building" placeholder="Building/Address" type="text" class="spanloc" style="display:inline; width:65%;"/>
				<input name="room" placeholder="Room" type="text" class="span1" style="display:inline; width:32%;"/>
			</div>
			
			<!-- place to put in additional info about event -->
			<div>
				<textarea name="comments" class="span6" rows="8" placeholder="Event Description (HTML formatting is allowed)"></textarea>
			</div>
	
			<!-- text at bottom (reminder)-->
			<h1 style="line-height:0px;">
				<small style="font-size:16px;">Please use this feature responsibly, or your event will be deleted.</small>
			</h1>
			<p class="lead">
			Make sure the information is correct. You won't be able to edit after you submit!
			</p>
			
			<!-- submit button -->
			<div class="control-group">
				<button type="submit" class="btn btn-primary" name="action" value="submit">Submit Event</button>
				<button type="submit" class="btn" name="action" value="preview">Preview</button>
			</div>
		</fieldset>
	</form>
</div>

<!-- back button -->
<div class="backbutton">
<a href="http://m.harvardfoodfinder.com"><< Back to Event Listings</a>
</div>