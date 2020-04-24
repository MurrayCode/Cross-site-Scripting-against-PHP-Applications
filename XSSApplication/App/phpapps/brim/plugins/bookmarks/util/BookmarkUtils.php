<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/util/ItemUtils.php');
require_once ('plugins/bookmarks/model/BookmarkFactory.php');

/**
 * Bookmark utilities; this file allows import and export from and
 * to opera bookmarks as well as netscape/mozilla bookmarks
 *
 * This opera import/export functionality is a modified version of the
 * one that can be found in the bookmark4u project
 * ({@link http://bookmark4u.sourceforge.net/
 * http://bookmark4u.sourceforge.net/}) which I wrote some time ago
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.bookmarks
 * @subpackage util
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class BookmarkUtils extends ItemUtils
{
	/**
	 * The factory used for bookmark creation
	 */
	var $factory;
	/**
	 * Empty default constructor
	 */
	function BookmarkUtils ()
	{
		parent::ItemUtils();
		$this->factory = new BookmarkFactory ();
	}
	
	/**
	 * Compares php version numbers
	 * 
	 * versions must must use the format ' x.y.z... '
	 * where (x, y, z) are numbers in [0-9] 
	 * http://be.php.net/manual/en/function.phpversion.php#58145
	 *
	 * @param string $currentversion
	 * @param string $requiredversion
	 * @return boolean 
	 */
	function checkVersion ($currentversion, $requiredversion)
	{
		list($majorC, $minorC, $editC) = split('[/.-]', $currentversion);
		list($majorR, $minorR, $editR) = split('[/.-]', $requiredversion);
		
		if ($majorC > $majorR) return true;
		if ($majorC < $majorR) return false;
		// same major - check ninor
		if ($minorC > $minorR) return true;
		if ($minorC < $minorR) return false;
		// and same minor
		if ($editC  >= $editR)  return true;
		return false;
	}

	/**
	 * import a bookmark file into user's DB
	 *
	 * @param string userId the identifier for the user
	 * @param string userFile the file that contains the opera
	 * 	bookmarks
	 */
	function importOperaBookmarks ($userId, $userfile, $callback,
		$parentId, $visibility)
	{
		$stringUtils = new StringUtils ();
		$bookmarkOperations = $callback;
	  	$name = "NAME";
	  	$url = "URL";
	  	$description = "DESCRIPTION";
	  	$typeIndicator = "Opera Hotlist";
		//
		// start at the root
		//
		$top=0;
		$stack = array ();
		$stack [$top++] = $parentId;
	  	$fp = fopen ($userfile, "r");
		if ($fp == null)
		{
			die ("Failed to open " . $userfile);
		}
		//
	  	// compare the first line. It should indicate that we are
	  	// processing a bookmark file of the Opera browser
		//
	  	$firstLine = fgets ($fp, 12288);
		if (!$firstLine)
		{
			die ("Could not open file");
		}
	  	if (!$stringUtils->startsWith ($firstLine, $typeIndicator))
			die ("Not a valid Opera file");
	  	$processing = false;
		//
	  	// process the file
		//
	  	while (!feof ($fp))
		{
			if ($this->checkVersion (phpversion(), '4.3.0'))
			{
	    		$currentLine = trim (fgets($fp));
			}
			else
			{
	    		$currentLine = trim (fgets($fp, 4096));
			}
			//
	    	// #FOLDER starts a (sub)folder
			//
	    	if ($currentLine == "#FOLDER")
			{
			$currentBookmark = $this->factory->getEmptyItem();
			$currentBookmark->visibility=$visibility;
			$currentBookmark->isParent=1;
	      		$processing = true;
	    	}
			//
	    	// #URL starts a bookmark
			//
	    	else if ($currentLine == "#URL")
			{
			$currentBookmark = $this->factory->getEmptyItem();
			$currentBookmark->visibility=$visibility;
			$currentBookmark->isParent=0;
	      		$processing = true;
	    	}
			//
	    	// - ends a folder
			//
	    	else if ($currentLine == "-")
			{
			//
			// pop current folder from stack
			//
	      		$parentId = $stack[--$top];
	      		$processing = false;
			}
	    	else if ($currentLine == "" && $processing)
			{
				//
	      		// Ok, we found an empty line, this means that we have
	      		// either found a folder or bookmark
	      		//
	      		// Attention! We will find an empty line just before a
	      		// folder seperator and just after! Make sure that we
	      		// are not processing the same bookmark twice (this is
	      		// why the boolean 'processing' is used.
				//
	      		if ($currentBookmark->isParent == 1)
				{
					//
	        		// place the current folder on the stack
					//
	        		$stack [$top++] = $parentId;
					$currentBookmark->parentId=$parentId;
					$parentId=$bookmarkOperations->addItem ($userId, $currentBookmark);
	      		}
	      		else
				{
				$currentBookmark->parentId = $parentId;
				$bookmarkOperations->addItem($userId, $currentBookmark);
	      		}
	      		$processing = false;
	    	}
			//
	   	 	// Parse the actual bookmark content. Note that the
			// 'processing' boolean is not needed here, but hey....
			// it cannot hurt
			//
	    	else if ($currentLine != "" && $processing)
			{
				//
	      		// Parse for the string 'NAME='
				//
	      		if ($stringUtils->startsWith ($currentLine, $name))
				{
	        		$currentName= $stringUtils->getProperty($currentLine, $name);
					if (isset ($currentName))
					{
					// replace special characters
					$currentName = str_replace('ä', '�', $currentName);
					$currentName = str_replace('Ä', '�', $currentName);
					$currentName = str_replace('ö', '�', $currentName);
					$currentName = str_replace('ü', '�', $currentName);
					$currentName = str_replace('Ü', '�', $currentName);
					$currentName = str_replace('é', '�', $currentName);
					$currentName = str_replace('É', '�', $currentName);
					$currentName = str_replace('è', '�', $currentName);
					$currentName = str_replace('È', '�', $currentName);
					$currentName = str_replace('à', '�', $currentName);
					$currentName = str_replace('À', '�', $currentName);
					$currentName = str_replace('§', '�', $currentName);
					$currentName = str_replace('°', '�', $currentName);
					$currentName = str_replace('¦', '�', $currentName);
					$currentName = str_replace('ç', '�', $currentName);
					$currentName = str_replace('ô', '�', $currentName);
					$currentName = str_replace('Ô', '�', $currentName);
					$currentName = str_replace('û', '�', $currentName);
					$currentName = str_replace('Û', '�', $currentName);
					$currentName = str_replace('î', '�', $currentName);
					$currentName = str_replace('Î', '�', $currentName);
					$currentName = str_replace('�', '�', $currentName);
					$currentName = str_replace('è', '�', $currentName);
					$currentName = str_replace('\\', '\\\\', $currentName);
				}
				$currentBookmark->name=$currentName;
			}
			//
	      		// Parse for the string 'DESCRIPTION='
			//
			else if ($stringUtils->startsWith($currentLine, $description))
			{
				$currentDescription = $stringUtils->getProperty
					($currentLine, $description);
				$currentBookmark->description=$currentDescription;
			}
			//
			// Parse for the string 'URL='
			//
	      		else if ($stringUtils->startsWith ($currentLine, $url))
			{
				$currentURL = $stringUtils->getProperty
					($currentLine, $url);
				$currentBookmark->locator=$currentURL;
			}
			else
			{
				//
				// Ignore the rest.
				//
			}
	    	}
	  	}
	  	fclose ($fp);
	}

	/**
	 * Export users bookmarks (starting from a certain Id)
	 *
	 * @param string id the identifier for the user
	 * @param integer parent the identifier for the parent id of the
	 * bookmarks (to enable recursive functioncall)
	 */
	function exportOperaBookmarks ($id, $parent, $callback)
	{
		$bookmarkOperations = $callback;
	  	$bookmarks = $bookmarkOperations->getChildren ($id, $parent);
		$newline="\n";
		$cnt_c = 0;
		$cnt_b = 0;
		//
		// Loop over the bookmarks
		//
	  	for ($i=0; $i<count ($bookmarks); $i++)
	  	{
			$currentBookmark = $bookmarks[$i];
			//
			// Folder
			//
	    		if ($currentBookmark->isParent == '1')
			{
	      			$cnt_c++;
	      			echo("#FOLDER$newline");
	      			echo("\tNAME=" . $currentBookmark->name . "$newline");
	      			echo("\tCREATED=$newline");
	      			echo("\tVISITED=0$newline");
	      			echo("\tORDER=$cnt_c$newline");
	      			echo("\tEXPANDED=YES$newline");
	      			echo("\tPERSONALBAR_POS=-1$newline");
	      			echo("\tDESCRIPTION=".$currentBookmark->description.
					"$newline");
	      			echo("\tSHORT NAME=$newline$newline");
	      			$this->exportOperaBookmarks($id,
					$currentBookmark->itemId, $callback);
	      			echo("$newline-$newline");
	    		}
	    		else
	    		{
	      			$cnt_b++;
	      			echo("#URL$newline");
	      			echo("\tNAME=" . $currentBookmark->name . "$newline");
	      			echo("\tURL=" . $currentBookmark->locator . "$newline");
	      			echo("\tCREATED=$newline");
	      			echo("\tVISITED=0$newline");
	      			echo("\tPERSONALBAR_POS=-1$newline");
	      			echo("\tPANEL_POS=-1$newline");
	      			echo("\tDESCRIPTION=". $currentBookmark->description.
					"$newline");
	      			echo("\tSHORT NAME=$newline");
	      			echo("\tORDER=$cnt_b$newline$newline");
	    		}
	  	}
	}

	/**
	 * Imports XBEL bookmarks from a file for a certain user
	 * Note that this function does not check for xml functions,
	 * this needs to be done before calling this function.
	 *
	 * @param string userId the identifier for the user
	 * @param string userfile the file that contains the bookmarks
	 */
	function importXBEL($userId, $userfile, $callback,
		$parentId, $visibility)
	{
		$stringUtils = new StringUtils ();
		//
		// The array that will contain the struct resulting
		// from the incoming data
		//
		$result = array ();
		$input = implode ('', file ($userfile));
		//
		// Now cleanup the input string, removing all ampersand
		// and replace them by '&amp;';
		//
		$data = $stringUtils->urlEncodeAmpersands ($input);
		//
		// The parser itself.
		//
		$parser = xml_parser_create ();
		xml_parse_into_struct ($parser, $data, $result);
		xml_parser_free ($parser);
		//
		// The stack will be used for the nesting of the bookmarks
		//
		$stack = array ();
		$stackCount = 0;
		//
		// Now loop over all found tags and create bookmarks from it
		//
		$currentTag = '';
		$currentBookmark = null;
		for ($i=0; $i<count ($result); $i++)
		{
			$currentTag = $result[$i];
			switch ($currentTag['tag'])
			{
				case 'XBEL':
					if ($currentTag['type'] == 'open')
					{
						//
						// The "main" folder. Only one exist
						//
						$currentBookmark =
							$this->factory->getEmptyItem();
						$currentBookmark->isParent = true;
						$currentBookmark->owner = $userId;
						$currentBookmark->visibility = $visibility;
						$currentBookmark->parentId = $parentId;
					}
					else if ($currentTag['type'] == 'close')
					{
						//
						// Ok, we are done
						//
					}
					break;
				case 'METADATA':
				case 'COMMENT':
				case 'INFO':
					// ignore
					break;
				case 'TITLE':
					//
					// Set the title
					//
					$currentBookmark->name =
						$currentTag['value'];
					break;
				case 'DESC':
					//
					// Set the description
					//
					$currentBookmark->description =
						$currentTag['value'];
					break;
				case 'FOLDER':
					if ($currentTag['type'] == 'open')
					{
						//
						// Found a folder open tag
						//
						$stack[$stackCount++] = $currentBookmark;
						$currentBookmark =
							$this->factory->getEmptyItem();
						$currentBookmark->isParent = true;
						$currentBookmark->owner = $userId;
						$currentBookmark->visibility = $visibility;
					}
					else if ($currentTag['type'] == 'close')
					{
						//
						// Ok, we have finished processing a folder,
						//
						$parent = $stack [--$stackCount];
						$parent->children[] = $currentBookmark;
						$currentBookmark = $parent;
					}
					break;
				case 'BOOKMARK':
					if ($currentTag['type'] == 'open')
					{
						//
						// Bookmark open tag. Place the current
						// (which is logically a folder) on the stack
						// and process the new
						//
						$stack[$stackCount++] = $currentBookmark;
						$currentBookmark =
							$this->factory->getEmptyItem();
						$currentBookmark->isParent = false;
						$currentBookmark->owner = $userId;
						$currentBookmark->visibility = $visibility;
						$currentBookmark->name =
							$currentTag['attributes']['ID'];
						$currentBookmark->locator =
							$currentTag['attributes']['HREF'];
					}
					else if ($currentTag['type'] == 'close')
					{
						//
						// Bookmark close.
						// Retrieve the last item from the stack.
						// This item is logically a folder and it
						// is the folder to which this bookmark
						// should be added
						//
						$parent = $stack[--$stackCount];
						$parent->children[] = $currentBookmark;
						$currentBookmark = $parent;
					}
					break;
				default:
					//
					// ignore all other tags
					//
					break;
			}
		}
		$this->recursiveAdd ($userId, $callback, $currentBookmark);
	}

	function exportXBEL ($username, $parentId, $callback)
	{
		//
		// Print XBEL header
		//
		echo ('<?xml version="1.0"?>
		');
		echo ('<!DOCTYPE xbel PUBLIC "+//IDN python.org//DTD XML Bookmark Exchange Language 1.0//EN//XML" "http://www.python.org/topics/xml/dtds/xbel-1.0.dtd">
		');
		echo ('<xbel version="1.0">
		');
		echo ('<title>Brim\'s XBEL export for '.$username.'</title>
		');

	  	$bookmarks = $callback->getChildren ($username, $parentId);
		//
		// Loop over the bookmarks
		//
	  	for ($i=0; $i<count ($bookmarks); $i++)
	  	{
			$currentBookmark = $bookmarks[$i];
	    		if ($currentBookmark->isParent == '1')
			{
				//
				// Folder
				//
				$this->exportXBELFolder
					($username, $callback, $currentBookmark, 0);
			}
			else
			{
				//
				// Bookmark
				//
				$this->exportXBELBookmark ($currentBoookmark, 0);
			}
		}
		echo ('</xbel>');
	}

	function exportXBELFolder ($username, $callback, $bookmark, $depth)
	{
		//
		// Open the folder tag
		//
		echo (str_repeat ('    ', $depth).'<folder>
		');
		//
		// Print the name
		//
		echo (str_repeat ('    ', $depth+1));
		echo ('<title>'.$bookmark->name.'</title>
		');
		//
		// Print the optional descirption
		//
		if (isset ($bookmark->description))
		{
			echo (str_repeat ('    ', $depth+1));
			echo ('<desc>'.$bookmark->description.'</desc>
			');
		}
		//
		// Recursively export this folder's children
		//
	  	$children =
			$callback->getChildren ($username, $bookmark->itemId);
		for ($i=0; $i<count ($children); $i++)
		{
			$current = $children[$i];
			if ($current->isParent ())
			{
				$this->exportXBELFolder
					($username, $callback, $current, $depth+1);
			}
			else
			{
				$this->exportXBELBookmark ($current, $depth+1);
			}
		}
		//
		// Close the folder tag
		//
		echo (str_repeat ('    ', $depth).'</folder>
		');
	}

	function exportXBELBookmark ($bookmark, $depth)
	{
		//
		// Open the bookmark tag
		//
		echo (str_repeat ('    ', $depth));
		echo ('<bookmark href="'.$bookmark->locator.'">
		');
		//
		// Print the name
		//
		echo (str_repeat ('    ', $depth+1));
		echo ('<title>'.$bookmark->name.'</title>
		');
		//
		// Print the optional description
		//
		if (isset ($bookmark->description))
		{
			echo (str_repeat ('    ', $depth+1));
			echo ('<desc>'.$bookmark->description.'</desc>
			');
		}
		//
		// Close the bookmark tag
		//
		echo (str_repeat ('    ', $depth));
		echo ('</bookmark>
		');
	}

	/**
	 * Recursively add a folder and its children to Brim's
	 * database
	 *
	 * @param string userid the username
	 * @param object callback the bookmarkcontroller
	 * @param object bookmark the bookmark to be added to the db
	 */
	function recursiveAdd ($userId, $callback, $bookmark)
	{
		if ($bookmark->name==null)
		{
			//
			// The XBEL file itself has an OPTIONAL name
			// description etc. If these are not set, ignore
			// this as folder, otherwise, use it as folder
			//
			$insertId = $bookmark->parentId;
		}
		else
		{
			$insertId = $callback->addItem ($userId, $bookmark);
		}
		//
		// If we are a folder, apply this function to all this
		// folder's children
		//
		if ($bookmark->isParent)
		{
			$children = $bookmark->getChildren ();
			for ($i=0; $i<count($children); $i++)
			{
				$current = $children[$i];
				$current->parentId = $insertId;
				$this->recursiveAdd ($userId, $callback, $current);
			}
		}
	}

	/**
	 * Imports bookmarks from a netscape file for a certain user
	 *
	 * @param string userId the identifier for the user
	 * @param string userfile the file that contains the netscape
	 * bookmarks
	 */
	function importNetscapeBookmarks($userId, $userfile, $callback,
		$parentId, $visibility)
	{
		$top=0;
		$stack = array ();
		$stack [$top++] = $parentId;
		$stringUtils = new StringUtils ();
		$bookmarkOperations = $callback;
	  	$fp = fopen($userfile, "r");
		//
	  	// check the first line which identifies the type
		//
	  	$firstLine = fgets($fp, 4096);
		if (!$firstLine)
		{
			die ("Could not open file");
		}
	  	$typeIndicator = "<!DOCTYPE NETSCAPE-Bookmark-file-1>";
	  	if (!$stringUtils->startsWith ($firstLine, $typeIndicator))
		{
			die ("Not a valid Netscape file");
		}
		//
		// While we have data
		//
	  	while (!feof($fp))
	  	{
			if ($this->checkVersion (phpversion(), '4.3.0'))
			{
	    		$currentLine = trim (fgets($fp));
			}
			else
			{
	    		$currentLine = trim (fgets($fp, 4096));
			}
			//
    		// Start a folder. Only used for indentation
			//
    		if (ereg("^[ ]*<DL", $currentLine))
    		{
				//
    			// nothing....
				//
    		}
			//
    		// Ends a (sub)folder. Only used for indentation
			//
    		else if (ereg("^[ ]*<\/DL", $currentLine))
    		{
      			$top--;
				//
      			// Hmm.. this seems to be a bug....
				// Next line should not be here.
				//
      			if ($top < 0)
				{
					//die (print_r ($stack));
					$top = 0;
                }
      			$parentId = $stack [$top];
    		}
			//
    		// Starts a bookmark
			//
    		else if (ereg("^[ ]*<DT", $currentLine))
    		{
      			if (ereg("<A", $currentLine))
      			{
					$currentBookmark =
						$this->factory->getEmptyItem();
        			$locator = ereg_replace("([^H]*HREF=\")([^\"]*)(\".*)", "\\2", $currentLine);
					$currentBookmark->locator = $locator;
        			$name = ereg_replace("^( *<DT><[^>]*>)([^<]*)(.*)", "\\2", $currentLine);
					$currentBookmark->visibility = $visibility;
					$currentBookmark->name = $name;
					$currentBookmark->isParent = 0;
					$currentBookmark->parentId = $parentId;
					$bookmarkOperations->addItem
						($userId, $currentBookmark);
      			}
				//
      			// Start a folder
				//
      			else
      			{
					//
      				// the actual folder definition
					//
        			$name = ereg_replace("^( *<DT><[^>]*>)([^<]*)(.*)", "\\2", $currentLine);
					$currentBookmark =
							$this->factory->getEmptyItem();
					$currentBookmark->visibility = $visibility;
					$currentBookmark->isParent = 1;
					$currentBookmark->parentId = $parentId;
					$currentBookmark->name = $name;
					//
					// put the current folder on the stack
					//
        			$stack[$top] = $parentId;
        			$top++;
					//
					// rertieve the new parent id by adding it
					//
					$parentId = $bookmarkOperations->addItem
						($userId, $currentBookmark);
      			}
    		}
	  	}
	  	fclose($fp);
	}


	/**
	 * Export bookmarks for a certain user starting at a certain
	 * item
	 *
	 * @param string userId the indentifier for the user for which
	 * we would like to export bookmarks
	 * @param integer rootId the identifier for the item and all its
	 * children which we would like to export.
	 */
	function exportNetscapeBookmarks ($userId, $rootId, $callback)
	{
		global $indentLevel;
		$newline = "\n";
		//
		// This is not correct if we would like to generate a proper
		// bookmark file from a subfolder
		//
		if ($rootId == 0)
		{
			echo ('<!DOCTYPE NETSCAPE-Bookmark-file-1>'.$newline);
			echo ('<META HTTP-EQUIV="Content-Type" ');
			echo ('CONTENT="text/html; charset=UTF-8">'.$newline);
			echo ('<TITLE>Bookmarks</TITLE>'.$newline);
			echo ('<H1>Bookmarks</H1>'.$newline.$newline);
			echo ('<DL><p>'.$newline);
		}
		$bookmarkOperations = $callback;
		$children = $bookmarkOperations->getChildren ($userId, $rootId);
		for ($i=0; $i<count($children); $i++)
		{
			$current = $children [$i];
			//
			// We have a folder
			//
			if ($current->isParent==1)
			{
				//
				// print the appropriate indentation
				//
				for ($j=0; $j<$indentLevel; $j++)
				{
					echo ('    ');
				}
				//
				// print name
				//
				echo ('<DT><H3>'.$current->name.'</H3>'.$newline);
				for ($j=0; $j<$indentLevel; $j++)
				{
					echo ('    ');
				}
				echo ('<DL><p>'.$newline);
				//
				// and do the same recursively for this folders
				// children
				//
				$indentLevel++;
				$this->exportNetscapeBookmarks ($userId,
					$current->itemId, $bookmarkOperations);
				$indentLevel--;
				for ($j=1; $j<$indentLevel; $j++, $callback)
				{
					echo ('    ');
				}
				echo ('</DL><p>'.$newline);
			}
			//
			// we have a node (bookmark)
			//
			else
			{
				for ($j=0; $j<$indentLevel; $j++)
				{
					echo ('    ');
				}
				echo ('<DT>');
				echo ('<A HREF="'.$current->locator.'">');
				echo ($current->name.'</A>'.$newline);
			}
		}
		//
		// proper closing
		//
		if ($rootId == null)
		{
			echo ('</DL><p>'.$newline);
		}
	}

	/**
 	 * Returns the input URL, keeping the path but stripping
	 * the file (if possible.)
	 *
	 * @param string url the input url
	 * @return string the modified url or null if null input
	 *
	 * Examples:
	 * <pre>
	 * input: ftp://username:password@ftp.netscape.com/
	 * output: ftp://username:password@ftp.netscape.com/
	 * input: http://www.barrel.net
	 * output: http://www.barrel.net/
	 * input: http://www.barrel.net/index.html
	 * output: http://www.barrel.net/
	 * input: http://www.barrel.net/booby/index.html
	 * output: http://www.barrel.net/booby/
	 * input: http://www.foo.com/pub/bar/baz.php?query+data
	 * output: http://www.foo.com/pub/bar/
	 * </pre>
	 */
    function getUrlWithoutFile ($url)
    {
		if ($url == null)
		{
			return null;
		}
        $parts = parse_url ($url);
		//
		// Cut the path in parts based on the forward slash and remove
		// the latst item
		//
        $path = explode ("/", $parts['path']);
        unset ($path[count($path)-1]);
		//
		// Now the latest is removed, rebuild the path
		//
        $thePath = implode ("/", $path);
		//
		// Check for password settings, leave empty if none found
		//
        $pwd = '';
        if (isset ($parts['user']) && isset ($parts['pass']))
        {
            $pwd = $parts['user'].':'.$parts['pass'].'@';
        }
		//
		// And glue everything back together again
		//
        return $parts['scheme'].'://'.$pwd.$parts['host'].$thePath.'/';
    }

	/**
 	 * Fetch the favicon from an URL. At first the header of the URL
	 * is downloaded and the string pointing to a favicon is retrieved.
	 * If the fails, the base url is  taken and an attempt is initiated
	 * to download that specific favicon
	 *
	 * @param string url the url for which we would like to retrieve the icon
	 * @return string a base64 encode form of the favicon or
	 * <code>null</code> if no favicon found
	 */
	function getFavicon ($url)
	{
		$favicon = null;
		if ($url != null)
		{
			$theUrl = $this->askForFavicon ($url);
			if ($theUrl == null)
			{
				$theUrl = $this->retrieveFaviconUrlFromHeader ($url);
			}
			if ($theUrl == null)
			{
				$parsedUrl = parse_url ($url);
				$tmpUrl = $parsedUrl['scheme'].'://'.$parsedUrl['host'].'/';
				$theUrl = $this->retrieveFaviconUrlFromHeader ($tmpUrl);
			}
			if ($theUrl == null)
			{
				$theUrl = $parsedUrl['scheme'].'://'.$parsedUrl['host'].'/favicon.ico';
			}
			if ($theUrl == null)
			{
				$theUrl = $this->getUrlWithoutFile ($url).'favicon.ico';
			}
			$favicon = $this->fetchFavicon ($theUrl);
		}
		return $favicon;
	}

	/**
	 * Download the actual favicon from the given URL
	 *
	 * @param string theUrl to url for which we would
	 * like to retrieve the icon
	 * @return string a base64 encode form of the favicon or
	 * <code>null</code> if no favicon found
	 */
	function fetchFavicon ($theUrl)
	{
		$favicon = null;
		$handle = @fopen ($theUrl, "rb");
		if ($handle)
		{
			$favicon='';
			while (!feof($handle))
			{
				$line = fread ($handle, 4096);
				if (strstr ($line, '<html') ||
					strstr ($line, '<?xml'))
				{
					fclose ($handle);
					return null;
				}
				$favicon .= chunk_split(base64_encode ($line));
			}
			fclose ($handle);
		}
		return $favicon;
	}

	/**
 	 * This function examines the html header of the given URL,
	 * looks for the favicon and returns the full (including http:// etc)
	 * address to the favicon (if found, null otherwise)
	 *
	 * @param string url the url for which we would like to have the favicon
 	 * @return string the url to the favicon or <code>null</code> if not found
	 */
	function retrieveFaviconUrlFromHeader ($url)
	{
		$handle = @fopen ($url, "r");
		if ($handle)
		{
			$contents='';
			while (!feof ($handle))
			{
				$line = fread ($handle, 4096);
				$contents .= $line;
				//
				// Stop when the header is read,
				// no need to go any further
				//
				if (stristr ($line, '</head'))
				{
					break;
				}
			}
			fclose ($handle);
		}
		$matches = array ();
		//
		// Retrieve all values for href from the content
		//
		if (preg_match_all('/href="([^"]*)"/', $contents, $matches))
		{
			//
			// All links are stored in $matches[1] ($matches[0] contains the link
			// in 'href="abc" format)
			//
			foreach ($matches[1] as $match)
			{
				if (stristr ($match, '.ico'))
				{
					//
					// Ok, we have found a link to a favicon
					//
					if (strstr ($match, 'http://'))
					{
						//
						// We found 'http://' in the match, we can assume
						// that we found a full url
						//
						return $match;
					}
					else
					{
						//
						// We found a relative url. Recompose a fully
						// qualified url
						//
						$urlArray = parse_url ($url);
						//
						// Use optional credentials or leave empty
						//
						$credentials = '';
						if (isset ($urlArray['user']) && isset ($urlArray['pass']))
						{
							$credentials=$urlArray['user'].':'.$urlArray['pass'].'@';
						}
						//
						// Go ahead
						//
						return $urlArray['scheme'].'://'.$credentials.$urlArray['host'].'/'.$match;
					}
				}
			}
		}
		return null;
	}

	function askForFavicon ($url)
	{
		$urlArray = parse_url ($url);
		$fp = fsockopen ($urlArray ['host'], isset ($urlArray ['port'])?$urlArray ['port']:80);
		if (!$fp)
		{
			return null;
		}
		fwrite ($fp, "HEAD /favicon.ico HTTP/1.0\r\nHost: ".$urlArray ['host']."\r\n\r\n");
		$buffer = fgets ($fp, 1024);
		fclose ($fp);
		if (strstr ("200 OK", $buffer))
		{
			$credentials = '';
			if (isset ($urlArray['user']) && isset ($urlArray['pass']))
			{
				$credentials=$urlArray['user'].':'.$urlArray['pass'].'@';
			}
			$port = '';
			if (isset ($urlArray['port']))
			{
				$port = ':'.$urlArray['port'];
			}
			return $urlArray['scheme'].'://'.$credentials.$urlArray['host'].$port.'/favicon.ico';
		}
		return null;
	}

}
?>
