<?php

/**
 * Preferences queries
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
$tableName = "brim_user_preferences";
include ("framework/sql/itemQueries.php");

$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, name, description, when_created, value) ".
		" VALUES (".
		"'%s', %d, '%s', '%s', '%s', '%s')";
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', ".
		"name='%s', ".
		"description='%s', ".
		"parent_id=%d, ".
		"value='%s' ".
		"WHERE item_id=%d";
$queries['getPreferenceId']=
		"SELECT item_id FROM ".$tableName." ".
		"WHERE owner='%s' AND name='%s'";
$queries['getPreference']=
		"SELECT value FROM ".$tableName." ".
		"WHERE owner='%s' AND name='%s'";
$queries['deleteAllPreferences']=
		"DELETE FROM ".$tableName." WHERE owner='%s'";
?>