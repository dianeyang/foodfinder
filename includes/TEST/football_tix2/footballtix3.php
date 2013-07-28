<?php

  require_once realpath(dirname(__FILE__)) . "/StubHub-master/lib/stubhub.php";
      
  // your database's name
  define("DATABASE", "cs50-foodfinder");

  // your database's password
  define("PASSWORD", "OpUwJlywmvty");

  // your database's server
  define("SERVER", "mysql.hcs.harvard.edu");

  // your database's username
  define("USERNAME", "cs50-foodfinder");

function query(/* $sql [, ... ] */)
{
      // SQL statement
      $sql = func_get_arg(0);

      // parameters, if any
      $parameters = array_slice(func_get_args(), 1);

      // try to connect to database
      static $handle;
      if (!isset($handle))
      {
          try
          {
              // connect to database
              $handle = new PDO("mysql:dbname=" . DATABASE . ";host=" . SERVER, USERNAME, PASSWORD);

              // ensure that PDO::prepare returns false when passed invalid SQL
              $handle->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
          }
          catch (Exception $e)
          {
              // trigger (big, orange) error
              trigger_error($e->getMessage(), E_USER_ERROR);
              exit;
          }
      }

      // prepare SQL statement
      $statement = $handle->prepare($sql);
      if ($statement === false)
      {
        // trigger (big, orange) error
        //trigger_error($handle->errorInfo()[2], E_USER_ERROR);
        exit;
      }

      // execute SQL statement
      $results = $statement->execute($parameters);

      // return result set's rows, if any
      if ($results !== false)
        return $statement->fetchAll(PDO::FETCH_ASSOC);
      else
        return false;
    }




  // MAIN PROGRAM

  // get total # of events
  $events = Event::search("Redskins");
  $ev_iters = $events->response->numFound;
  $ev_last_iter = $ev_iters % 30;

  // go through each event
  for($i = 0; $i < $ev_iters; $i+= 30)
  {
  	// get events in groups of 30
    if($i < ($ev_iters - 29))
    {
      $options['start'] = $i;
      $options['row'] = 30;
      $events = Event::search("Redskins", $options);
    }
    else
    {
      $options['start'] = $i;
      $options['row'] = $ev_last_iter;
      $events = Event::search("Redskins", $options);
	}

    // iterate through each event
	$eventts = $events->response->docs;
	foreach($eventts as $event)
	{
	  // ignore parking passes
	  if(strpos($event->description,'PARKING') !== FALSE)
	    continue;
	  else
	  {
	  	// get total # of tickets
      $id = $event->event_id;
	    $tickets = Event::tickets($id);
	    $tix_iters = $tickets->response->numFound;
	    $tix_last_iter = $tix_iters % 30;

        // get tickets 30 at a time
        for($j = 0; $j < $tix_iters; $j += 30)
        {
          if($j < $tix_iters - 29)
          {
          	$options['start'] = $j;
          	$options['row'] = 30;
         	  $tickets = Event::tickets($id, $options);
          }
          else
          {
          	$options['start'] = $j;
          	$options['row'] = $tix_last_iter;
         	  $tickets = Event::tickets($id, $options);
          }
          
          $i = 0;
          // iterate through each ticket
          foreach($tickets->response->docs as $tix)
	        {
            print($i);
            $i++;
            query("INSERT INTO temp_tix (event_id, section, price) VALUES (?, ?, ?)", $id, 
              $tix->section, $tix->curr_price);
	        }

        }  
	  }
	}
  }

?>