<?php

require_once 'includes/ZendFramework-1.12.1/library/Zend/Loader.php';
 
Zend_Loader::loadClass('Gdata');
Zend_Loader::loadClass('Gdata_AuthSub');
Zend_Loader::loadClass('Gdata_ClientLogin');
Zend_Loader::loadClass('Gdata_Calendar');
 
$user = 'harvardfoodfinder@gmail.com';
$pass = 'canadayf34';
$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
 
try
{
	$client = Zend_Gdata_ClientLogin::getHttpClient($user,$pass,$service);			
}
catch(Exception $e)
{
	// prevent Google username and password from being displayed
	// if a problem occurs
	echo "Could not connect to calendar.";
	die();
}

// parameters
$title = "French revolution celebration";
$where = "at my place";
$description = "only mineral water";
 
$start_date = "2013-01-21 07:00:00";
$end_date = "2013-01-21 07:00:00";
 
$calendar_user = 'harvard.food.finder%40gmail.com';
$tzOffset = '-07'; // timezone offset
 
// build event
$start_date = str_replace(' ', 'T', $start_date);
$end_date = str_replace(' ', 'T', $end_date);
 
$gdataCal = new Zend_Gdata_Calendar($client);
$newEvent = $gdataCal->newEventEntry();
 
$newEvent->title = $gdataCal->newTitle($title);
$newEvent->where = array($gdataCal->newWhere($where));
$newEvent->content = $gdataCal->newContent($description);
 
$when = $gdataCal->newWhen();
$when->startTime = "{$start_date}.000{$tzOffset}:00";
$when->endTime = "{$end_date}.000{$tzOffset}:00";
$newEvent->when = array($when);
 
// insert event
$createdEvent = $gdataCal->insertEvent($newEvent, "http://www.google.com/calendar/feeds/$calendar_user/private/full");
 
// event id
$event_id = $createdEvent->id->text;

?>