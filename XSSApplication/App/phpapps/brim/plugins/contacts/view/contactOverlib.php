<?php

require_once ("framework/util/StringUtils.php");
require_once ("framework/view/TreeDelegate.php");

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2004
 * @package org.brim-project.plugins.contacts
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
function overlibPopup ($item, $dictionary)
{
	$popUp  = '<table cellspacing=2>';
	if (isset ($item->alias) && ($item->alias != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['alias'].":</b></td>";
		$popUp .= "<td>".$item->alias."</td>";
		$popUp .= "</tr>";
	}
	if (isset ($item->email1) && ($item->email1 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['email_home']."1:</b></td>";
		$popUp .= '<td><a href="mailto:'.$item->email1.'">'.$item->email1.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->email2) && ($item->email2 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['email_work'].":</b></td>";
		$popUp .= '<td><a href="mailto:'.$item->email2.'">'.$item->email2.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->email3) && ($item->email3 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['email_other'].":</b></td>";
		$popUp .= '<td><a href="mailto:'.$item->email3.'">'.$item->email3.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->webaddress1) && ($item->webaddress1 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['webaddress_homepage'].":</b></td>";
		$popUp .= '<td><a href="'.$item->webaddress1.'">'.$item->webaddress1.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->webaddress2) && ($item->webaddress2 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['webaddress_work'].":</b></td>";
		$popUp .= '<td><a href="'.$item->webaddress2.'">'.$item->webaddress2.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->webaddress3) && ($item->webaddress3 != ''))
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['webaddress_home'].":</b></td>";
		$popUp .= '<td><a href="'.$item->webaddress3.'">'.$item->webaddress3.'</a></td>';
		$popUp .= "</tr>";
	}
	if (isset ($item->tel_home) && $item->tel_home != '')
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['tel_home'].":</b></td>";
		$popUp .= "<td>".$item->tel_home."</td>";
		$popUp .= "</tr>";
	}
	if (isset ($item->tel_work) && $item->tel_work != '')
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['tel_work'].":</b></td>";
		$popUp .= "<td>".$item->tel_work."</td>";
		$popUp .= "</tr>";
	}
	if (isset ($item->mobile) && $item->mobile != '')
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['mobile'].":</b></td>";
		$popUp .= "<td>".$item->mobile."</td>";
		$popUp .= "</tr>";
	}
	if (isset ($item->faximile) && $item->faximile != '')
	{
		$popUp .= "<tr>";
		$popUp .= "<td><b>".$dictionary['faximile'].":</b></td>";
		$popUp .= "<td>".$item->faximile."</td>";
		$popUp .= "</tr>";
	}
	if (isset ($item->description) && $item->description != '')
	{
		$popUp .= "<tr>";
		$popUp .= '<td colspan="2">'.$item->description."</td>";
		$popUp .= "</tr>";
	}
	$popUp .= "</table>";
	return $popUp;
}

?>