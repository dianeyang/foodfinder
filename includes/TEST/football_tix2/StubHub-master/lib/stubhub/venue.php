<?php
    /**
     * StubHubApi
     * venue.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Venue
    {
        /**
         * find_venue_by_id()
         * Get venue information by venue ID.
         * @param $venue_id
         * @param array $options
         * @return string
         */
        public static function find_venue_by_id($venue_id, $options=array())
        {
            $params = array(
                "stubhubDocumentType"    =>  "venue",
                "venue_id"              =>  (int)$venue_id
            );

            return Client::make_request($params, $options);
        }

        /**
         * search()
         * Search StubHub for venues matching the specified query string.
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
                "stubhubDocumentType"   =>  "venue",
                "description"           =>  $query
            );

            return Client::make_request($params, $options);
        }
    }