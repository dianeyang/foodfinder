<?php

    /***********************************************************************
     * config.php
     *
     * Computer Science 50
     * 
     *
     * Configures pages.
     **********************************************************************/

    // display errors, warnings, and notices
    ini_set("display_errors", true);
    error_reporting(E_ALL);

    // requirements
    require("constants.php");
    require("functions.php");

    // enable sessions
    //session_start();
    
    // detect mobile device
	require_once '/nfs/home/groups/cs50-foodfinder/web/mobile/Mobile_Detect.php';
	$detect = new Mobile_Detect();
	
	// redirect to mobile
	if ($detect->isMobile()) 
	{
        header( 'Location: http://m.harvardfoodfinder.com/');
	}
    
    // redirect to www.harvardfoodfinder.com
    elseif (preg_match('/^(http:\/\/)?(www.)?hcs.harvard.edu\/cs50-foodfinder\//', geturl($_SERVER)))
    {
        $extension = preg_replace('/^(http:\/\/)?(www.)?hcs.harvard.edu\/cs50-foodfinder\//','', geturl($_SERVER));
        header( 'Location: http://www.harvardfoodfinder.com/' . $extension);
    }
    
    elseif (preg_match('/^(http:\/\/)?harvardfoodfinder.com\//', geturl($_SERVER)))
    {
        $extension = preg_replace('/^(http:\/\/)?harvardfoodfinder.com\//','', geturl($_SERVER));
        header( 'Location: http://www.harvardfoodfinder.com/' . $extension);
    }

?>