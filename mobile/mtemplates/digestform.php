<div>
	<!--directions for page-->
	<p class="lead">
		The Daily Digest is an email sent out each morning listing the free food events on that day. 
		<br/>
		Subscribe or unsubscribe to the Daily Digest email list here.
	</p>
</div>

<br/>

<div>
	<!--form to subscribe or unsubscribe for emails-->
    <form action="digest.php" method="post">
        <fieldset>
        	<p>Please note that some email providers mark Daily Digest Emails as spam.</p>
        	<p>Enter your email here to subscribe to Daily Digest Emails.</p>
        	<div class="input-append">
				
				<!-- field for subscribing-->
				<input id="appendedInputButton" name="sub" placeholder="Email" type="text" class="span3"/>
				
				<!-- submit button 1-->
				<button class="btn" type="submit" name="action" value="subscribe">Subscribe</button>
        	</div>
        	
        	<br/><br/>
        	
        	
            <p>Enter your email here to unsubscribe from Daily Digest Emails.</p>
        	<div class="input-append">
				<!-- field for unsubscribing-->
				<input id="appendedInputButton" name="unsub" placeholder="Email" type="text" class="span3"/>
				<!-- submit button 2-->
				<button class="btn" name="action" value="unsubscribe">Unsubscribe</button>
        	</div>
           
           
        </fieldset>
	</form>
</div>


<!-- back button -->
<a href="/cs50-foodfinder";>Back to Event Listings</a>