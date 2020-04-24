<?php
/**
 * Password queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage sql
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName="brim_passwords";
include ("framework/sql/itemQueries.php");

/*
 * Password specific
 */
$queries['addItem']=
		"INSERT INTO ".$tableName.
		" (owner, parent_id, is_parent, name, description, visibility, category, when_created, login, password, url) ".
		" VALUES (".
		"'%s', %d, %d, '%s', '%s', ".
		"'%s', '%s', '%s', '%s', '%s', '%s')";
$queries['modifyItem']=
		"UPDATE ".$tableName." SET ".
		"when_modified='%s', ".
		"name='%s', ".
		"visibility='%s', ".
		"description='%s', ".
		"parent_id=%d, ".
		"is_deleted=%d, ".
		"login='%s', ".
		"password='%s', ".
		"url='%s' ".
		"WHERE item_id=%d";
?>