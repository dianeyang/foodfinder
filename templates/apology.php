<!-- display text above message-->
<p class="lead text-error">
    Sorry!
</p>

<!-- display message-->
<p class="text-error">
    <?= htmlspecialchars($message) ?>
</p>

<?php
if ($message == "This event has been deleted.")
	print '<!-- back to homepage button-->
		<a href="/cs50-foodfinder/">Back to Event Listings</a>';

else
	print '<!-- back button-->
	<a href="javascript:history.go(-1);">Back</a>';
?>


