<?php

/**
 * HTML utilities
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.framework
 * @subpackage util
 *
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 * @todo what ever happend to the line function??
 */
class HTMLUtils
{
	/**
	 * Empty constructor
	 */
	function HTMLUtils ()
	{
	}

	/**
	 * Draws a horizontal line with given offest, length and color
	 * (not to confuse with the &lt;hr /&gt; tag!)
	 *
	 * @param int x the x offset
	 * @param int y the y offset
	 * @param int length the length of the line
	 * @param string color the color of the line
	 */
	function horizontalLine ($x, $y, $length, $color="#000000")
	{
		$resultString = '<div ';
		$resultString .= 'style="position:absolute;';
		$resultString .= 'background-color:'.$color.'; ';
		$resultString .= 'top:'.$x.';';
		$resultString .= 'left:'.$y.';';
		$resultString .= 'height:1;';
		$resultString .= 'width:'.$length.';';
		$resultString .= 'z-index:3 ';
		$resultString .= '></div>
		';
		return $resultString;
	}

	/**
	 * Draws a vertical line with given offest, length and color (not to confuse
	 * with the &lt;hr /&gt; tag!)
	 *
	 * @param int x the x offset
	 * @param int y the y offset
	 * @param int length the length of the line
	 * @param string color the color of the line
	 */
	function verticalLine ($x, $y, $length, $color="#000000")
	{
		$resultString = '<div ';
		$resultString .= 'style="position:absolute;';
		$resultString .= 'background-color:'.$color.'; ';
		$resultString .= 'top:'.$x.';';
		$resultString .= 'left:'.$y.';';
		$resultString .= 'height:'.$length.';';
		$resultString .= 'width:1;';
		$resultString .= 'z-index:3 ';
		$resultString .= '></div>
		';
		return $resultString;
	}

	function cellOutline ($top, $left, $height, $width, $zIndex)
	{
		$resultString  = '<div ';
		$resultString .= line ('#000000', $top, $left, $height, $width, $zIndex);
		$resultString .= '"></div>';
		return $resultString;
	}
	function cellShading ($top, $left, $height, $width, $zIndex)
	{
		$resultString  = '<div ';
		$resultString .= line ('#bbbbbb', $top, $left, $height, $width, $zIndex);
		$resultString .= '"></div>';
		return $resultString;
	}

	function tableCell ($bgColor, $top, $left, $height, $width, $zIndex)
	{
		$resultString = line ($bgColor, $top, $left, $height, $width, $zIndex);
		$resultString .= "clip:rect(auto auto ".$height.".px auto)";
		return $resultString;
	}
	function cell ($bgColor, $top, $left, $height, $width, $name)
	{
		$resultString  = '<div ';
		$resultString .= tableCell ($bgColor, $top, $left, $height, $width, 5);
		$resultString .= '">';
		$resultString .= '<table border="0" cellpadding="5" cellspacing="0" align="center" valign="center">';
		$resultString .= '<tr><td height="'.$height.'"><span style="font-size:11pt">'.$name.'</span></td></tr>';
		$resultString .= '</table>';
		$resultString .= '</div>';
		$resultString .= cellOutline ($top-1, $left-1, $height+2, $width+2, 4);
		$resultString .= cellShading ($top+3, $left+3, $height+2, $width+2, 1);
		return $resultString;
	}

}
?>