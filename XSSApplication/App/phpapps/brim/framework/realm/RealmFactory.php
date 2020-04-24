<?php

require_once ('framework/realm/LdapRealm.php');
require_once ('framework/realm/DatabaseRealm.php');

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
class RealmFactory
{
	/**
	 * Constructor. Constructs a factory for a Realm implementation.
	 */
        function RealmFactory() {
        }

	/**
	 * Creates an instance of Realm depending on the configuration
	 * in framework/configuration/realmConfiguration.php
	 *
	 * @return Realm the new Realm or null if wrong configured
	 */
        function createRealm () {
            include ('framework/configuration/realmConfiguration.php');
            switch ($realm){
	        case 'ldap':
                    return new LdapRealm;
		    break;	
	        case 'database':
                    return new DatabaseRealm;
		    break;	
            }
            return null;
        }

}

?>
