<?php

/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

echo $applicationHelp;
ksort ($helpItems);

//
// Build up clickable listitems with the items names
//
echo ('<a name="plugins" />');
echo ('<h1>'.$dictionary['plugins'].'</h1>');
echo ('<ul>');
foreach ($helpItems as $item=>$help)
{
	echo ('<li>');
	echo ('<a href="#'.$item.'">'.$item.'</a>');
	//echo ('<a href="#'.$item.'">'.$dictionary[$item].'</a>');
	echo ('</li>');
}
echo ('</ul>');


//
// Display the actual help content per item
//
foreach ($helpItems as $item=>$help)
{
		echo ('<hr class="help">');
		echo ('<a name="'.$item.'" />');
		echo ('<h2>'.$item.'</h2>');
		echo $help;
}
?>