<!DOCTYPE html>

<html style="height:100%;">

    <head>
		
		<!-- include css styling-->
        <link href="./html/css/bootstrap.css" rel="stylesheet"/>
        <link href="./html/css/bootstrap-responsive.css" rel="stylesheet"/>
        <link href="./html/css/styles.css" rel="stylesheet"/>

		
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

<link rel="stylesheet" type="text/css" href="bootstrap-wysihtml5-0.0.2/libs/bootstrap-wysihtml5-0.0.2.css"></link>
<link rel="stylesheet" type="text/css" href="bootstrap-wysihtml5-0.0.2/libs/css/bootstrap.min.css"></link>
<script src="bootstrap-wysihtml5-0.0.2/libs/js/wysihtml5-0.3.0_rc2.js"></script>
<script src="bootstrap-wysihtml5-0.0.2/libs/js/jquery-1.7.2.min_rc2.js"></script>
<script src="bootstrap-wysihtml5-0.0.2/libs/js/bootstrap.min.js"></script>
<script src="bootstrap-wysihtml5-0.0.2/bootstrap-wysihtml5-0.0.2.js"></script>


    </head>

    <body style="height:100%;">

        <div class="container">

            <div id="top" style="overflow:hidden">
				<a href="/cs50-foodfinder">
					<table style="table-layout:fixed; width:100%;">
						<tr>
							<td style="text-align:left; width:400px;">
							
								<!-- page header/subheader-->
								<h1 style="line-height:30px; color:black;">Harvard Food Finder
									<br/>
									<small style="font-size:18px;">Free Food Events on Campus</small>
								</h1>
							</td>
							<td style="text-align:right;white-space:nowrap;">
								
								<!-- banner image-->
								<img alt="fork banner" src="./html/img/temp.gif"/>
								<img alt="fork banner" src="./html/img/temp.gif"/>
							</td>
						</tr>
					</table>
				</a>
            </div>

			<!-- include navbar at top of page-->
			<div class="navbar navbar-inverse">
				<div class="navbar-inner">
					<div class="container">
					
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
									<a href="./add.php">Manually Add an Event</a>
								</li>
								<li class="">
									<a href="./digest.php">Daily Digest Emails</a>
								</li>
								<li class="dropdown" style="text-align:left;">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										About (for CS50 graders)
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

