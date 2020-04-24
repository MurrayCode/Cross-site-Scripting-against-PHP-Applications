<?php

require_once 'framework/realm/Realm.php';

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
class LdapRealm extends Realm
{

	/**
	 * Constructor. Implementation of Realm against
	 * LDAP server.
	 */
        function LdapRealm() {
        }

	/**
	 * @see framework/realm/Realm#authenticate(string,string)
	 */
        function authenticate ($userid,$passwd) {
            $res = false;
            include 'framework/configuration/ldapConfiguration.php';
            $ldapconn=ldap_connect($connectionURL);
            if ($ldapconn) {
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                $ldaprdn = str_replace ("{0}",$userid,$userPattern);

                $errorlevel = error_reporting();
                error_reporting(E_ERROR);
                $res=ldap_bind($ldapconn, $ldaprdn, $passwd);
                error_reporting($errorlevel);

                ldap_close($ldapconn);
            }

            return $res;
        }
       
        /**
         * @see framework/realm/Realm#setPassword(string,string)
         */
        function setPassword ($userid,$newPasswd) {
            include 'framework/configuration/ldapConfiguration.php';
            $ldapconn=ldap_connect($connectionURL);
            if ($ldapconn) {
                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                $ldaprdn = str_replace ("{0}",$userid,$userPattern);

                $ldapbind = ldap_bind($ldapconn,$rootBindDN,base64_decode($rootBindPw));
                $newPw["userPassword"] = '{md5}' . base64_encode(pack('H*',
                    md5($newPasswd)));
                ldap_modify($ldapconn,$ldaprdn,$newPw);

                ldap_close($ldapconn);
            }

        }
 
	/**
         * @see framework/realm/Realm#isMemberOf(string,string)
         */
        function isMemberOf ($userid,$role) {
            $res = false;
            include 'framework/configuration/ldapConfiguration.php';
            $ldapconn=ldap_connect($connectionURL);
            if ($ldapconn) {

                switch ($role) {
                    case 'admin':
                        $ldapGroup = $roleAdmin;
                        break;
                    case 'user':
                        $ldapGroup = $roleUser;
                        break;
                }

                ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                $rdn = str_replace ("{0}",$userid,$userPattern);
                $filterMember =  str_replace ("{0}",$rdn,$roleSearch);
                $filterRole = $roleName . "=" . $ldapGroup; 
                $sr = ldap_search($ldapconn, $roleBase, "(&(" . $filterRole . ")" . $filterMember . ")");
                $info = ldap_get_entries($ldapconn, $sr);
                $res = $info["count"] > 0;
                ldap_close($ldapconn);
            }
            return $res;
        }

}

?>
