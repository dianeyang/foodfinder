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

<!-- Add fancyBox -->
<link rel="stylesheet" href="./html/css/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="./html/js/jquery.fancybox.pack.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$(".fancybox").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: false,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	});
</script>


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

</head>

<body style="height:100%;">

<div class="container" style="width:100%;">

<div id="top" style="overflow:hidden">
		<table id="headertable" class="centeralignment">
			<tr>
				<!-- logo -->
				<td style="width:89px;">
					<a href="/cs50-foodfinder" style="text-decoration:none;" onmouseover="changeheader('./html/img/radar-logo-red.png', '#FF3300', '#FF3300')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
    					<img id="logo" alt="logo" src="http://www.hcs.harvard.edu/cs50-foodfinder/html/img/radar-logo.png"/>
					</a>
				</td>
				<!-- page header -->
				<td style="text-align:left; padding-left:10px;">
					<h1 class="pageheader" style="font-size:26px;">
						<a href="/cs50-foodfinder" style="text-decoration:none;" onmousemove="changeheader('./html/img/radar-logo-red.png', '#FF3300', '#FF3300')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
							<small id="harvard">HARVARD</small>
						</a>
					</h1>
					<h1 class="pageheader">
						<a id="foodfinder" href="/cs50-foodfinder" style="text-decoration:none;" onmousemove="changeheader('./html/img/radar-logo-red.png', '#FF3300', '#FF3300')" onmouseout="changeheader('./html/img/radar-logo.png', '#999999', '#000000')">
							<span style="font-weight:700;">FOOD</span><span style="font-weight:400;">FINDER</span>
						</a>
					</h1>
				</td>
				<td style="text-align:right; width:90px; vertical-align:bottom; padding-bottom:15px; padding-right:10px; color:#999;">
					Subscribe to our updates:
				</td>
				<td style="width:70px; padding:5px;">
					<a href="./digest.php" onmousemove="changeimage('mailinglist', './html/img/mailinglist-red.png')" onmouseout="changeimage('mailinglist', './html/img/mailinglist.png')">
						<img id="mailinglist" src="./html/img/mailinglist.png"/>
					</a>
				</td>
				<td style="width:70px; padding:5px;">
					<a href="http://www.facebook.com/pages/Harvard-Food-Finder/396601167087384?ref=hl" onmousemove="changeimage('facebook', './html/img/facebook-red.png')" onmouseout="changeimage('facebook', './html/img/facebook.png')">
						<img id="facebook" src="./html/img/facebook.png"/>
					</a>
				</td>
				<td style="width:70px; padding:5px;" onmousemove="changeimage('twitter', './html/img/twitter-red.png')" onmouseout="changeimage('twitter', './html/img/twitter.png')">
					<a href="http://www.twitter.com/harvardfoodfind">
						<img id="twitter" src="./html/img/twitter.png"/>
					</a>
				</td>
			</tr>
		</table>
</div>

<!-- include navbar at top of page -->
<div class="navbar navbar-inverse">
<div class="navbar-inner" style="border-radius: 0px; text-transform:uppercase; font-family:'Dosis'; font-weight:bold;">
<div class="container" style="width:80%;">

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
<a href="/cs50-foodfinder">Upcoming Events</a>
</li>
<li class="">
<a href="add.php">Manually Add an Event</a>
</li>
<li class="dropdown" style="text-align:left;">
<a href="#" class="dropdown-toggle" data-toggle="dropdown">
About
<b class="caret"></b>
</a>
<ul class="dropdown-menu">
<li><a href="documentation.php">Documentation</a></li>
<li><a href="design.php">Design</a></li>
<li><a href="video.php">Video</a></li>
</ul>
</li>
</ul>
</div>
</div>
</div>
</div>

<div id="middle">
    <div class="container" style="width:75%; background-color:#FFFFFF; padding:15px;">