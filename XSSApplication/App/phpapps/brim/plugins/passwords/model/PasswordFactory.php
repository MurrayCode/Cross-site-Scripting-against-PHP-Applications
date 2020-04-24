<?php

require_once ('framework/model/ItemFactory.php');
require_once ('plugins/passwords/model/Password.php');

/**
 * PasswordFactory
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - November 2004
 * @package org.brim-project.plugins.passwords
 * @subpackage model
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class PasswordFactory  extends ItemFactory
{
		/**
		 * Default constructor
		 */
		function PasswordFactory ()
		{
			parent::ItemFactory ();
		}

		/**
		 * Returns the type of this specific item
		 *
		 * @return string the type of this specific
		 * item: <code>Password</code>
		 */
		function getType ()
		{
			return "Password";
		}

		/**
		 * Factory method. Return an HTTP request into an item by
		 * fecthing the appropriate parameters from the POST request
		 *
		 * @return object the item constructed from the POST request
		 * @uses the POST request
		 */
		function requestToItem ()
		{
			$phrase = $this->getFromPost ('passPhrase', null);
			$iv_size = mcrypt_get_iv_size
				(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);

			$itemId = $this->getFromPost ('itemId', 0);
			$parentId = $this->getFromPost ('parentId', 0);
			$isParent = $this->getFromPost ('isParent', 0);
			$isDeleted = $this->getFromPost ('isDeleted', 0);
			$name = $this->getFromPost ('name', null);
			$when_created = $this->getFromPost ('when_created', null);
			$when_modified = $this->getFromPost ('when_modified', null);
			$visibility = $this->getFromPost ('visibility', 'private');
			$category = $this->getFromPost ('category', null);
			$description = $this->getFromPost ('description', null);
			$description = mcrypt_encrypt (MCRYPT_RIJNDAEL_256,
				$phrase, $description,  MCRYPT_MODE_ECB, $iv);
			if (!isset ($description) || $description == '')
			{
				$description = '-';
			}
			$login = $this->getFromPost ('login', null);
			if (!isset ($login) || $login == '')
			{
				$login = '-';
			}
			$login = mcrypt_encrypt (MCRYPT_RIJNDAEL_256,
				$phrase, $login,  MCRYPT_MODE_ECB, $iv);
			$password = $this->getFromPost ('password', null);
			if (!isset ($password) || $password == '')
			{
				$password = '-';
			}
			$password = mcrypt_encrypt (MCRYPT_RIJNDAEL_256,
				$phrase, $password,  MCRYPT_MODE_ECB, $iv);
			$url = $this->getFromPost ('url', null);
			if (!isset ($url) || $url == '')
			{
				$url = '-';
			}
			$url = mcrypt_encrypt (MCRYPT_RIJNDAEL_256,
				$phrase, $url,  MCRYPT_MODE_ECB, $iv);

			$item = new Password
			(
					$itemId,
					$_SESSION['brimUsername'],
					$parentId,
					$isParent,
					$name,
					$description,
					$visibility,
					$category,
					$isDeleted,
					$when_created,
					$when_modified,
					$login,
					$password,
					$url
			);
			return $item;
		}

		function resultsetToItems ($result, $phrase=null)
		{
			//$phrase = 'Testje';
			$iv_size = mcrypt_get_iv_size
				(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$items = array ();
			//die (print_r ($result));
			while (!$result->EOF)
			{
				//
				// In previous versions of Brim, the password field was not set
				// first check if field != null and only decrypt if this is the case
				//
				if (!isset ($result->fields['password']) || $result->fields['password']==null)
				{
					$password = null;
				}
				else
				{
					$password = trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $result->fields['password'],
							MCRYPT_MODE_ECB, $iv));
				}
				$item = new Password
				(
					$result->fields['item_id'],
					trim ($result->fields['owner']),
					$result->fields['parent_id'],
					$result->fields['is_parent'],
					trim ($result->fields['name']),
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $result->fields['description'],
							MCRYPT_MODE_ECB, $iv)),
					trim ($result->fields['visibility']),
					trim ($result->fields['category']),
					$result->fields['is_deleted'],
					$result->fields['when_created'],
					$result->fields['when_modified'],
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $result->fields['login'],
							MCRYPT_MODE_ECB, $iv)),
					$password,
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $result->fields['url'],
							MCRYPT_MODE_ECB, $iv))
				);
				$items [] = $item;
				$result->MoveNext();
			}
			return $items;
		}


		function requestToItemErrors ()
		{
			$errors = array ();

			if (!isset ($_POST['name']) || ($_POST['name'] == ''))
			{
				$errors [] =  "nameMissing";
			}
			if (!isset ($_POST['passPhrase']) || ($_POST['passPhrase'] == ''))
			{
				if (!$_POST['isParent'])
				{
					$errors [] =  "passPhraseMissing";
				}
			}
			return $errors;
		}

		function decode ($item, $phrase=null)
		{
			$iv_size = mcrypt_get_iv_size
				(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$decoded = $item;
			$decoded->login =
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $item->login,
							MCRYPT_MODE_ECB, $iv));
			$decoded->password =
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $item->password,
							MCRYPT_MODE_ECB, $iv));
			$decoded->url =
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $item->url,
							MCRYPT_MODE_ECB, $iv));
			$decoded->description =
					trim (mcrypt_decrypt (MCRYPT_RIJNDAEL_256,
						$phrase, $item->description,
							MCRYPT_MODE_ECB, $iv));
			return $decoded;
		}
}
?>