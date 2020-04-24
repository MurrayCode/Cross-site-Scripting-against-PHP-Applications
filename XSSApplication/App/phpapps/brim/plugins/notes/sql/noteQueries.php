<?php
/**
 * Note queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.notes
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName="brim_notes";
include ("framework/sql/itemQueries.php");

/*
 * Note specific
 */
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, category, when_created, position)".
		" VALUES ('%s', %d, %d, '%s', '%s', '%s', '%s', '%s', '%s')";
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', ".
		"name='%s', ".
		"visibility='%s', ".
		"description='%s', ".
		"parent_id=%d, ".
		"is_deleted=%d, ".
		"position='%s' ".
		"WHERE item_id=%d";
$queries['getNotePositions']=
		"SELECT item_id, position FROM ".$tableName;
?>
