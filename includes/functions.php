<?php

    /***********************************************************************
     * functions.php
     *
     * Computer Science 50
     * 
     *
     * Helper functions.
     **********************************************************************/

    require_once("constants.php");
    require_once(dirname(__FILE__) . "/../mobile/Mobile_Detect.php");

    /**
     * Apologizes to user with message.
     */
    function apologize($message)
    {
        render("templates/apology.php", array("message" => $message));
        exit;
    }

    /**
     * Sends confirmation email to user after subscribing to Daily Digest
     */
    function confirmation($to)
    {
		require_once dirname(__FILE__) . "/../includes/config.php";
		require_once dirname(__FILE__) . "/../includes/functions.php";
		
		// set subject of email
		$date = getdate();
		$subject = 'Daily Digest: Confirm Subscription';
		
		// prepare the tags to start and end an html document
		$htmlstart = "<html>
					<head>
					<title>Daily Digest" . $date["mon"] . "/" . $date["mday"] . "/" . $date["year"] .
					"</title>
					</head>
					<body>";
					
		$htmlend = "</body>
					</html>";
		

		// set intro to email, default events, and footer
		$image = "<div><img src=\"http://hcs.harvard.edu/cs50-foodfinder/html/img/food-finder-logo.jpg\"></div>";
		$message = "<p>Hello!</p>
					<p>Thank you for subscribing to Daily Digest emails from <a href=\"http://hcs.harvard.edu/cs50-foodfinder/\">Harvard Food Finder</a>.
					This is an easy, convenient way to have free food opportunities sent directly to your inbox once a day.</p>
					<p>If you believe this email was sent in error, or if you would like to unsubscribe, please click
					<a href=\"http://hcs.harvard.edu/cs50-foodfinder/digest.php\">here</a>.</p>
					<p>Have a nice day!</p>
					<p>From,</p>
					<p>The Team at Harvard Food Finder</p>";
			
		// add to intro, message, and html start & end tags
		$message = $htmlstart. $image . $message . $htmlend;
		
		// set headers for email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: <foodfinder@lists.hcs.harvard.edu>' . "\r\n";
		
		// send email to user
		mail($to, $subject, $message, $headers);
   
    }

    /**
     * Facilitates debugging by dumping contents of variable
     * to browser.
     */
    function dump($variable)
    {
        require("templates/dump.php");
        exit;
    }

    /**
     * Logs out current user, if any.  Based on Example #1 at
     * http://us.php.net/manual/en/function.session-destroy.php.
     */
    function logout()
    {
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (!empty($_COOKIE[session_name()]))
        {
            setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
    }
    /**
     * Executes SQL statement, possibly with parameters, returning
     * an array of all rows in result set or false on (non-fatal) error.
     */
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
         //   trigger_error($handle->errorInfo()[2], E_USER_ERROR);
            exit;
        }

        // execute SQL statement
        $results = $statement->execute($parameters);

        // return result set's rows, if any
        if ($results !== false)
        {
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Lets the user know that an action has successfully been completed.
     */
    function success($message)
    {
        render("templates/success.php", array("message" => $message));
        exit;
    }

    /**
     * Redirects user to destination, which can be
     * a URL or a relative path on the local host.
     *
     * Because this function outputs an HTTP header, it
     * must be called before caller outputs any HTML.
     */
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^https?:\/\//", $destination))
        {
            header("Location: " . $destination);
        }

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (isset($_SERVER["HTTPS"])) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }

    /**
     * Renders template, passing in values.
     */
    function render($template, $values = array())
    {
        // if template exists, render it
        if (file_exists("$template"))
        {
            // extract variables into local scope
            extract($values);

            // render header
            require("templates/header_modal.php");

            // render template
            require("$template");

            // render footer
            require("templates/footer.php");
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

    function render_no_modal($template, $values = array())
    {
        // if template exists, render it
        if (file_exists("$template"))
        {
            // extract variables into local scope
            extract($values);
            
		    $detect = new Mobile_Detect();
			if ($detect->isMobile())
			{			
				// render header
				require("mobile/mtemplates/header_no_modal.php");
				
				// render template
				require("mobile/m$template");
	
				// render footer
				require("mobile/mtemplates/footer.php");
            }
            
            else
            {
				// render header
				require("templates/header_no_modal.php");
	
				// render template
				require("$template");
	
				// render footer
				require("templates/footer.php");
            }
        }

        // else err
        else
        {
            trigger_error("Invalid template: $template", E_USER_ERROR);
        }
    }

?>
