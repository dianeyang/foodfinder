<?php

// courtesy of http://www.webmaster-source.com/2013/01/09/post-to-twitter-from-a-php-script-2013-edition/
// ... and http://davidwalsh.name/bitly-php

require_once dirname(__FILE__) . "/../includes/config.php";
require_once dirname(__FILE__) . "/../includes/functions.php";
	
require dirname(__FILE__)  . '/./tmhOAuth-master/tmhOAuth.php';
require dirname(__FILE__) . '/./tmhOAuth-master/tmhUtilities.php';

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
        $event_hour = $event["hour"] + 12;  
    else
        $event_hour = $event["hour"];
    if (($event_hour - $date["hours"]) == 2)
    {
        $event["title"] = preg_replace("/\[.*?\]/", "", $event["title"]);
        $text = "Free food at " . $event["hour"] . ":" . $event["minute"] . " " . $event["ampm"] . ": " . $event["title"];
        
        // make url
        $url = 'http://www.hcs.harvard.edu/cs50-foodfinder/displayemail.php?id=' . $event['id'];
        $short = make_bitly_url($url);
        
        echo $short . "\n";
        
        
        // construct the text of the post. if length is >140, shorten it
        if (strlen($text . $short) > 140)
            $text = substr($text, 0, 136 - strlen($short)) . "... " . $short;
        
        else
            // append to text 
            $text = $text . " " . $short;
        
        $response = $tmhOAuth->request('POST', $tmhOAuth->url('1.1/statuses/update'), array('status' => $text));
        
        echo $response . "\n";
        
        if ($response != 200) 
        {
            //Do something if the request was unsuccessful
            echo 'There was an error posting the message.';
        }
    
    }
}


function make_bitly_url($url,$login = 'foodfinder',$appkey = 'R_18ace426c49b4eed1fe786a9963e58f6',$format = 'xml',$version = '2.0.1')
{
  //create the URL
  $bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
  
  //get the url
  //could also use cURL here
  $response2 = file_get_contents($bitly);
  
  echo $format . "\n";

  if (strtolower($format) === 'xml')
  {
    $xml = simplexml_load_string($response2);
    var_dump($xml);
    return $xml->results->nodeKeyVal->shortUrl;
  }
}

?>