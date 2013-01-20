<?php

// courtesy of http://www.webmaster-source.com/2013/01/09/post-to-twitter-from-a-php-script-2013-edition/

require_once dirname(__FILE__) . "/../includes/config.php";
require_once dirname(__FILE__) . "/../includes/functions.php";
	
include './tmhOAuth-master/tmhOAuth.php';
include './tmhOAuth-master/tmhUtilities.php';

$date = getdate();

$events = query("SELECT * FROM food_info WHERE day = ? AND month = ? ORDER BY minute", $date["mday"], $date["mon"]);

$tmhOAuth = new tmhOAuth(array(
  'consumer_key' => 'sfGOj3d6cirqV6mrZDsBg',
  'consumer_secret' => 'jmgMP5JtgX63aL9eQzfP87xxLPYDNNU0jyp9Ctass',
  'user_token' => '925219884-wM7hjehIHBTzgOIDUZh35bXFrqOGCX6XRTjp4euz',
  'user_secret' => 'QRQvxYVNE63TIVddx9Lai4WbYEKg6rZmsFfX9tvKfY',
));

foreach ($events as $event)
{
    
    if ($event["ampm"] === "pm")
    {
        $event_hour = $event["hour"] + 12;
    }
    
    else
    {
        $event_hour = $event["hour"];
    }
    
    if ($event_hour - $date["hours"] <= 2)
    {
    
        $event["title"] = preg_replace("/\[.*?\]/", "", $event["title"]);
        
        $text = "Free food at " . $event["hour"] . ":" . $event["minute"] . " " . $event["ampm"] . ": " . $event["title"];
        
        if (strlen($text) > 140)
        {
            $text = substr($text, 0, 137) . "...";
        }
        
        $response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array(
          'status' => $text
        ));
        
        echo $response . "\n";
        
        if ($response != 200) {
            //Do something if the request was unsuccessful
            echo 'There was an error posting the message.';
        }
    
    }
}
?>