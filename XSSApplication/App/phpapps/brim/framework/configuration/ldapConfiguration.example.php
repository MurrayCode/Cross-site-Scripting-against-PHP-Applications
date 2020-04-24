<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Joerg Zissel - July 2006
 * @package org.brim-project.framework
 * @subpackage configuration
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
//
// The LDAP server URL 
//
$connectionURL="ldap://localhost:389";
//
// The directory username to use when establishing a connection to the directory
// for LDAP search and update operations.
//
$rootBindDN="cn=manager,dc=my-domain,dc=org";
//
// The directory password (base64 encrypted) to use when establishing a connection
// to the directory for LDAP search and update operations.
//
$rootBindPw="hDhtr5Ndpo";
//
// Pattern for the distinguished name (DN) of the user's directory entry,
// with {0} marking where the actual username should be inserted.
//
$userPattern="uid={0},ou=people,dc=my-domain,dc=org";
//
// The base directory entry for performing role searches.
//
$roleBase="ou=groups,dc=my-domain,dc=org";
//
// The name of the attribute that contains role names in the directory entries
// found by a role search.
//
$roleName="cn";
//
// The LDAP filter expression used for performing role searches.
// Use {0} to substitute the distinguished name (DN) of the user.
//
$roleSearch="(member={0})";
//
// The role name of the Brim administrator role.
//
$roleAdmin = 'brim-admin';
//
// The role name of the Brim user role.
//
$roleUser = 'brim-user';
?>
