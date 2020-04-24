<?php

/**
 * Holder folder some widgets
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - January 2006
 * @package org.brim-project.framework
 * @subpackage view
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class Widget
{
	/**
	 * Shows a bar (width = 100) with green and red colours indicating a status.
	 */
	function percentCompletedBar ($total, $completed,
		$completedImage="framework/view/pics/completed.gif",
		$uncompletedImage="framework/view/pics/uncompleted.gif")
	{
		$completedPercentage = floor(($completed/$total)*100);
		$result  = '<img src="'.$completedImage.'" alt="Completed" ';
		$result .= 'height="16" ';
		$result .= 'width="'.$completedPercentage.'">';
		$result .= '<img src="'.$uncompletedImage.'" alt="Uncompleted" ';
		$result .= 'height="16" ';
		$result .= 'width="'.(100-$completedPercentage).'">&nbsp;';
		$result .= $completedPercentage.'%';
		$result .= '&nbsp;<img src="framework/view/pics/tree/shaded_minus_2.gif">&nbsp;';
		$result .= '<img src="framework/view/pics/tree/shaded_plus_2.gif">&nbsp;';
		return $result;
	}
	
	/**
	 * Shows a bar (width = 100) with green and red colours indicating a status.
	 * Additionally there is a plus and minus sign next to the bar IF
	 * callback routines are provided
	 * 
	 * The expected input is an array with the following items:
	 * 
	 * - total (integer: necessary) 
	 * - completed (integer: necessary)
	 * - completedImage (string: optional. Defaults to "framework/view/pics/completed.gif")
	 * - uncompletedImage (string: optional. Defaults to "framework/view/pics/uncompleted.gif")
	 * - increaseCallback (string: optional. If not provided, no plus/minus sign will be shown)
	 * - decreaseCallback (string: optional. If not provided, no plus/minus sign will be shown)
	 * - increaseImage (string: optional. Defaults to "framework/view/pics/tree/shaded_plus_2.gif")
	 * - decreaseImage (string: optional. Defaults to "framework/view/pics/tree/shaded_minus_2.gif")
	 * - completedDivId (string: optional. A div id that will be added around the result if provided)
	 * 
	 * The percentage part of total will be calculated
	 * Note that if the increaseCallback is provided, the decreaseCallback MUST be probided as well
	 */
	function percentBar ($inputArray)
	{
		if (!isset ($inputArray ['completed']) || !isset ($inputArray ['total']))
		{
			return;
		}
		$completedPercentage = floor(($inputArray ['completed']/$inputArray['total'])*100);
		if (isset ($inputArray ['completedImage']))
		{	
			$completedImage= $input ['completedImage'];
		}
		else 
		{
			$completedImage = "framework/view/pics/completed.gif";
		}
		if (isset ($inputArray ['uncompletedImage']))
		{	
			$uncompletedImage= $input ['uncompletedImage'];
		}
		else 
		{
			$uncompletedImage = "framework/view/pics/uncompleted.gif";
		}
		if (isset ($inputArray ['increaseCallback']) && isset ($inputArray ['decreaseCallback']))
		{
			$plusMinus = true;
			$increaseCallback = $inputArray ['increaseCallback'];
			$decreaseCallback = $inputArray ['decreaseCallback'];
			if (isset ($inputArray ['increaseImage']))
			{
				$increaseImage = $inputArray ['increaseImage'];
			}
			else
			{
				$increaseImage = 'framework/view/pics/tree/shaded_plus_2.gif';
			}
			if (isset ($inputArray ['decreaseImage']))
			{
				$decreaseImage = $inputArray ['decreaseImage'];
			}
			else
			{
				$decreaseImage = 'framework/view/pics/tree/shaded_minus_2.gif';
			}
		}
		else 
		{
			$plusMinus = false;
		}
		$result = '';
		if (isset ($inputArray ['completedDivId']))
		{
			$result .= '<div id="'.$inputArray ['completedDivId'].'">';
		}
		$result .= '<span style="white-space: nowrap;">';
		$result .= '<img src="'.$completedImage.'" alt="Completed" ';
		$result .= 'height="16" ';
		$result .= 'width="'.$completedPercentage.'">';
		$result .= '<img src="'.$uncompletedImage.'" alt="Uncompleted" ';
		$result .= 'height="16" ';
		$result .= 'width="'.(100-$completedPercentage).'">&nbsp;';
		if ($completedPercentage == 0)
		{
			$result .= '&nbsp;';
		}
		$result .= $completedPercentage.'%';
		if ($plusMinus)
		{
			$result .= '&nbsp;';
			$result .= '<a href="'.$decreaseCallback.'">';
			$result .= '<img border="0" src="'.$decreaseImage.'"></a>&nbsp;';
			$result .= '<a href="'.$increaseCallback.'">';
			$result .= '<img border="0" src="'.$increaseImage.'"></a>';
		}
		$result .= '&nbsp;</span>';
		if (isset ($inputArray ['completedDivId']))
		{
			$result .= '</div>';
		}
		return $result;
	}
}
?>
