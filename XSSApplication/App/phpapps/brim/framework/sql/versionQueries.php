<?php

/**
 * Version related queries
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
$tableName = "brim_admin";
$queries = array ();
$queries['getCurrentVersion']=
	"SELECT * FROM ".$tableName." ".
	"WHERE name='brim_version'";
$queries['install']=
	"INSERT INTO ".$tableName." ".
	"VALUES ('brim_version', '%s')";
$queries['upgrade']=
	"UPDATE ".$tableName." ".
	"SET value='%s' WHERE name='brim_version'";
?>