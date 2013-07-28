<div class="container">
	<!--directions for page-->
	<p class="lead">
		The Daily Digest is an email sent out each morning listing the free food events for the day. 
		<br/>
		Subscribe or unsubscribe here.
	</p>

	<!--form to subscribe or unsubscribe for emails-->
    <form action="digest.php" method="post">
        <fieldset>
        	<p>Please note that some email providers mark Daily Digest Emails as spam.</p>
        	<p>Enter your email here to subscribe.</p>
        	<div class="input-append">
				
				<!-- field for subscribing-->
				<input id="appendedInputButton" name="sub" placeholder="Email" type="text" class="span3"/>
				
				<!-- submit button 1-->
				<button class="btn" type="submit" name="action" value="subscribe" style="width:110px;">Subscribe</button>
        	</div>
        	
        	<hr>
        	
            <p>Enter your email here to unsubscribe.</p>
        	<div class="input-append">
				<!-- field for unsubscribing-->
				<input id="appendedInputButton" name="unsub" placeholder="Email" type="text" class="span3"/>
				<!-- submit button 2-->
				<button class="btn" name="action" value="unsubscribe" style="width:110px;">Unsubscribe</button>
        	</div>
           
           
        </fieldset>
	</form>
</div>

<!-- back button -->
<div class="backbutton">
<a href="http://m.harvardfoodfinder.com"><< Back to Event Listings</a>
</div>