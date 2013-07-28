<?php

    /**
     * StubHubApi
     * event.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Event
    {
        /**
         * find_event_by_id()
         * Search StubHub for event with specified event ID.
         * @param $event_id
         * @param $options
         * @return string
         */
        public static function find_event_by_id($event_id, $options=array())
        {
            $params = array(
                "stubhubDocumentType"   =>  "event",
                "event_id"              =>  $event_id
            );

            return Client::make_request($params, $options);
        }

        /**
         * search()
         * Search for StubHub events with a description matching specified search term.
         * @param $query
         * @param array $options
         * @param bool $exact_match
         * @return string
         */
        public static function search($query, $options=array(), $exact_match=true)
        {
            $query = strtolower($query);

            // Check if searching for exact match.
            // If we are, enclose the query in quotes
            if ($exact_match){
                $query = '"' . $query . '"';
            }

            $query = urlencode($query);

            $params = array(
                "stubhubDocumentType"   =>  "event",
                "description"           =>  $query
            );

            return Client::make_request($params, $options);
        }


        /**
         * tickets()
         * Find all tickets associated with specified event ID.
         * @param $event_id
         * @param array $options
         * @return string
         */
        public static function tickets($event_id, $options=array())
        {
            $params = array(
                "stubhubDocumentType"   =>  "ticket",
                "event_id"              =>  (int)$event_id
            );

            return Client::make_request($params, $options);
        }

    }