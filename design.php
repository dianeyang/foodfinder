<?php
	$title = "Design";
	require("includes/config.php");
	require("templates/header2.php");
?>

<div style="text-align:left">

	<div id="jumpto" style="text-align:center">
		<p class="lead"><a id="start">Food Finder's Design</a></p>
		<ul class="nav nav-pills">
			<li class="active"><a href="#">Jump to:</a></li>
		    <li><a href="#backend">Back End</a></li>
		    <li><a href="#features">Other Features</a></li>
		    <li><a href="#frontend">Front End</a></li>
		    <li><a href="#major">Major Design Decisions</a></li>
		</ul>
	</div>

	<p class="lead"><a id="backend">Back End:</a></p>
	<p>
		Every six hours, a cron job runs popdownloader.py and downloads all the emails in our harvardfoodfinder@gmail.com 
		email account that have not previously been downloaded. It saves both the plaintext version 
		(for the purpose of parsing), the HTML version (for the purpose of displaying on the website), and inline images
		(which it inserts into the HTML version of the email). 
		It then uploads the subject of the email as well as the path to where the plaintext and HTML 
		versions were saved into the SQL table called "emails". (This sql table also keeps track of whether 
		the email has been parsed and whether it has been identified as free food- which will both 
		be explained later). 
	</p>
	<p>
		After that step is complete, another cron job runs freefoodfilter.py. This file
		is an algorithm based off of Bayes Theorem and borrows concepts from Bayesian spam filtering methods.
		To train the algorithm, we used emaildownload.py download a training set of 912 emails collected from mailing lists,
		containing a mixture of free food emails and non-free-food emails. All of these emails can be found in finalset.zip. 
		After manually going through 812 of the emails and identifying them as free food or non-free-food in trainingset.csv,
		we ran training.py to count the frequencies of several dozen keywords and patterns (defined with regular expressions).
		These keywords could either be strong indicators of a free food email, or strong indicators of a not-free-food email.
		It then used the frequencies to calculate the probability of free food given each word.
	</p>
	<p>
		We saved the last 100 emails (listed in testset.csv) for testing the algorithm using testingloop.py. The results of our testing
		and tweaking can be found in results.csv. We found that setting the threshold (the minimum probability we must calculate
		in order to label something as free food) to .74 gave us the best results based on our small test, so we stuck with that.
	</p>
	<p>    	
    	Once we fit our algorithm to our training set and tested it on our test set, we wrote freefoodfilter.py, the
    	version of the algorithm that runs on the server. This algorithm searches for our keywords and uses the statistics
    	from the dataset to calculate the probability of free food given that a set of keywords appears. If the probability
    	of free food given those words is calculated to be greater than .74, the program marks the email as free food by
    	changing the "freefood" column of the email SQL table from 0 to 1.
	</p>
	<p>
		Once that has run, another cron job begins that runs parse.php. This file runs through each 
		word in the email to try to pull the date, time, location, and rsvp status of the event. 
		Each word is compared against myriad dictionaries and phrasing scenarios to determine
		if it is a logistically-relevant word. To prevent errors, we only take in the first
		word found to be relevant to each field (eg: "month"), as in our testing the first instance was most 
		often the correct date/time/location for the event. 
		We also eliminate the header of the email; if it is a forwarded email,
		for example, it would include the date and time of the original message on top. These would
		be picked up incorrectly by parse.php since they have date and time format, so we got rid
		of them. Because parse.php is inevitably imperfect, being
		that stating a time or location can be done in infinitely many ways, we allow events to be
		uploaded to the site even if parse was unable to pick up all of the logistical information.
		We also included a report function on the main page so we can correct errors made by parse.php
		as detected by our users. 
	</p>
	<p>
		The final step in this process is done by index.php and eventslist.php; all the information 
		in the SQL food_info table is put, organized chronologically, onto the main page of the site.
		We also concatenate all the information of one 'type' (date, time, location) so it is more 
		human readable- rather than having columns for month, day, year etc like in the SQL table. 
	</p>
	<p>
		Additionally, right after midnight every day, all old events in food_info are deleted
		through an additional cron job since they are no longer relevant (by deleteold.php). 
		deleteold.php also deletes all entries in the emails sql and all email files (html,
		plaintext, and images) which 
		correspond to an email that's not in food_info. This could be because the email is old,
		or because it was not free food related. 
	</p>
	<p>
		Note that all of these events run on cron jobs. Because of this, they don't run based
		on user interaction with the website, so users aren't waiting for the programs. 
		Therefore, when designing these functions, time efficiency
		was not up utmost importance- the user would never notice if parsing an email took .1 second or .2,
		since they wouldn't be waiting for the results of the program.
		Therefore, we sacrificed some time in our code to make it a more
		intuitive and human-readable progression. This major design decision
		made it significantly easier for us to go back
		and tweak the code to make the algorithm and parsing more accurate, among other changes, yet did
		not sacrifice user experience. 
	</p>
	<br/>
	
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
	<p class="lead"><a id="features">Other Features:</a></p>

	<p>
	The other features, add, report, and Daily Digest, are much simpler and more straightforward.
	</p>
	<ul>
		<li>
			<b>Add:</b> This function is almost completely described in the project's documentation. Basically,
			if the user fills out the add form completely, the event gets added to the food_info SQL page 
			and put onto the event list. To mirror these additions after emails, we also store the 
			comments as we would an email. However, we only store the comments (equivalent to the full
			text of the email) in the html file location, despite the comments being plaintext. 
			There are two reasons for this: first, the plaintext file only exists because it is easier
			to parse than an html file. Since the user is entering the logistics directly, there is nothing
			to parse, so the plaintext file is unnecessary. Also, our code that pulls up the full text email
			does so by following a path to html files. Therefore, to get the comments to show up, they 
			must be stored in an html file format and location, despite them not actually being html files.
			With this function, we also chose to blindly allow whatever is typed to be put on the website,
			without scanning the contents for obscenities or requiring our approval before posting. If it 
			turns out that this system is frequently abused, we will implement one of the above two methods
			for manual adds. 
		</li>
		<li>
			<b>Report:</b> This function is even simpler than add. If a user chooses to report a function,
			for reasons of abuse, misinformation, or the event not being free food related, they will 
			be directed to a short form that includes a field to put in which event is relevant, and 
			put in comments about what's wrong with the event. One user optimization we made was that 
			the event field will automatically fill in which event corresponds to the report button was
			clicked. This means that, as long as the user clicked on the correct report button, they
			will not have to adjust this field. However, if they did click the wrong one, they still
			have the option to. After the form is filled out, the information then uploads to the report SQL table.
			We will periodically check this SQL table to see if any complaints have been added, at which
			point we will decide what action to take.  
		</li>
		<li>
			<b>Daily Digest:</b> The Daily Digest function allows users to sign up to receive an email
			every morning with links to all the events in our database that are occurring that day. To do 
			this, we employ php's mail function. We generate a subject based on concatenating parts of the
			current date. The message consists of a header (made of a screenshot of our website's header), and hyperlinks
			and concatenated logistics of the relevant events. Once the message has been put together,
			the email is sent to each email address in the Daily Digest SQL list. 
		</li>
	</ul>

	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
	<p class="lead"><a id="frontend">Front End:</a></p>
	<p>
		Designing the front end did not lead to very many controversial design decisions. Our major focus
		was to give users the most optimized and intuitive experience. One major decision
		we made was putting links to the different pages in a navigation bar in the header. This allowed
		the user to access all the links from any page on the website. If we had left all the links as 
		buttons on the home page, as the website was originally designed, the user would have to click 
		back to the home page before going to any other page on the site. Another decision we made,
		in this same vein, was to make the contents of the web page (everything not included in the header
		or footer) in a scroll box. This means that when the user scrolls through the page, the header is 
		always visible. This is helpful in much the same way the navigation bar is- no matter where
		the user is on the site, they are able to click to any page they want. 
	</p>
	<br/>
	
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
	<p class="lead"><a id="major">Major Design Decisions:</a></p>
	<p>
	The most major design decision we had to make for Food Finder had to do with the order of 
	detecting free food and parsing emails received in the back-end. There were two options:
	</p>
	<ol>
		<li>
			Detect for free food. If it is free food, parse it. The upside to this method is 
			that we are only going through the extensive parse process if we actually need to. That is,
			only emails that will eventually be on the website are parsed. The downside is that 
			we are iterating through every word in the email twice. First, every word is checked against
			the dictionary with probabilities in [algorithm name].php, and then every word is checked 
			against the logistical dictionaries included in parse.php. Going through every word 
			twice could be a major inefficiency if the email was sufficiently long.
		</li>
		<li>
			Detect for free food and find logistics at the same time. This means that when
			each word is put through parse.php and sent to all the logistical dictionaries, it 
			would also be sent through the algorithm's dictionary. At the end of the program, we 
			would calculate whether the email was likely free food or not, and if it passed the 
			aforementioned 75% threshold, then all the logistical information would be uploaded
			to the food_info SQL. 
		</li>
	</ol>
	<p>
		Clearly (based on the back-end description above), we went with the first option.
		We ran both of these programs- the algorithm and the parse- and both ran essentially
		instantaneously. Therefore, which ever of the two options we picked, there would not be 
		a huge impact on time spent, since the two programs independently took no time at all.
		Additionally, it was not essential for this process, since it is in the back-end and 
		operating on a cron job rather than a user action, to be of utmost efficiency. Since 
		we concluded that there was not a significant difference between either of the two
		methods, we decided to go with the first since it seemed like a more intuitive and 
		logical progression through steps. Also, this left the two programs slightly less
		integrated with each other, meaning if changes had to be made, it would be easier
		to do so without messing up the program's function as a whole. Therefore, the first
		option was the better one.
	</p>
	<p>
		One other major design decision we made was in the 'id' column of our SQL tables.
		We decided that between the three SQL tables (food_info, emails,
		and report) and the email paths, all of which relate to specific emails, that each email 
		would be given one id number
		to be used in all the aforementioned places. This made the process of cross-referencing between
		the tables and email texts much simpler, both for some of our programs, like deleteold.php, and for
		our own convenience when checking for program success ourselves. This is also very 
		useful for situations like reporting: since the event that the user selects 
		corresponds to an id in food_info, it is very easy for us, acting as moderators, 
		to use that id to look up the email text, stored in a path with the same id number.
		This, along with many other applications, make the use of a single id number system
		extremely efficient for our purposes.

	</p>
	<p>
		There were also plenty of design decisions to be made while writing the free-food-identifying algorithm.
		At first, we had written the program to function similarly compared to the Bayesian spam filter outlined
		in the oft-cited <a href="http://www.paulgraham.com/spam.html">A Plan For Spam</a> by Paul Graham.
		This basically meant that we would look at every single word in every single email to count their frequencies
		and calculate their probabilities. When scanning over a new email, we would then find the 15 most "interesting"
		words (meaning the 15 words that were furthest away from 0.5). The purpose of doing this was to attempt to catch
		any and all words that could possibly indicate free food.
	</p>
	<p>
		However, repeated tests showed that this method gave us too many false negatives, meaning that we incorrectly
		identified emails as not having free food when they actually did (interestingly, we almost never got false
		positives). We spoke with Professor Michael Parzen (Statistics department) to get some advice, and he suggested
		that instead of looking at every single word, we limit our search to a few dozen key words so that we could avoid
		all the white noise and junk that was throwing us off. We rewrote the algorithm to follow his suggestions and
		saw great improvements in our accuracy.
	</p>
	<p>
		Another smaller decision regarding the development of our algorithm:
		You might also notice that some emails are missing from trainingset.csv.
    	Since we had a relatively small sample size for a training set (only a few hundred as opposed to
    	a few thousand), we decided to remove some emails that we felt were nonrepresentative of the usual
    	stream of information received from these mailing lists. For instance, one group of emails from an
    	open mailing list featured a random, lengthy conversation about a strange new pizza topping at Domino's.
    	Usually, "pizza" would be a very strong indicator of a free food event, but these outlier emails were 
    	drastically skewing our data. Thus, we excluded those emails.
    </p>
    
	<div class="totop">
		<a href="#start">Jump to top</a>
		<hr>
	</div>
	
</div>

<?php
	require("templates/footer.php");
?>