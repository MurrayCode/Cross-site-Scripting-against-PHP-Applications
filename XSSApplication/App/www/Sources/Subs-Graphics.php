<?php
/******************************************************************************
* Subs-Graphics.php                                                           *
*******************************************************************************
* SMF: Simple Machines Forum                                                  *
* Open-Source Project Inspired by Zef Hemel (zef@zefhemel.com)                *
* =========================================================================== *
* Software Version:           SMF 1.0                                         *
* Software by:                Simple Machines (http://www.simplemachines.org) *
* Copyright 2001-2004 by:     Lewis Media (http://www.lewismedia.com)         *
* Support, News, Updates at:  http://www.simplemachines.org                   *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the provided license as published by Lewis Media.        *
*                                                                             *
* This program is distributed in the hope that it is and will be useful,      *
* but WITHOUT ANY WARRANTIES; without even any implied warranty of            *
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                        *
*                                                                             *
* See the "license.txt" file for details of the Simple Machines license.      *
* The latest version can always be found at http://www.simplemachines.org.    *
*******************************************************************************
* Gif Util copyright 2003 by Yamasoft (S/C). All rights reserved.             *
* Do not remove this portion of the header, or use these functions except     *
* from the original author. To get it, please navigate to:                    *
* http://www.yamasoft.com/php-gif.zip                                         *
******************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

/*	This whole file deals almost exclusively with handling avatars,
	specifically uploaded ones.  It uses, for gifs at least, Gif Util... for
	more information on that, please see its website, shown above.  The other
	functions are as follows:

	bool downloadAvatar(string url, int ID_MEMBER, int max_width,
			int max_height)
		- downloads file from url and stores it locally for avatar use
		  by ID_MEMBER.
		- supports GIF, JPG, PNG, BMP and WBMP formats.
		- detects if GD2 is available.
		- if GIF support isn't present in GD, handles GIFs with gif_loadFile()
		  and gif_outputAsPng().
		- uses resizeImage() to resize to max_width by max_height, if needed,
		  and saves the result to a file.
		- updates the database info for the member's avatar.
		- returns whether the download and resize was successful.

	void resizeImage(resource src_img, string destination_filename,
			int src_width, int src_height, int max_width, int max_height)
		- resizes src_img proportionally to fit within max_width and
		  max_height limits if it is too large.
		- if GD2 is present as detected in downloadAvatar(), it'll use it to
		  achieve better quality.
		- saves the new image to destination_filename.
		- saves as a PNG or JPEG depending on the avatar_download_png setting.

	void imageCopyResampleBicubic(resource dest_img, resource src_img,
			int dest_x, int dest_y, int src_x, int src_y, int dest_w,
			int dest_h, int src_w, int src_h)
		- used when imagecopyresample() is not available.

	resource gif_loadFile(string filename, int animation_index)
		- loads a gif file with the Yamasoft GIF utility class.
		- returns a new GD image.

	bool gif_outputAsPng(resource gif, string destination_filename,
			int bgColor = -1)
		- writes a gif file to disk as a png file.
		- returns whether it was successful or not.

	bool imagecreatefrombmp(string filename)
		- is set only if it doesn't already exist (for forwards compatiblity.)
		- only supports uncompressed bitmaps.
		- returns an image identifier representing the bitmap image obtained
		  from the given filename.
*/

function downloadAvatar($url, $memID, $max_width, $max_height)
{
	global $modSettings, $db_prefix, $sourcedir, $gd2;

	$destName = 'avatar_' . $memID . '.' . (!empty($modSettings['avatar_download_png']) ? 'png' : 'jpeg');

	$default_formats = array(
		'1' => 'gif',
		'2' => 'jpeg',
		'3' => 'png',
		'6' => 'bmp',
		'15' => 'wbmp'
	);

	// Check to see if GD is installed and what version.
	$testGD = get_extension_funcs('gd');

	// If GD is not installed, this function is pointless.
	if (empty($testGD))
		return false;

	// GD 2 maybe?
	$gd2 = in_array('imagecreatetruecolor', $testGD) && function_exists('imagecreatetruecolor');
	unset($testGD);

	require_once($sourcedir . '/ManageAttachments.php');
	removeAttachments('a.ID_MEMBER = ' . $memID);
	db_query("
		INSERT INTO {$db_prefix}attachments
			(ID_MEMBER, filename, size)
		VALUES ($memID, '$destName', 1)", __FILE__, __LINE__);
	$attachID = db_insert_id();

	$destName = $modSettings['attachmentUploadDir'] . '/' . $destName . '.tmp';

	$success = false;
	$sizes = url_image_size($url);

	// Gif? That might mean trouble if gif support is not available.
	if ($sizes[2] == 1 && !function_exists('imagecreatefromgif') && function_exists('imagecreatefrompng'))
	{
		// Download it to the temporary file... use the special gif library... and save as png.
		if (copy($url, $destName) && $img = @gif_loadFile($destName) && gif_outputAsPng($img, $destName))
		{
			// From here it can be resized.
			if ($src_img = imagecreatefrompng($destName))
			{
				resizeImage($src_img, $destName, imagesx($src_img), imagesy($src_img), $max_width, $max_height);
				$success = true;
			}
		}
	}
	// A known and supported format?
	elseif (isset($default_formats[$sizes[2]]) && function_exists('imagecreatefrom' . $default_formats[$sizes[2]]))
	{
		$imagecreatefrom = 'imagecreatefrom' . $default_formats[$sizes[2]];
		if ($src_img = $imagecreatefrom($url))
		{
			resizeImage($src_img, $destName, imagesx($src_img), imagesy($src_img), $max_width, $max_height);
			$success = true;
		}
	}
	// Remove the .tmp extension.
	$destName = substr($destName, 0, -4);

	if ($success)
	{
		// Remove the .tmp extension from the attachment.
		if (rename($destName . '.tmp', $destName))
		{
			// Write filesize in the database.
			db_query("
				UPDATE {$db_prefix}attachments
				SET size = " . filesize($destName) . "
				WHERE ID_ATTACH = $attachID
				LIMIT 1", __FILE__, __LINE__);
			return true;
		}
		else
			return false;
	}
	else
	{
		db_query("
			DELETE FROM {$db_prefix}attachments
			WHERE ID_ATTACH = $attachID
			LIMIT 1", __FILE__, __LINE__);

		@unlink($destName . '.tmp');
		return false;
	}
}

function resizeImage($src_img, $destName, $src_width, $src_height, $max_width, $max_height)
{
	global $gd2, $modSettings;

	// Determine whether to resize to max width or to max height (depending on the limits.)
	if (!empty($max_width) && (empty($max_height) || $src_height * $max_width / $src_width <= $max_height))
	{
		$dst_width = $max_width;
		$dst_height = floor($src_height * $max_width / $src_width);
	}
	else
	{
		$dst_width = floor($src_width * $max_height / $src_height);
		$dst_height = $max_height;
	}

	// (make a true color image, because it just looks better for resizing.)
	if ($gd2)
		$dst_img = imagecreatetruecolor($dst_width, $dst_height);
	else
		$dst_img = imagecreate($dst_width, $dst_height);

	// Resize it!
	if ($gd2)
		imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);
	else
		imageCopyResampleBicubic($dst_img, $src_img, 0, 0, 0, 0, $dst_width, $dst_height, $src_width, $src_height);

	// Save it!
	if (!empty($modSettings['avatar_download_png']))
		imagepng($dst_img, $destName);
	else
		imagejpeg($dst_img, $destName);

	// Free the memory.
	imagedestroy($src_img);
	imagedestroy($dst_img);
}

function imageCopyResampleBicubic($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h)
{
	$palsize = ImageColorsTotal($src_img);
	for ($i = 0; $i < $palsize; $i++)
	{
		$colors = ImageColorsForIndex($src_img, $i);
		ImageColorAllocate($dst_img, $colors['red'], $colors['green'], $colors['blue']);
	}

	$scaleX = ($src_w - 1) / $dst_w;
	$scaleY = ($src_h - 1) / $dst_h;

	$scaleX2 = (int) $scaleX / 2;
	$scaleY2 = (int) $scaleY / 2;

	for ($j = $src_y; $j < $dst_h; $j++)
	{
		$sY = (int) $j * $scaleY;
		$y13 = $sY + $scaleY2;

		for ($i = $src_x; $i < $dst_w; $i++)
		{
			$sX = (int) $i * $scaleX;
			$x34 = $sX + $scaleX2;

			$color1 = ImageColorsForIndex($src_img, ImageColorAt($src_img, $sX, $y13));
			$color2 = ImageColorsForIndex($src_img, ImageColorAt($src_img, $sX, $sY));
			$color3 = ImageColorsForIndex($src_img, ImageColorAt($src_img, $x34, $y13));
			$color4 = ImageColorsForIndex($src_img, ImageColorAt($src_img, $x34, $sY));

			$red = ($color1['red'] + $color2['red'] + $color3['red'] + $color4['red']) / 4;
			$green = ($color1['green'] + $color2['green'] + $color3['green'] + $color4['green']) / 4;
			$blue = ($color1['blue'] + $color2['blue'] + $color3['blue'] + $color4['blue']) / 4;

			ImageSetPixel($dst_img, $i + $dst_x - $src_x, $j + $dst_y - $src_y, ImageColorClosest($dst_img, $red, $green, $blue));
		}
	}
}

if (!function_exists('imagecreatefrombmp'))
{
	function imagecreatefrombmp($filename)
	{
		global $gd2;

		$fp = fopen($filename, 'rb');

		$errors = error_reporting(0);

		$header = unpack('vtype/Vsize/Vreserved/Voffset', fread($fp, 14));
		$info = unpack('Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyres/Vncolor/Vcolorimportant', fread($fp, 40));

		if ($header['type'] != 0x4D42)
			false;

		if ($gd2)
			$dst_img = imagecreatetruecolor($info['width'], $info['height']);
		else
			$dst_img = imagecreate($info['width'], $info['height']);

		$palette_size = $header['offset'] - 54;
		$info['ncolor'] = $palette_size / 4;

		$palette = array();

		$palettedata = fread($fp, $palette_size);
		$n = 0;
		for ($j = 0; $j < $palette_size; $j++)
		{
			$b = ord($palettedata{$j++});
			$g = ord($palettedata{$j++});
			$r = ord($palettedata{$j++});

			$palette[$n++] = imagecolorallocate($dst_img, $r, $g, $b);
		}

		$scan_line_size = ($info['bits'] * $info['width'] + 7) >> 3;
		$scan_line_align = $scan_line_size & 3 ? 4 - ($scan_line_size & 3) : 0;

		for ($y = 0, $l = $info['height'] - 1; $y < $info['height']; $y++, $l--)
		{
			fseek($fp, $header['offset'] + ($scan_line_size + $scan_line_align) * $l);
			$scan_line = fread($fp, $scan_line_size);

			if (strlen($scan_line) < $scan_line_size)
				continue;

			if ($info['bits'] == 32)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b = ord($scan_line{$j++});
					$g = ord($scan_line{$j++});
					$r = ord($scan_line{$j++});
					$j++;

					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 24)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b = ord($scan_line{$j++});
					$g = ord($scan_line{$j++});
					$r = ord($scan_line{$j++});

					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 16)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$b1 = ord($scan_line{$j++});
					$b2 = ord($scan_line{$j++});

					$word = $b2 * 256 + $b1;

					$b = (($word & 31) * 255) / 31;
					$g = ((($word >> 5) & 31) * 255) / 31;
					$r = ((($word >> 10) & 31) * 255) / 31;

					// Scale the image colors up properly.
					$color = imagecolorexact($dst_img, $r, $g, $b);
					if ($color == -1)
					{
						$color = imagecolorallocate($dst_img, $r, $g, $b);

						// Gah!  Out of colors?  Stupid GD 1... try anyhow.
						if ($color == -1)
							$color = imagecolorclosest($dst_img, $r, $g, $b);
					}

					imagesetpixel($dst_img, $x, $y, $color);
				}
			}
			elseif ($info['bits'] == 8)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
					imagesetpixel($dst_img, $x, $y, $palette[ord($scan_line{$j++})]);
			}
			elseif ($info['bits'] == 4)
			{
				$x = 0;
				for ($j = 0; $j < $scan_line_size; $x++)
				{
					$byte = ord($scan_line{$j++});

					imagesetpixel($dst_img, $x, $y, $palette[(int) ($byte / 16)]);
					if (++$x < $info['width'])
						imagesetpixel($dst_img, $x, $y, $palette[$byte & 15]);
				}
			}
			else
			{
				// Sorry, I'm just not going to do monochrome :P.
			}
		}

		fclose($fp);

		error_reporting($errors);

		return $dst_img;
	}
}

function gif_loadFile($lpszFileName, $iIndex = 0)
{
	$gif = new CGIF();

	if (!$gif->loadFile($lpszFileName, $iIndex))
		return false;

	return $gif;
}

function gif_outputAsPng($gif, $lpszFileName, $bgColor = -1)
{
	if (!isset($gif) || @get_class($gif) != 'cgif' || !$gif->loaded() || $lpszFileName == '')
		return false;

	$fd = $gif->getPng($bgColor);
	if (strlen($fd) <= 0)
		return false;

	if (!($fh = @fopen($lpszFileName, 'wb')))
		return false;

	@fwrite($fh, $fd, strlen($fd));
	@fflush($fh);
	@fclose($fh);

	return true;
}

class CGIFLZW
{
	var $MAX_LZW_BITS;
	var $Fresh, $CodeSize, $SetCodeSize, $MaxCode, $MaxCodeSize, $FirstCode, $OldCode;
	var $ClearCode, $EndCode, $Next, $Vals, $Stack, $sp, $Buf, $CurBit, $LastBit, $Done, $LastByte;

	// CONSTRUCTOR
	function CGIFLZW()
	{
		$this->MAX_LZW_BITS = 12;
		unset($this->Next);
		unset($this->Vals);
		unset($this->Stack);
		unset($this->Buf);

		$this->Next  = range(0, (1 << $this->MAX_LZW_BITS)       - 1);
		$this->Vals  = range(0, (1 << $this->MAX_LZW_BITS)       - 1);
		$this->Stack = range(0, (1 << ($this->MAX_LZW_BITS + 1)) - 1);
		$this->Buf   = range(0, 279);
	}

	function deCompress($data, &$datLen)
	{
		$stLen  = strlen($data);
		$datLen = 0;
		$ret    = '';

		// INITIALIZATION
		$this->LZWCommand($data, true);

		while (($iIndex = $this->LZWCommand($data, false)) >= 0)
			$ret .= chr($iIndex);

		$datLen = $stLen - strlen($data);

		if ($iIndex != -2)
			return false;

		return $ret;
	}

	function LZWCommand(&$data, $bInit)
	{
		if ($bInit)
		{
			$this->SetCodeSize = ord($data{0});
			$data = substr($data, 1);

			$this->CodeSize    = $this->SetCodeSize + 1;
			$this->ClearCode   = 1 << $this->SetCodeSize;
			$this->EndCode     = $this->ClearCode + 1;
			$this->MaxCode     = $this->ClearCode + 2;
			$this->MaxCodeSize = $this->ClearCode << 1;

			$this->GetCode($data, $bInit);

			$this->Fresh = 1;
			for ($i = 0; $i < $this->ClearCode; $i++)
			{
				$this->Next[$i] = 0;
				$this->Vals[$i] = $i;
			}

			for (; $i < (1 << $this->MAX_LZW_BITS); $i++)
			{
				$this->Next[$i] = 0;
				$this->Vals[$i] = 0;
			}

			$this->sp = 0;
			return 1;
		}

		if ($this->Fresh)
		{
			$this->Fresh = 0;
			do
			{
				$this->FirstCode = $this->GetCode($data, $bInit);
				$this->OldCode   = $this->FirstCode;
			}
			while ($this->FirstCode == $this->ClearCode);

			return $this->FirstCode;
		}

		if ($this->sp > 0)
		{
			$this->sp--;
			return $this->Stack[$this->sp];
		}

		while (($Code = $this->GetCode($data, $bInit)) >= 0)
		{
			if ($Code == $this->ClearCode)
			{
				for ($i = 0; $i < $this->ClearCode; $i++)
				{
					$this->Next[$i] = 0;
					$this->Vals[$i] = $i;
				}

				for (; $i < (1 << $this->MAX_LZW_BITS); $i++)
				{
					$this->Next[$i] = 0;
					$this->Vals[$i] = 0;
				}

				$this->CodeSize    = $this->SetCodeSize + 1;
				$this->MaxCodeSize = $this->ClearCode << 1;
				$this->MaxCode     = $this->ClearCode + 2;
				$this->sp          = 0;
				$this->FirstCode   = $this->GetCode($data, $bInit);
				$this->OldCode     = $this->FirstCode;

				return $this->FirstCode;
			}

			if ($Code == $this->EndCode)
				return -2;

			$InCode = $Code;
			if ($Code >= $this->MaxCode)
			{
				$this->Stack[$this->sp] = $this->FirstCode;
				$this->sp++;
				$Code = $this->OldCode;
			}

			while ($Code >= $this->ClearCode)
			{
				$this->Stack[$this->sp] = $this->Vals[$Code];
				$this->sp++;

				if ($Code == $this->Next[$Code]) // Circular table entry, big GIF Error!
					return -1;

				$Code = $this->Next[$Code];
			}

			$this->FirstCode = $this->Vals[$Code];
			$this->Stack[$this->sp] = $this->FirstCode;
			$this->sp++;

			if (($Code = $this->MaxCode) < (1 << $this->MAX_LZW_BITS))
			{
				$this->Next[$Code] = $this->OldCode;
				$this->Vals[$Code] = $this->FirstCode;
				$this->MaxCode++;

				if (($this->MaxCode >= $this->MaxCodeSize) && ($this->MaxCodeSize < (1 << $this->MAX_LZW_BITS)))
				{
					$this->MaxCodeSize *= 2;
					$this->CodeSize++;
				}
			}

			$this->OldCode = $InCode;
			if ($this->sp > 0)
			{
				$this->sp--;
				return $this->Stack[$this->sp];
			}
		}

		return $Code;
	}

	function GetCode(&$data, $bInit)
	{
		if ($bInit)
		{
			$this->CurBit   = 0;
			$this->LastBit  = 0;
			$this->Done     = 0;
			$this->LastByte = 2;

			return 1;
		}

		if (($this->CurBit + $this->CodeSize) >= $this->LastBit)
		{
			if ($this->Done)
			{
				// Ran off the end of my bits...
				if ($this->CurBit >= $this->LastBit)
					return 0;

				return -1;
			}

			$this->Buf[0] = $this->Buf[$this->LastByte - 2];
			$this->Buf[1] = $this->Buf[$this->LastByte - 1];

			$Count = ord($data{0});
			$data  = substr($data, 1);

			if ($Count)
			{
				for ($i = 0; $i < $Count; $i++)
					$this->Buf[2 + $i] = ord($data{$i});

				$data = substr($data, $Count);
			}
			else
				$this->Done = 1;

			$this->LastByte = 2 + $Count;
			$this->CurBit   = ($this->CurBit - $this->LastBit) + 16;
			$this->LastBit  = (2 + $Count) << 3;
		}

		$iRet = 0;
		for ($i = $this->CurBit, $j = 0; $j < $this->CodeSize; $i++, $j++)
			$iRet |= (($this->Buf[intval($i / 8)] & (1 << ($i % 8))) != 0) << $j;

		$this->CurBit += $this->CodeSize;
		return $iRet;
	}
}

class CGIFCOLORTABLE
{
	var $m_nColors;
	var $m_arColors;

	// CONSTRUCTOR
	function CGIFCOLORTABLE()
	{
		unset($this->m_nColors);
		unset($this->m_arColors);
	}

	function load($lpData, $num)
	{
		$this->m_nColors  = 0;
		$this->m_arColors = array();

		for ($i = 0; $i < $num; $i++)
		{
			$rgb = substr($lpData, $i * 3, 3);
			if (strlen($rgb) < 3)
				return false;

			$this->m_arColors[] = (ord($rgb{2}) << 16) + (ord($rgb{1}) << 8) + ord($rgb{0});
			$this->m_nColors++;
		}

		return true;
	}

	function toString()
	{
		$ret = '';

		for ($i = 0; $i < $this->m_nColors; $i++)
		{
			$ret .=
				chr(($this->m_arColors[$i] & 0x000000FF))       . // R
				chr(($this->m_arColors[$i] & 0x0000FF00) >>  8) . // G
				chr(($this->m_arColors[$i] & 0x00FF0000) >> 16);  // B
		}

		return $ret;
	}

	function colorIndex($rgb)
	{
		$rgb  = intval($rgb) & 0xFFFFFF;
		$r1   = ($rgb & 0x0000FF);
		$g1   = ($rgb & 0x00FF00) >>  8;
		$b1   = ($rgb & 0xFF0000) >> 16;
		$idx  = -1;

		for ($i = 0; $i < $this->m_nColors; $i++)
		{
			$r2 = ($this->m_arColors[$i] & 0x000000FF);
			$g2 = ($this->m_arColors[$i] & 0x0000FF00) >>  8;
			$b2 = ($this->m_arColors[$i] & 0x00FF0000) >> 16;
			$d  = abs($r2 - $r1) + abs($g2 - $g1) + abs($b2 - $b1);

			if (($idx == -1) || ($d < $dif))
			{
				$idx = $i;
				$dif = $d;
			}
		}

		return $idx;
	}
}

class CGIFFILEHEADER
{
	var $m_lpVer;
	var $m_nWidth;
	var $m_nHeight;
	var $m_bGlobalClr;
	var $m_nColorRes;
	var $m_bSorted;
	var $m_nTableSize;
	var $m_nBgColor;
	var $m_nPixelRatio;
	var $m_colorTable;

	// CONSTRUCTOR
	function CGIFFILEHEADER()
	{
		unset($this->m_lpVer);
		unset($this->m_nWidth);
		unset($this->m_nHeight);
		unset($this->m_bGlobalClr);
		unset($this->m_nColorRes);
		unset($this->m_bSorted);
		unset($this->m_nTableSize);
		unset($this->m_nBgColor);
		unset($this->m_nPixelRatio);
		unset($this->m_colorTable);
	}

	function load($lpData, &$hdrLen)
	{
		$hdrLen = 0;

		$this->m_lpVer = substr($lpData, 0, 6);
		if (($this->m_lpVer != 'GIF87a') && ($this->m_lpVer != 'GIF89a'))
			return false;

		$this->m_nWidth  = $this->w2i(substr($lpData, 6, 2));
		$this->m_nHeight = $this->w2i(substr($lpData, 8, 2));
		if (!$this->m_nWidth || !$this->m_nHeight)
			return false;

		$b = ord(substr($lpData, 10, 1));
		$this->m_bGlobalClr  = ($b & 0x80) ? true : false;
		$this->m_nColorRes   = ($b & 0x70) >> 4;
		$this->m_bSorted     = ($b & 0x08) ? true : false;
		$this->m_nTableSize  = 2 << ($b & 0x07);
		$this->m_nBgColor    = ord(substr($lpData, 11, 1));
		$this->m_nPixelRatio = ord(substr($lpData, 12, 1));
		$hdrLen = 13;

		if ($this->m_bGlobalClr)
		{
			$this->m_colorTable = new CGIFCOLORTABLE();
			if (!$this->m_colorTable->load(substr($lpData, $hdrLen), $this->m_nTableSize))
				return false;

			$hdrLen += 3 * $this->m_nTableSize;
		}

		return true;
	}

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}
}

class CGIFIMAGEHEADER
{
	var $m_nLeft;
	var $m_nTop;
	var $m_nWidth;
	var $m_nHeight;
	var $m_bLocalClr;
	var $m_bInterlace;
	var $m_bSorted;
	var $m_nTableSize;
	var $m_colorTable;

	// CONSTRUCTOR
	function CGIFIMAGEHEADER()
	{
		unset($this->m_nLeft);
		unset($this->m_nTop);
		unset($this->m_nWidth);
		unset($this->m_nHeight);
		unset($this->m_bLocalClr);
		unset($this->m_bInterlace);
		unset($this->m_bSorted);
		unset($this->m_nTableSize);
		unset($this->m_colorTable);
	}

	function load($lpData, &$hdrLen)
	{
		$hdrLen = 0;

		$this->m_nLeft   = $this->w2i(substr($lpData, 0, 2));
		$this->m_nTop    = $this->w2i(substr($lpData, 2, 2));
		$this->m_nWidth  = $this->w2i(substr($lpData, 4, 2));
		$this->m_nHeight = $this->w2i(substr($lpData, 6, 2));

		if (!$this->m_nWidth || !$this->m_nHeight)
			return false;

		$b = ord($lpData{8});
		$this->m_bLocalClr  = ($b & 0x80) ? true : false;
		$this->m_bInterlace = ($b & 0x40) ? true : false;
		$this->m_bSorted    = ($b & 0x20) ? true : false;
		$this->m_nTableSize = 2 << ($b & 0x07);
		$hdrLen = 9;

		if ($this->m_bLocalClr)
		{
			$this->m_colorTable = new CGIFCOLORTABLE();
			if (!$this->m_colorTable->load(substr($lpData, $hdrLen), $this->m_nTableSize))
				return false;

			$hdrLen += 3 * $this->m_nTableSize;
		}

		return true;
	}

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}
}

class CGIFIMAGE
{
	var $m_disp;
	var $m_bUser;
	var $m_bTrans;
	var $m_nDelay;
	var $m_nTrans;
	var $m_lpComm;
	var $m_gih;
	var $m_data;
	var $m_lzw;

	function CGIFIMAGE()
	{
		unset($this->m_disp);
		unset($this->m_bUser);
		//unset($this->m_bTrans);
		unset($this->m_nDelay);
		unset($this->m_nTrans);
		unset($this->m_lpComm);
		unset($this->m_data);
		$this->m_gih = new CGIFIMAGEHEADER();
		$this->m_lzw = new CGIFLZW();
	}

	function load($data, &$datLen)
	{
		$datLen = 0;

		while (true)
		{
			$b = ord($data{0});
			$data = substr($data, 1);
			$datLen++;

			switch ($b)
			{
			case 0x21: // Extension
				if (!$this->skipExt($data, $len = 0))
					return false;

				$datLen += $len;
				break;

			case 0x2C: // Image
				// LOAD HEADER & COLOR TABLE
				if (!$this->m_gih->load($data, $len = 0))
					return false;

				$data = substr($data, $len);
				$datLen += $len;

				// ALLOC BUFFER
				if (!($this->m_data = $this->m_lzw->deCompress($data, $len = 0)))
					return false;

				$data = substr($data, $len);
				$datLen += $len;

				if ($this->m_gih->m_bInterlace)
					$this->deInterlace();

				return true;

			case 0x3B: // EOF
			default:
				return false;
			}
		}
		return false;
	}

	function skipExt(&$data, &$extLen)
	{
		$extLen = 0;

		$b = ord($data{0});
		$data = substr($data, 1);
		$extLen++;

		switch ($b)
		{
		case 0xF9: // Graphic Control
			$b = ord($data{1});
			$this->m_disp   = ($b & 0x1C) >> 2;
			$this->m_bUser  = ($b & 0x02) ? true : false;
			$this->m_bTrans = ($b & 0x01) ? true : false;
			$this->m_nDelay = $this->w2i(substr($data, 2, 2));
			$this->m_nTrans = ord($data{4});
			break;

		case 0xFE: // Comment
			$this->m_lpComm = substr($data, 1, ord($data{0}));
			break;

		case 0x01: // Plain text
			break;

		case 0xFF: // Application
			break;
		}

		// SKIP DEFAULT AS DEFS MAY CHANGE
		$b = ord($data{0});
		$data = substr($data, 1);
		$extLen++;
		while ($b > 0)
		{
			$data = substr($data, $b);
			$extLen += $b;
			$b    = ord($data{0});
			$data = substr($data, 1);
			$extLen++;
		}
		return true;
	}

	function w2i($str)
	{
		return ord(substr($str, 0, 1)) + (ord(substr($str, 1, 1)) << 8);
	}

	function deInterlace()
	{
		$data = $this->m_data;

		for ($i = 0; $i < 4; $i++)
		{
			switch ($i)
			{
			case 0:
				$s = 8;
				$y = 0;
				break;

			case 1:
				$s = 8;
				$y = 4;
				break;

			case 2:
				$s = 4;
				$y = 2;
				break;

			case 3:
				$s = 2;
				$y = 1;
				break;
			}

			for (; $y < $this->m_gih->m_nHeight; $y += $s)
			{
				$lne = substr($this->m_data, 0, $this->m_gih->m_nWidth);
				$this->m_data = substr($this->m_data, $this->m_gih->m_nWidth);

				$data =
					substr($data, 0, $y * $this->m_gih->m_nWidth) .
					$lne .
					substr($data, ($y + 1) * $this->m_gih->m_nWidth);
			}
		}

		$this->m_data = $data;
	}
}

class CGIF
{
	var $m_gfh;
	var $m_lpData;
	var $m_img;
	var $m_bLoaded;

	// CONSTRUCTOR
	function CGIF()
	{
		$this->m_gfh     = new CGIFFILEHEADER();
		$this->m_img     = new CGIFIMAGE();
		$this->m_lpData  = '';
		$this->m_bLoaded = false;
	}

	function loadFile($lpszFileName, $iIndex)
	{
		if ($iIndex < 0)
			return false;

		// READ FILE
		if (!($fh = @fopen($lpszFileName, 'rb')))
			return false;

		$this->m_lpData = @fread($fh, @filesize($lpszFileName));
		fclose($fh);

		// GET FILE HEADER
		if (!$this->m_gfh->load($this->m_lpData, $len = 0))
			return false;

		$this->m_lpData = substr($this->m_lpData, $len);

		do
		{
			if (!$this->m_img->load($this->m_lpData, $imgLen = 0))
				return false;

			$this->m_lpData = substr($this->m_lpData, $imgLen);
		}
		while ($iIndex-- > 0);

		$this->m_bLoaded = true;
		return true;
	}

	function getPng($bgColor)
	{
		$out = '';

		if (!$this->m_bLoaded)
			return false;

		// PREPARE COLOR TABLE (RGBQUADs)
		if ($this->m_img->m_gih->m_bLocalClr)
		{
			$nColors = $this->m_img->m_gih->m_nTableSize;
			$pal     = $this->m_img->m_gih->m_colorTable->toString();
			if ($bgColor != -1)
				$bgColor = $this->m_img->m_gih->m_colorTable->colorIndex($bgColor);
		}
		elseif ($this->m_gfh->m_bGlobalClr)
		{
			$nColors = $this->m_gfh->m_nTableSize;
			$pal     = $this->m_gfh->m_colorTable->toString();
			if ($bgColor != -1)
				$bgColor = $this->m_gfh->m_colorTable->colorIndex($bgColor);
		}
		else
		{
			$nColors =  0;
			$bgColor = -1;
		}

		// PREPARE BITMAP BITS
		$data = $this->m_img->m_data;
		$nPxl = 0;
		$bmp  = '';
		for ($y = 0; $y < $this->m_gfh->m_nHeight; $y++)
		{
			$bmp .= "\x00";
			for ($x = 0; $x < $this->m_gfh->m_nWidth; $x++, $nPxl++)
			{
				if (
					($x >= $this->m_img->m_gih->m_nLeft) &&
					($y >= $this->m_img->m_gih->m_nTop) &&
					($x <  ($this->m_img->m_gih->m_nLeft + $this->m_img->m_gih->m_nWidth)) &&
					($y <  ($this->m_img->m_gih->m_nTop  + $this->m_img->m_gih->m_nHeight)))
				{
					// PART OF IMAGE
					$bmp .= $data{$nPxl};
				}
				else
				{
					// BACKGROUND
					if ($bgColor == -1)
						$bmp .= chr($this->m_gfh->m_nBgColor);
					else
						$bmp .= chr($bgColor);
				}
			}
		}
		$bmp = gzcompress($bmp, 9);

		///////////////////////////////////////////////////////////////////////
		// SIGNATURE
		$out .= "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";
		///////////////////////////////////////////////////////////////////////
		// HEADER
		$out .= "\x00\x00\x00\x0D";
		$tmp  = 'IHDR';
		$tmp .= $this->ndword($this->m_gfh->m_nWidth);
		$tmp .= $this->ndword($this->m_gfh->m_nHeight);
		$tmp .= "\x08\x03\x00\x00\x00";
		$out .= $tmp;
		$out .= $this->ndword(crc32($tmp));
		///////////////////////////////////////////////////////////////////////
		// PALETTE
		if ($nColors > 0)
		{
			$out .= $this->ndword($nColors * 3);
			$tmp  = 'PLTE';
			$tmp .= $pal;
			$out .= $tmp;
			$out .= $this->ndword(crc32($tmp));
		}
		///////////////////////////////////////////////////////////////////////
		// TRANSPARENCY
		if ($this->m_img->m_bTrans && ($nColors > 0))
		{
			$out .= $this->ndword($nColors);
			$tmp  = 'tRNS';
			for ($i = 0; $i < $nColors; $i++)
				$tmp .= ($i == $this->m_img->m_nTrans) ? "\x00" : "\xFF";
			$out .= $tmp;
			$out .= $this->ndword(crc32($tmp));
		}
		///////////////////////////////////////////////////////////////////////
		// DATA BITS
		$out .= $this->ndword(strlen($bmp));
		$tmp  = "IDAT";
		$tmp .= $bmp;
		$out .= $tmp;
		$out .= $this->ndword(crc32($tmp));
		///////////////////////////////////////////////////////////////////////
		// END OF FILE
		$out .= "\x00\x00\x00\x00IEND\xAE\x42\x60\x82";

		return $out;
	}

	function dword($val)
	{
		$val = intval($val);
		return chr($val & 0xFF).chr(($val & 0xFF00) >> 8).chr(($val & 0xFF0000) >> 16).chr(($val & 0xFF000000) >> 24);
	}

	function ndword($val)
	{
		$val = intval($val);
		return chr(($val & 0xFF000000) >> 24).chr(($val & 0xFF0000) >> 16).chr(($val & 0xFF00) >> 8).chr($val & 0xFF);
	}

	function width()
	{
		return $this->m_gfh->m_nWidth;
	}

	function height()
	{
		return $this->m_gfh->m_nHeight;
	}

	function loaded()
	{
		return $this->m_bLoaded;
	}
}

?>