<?php

/**
 * ItemParticipation queries
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - May 2006
 * @package org.brim-project.framework
 * @subpackage sql
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
$tableName = "brim_item_participation";
$queries = array ();

$queries ['getItemParticipation']=
	"SELECT * FROM ".$tableName." WHERE ".
	"item_id=%d AND ".
	"owner='%s' AND ".
	"participator='%s' AND ".
	"plugin='%s'";
$queries ['deleteItemParticipation']=
	"DELETE FROM ".$tableName." WHERE ".
	"item_id=%d AND ".
	"owner='%s' AND ".
	"participator='%s' AND ".
	"plugin='%s'";
$queries ['addItemParticipation']=
	"INSERT INTO ".$tableName." (item_id, owner, participator, plugin) VALUES (".
	"%d, '%s', '%s', '%s')";
$queries ['getParticipators']=
	"SELECT * FROM ".$tableName." WHERE ".
	"item_id=%d AND ".
	"plugin='%s' AND ".
	"activation_code = ''";
$queries ['getTempParticipators']=
	"SELECT * FROM ".$tableName." WHERE ".
	"item_id=%d AND ".
	"plugin='%s' AND ".
	"activation_code != ''";
$queries ['getItemOwner']=
	"SELECT owner FROM ".$tableName." WHERE ".
	"item_id=%d";
?>
