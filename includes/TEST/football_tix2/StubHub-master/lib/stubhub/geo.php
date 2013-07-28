<?php
    /**
     * StubHubApi
     * geo.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Geo
    {
        /**
         * find_by_eo_id()
         * Search StubHub for location with matching geo ID
         * @param $geo_id
         * @param array $options
         * @return string
         */
        public static function find_by_geo_id($geo_id, $options=array())
        {
            $params = array(
                "stubhubDocumentType"   =>  "geo",
                "geoId"                 =>  $geo_id
            );

            return Client::make_request($params, $options);
        }

        /**
         * find_by_locale()
         * Search for locale by description. By default will look for partial matches.
         * @param $locale
         * @param array $options
         * @param bool $exact_match
         * @return string
         */
        public static function find_by_locale($locale, $options=array(), $exact_match=false)
        {
            if($exact_match){
                $locale = '"' . $locale . '"';
            }

            $locale = urlencode($locale);

            $params = array(
                "stubhubDocumentType"   =>  "geo",
                "localeDescription"     =>  $locale
            );

            return Client::make_request($params, $options);
        }

        /**
         * search()
         * Search for geo by description.
         * @param $query
         * @param array $options
         * @param bool $exact_match
         * @return string
         */
        public static function search($query, $options=array(), $exact_match=true)
        {
            if ($exact_match){
                $query = '"' . $query . '"';
            }

            $query = urlencode($query);

            $params = array(
                "stubhubDocumentType"   =>  "geo",
                "description"           =>  $query
            );

            return Client::make_request($params, $options);
        }

        /**
         * events()
         * Find all events for a specific Geo ID.
         * @param $geoID
         * @param array $options
         * @return string
         */
        public static function events($geoID, $options=array())
        {
            $params = array(
                "stubhubDocumentType"   =>  "event",
                "ancestorGeoIds"        =>  (int)$geoID
            );

            return Client::make_request($params, $options);
        }
    }