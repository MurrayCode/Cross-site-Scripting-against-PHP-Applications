<?php

require_once ('framework/util/StringUtils.php');
require_once ('framework/util/ItemUtils.php');

require_once ('ext/File/IMC/Build/vCard.php');

/**
 * Contact utilities, this file allows import and export from and to
 * opera contacts
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.contacts
 * @subpackage util
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 * @todo also embed the vCard functionality in this class, it might
 * serve as an interface and hide the actual implementation
 */
class ContactUtils extends ItemUtils
{
	/**
	 * Empty default constructor
	 */
	function ContactUtils ()
	{
		parent::ItemUtils();
	}

	/**
	 * import a opera contact file into user's DB
	 *
	 * @param string userId the identifier for the user
	 * @param string userFile the file that contains the opera bookmarks
	 */
	function importOperaContacts
		($userId, $userfile, $callback, $parentId, $visibility)
	{
		$stringUtils = new StringUtils ();
		$contactOperations = $callback;

		$id   = "ID"; // not used
		$icon = "ICON"; // not used
		$created = "CREATED"; //not used
	  	$name = "NAME";
	  	$mail = "MAIL";
	  	$description = "DESCRIPTION";
		$phone= "PHONE";
		$fax  = "FAX";
		$addr = "POSTALADDRESS";
		$url  = "URL";
	  	$typeIndicator = "Opera Hotlist";

		$top=0;
		$stack = array ();
		$stack[$top++]=$parentId;

	  	$fp = fopen($userfile, "r");
		if ($fp == null)
		{
			die ("Failed to open " . $userfile);
		}
	  	// compare the first line. It should indicate that we are
	  	// processing a bookmark file of the Opera browser
	  	$firstLine = fgets($fp, 4096);
	  	if (!$stringUtils->startsWith ($firstLine, $typeIndicator))
		{
			die ("Not a valid Opera file");
		}
	  	$processing = false;

		$currentContact = null;
	  	// process the file
	  	while (!feof($fp))
		{
	    	$currentLine = trim (fgets($fp, 4096));

	    	// #FOLDER starts a (sub)folder
	    	if ($currentLine == "#FOLDER")
			{
				$currentContact = new Contact
					(null,null,null,null,
					null,null,null,null,null,
					null,null,null,null,null);
				$currentContact->visibility=$visibility;
				$currentContact->isParent=1;
	      		$processing = true;
	    	}
	    	// #URL starts a bookmark
	    	else if ($currentLine == "#CONTACT")
			{
				$currentContact = new Contact
					(null,null,null,null,
					null,null,null,null,null,
					null,null,null,null,null);
				$currentContact->visibility=$visibility;
				$currentContact->isParent=0;
	      		$processing = true;
	    	}
	    	// - ends a folder
	    	else if ($currentLine == "-")
			{
				// pop current folder from stack
	      		$parentId = $stack[--$top];
	      		$processing = false;
			}
	    	else if ($currentLine == "" && $processing)
			{
	      		// Ok, we found an empty line, this means that we have
	      		// either found a folder or bookmark
	      		//
	      		// Attention! We will find an empty line just before a
	      		// folder seperator and just after! Make sure that we
	      		// are not processing the same bookmark twice (this is
	      		// why the boolean 'processing' is used.
	      		if ($currentContact->isParent == 1)
				{
	        		// place the current folder on the stack
	        		$stack [$top++] = $parentId;
					$currentContact->parentId=$parentId;
					$parentId=$contactOperations->
						addItem($userId, $currentContact);
	      		}
	      		else
				{
					$currentContact->parentId=$parentId;
					$contactOperations->addItem
						($userId, $currentContact);
	      		}
	      		$processing = false;
	    	}
	    	// Parse the actual bookmark content. Note that the
			// 'processing' boolean is not needed here, but hey....
			// it cannot hurt
	    	else if ($currentLine != "" && $processing)
			{
	      		// Parse for the string 'NAME='
	      		if ($stringUtils->startsWith ($currentLine, $name))
				{
	        		$currentName= $stringUtils->getProperty
						($currentLine, $name);
	        		$currentContact->name=$currentName;
	      		}
	      		// Parse for the string 'DESCRIPTION='
	      		else if ($stringUtils->startsWith
					($currentLine, $description))
				{
	        		$currentDescription = $stringUtils->getProperty
						($currentLine, $description);
	        		$currentContact->description=$currentDescription;
	      		}
	      		// Parse for the string 'URL='
	      		else if ($stringUtils->startsWith ($currentLine, $url))
				{
	        		$currentURL = $stringUtils->getProperty
						($currentLine, $url);
	        		$currentContact->locator=$currentURL;
	      		}
	      		// Parse for the string 'MAIL='
	      		else if ($stringUtils->startsWith ($currentLine, $mail))
				{
	        		$currentMail = $stringUtils->getProperty
						($currentLine, $mail);
					// This line might actually contain
					// multuple email addresses...
					// Additional parsing is needed
	        		$currentContact->email1=$currentMail;
	      		}
	      		// Parse for the string 'PHONE='
	      		else if ($stringUtils->startsWith
					($currentLine, $phone))
				{
	        		$currentPhone = $stringUtils->getProperty
						($currentLine, $phone);
					// This might also be telephone work,
					// but there is no way to determine the
					// difference
	        		$currentContact->tel_home=$currentPhone;
	      		}
	      		// Parse for the string 'FAX='
	      		else if ($stringUtils->startsWith ($currentLine, $fax))
				{
	        		$currentFax =
						$stringUtils->getProperty ($currentLine, $fax);
	        		$currentContact->faximile=$currentFax;
	      		}
	      		// Parse for the string 'ADDR='
	      		else if ($stringUtils->startsWith ($currentLine, $addr))
				{
	        		$currentAddress =
						$stringUtils->getProperty ($currentLine, $addr);
	        		$currentContact->address=$currentAddress;
	      		}
	      		else
				{
	        		// Ignore the rest.
	      		}
	    	}
	  	}
	  	fclose($fp);
	}


	/**
	 * Export users contacts in Opera format (starting from a certain
	 * Id)
	 *
	 * @param string id the identifier for the user
	 * @param integer parent the identifier for the parent id of the
	 * bookmarks (to enable recursive functioncall)
	 */
	function exportOperaContacts ($id, $parent, $callback)
	{
		$itemServices = $callback;
	  	$items = $itemServices->getChildren ($id, $parent);

		$newline="\n";
		$cnt_c = 0;
		$cnt_b = 0;
		for ($i=0;$i<count($items); $i++)
	  	{
			$currentItem = $items[$i];

	    	if ($currentItem->isParent == '1')
			{
	      		$cnt_c++;
	      		echo("#FOLDER$newline");
	      		echo("\tNAME=" . $currentItem->name . "$newline");
	      		echo("\tCREATED=$newline");
	      		echo("\tVISITED=0$newline");
	      		echo("\tORDER=$cnt_c$newline");
	      		echo("\tEXPANDED=YES$newline");
	      		echo("\tDESCRIPTION=".$currentItem->description.$newline.$newline);


	      		$this->exportOperaContacts($id, $currentItem->itemId, $callback);

	      		echo("$newline-$newline");
	    	}
	    	else
	    	{
	      		$cnt_b++;
	      		echo("#CONTACT$newline");
	      		echo("\tNAME=" . $currentItem->name . "$newline");
	      		echo("\tURL=" . $currentItem->locator . "$newline");
	      		echo("\tCREATED=$newline");
		  		echo("\tACTIVE=YES$newline");
		  		echo("\tPHONE=".$currentItem->tel_home.$newline);
		  		echo("\tFAX=".$currentItem->faximile.$newline);
		  		echo("\tPOSTALADDRESS=".$currentItem->address.$newline);
		  		echo("\tICON=Contact0$newline");
		  		echo("\tMAIL=".$currentItem->email1.$newline);
		  		// try to add things that are not understood by the
		  		// opera format in the description.
	      		echo("\tDESCRIPTION=". $currentItem->description);
		  		if (isset ($currentItem -> email2))
		  		{
		  			echo(" email2:".$currentItem->email2);
		  		}
		  		if (isset ($currentItem -> email3))
		  		{
		  			echo(" email3:".$currentItem->email3);
		  		}
		  		if (isset ($currentItem -> alias))
		  		{
		  			echo(" alias:".$currentItem->alias);
		  		}
		  		if (isset ($currentItem -> job))
		  		{
		  			echo(" job:".$currentItem->job);
		  		}
		  		if (isset ($currentItem -> mobile))
		  		{
		  			echo(" mobile:".$currentItem->mobile);
		  		}
		  		if (isset ($currentItem -> tel_work))
		  		{
		  			echo(" telephoneWork:".$currentItem->tel_work);
		  		}
		  		if (isset ($currentItem -> organization))
		  		{
		  			echo(" organization:".$currentItem->organization);
		  		}
		  		echo($newline.$newline);
	    	}
	  	}
	}

	function exportVCards ($id, $parent, $callback)
	{
		$itemServices = $callback;
		$items = $itemServices->getChildren ($id, $parent);

		$newline="\n";
  		for ($i=0;$i<count($items); $i++)
  		{
			$currentItem = $items[$i];

    		if ($currentItem->isParent == '1')
			{
				// folders are ignored in vcards.
				// process children anyway
      			$this->exportVCards
					($id, $currentItem->itemId, $callback);
			}
			else
			{
				$vCard = new File_IMC_Build_vCard ();
				$vCard->setFormattedName ($currentItem->name);
				$vCard->setName ('','', '', '', '');
				if (isset ($currentItem->email1))
				{
					$vCard->addEmail ($currentItem->email1);
					$vCard->addParam ('TYPE', 'WORK');
				}
				if (isset ($currentItem->email2))
				{
					$vCard->addEmail ($currentItem->email2);
					$vCard->addParam ('TYPE', 'HOME');
				}
				if (isset ($currentItem->email3))
				{
					$vCard->addEmail ($currentItem->email3);
					$vCard->addParam ('TYPE', 'OTHER');
				}
				if (isset ($currentItem->job))
				{
					$vCard->setTitle ($currentItem->job);
				}
				if (isset ($currentItem->tel_home))
				{
					$vCard->addTelephone ($currentItem->tel_home);
					$vCard->addParam ('TYPE', 'HOME');
				}
				if (isset ($currentItem->tel_work))
				{
					$vCard->addTelephone ($currentItem->tel_work);
					$vCard->addParam ('TYPE', 'WORK');
				}
				if (isset ($currentItem->organization))
				{
					$vCard->addOrganization
						($currentItem->organization);
				}
				if (isset ($currentItem->organizationalAddress))
				{
					$vCard->addAddress ($currentItem->organizationalAddress);
					$vCard->addParam ('TYPE', 'WORK');
				}
				if (isset ($currentItem ->alias))
				{
					$vCard->addNickname ($currentItem->alias);
				}
				if (isset ($currentItem->description))
				{
					$vCard->setNote ($currentItem->description);
				}
				if (isset ($currentItem->webaddress1))
				{
					$vCard->setUrl ($currentItem->webaddress1);
					$vCard->addParam ('TYPE', 'WORK');
				}
				echo ($vCard->fetch());
				echo ($newline.$newline);
			}
		}
	}

	function exportVCards2 ($id, $parent, $callback)
	{
		$itemServices = $callback;
		$items = $itemServices->getChildren ($id, $parent);

		$newline="\n";
  		for ($i=0;$i<count($items); $i++)
  		{
			$currentItem = $items[$i];
			//print_r($currentItem);
			//die();
    		if ($currentItem->isParent == '1')
			{
				// folders are ignored in vcards.
				// process children anyway
      			$this->exportVCards
					($id, $currentItem->itemId, $callback);
			}
			else
			{
				echo ("BEGIN:VCARD".$newline);
				echo ("VERSION:2.1".$newline);
				echo ("FN:".$currentItem->name.$newline);
				if ($currentItem->email1!="")
				{
					echo ("EMAIL;WORK;INTERNET:".$currentItem->email1.$newline);
				}
				if ($currentItem->email2!="")
				{
					echo ("EMAIL;HOME;INTERNET:".$currentItem->email2.$newline);
				}
				if ($currentItem->email3!="")
				{
					echo ("EMAIL;INTERNET:".$currentItem->email3.$newline);
				}
				if ($currentItem->webaddress1!="")
				{
					echo ("URL;type=pref:".$currentItem->webaddress1.$newline);
				}
				if ($currentItem->webaddress2!="")
				{
					echo ("URL;type=WORK:".$currentItem->webaddress2.$newline);
				}
				if ($currentItem->webaddress3!="")
				{
					echo ("URL;type=HOME:".$currentItem->webaddress3.$newline);
				}
				if ($currentItem->job!="")
				{
					echo ("TITLE:".$currentItem->job.$newline);
				}
				if ($currentItem->tel_home!="")
				{
					echo ("TEL;HOME;VOICE:".$currentItem->tel_home.$newline);
				}
				if ($currentItem->tel_work!="")
				{
					echo ("TEL;WORK;VOICE:".$currentItem->tel_work.$newline);
				}
				if ($currentItem->tel_mobile!="")
				{
					echo ("TEL;CELL;VOICE:".$currentItem->tel_mobile.$newline);
				}
				if ($currentItem->faximile!="")
				{
					echo ("TEL;WORK;FAX:".$currentItem->faximile.$newline);
				}
				if ($currentItem->organization!="")
				{
					echo ("ORG:".$currentItem->organization.$newline);
				}
				if ($currentItem->address!="")
				{
					$addr=ereg_replace("\r\n|\r|\n",";", $currentItem->address);
					$addr=ereg_replace("=0D=0A",";", $addr);
					echo ("ADR;HOME:".$addr.$newline);
				}
				if ($currentItem->org_address!="")
				{
					$org_addr=ereg_replace("\r\n|\r|\n",";", $currentItem->org_address);
					$org_addr=ereg_replace("=0D=0A",";", $org_addr);
					echo ("ADR;WORK:".$org_addr.$newline);
				}
				if ($currentItem ->alias!="")
				{
					echo ("NICKNAME:".$currentItem->alias.$newline);
				}
				if (isset ($currentItem->birthday))
				{
					echo ("BDAY:".$currentItem->birthday.$newline);
				}
				if ($currentItem->description!="")
				{
						echo ("NOTE:".$currentItem->description.$newline);
				}
				echo ("END:VCARD".$newline.$newline);
			}
		}
	}

	function importLDIF
		($userId, $userfile, $callback, $parentId, $visibility)
	{
		/*
			objectclass
					orgEmployee     Name of object class
				requires
					sn,             Data attributes required to
									define object class
					cn,
					mail,
					objectclass     All classes must inherit from a
									parent classes which eventually
									refer back to class "top"
				allows
					nickname,       Data attributes which are allowed
									but optional.
					usehtmlmail,
					o,
					l,
					givenname,
					sn,
					st,
					description,
					title,
					streetaddress,
					postalcode,
					c,
					telephonenumber,
					homephone,
					facsimiletelephonenumber,
					ou,
					pager,
					mobile,
					seeAlso


					dn	Distinguished Name (Unique key)
					cn	Common Name
					sn	Surename (Last name)
					bin	Binary
					boolean	true/false yes/no on/off
					cis	Case ignore string.
					(Case ignored during string comparisons)
					ces	Case exact string
					(Case must match during a string comparison)
					tel	Telephone number string.
					("-" and spaces ignored)
					int	Integer
					operational	Not displayed in search results
					dc	Domain component
					o	Organization name
					ou	Organization unit
					street	Street
					l	Locality
					st	State/Province
					c	Country
					aci	Access control information.
					(Netscape Directory server only)
					seeAlso	URL of info
					mail	e-Mail address
		*/
		$contactOperations = $callback;
	    $file = file($userfile);

		// As per rfc2849, an end of line may be CR, LF or both CR and LF
		// the "file" command above will only break the file on newlines
		// so we must break it on carriage returns ourselves
		$file = preg_replace('/\r/', "", $file);
		$lineCount = count($file);
		// Process the file
		$currentContact = null;
		for ($i=0; $i < $lineCount; $i++)
		{
			$currentLine = trim ($file[$i]);
			//die (print_r ($currentLine));
			// TODO, check for $file[$i] == '' -> flush
			if ($i == 0 || $currentLine == '')
			{
				// we already have processed lines, we have a
				// contact so we can write it
				if ($i != 0)
				{
					// Ok, examine the contact for names...
					if ($currentContact->getParameter ('givenname')
						!= null)
					{
						$currentContact->name=
							$currentContact->getParameter ('givenname');
					}
					else if ($currentContact->getParameter ('cn')
						!= null)
					{
						$currentContact->name=
							$currentContact->getParameter ('cn');
					}
					else if ($currentContact->getParameter ('sn')
						!= null)
					{
						$currentContact->name=
							$currentContact->getParameter ('sn');
					}
					else
					{
						$currentContact->name=
							$currentContact->getParameter ('dn');
					}
					$currentContact->address = '';
					if ($currentContact->getParameter ('street')
						!= null)
					{
						$currentContact->address .=
							$currentContact->getParameter ('street');
						$currentContact->address .= '    ';
					}
					if ($currentContact->getParameter ('postalcode')
						!= null)
					{
						$currentContact->address .=
							$currentContact->getParameter ('postalcode');
						$currentContact->address .= '    ';
					}
					if ($currentContact->getParameter ('l')
						!= null)
					{
						$currentContact->address .=
							$currentContact->getParameter ('l');
						$currentContact->address .= '    ';
					}
					if ($currentContact->getParameter ('st')
						!= null)
					{
						$currentContact->address .=
							$currentContact->getParameter ('st');
					}
					//die (print_r ($currentContact));
					$callback->addItem ($userId, $currentContact);
				}
				$currentContact = new Contact
					(null,null,null,null,
					null,null,null,null,
					null,null,null,null,null,
					null,null,null,null,
					null,null,null,null,null,
					null,null,null,null,null);
				$currentContact->visibility=$visibility;
				// no folders in LDIF?
				$currentContact->isParent=0;
				$currentContact->userId=$userId;
				$currentContact->parentId=$parentId;
				$currentContact->description = '';
			}
			// Items can span more lines. This is indicated
			// by a leading space on the next line. This is
			// handled by checking for space, and concatenate
			// the next line to the current as well as removing
			// this line from the array
			// is folded (as per rfc2849)
			while (ereg("^ ", $file[$i+1]))
			{
				$currentLine .= substr($file[$i+1], 1);
				// remove
				array_splice($file, $i+1, 1);
			}

			// Split each line in name/value pairs
			if (strpos ($currentLine, '::'))
			{
				// base64 encoding. For instance on tag jpegPhoto
				$tupel = explode ('::',  $currentLine, 2);
				$value = base64_decode (trim($tupel[1]));
			}
			else
			{
				$tupel = explode (':',  $currentLine, 2);
				$value = trim ($tupel[1]);
			}
			$name = strtolower (trim ($tupel[0]));
			// Examine current contents
			switch ($name)
			{
				// TODO Perhaps add a array of name/value
				// pairs to the item which can be used for
				// intermediate processing
				case 'postalcode':
				case 'l':
				case 'street':
				case 'st':
				case 'sn':
				case 'cn':
				case 'givenname':
					// No direct mapping. We first add them as
					// parameters and when we will actually add
					// the contact, we see which parts of the name
					// we have
					$currentContact->addParameter ($name, $value);
					break;
				case 'mail':
					$currentContact->email1 = $value;
					break;
				case 'uid':
				case 'userid':
					$currentContact->alias = $value;
					break;
				case 'description':
					$currentContact->description = $value;
					break;
				case 'telephonenumber':
				case 'homephone':
					$currentContact->tel_home = $value;
					break;
				case 'streetaddress':
					$currentContact->address = $value;
					break;
				case 'phone2':
					$addaddr['label'] .= 'phone2: ' . $value . '  ' ;
					break;
				case 'o':
				case 'organization':
					$currentContact->organization = $value;
					break;
				case 'facsimiletelephonenumber':
					$currentContact->faximile = $value;
					break;
				case 'mobile':
				case 'cellphone':
					$currentContact->mobile = $value;
					break;
				case 'homeurl':
					$currentContact->webaddress1 = $value;
					break;
				case 'objectclass':
				case 'modifytimestamp':
				case 'dn':
					// ignore. Examine where the dn is used for
					break;
				default:
					$currentContact->description .= '['.$name.'].';
					$currentContact->description .= $value.'    ';
					break;
			}
		}
	}


/**
* Palm CSV Export
Last Name
First Name
Title
Company
Work
Home
Fax
Other
E-Mail
Address
City
State
Zip Code
Country
Custom 1
Custom 2
Custom 3
Custom 4
Notes
*/
}
?>
