<?php

/**
 * Authentication queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage sql
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName = "brim_users";
/*
 * LOGIN
 */
$queries['getUsernamePassword']=
		"SELECT loginname, password FROM ".$tableName.
		" WHERE loginname='%s'";
$queries['getPassword']=
		"SELECT password FROM ".$tableName.
		" WHERE loginname='%s'";
$queries['updateLogin']=
		"UPDATE ".$tableName." SET last_login='%s' ".
		"WHERE loginname='%s'";
?>