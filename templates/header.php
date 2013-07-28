<!DOCTYPE html>

<html style="height:100%;">

<head>

<!-- favicon courtesy of www.degraeve.com-->
<LINK REL="SHORTCUT ICON" HREF="http://www.hcs.harvard.edu/cs50-foodfinder/html/img/favicon.ico" />

<!-- include css styling-->
<link href="./html/css/bootstrap.css" rel="stylesheet"/>
<link href="./html/css/bootstrap-responsive.css" rel="stylesheet"/>
<link href="./html/css/styles.css" rel="stylesheet"/>

<!-- include google font-->
<link href='http://fonts.googleapis.com/css?family=Dosis:400,700|PT Sans' rel='stylesheet' type='text/css'>

<!-- script for sharing -->
<script type="text/javascript">var switchTo5x=true;</script>
<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "ab7a537c-4e64-44bf-8173-89e85480e101"});</script>

<!-- set title-->
<?php if (isset($title)): ?>
<title>Harvard Food Finder: <?= htmlspecialchars($title) ?></title>
<?php else: ?>
<title>Harvard Food Finder</title>
<?php endif ?>


<!-- set up javascript-->
<script src="./html/js/jquery-1.8.2.js"></script>
<script src="./html/js/bootstrap.js"></script>
<script src="./html/js/scripts.js"></script>


<!-- google analytics-->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38041409-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


<!-- set base url -->
<?php if (preg_match('/^(http:\/\/)?(www.)?harvardfoodfinder.com\//', geturl($_SERVER))): ?>
    <base href="http://www.harvardfoodfinder.com/">
<?php elseif (preg_match('/^(http:\/\/)?(www.)?hcs.harvard.edu\/cs50-foodfinder\//', geturl($_SERVER))): ?>
    <base href="http://hcs.harvard.edu/cs50-foodfinder/">
<?php endif ?>

</head>

<body style="height:100%;">

<div class="container" style="width:100%;">

<div id="top" style="overflow:hidden;">
		<table id="headertable" class="centeralignment">
			<tr>
				<!-- logo -->
				<td style="width:50px; padding-left:5px;">
					<a href="" style="text-decoration:none;" onmouseover="changeheader('./html/img/radar-logo-red.png', '#F00', '#F00')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
    					<img id="logo" alt="logo" src="http://www.hcs.harvard.edu/cs50-foodfinder/html/img/radar-logo.png"/>
					</a>
				</td>
				<!-- page header -->
				<td style="text-align:left; padding-left:10px;">
					<h1 class="pageheader" style="font-size:14px;">
						<a href="" style="text-decoration:none;" onmouseover="changeheader('./html/img/radar-logo-red.png', '#F00', '#F00')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
							<small id="harvard">HARVARD</small>
						</a>
					</h1>
					<h1 class="pageheader">
						<a id="foodfinder" href="" style="text-decoration:none;" onmouseover="changeheader('./html/img/radar-logo-red.png', '#F00', '#F00')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
							<span style="font-weight:700;">FOOD</span><span style="font-weight:400;">FINDER</span>
						</a>
						<small id="harvard" style="text-transform:none;font-size:16px;font-family:'PT Sans';">Tracking down free food events on campus</small>
					</h1>
					
				</td>
				<td style="text-align:right; width:100px; vertical-align:bottom; padding-bottom:5px; padding-right:10px; color:#999; white-space:normal;">
					Subscribe to our updates:
				</td>
				<td style="width:50px; padding:5px;">
					<a href="digest.php" onmousemove="changeimage('mailinglist', './html/img/mailinglist-red.png')" onmouseout="changeimage('mailinglist', './html/img/mailinglist.png')">
						<img class= "iconlink" id="mailinglist" src="./html/img/mailinglist.png"/>
					</a>
				</td>
				<td style="width:50px; padding:5px;">
					<a href="http://www.facebook.com/pages/Harvard-Food-Finder/396601167087384?ref=hl" onmousemove="changeimage('facebook', './html/img/facebook-red.png')" onmouseout="changeimage('facebook', './html/img/facebook.png')">
						<img class= "iconlink" id="facebook" src="./html/img/facebook.png"/>
					</a>
				</td>
				<td style="width:50px; padding:5px;" onmousemove="changeimage('twitter', './html/img/twitter-red.png')" onmouseout="changeimage('twitter', './html/img/twitter.png')">
					<a href="http://www.twitter.com/harvardfoodfind">
						<img class= "iconlink" id="twitter" src="./html/img/twitter.png"/>
					</a>
				</td>
			</tr>
		</table>
		
</div>


<!-- include navbar at top of page -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner" style="border-radius: 0px; text-transform:uppercase; font-family:'Dosis'; font-weight:bold;">
            <div class="container" style="width:100%;">
            
                <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            
            <!-- Everything you want hidden at 940px or less, place within here -->
                <div class="nav-collapse collapse">
                <!-- .nav, .navbar-search, .navbar-form, etc -->
                
                    <ul class="nav">
                        <li class="">
                        <a href="">Upcoming Events</a>
                        </li>
                        <li class="">
                        <a href="add.php">Manually Add an Event</a>
                        </li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="height:10px; margin-bottom:5px;">
                        About
                        <b class="caret"></b>
                        </a>
                            <ul class="dropdown-menu">
                                <li><a href="documentation.php">Documentation</a></li>
                                <li><a href="design.php">Design</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav" id="reportbutton">
                        <li class="right">
                        <a href="report.php">Report Abuse/Mistake</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
        <h3 id="myModalLabel">(Untitled)</h3>
    </div>
    <div id="displaycontents" class="modal-body">
        (No description)
    </div>
<!-- 
    <div class="modal-footer">
    	<div id="modalshare">
			<span class='st_facebook_large' id='st_facebook' st_url=''></span>
			<span class='st_twitter_large' id='st_twitter' st_url='' st_title='' st_via=''></span>
			<span class='st_googleplus_large' id='st_gplus' st_url='' displayText='Google +'></span>
		</div>
		<button class="btn modalbtn" aria-hidden="true">Add to G-Cal</button>
        <button class="btn modalbtn" type="submit" formaction="report.php" aria-hidden="true">Report</button>
        <button class="btn modalbtn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
 -->
</div>

<div id="middle">
    <div class="container" id ="middlebody">