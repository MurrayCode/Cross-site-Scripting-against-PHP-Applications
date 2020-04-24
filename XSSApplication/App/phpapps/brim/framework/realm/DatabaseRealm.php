<?php

require_once ('framework/realm/Realm.php');

/**
 * This file is part of the Brim project.
 *
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Joerg Zissel - July 2006
 * @package org.brim-project.framework
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class DatabaseRealm extends Realm
{

        /**
         * The database connection
         * @var object db the database connection
         */
        var $db;

        /**
         * The queries that are used for database interactions
         * @var array queries
         */
        var $queries;

        /**
         * Constructor. Implementation of Realm against
         * database.
         */
        function DatabaseRealm() {
            $db = null;
            $queries = array ();
            include ('framework/sql/userQueries.php');
            include ('framework/util/databaseConnection.php');
            $this->db = $db;
            $this->queries = $queries;
        }

        /**
         * @see framework/realm/Realm#authenticate(string,string)
         */
        function authenticate ($userid,$passwd) {
            $res = false;

            include ('framework/sql/authQueries.php');
            include ('framework/util/databaseConnection.php');
            //
            // Query the database
            //
            $query = sprintf ($queries ['getUsernamePassword'], $userid);
            $result = $db->Execute($query);
            $dbPassword = trim ($result->fields[1]);
            //
            // Check password
            //
            if (md5($passwd) == $dbPassword)
            {
                $res = true;
            }

            return $res;
        }
        
        /**
         * @see framework/realm/Realm#setPassword(string,string)
         */
        function setPassword ($userid,$newPasswd) {
            $query = sprintf ($this->queries['setPassword'],
                    MD5($newPasswd),
                    $userid);
            $this->db->Execute ($query)
                    or die ("Error changing password: " .
                            $this->db->ErrorMsg () . " " . $query);
        }

        /**
         * @see framework/realm/Realm#isMemberOf(string,string)
         */
        function isMemberOf ($userid,$role) {
            $res = false;

            switch ($role) {
                case 'admin':
                    $res = $userid == "admin";
                    break;
                case 'user':
                    $res = true;
                    break;
            }

            return $res;
        }

}

?>
