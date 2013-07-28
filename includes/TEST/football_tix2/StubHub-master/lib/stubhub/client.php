<?php

    /**
     * StubHubApi
     * client.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Client
    {
        // Define the end point for the StubHub API.
        const endpoint = "http://publicfeed.stubhub.com/listingCatalog/select/?q=";

        // Set the default options for StubHub API. These can be overridden for each request.
        private static $default_options = array(
            'start' =>  0,
            'rows'  =>  30,
            'wt'    =>  'json',
        );

        /**
         * make_request()
         * Send request to StubHub and return result.
         * @param null $params
         * @param null $options
         * @return string
         */
        public static function make_request($params=array(), $options=array())
        {
            $query_url = self::convert_query_to_url($params, $options);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $query_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //curl_setopt($curl, CURLOPT_REFERER, self::referrer);
            $body = curl_exec($curl);

            curl_close($curl);

            return json_decode($body);
        }

        /**
         * convert_query_to_string()
         * Parses parameters and options into SOLR formatted query string
         * @param array $params
         * @param array $options
         * @return string
         */
        private static function convert_query_to_url($params=array(), $options=array())
        {
            // Get options
            $options = self::get_options($options);

            // Initiate URL string
            $url = "";

            // Build param vars
            foreach ($params as $k => $v){
                $url .= "%2B+{$k}%3A{$v}%0D%0A";
            }

            // Build option vars
            foreach ($options as $k => $v){
                $url .= "&{$k}={$v}";
            }

            return self::endpoint . $url;
        }

        /**
         * get_options()
         * Merges user options with default options and returns the updated options.
         * @param $options
         * @return array
         */
        private static function get_options($options)
        {
            $options = array_merge(self::$default_options, $options);

            return $options;
        }
    }