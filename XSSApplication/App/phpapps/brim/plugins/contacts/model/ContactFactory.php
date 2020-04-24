<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/contacts/model/Contact.php');

/**
 * ContactFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.contacts
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactFactory extends ItemFactory
{
		/**
		 * Default constructor
		 */
		function ContactFactory ()
		{
			parent::ItemFactory ();
		}


		/**
		 * Returns the type of this specific item
		 * @return string the type of this specific item:
		 * <code>Contact</code>
		 */
		function getType ()
		{
			return "Contact";
		}

		/**
		 * Removes some characters from the input string
		 * These characters are: ';' and ','
		 * @private
		 *
		 * @param string input the input string
		 * @return string the modified string
		 * @todo move this function to the StringUtils class?
		 */
		function removeSpecialChars ($input)
		{
			$result = str_replace(';', '', $input);
			$result = str_replace(',', '', $result);
			$result = str_replace("'", '', $result);
			$result = str_replace('"', '', $result);
			return $result;
		}

		/**
		 * Factory method. Takes the input received from the vCard
		 * parsing class and returns an array of contacts
		 *
		 * @todo Move to util directory
		 * @param object vcards an array containing vCard information
		 * (more information:
		 * <a href=http://ciaweb.net/free/contact_vcard_parse/"
		 * >ciaweb.net/free/contact_vcard_parse/</a> )
		 * @return array an array of contacts
		 */
		function vcardsToContacts ($theVcards)
		{

			$vcards = $theVcards ['VCARD'];
			//print_r($vcards);
			// the resulting contacts
			$result = array ();
			// seperator used in the address
			//$sep = " ";

			// loop over the input array
			for ($i=0; $i<count($vcards); $i++)
			{

				$currentContact = $this->getEmptyItem ();

				$currentArray = $vcards[$i];

				// TBD TODO FIXME BARRY, what to do with the names,
				// we should perhaps
				// install some additional checks: if FN but !N etc
				$name = // $this->removeSpecialChars
					($currentArray['N'][0]['value'][0][0]);
				if (isset ($name) && trim($name) != "")
				{
					$currentContact->name =$name;
				}
				$name = //$this->removeSpecialChars
					($currentArray['FN'][0]['value'][0][0]);
				if (isset ($name) && trim($name) != "")
				{
					$currentContact->name = $name;
				}

				// CM - Separate EMAIL out into WORK, HOME & OTHER
				// Note Windows XP flags one email as PREF and does not flag
				// the other emails.
				// Assuming second email in vCard is work and third is other
				$emails = $currentArray['EMAIL'];
				if (isset ($emails) && count($emails) > 0)
				{
					for ($m=0; $m<count($emails); $m++)
					{
						$email = $emails[$m];
						//print_r($email);
						$values = $email['value'];
						//print $email['param']['TYPE'][1];
						if ($email['param']['TYPE'][1] == 'HOME'
							|| $email['param']['TYPE'][0] == 'PREF')
						{
							$currentContact->email1 = $values[0][0];
						}
						else if ($email['param']['TYPE'][1] == 'WORK'
							|| ($email['param']['TYPE'][0]=='INTERNET' && $m==1))
						{
							$currentContact->email2 = $values[0][0];
						}
						else
						{
							$currentContact->email3 = $values[0][0];
						}
					}
				}
				unset($emails);
				unset($email);
				unset($values);

				//
				$addresses = $currentArray['ADR'];
				if (isset ($addresses) && count($addresses) > 0)
				{

					for ($j=0; $j<count($addresses); $j++)
					{
						$addr = $addresses[$j];
						$values = $addr['value'];
						$theAddress = false;

						for($n=0; $n<count($values); $n++){
							if($values[$n][0]!=""){
								$values[$n][0] = preg_replace("/(\r\n|\n|\r)/",
									"", $values[$n][0]);
								$theAddress.=  $values[$n][0]."\n";
							}
						}

						if ($addr['param']['TYPE'][0] == 'WORK')
						{
							$currentContact->org_address = ($theAddress);
						}
						else if (strtolower ($addr['param']['TYPE'][0]) == 'home')
						{
							// KMail vCard export has lower case 'home'
							$currentContact->address = $theAddress;
						}
					}
				}
				unset($addresses);
				unset($addr);
				unset($values);
				unset($theAddress);


				// TELEPHONE SECTION

				$tel = $currentArray['TEL'];
				for ($k=0; $k<count($tel); $k++)
				{
					$currentTel = $tel[$k];
					if ($currentTel['param']['TYPE'][0] == 'WORK' )
					{
						// CM Add condition for no existence of 'VOICE' param
						// CM FIX: remove 's' from end of params, and extra [0]
						// array elements
						// CM TODO: Add flag to show preferred number
						// ([param][type][1]==pref)

						if (!isset($currentTel['param']['TYPE'][1]) ||
							($currentTel['param']['TYPE'][1] == 'VOICE'))
						{
							$currentContact->tel_work = $currentTel['value'][0][0];
						}
						else if (isset($currentTel['param']['TYPE'][1]) &&
							($currentTel['param']['TYPE'][1] == 'FAX'))
						{
							$currentContact->faximile = $currentTel['value'][0][0];
						}
					}
					else if ($currentTel['param']['TYPE'][0] == 'CELL')
					{
						$currentContact->mobile = $currentTel['value'][0][0];
					}
					else
					{
						$currentContact->tel_home = $currentTel['value'][0][0];
					}

				}
				unset($tel);
				unset($currentTel);

				// URL SECTION
				// CM - Separate URLS out into WORK, HOME & OTHER/non-existant
				// (homepage)
				$urls = $currentArray['URL'];
				if (isset ($urls) && count($urls) > 0)
				{
					for ($l=0; $l<count($urls); $l++)
					{
						$url = $urls[$l];
						//print_r($email);
						$values = $url['value'];
						//print $url['param']['TYPE'][0];
						if ($url['param']['TYPE'][0] == 'HOME')
						{
							$currentContact->webaddress3 = $values[0][0];
						}
						else if ($url['param']['TYPE'][0] == 'WORK')
						{
							$currentContact->webaddress2 = $values[0][0];
						}
						else
						{
							$currentContact->webaddress1 = $values[0][0];
						}
					}
				}
				unset($urls);
				unset($url);
				unset($values);

				$currentContact->organization =
					($currentArray['ORG'][0]['value'][0][0]);
				$currentContact->job =
					($currentArray['TITLE'][0]['value'][0][0]);
				$currentContact->alias =
					($currentArray['NICKNAME'][0]['value'][0][0]);
				$currentContact->description =
					($currentArray['NOTE'][0]['value'][0][0]);
			
				$result [] = $currentContact;
			}

			return $result;
		}

		/**
		 * Returns an empty item
		 * @return object an empty item, all values
		 * set to -null-
		 */
		function getEmptyItem ()
		{
			$item = new Contact (
				null, null, null, null,
				null, null, null, null,
				null, null, null, null,
				null, null, null, null,
				null, null, null, null,
				null, null, null, null,
				null, null, null);
			return $item;
		}


	function requestToItem ()
	{
		$itemId = $this->getFromPost ('itemId', 0);
		$owner = $_SESSION['brimUsername'];
		$parentId = $this->getFromPost ('parentId', 0);
		$isParent = $this->getFromPost ('isParent', 0);
		$name =
			$this->stringUtils->gpcStripSlashes ($_POST['name']);
		$description = $this->getFromPost ('description', null);
		$visibility = $this->getFromPost ('visibility', 'private');
		$category = $this->getFromPost ('category', null);
		$isDeleted = $this->getFromPost ('isDeleted', 0);
		$when_created = $this->getFromPost ('when_created', null);
		$when_modified = $this->getFromPost ('when_modified', null);
		$alias = $this->getFromPost ('alias', null);
		$address = $this->getFromPost ('address', null);
		// TODO
		$birthday = null;
		$mobile = $this->getFromPost ('mobile', null);
		$faximile = $this->getFromPost ('faximile', null);
		$telHome = $this->getFromPost ('tel_home', null);
		$telWork = $this->getFromPost ('tel_work', null);
		$organization = $this->getFromPost ('organization', null);
		$orgAddress = $this->getFromPost ('org_address', null);
		$job = $this->getFromPost ('job', null);
		$email1 = $this->getFromPost ('email1', null);
		$email2 = $this->getFromPost ('email2', null);
		$email3 = $this->getFromPost ('email3', null);
		$webaddress1 = $this->getFromPost ('webaddress1', null);
		$webaddress2 = $this->getFromPost ('webaddress2', null);
		$webaddress3 = $this->getFromPost ('webaddress3', null);
		$item = new Contact
			(
				$itemId,
				$owner,
				$parentId,
				$isParent,
				$name,
				$description,
				$visibility,
				$category,
				$isDeleted,
				$when_created,
				$when_modified,
				$alias,
				$address,
				$birthday,
				$mobile,
				$faximile,
				$telHome,
				$telWork,
				$organization,
				$orgAddress,
				$job,
				$email1,
				$email2,
				$email3,
				$webaddress1,
				$webaddress2,
				$webaddress3
			);
		return $item;
	}

	/**
	 * Factory method: Returns a database result into an item
	 *
	 * @param object result the result retrieved from the database
	 * @return array the items constructed from the database resultset
	 */
	function resultsetToItems ($result)
	{
		$items = array ();
		while (!$result->EOF)
		{
			$item = new Contact (
				$result->fields['item_id'],
				$result->fields['owner'],
				$result->fields['parent_id'],
				$result->fields['is_parent'],
				$this->stringUtils->gpcStripSlashes
					($result->fields['name']),
				$this->stringUtils->gpcStripSlashes
					($result->fields['description']),
				$result->fields['visibility'],
				$result->fields['category'],
				$result->fields['is_deleted'],
				$result->fields['when_created'],
				$result->fields['when_modified'],
				$this->stringUtils->gpcStripSlashes
					($result->fields['alias']),
				$this->stringUtils->gpcStripSlashes
					($result->fields['address']),
				$result->fields['birthday'],
				$result->fields['mobile'],
				$result->fields['faximile'],
				$result->fields['tel_home'],
				$result->fields['tel_work'],
				$this->stringUtils->gpcStripSlashes
					($result->fields['organization']),
				$this->stringUtils->gpcStripSlashes
					($result->fields['org_address']),
				$this->stringUtils->gpcStripSlashes
					($result->fields['job']),
				$result->fields['email1'],
				$result->fields['email2'],
				$result->fields['email3'],
				$result->fields['webaddress1'],
				$result->fields['webaddress2'],
				$result->fields['webaddress3']);
			$items [] = $item;
			$result->MoveNext();
		}
		return $items;
	}
}
?>
