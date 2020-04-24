<?php
/**
 * Todo/Tasks queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName = "brim_tasks";
include ("framework/sql/itemQueries.php");

/*
 * Task specific
 */
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, when_created, ".
		" priority, start_date, end_date, status, percent_complete, is_finished)".
		" VALUES (".
		"'%s', %d, %d, '%s', '%s', '%s', ".
		"'%s', %d, '%s', '%s', '%s', %d, %d)";
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', name='%s', visibility='%s', ".
		"is_deleted=%d, ".
		"description='%s', priority=%d, ".
		"status='%s', start_date='%s', ".
		"end_date='%s', percent_complete=%d, ".
		"parent_id=%d ".
		"WHERE item_id=%d";
$queries['getCompletedItems']=
		"SELECT * from " . $tableName . " WHERE owner='%s' ".
		"AND percent_complete=100 ".
		"ORDER BY is_parent DESC, name ASC";
?>
