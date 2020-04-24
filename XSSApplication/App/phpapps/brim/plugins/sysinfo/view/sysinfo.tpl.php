<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.sysinfo
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
?>
<style type="text/css">
table.sysinfo
{

		border-collapse: collapse;
	    text-align: center;
	    margin-left: auto;
	    margin-right: auto;
	    text-align: left;
	    border: 1px solid #000000;
	    width: 600px;
}
tr.sysinfo
{
	    border: 1px solid #000000;
}
tr.sysinfoheader
{
	    background-color:#9999cc;
}
td.sysinfoheader
{
	    border: 1px solid #000000;
	    font-weight: bold; color: #000000;
}
td.sysinfoname
{
	    border: 1px solid #000000;
	    background-color:#ccccff;
	    font-weight: bold; color: #000000;
	    vertical-align: baseline;
}
td.sysinfovalue
{
	    background-color:#cccccc;
	    vertical-align: baseline;
	    width: 100%;
}
</style>
<?php
	include 'framework/configuration/databaseConfiguration.php';

	echo ('<h2>PHP version '.phpversion().'</h2>');
	if (strtolower($engine) == 'mysql')
	{
		echo ('<h3><a href="SysinfoController.php?action=databaseDump"
			>database dump</a> - Experimental!!</h3>');
	}
	foreach ($renderObjects as $informationBlock)
	{
		echo ('<h3>'.$informationBlock['name'].'</h3>');
		echo ('<table class="sysinfo">');
		echo ('<tr class="sysinfoheader">
				<td class="sysinfoheader">Directive</td>
				<td class="sysinfoheader">Value</td>
			</tr>
		');
		foreach ($informationBlock['contents'] as $information)
		{
			echo ('<tr class="sysinfo">
				<td class="sysinfoname">');
			echo $information['name'];
			echo ('</td>
				<td class="sysinfovalue">');
			echo $information['value'];
			echo ('</td>
				</tr>');
		}
		echo ('</table>');
	}
?>