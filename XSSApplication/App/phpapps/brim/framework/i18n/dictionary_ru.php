<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary = array ();
}
$dictionary['about']='��������';
$dictionary['about_page']=' <h2>��������</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> ��� ���������� �������� Barry Mauta (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> ���� - ���������� OpenSoruce ���������� ������� ���������� ������� ������ (�.�. ����� ������, ����������, ������� � �.�.  � ����� ���������) </p> <p> ��� ��������� ('.$dictionary['programname'].') ����������� ��� ��������� GNU General Public License. ������� <a href="gpl.html">�����</a>, ��� �� ������� ���� ����� ��������.  �������� ��������� ������� �� ������ ���� �� ������: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']='��������';
$dictionary['add']='��������';
$dictionary['addFolder']='�������� �����';
$dictionary['addNode']='�������� �������';
$dictionary['adduser']='�������� ������������';
$dictionary['admin']='�������������';
$dictionary['adminConfig']='������������';
$dictionary['admin_email']='Email ��������������';
$dictionary['allow_account_creation']='��������� ������������ �����������';

?>
