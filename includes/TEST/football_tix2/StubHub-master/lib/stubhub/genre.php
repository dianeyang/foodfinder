<?php
    /**
     * StubHubApi
     * genre.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Genre
    {
        /**
         * search()
         * Search StubHub for genres matching the specified query string.
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
                "stubhubDocumentType"   =>  "genre",
                "description"           =>  $query
            );

            return Client::make_request($params, $options);
        }
    }