<?php
    /**
     * StubHubApi
     * ticket.php
     * Author: Tom Seldon (http://tomseldon.co.uk)
     * URL: http://www.github.com/TomSeldon/StubHubApi
     * Created: 24/05/2013
     * Changed: 24/05/2013
     * Version: 0.1
     * License: GPL v3 (http://www.gnu.org/licenses/gpl.txt)
     */

    class Ticket
    {
        /**
         * find_ticket_by_id()
         * Get ticket details by ticket ID.
         * @param $ticket_id
         * @param array $options
         * @return string
         */
        public static function find_ticket_by_id($ticket_id, $options=array())
        {
            $params = array(
                "stubhubDocumentType"   =>  "ticket",
                "id"                    =>  $ticket_id
            );

            return Client::make_request($params, $options);
        }
    }