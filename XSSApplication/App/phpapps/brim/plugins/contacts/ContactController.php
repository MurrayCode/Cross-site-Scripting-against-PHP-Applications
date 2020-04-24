<?php

require_once ('framework/ItemController.php');
require_once ('ext/File/IMC/Parse/vCard.php');
require_once ('plugins/contacts/util/ContactUtils.php');
require_once ('plugins/contacts/model/ContactServices.php');
require_once ('plugins/contacts/model/ContactPreferences.php');

/**
 * The Contact Controller
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - July 2003
 * @package org.brim-project.plugins.contacts
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
class ContactController extends ItemController
{
	/**
	 * Constructor.
	 * Makes sure that the appropriate operations are instantiated.
	 * @param string renderEngineName the render engine that is used
	 * (either Smarty or phpSavant)
	 */
	function ContactController ()
	{
		parent::ItemController ();
		$this->operations = new ContactServices ();
		$this->preferences = new ContactPreferences ();
		$this->itemFactory = new ContactFactory ();

		$this->pluginName = 'contacts';
		$this->title = 'Brim - Contacts';
		$this->itemName = 'Contact';
		$this->expandName = 'contactExpand';
	}

	/**
 	 * Returns the actions defined for this item only
 	 *
 	 * @return array an array of item specific actions
	 * (like search, import etc.)
 	 */
	function getActions ()
	{
		//
		// Actions
		//
		$actions[0]['name']='actions';
		$actions[0]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=add&amp;parentId='.$this->getParentId (),
						'name' => 'add');
		$actions[0]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=multipleSelectPre',
						'name' => 'multipleSelect');
		$actions[0]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=import&amp;parentId='.$this->getParentId (),
						'name' => 'importTxt');
		$actions[0]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=export&amp;parentId='.$this->getParentId (),
						'name' => 'exportTxt');
		$actions[0]['contents'][]=
	    			array('href' => 'index.php?plugin=contacts&amp;action=search',
	        			'name' => 'search');
		//
		// If we are simply 'viewing' an item, add 'modify' as action
		// to the action list
		//
		if ($this->renderEngine->getTokens ('viewAction') == 'show')
		{
			$actions[0]['contents'][]= array (
				'href'=>'index.php?plugin=contacts&amp;action=modify&amp;itemId='.$this->itemId,
				'name'=>'modify');
		}
        $actions[0]['contents'][] = array(
            'href' => 'index.php?plugin=contacts&amp;action=findDoubles',
            'name' => 'findDoubles'
            );

		//
		// View
		//
		$actions[1]['name']='view';
		if (!isset ($_SESSION['brimEnableAjax']) || ($_SESSION['brimEnableAjax'] == 'false'))
		{
			$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;expand=*',
						'name' => 'expand');
			$actions[1]['contents'][]=
						array('href' => 'index.php?plugin=contacts&amp;expand=0',
						'name' => 'collapse');
			$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=setYahooTree&amp;parentId='.$this->getParentId (),
						'name' => 'yahooTree');
			$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=setExplorerTree&amp;parentId='.$this->getParentId (),
						'name' => 'explorerTree');
			$actions[1]['contents'][]=
					array('href' => 'index.php?plugin=contacts&amp;action=setLineBasedTree&amp;parentId='.$this->getParentId (),
						'name' => 'lineBasedTree');
		}
		$actions[1]['contents'][] =
					array('href' => 'index.php?plugin=contacts&amp;action=setModePublic&amp;parentId='.$this->getParentId (),
						'name' => 'setModePublic');
		$actions[1]['contents'][] =
					array('href' => 'index.php?plugin=contacts&amp;action=setModePrivate&amp;parentId='.$this->getParentId (),
					'name' => 'setModePrivate');
		//
		// Sort
		//
		if (!isset ($_SESSION['brimEnableAjax']) || ($_SESSION['brimEnableAjax'] == 'false'))
		{
			$actions[2]['name']='sort';
			$actions[2]['contents'][]=
					array('href'=>'index.php?plugin=contacts&amp;action=sort&amp;order=ASC&amp;field=alias',
						'name'=> 'alias');
			$actions[2]['contents'][]=
					array('href'=>'index.php?plugin=contacts&amp;action=sort&amp;order=ASC&amp;field=email1',
						'name'=> 'email');
			$actions[2]['contents'][]=
					array('href'=>'index.php?plugin=contacts&amp;action=sort&amp;order=ASC&amp;field=organization',
						'name'=> 'organization');
		}

		//
		// Preferences
		//
		$actions[3]['name']='preferences';
		$actions[3]['contents'][]=
					array ('href'=>'index.php?plugin=contacts&amp;action=modifyPreferencesPre',
						'name'=>'modify');

		$actions[4]['name']='help';
		$actions[4]['contents'][]=
			array ('href'=>'index.php?plugin=contacts&amp;action=help',
					'name'=>'help'
			);
		return $actions;
	}

	/**
	 * Activate. Basically this means that the appropriate actions
	 * are executed and an optional result is returned
	 * to be processed/displayed
	 */
	function activate ()
	{
		switch ($this->getAction ())
		{
			case "help":
				$this->helpAction ();
				break;
			case "add":
				$this->addAction ();
				break;
			case "addAndAddAnother":
                                $this->addItemAction ();
                                unset ($this->renderObjects);
                                $this->addAction ();
                                break;
            case "findDoubles":
                $all = $this->operations->getItems ($this->getUserName());
                usort ($all, array ('Contact', 'equals'));
                $this->renderObjects = $this->operations->findDoubles ($all);
                $this->renderer = 'showContacts';
				break;
            case "showTrash":
                $this->renderObjects=$this->operations->getTrashedItems
                    ($this->getUserName ());
                $this->renderer = 'trash';
                break;
            case "deleteForever":
                foreach ($_POST as $submitted=>$value)
                {
                    if (substr ($submitted, 0, strlen ('itemid_')) == 'itemid_')
                    {
                        if ($value=='on')
                        {
                            //die (print_r ("Deleting ".substr ($submitted, strlen ('itemid_'))));
                            $this->operations->deleteItem
                                ($this->getUsername (), substr ($submitted, strlen ('itemid_')));
                        }
                    }
                }
                $this->getShowItemsParameters ();
                break;
            case "undelete":
                foreach ($_POST as $submitted=>$value)
                {
                    if (substr ($submitted, 0, strlen ('itemid_')) == 'itemid_')
                    {
                        if ($value=='on')
                        {
                            $this->operations->unTrash
                                ($this->getUsername (), substr ($submitted, strlen ('itemid_')));
                        }
                    }
                }
                $this->renderEngine->assign ('trashCount',
                    $this->operations->getTrashCount ($this->getUserName ()));
                $this->getShowItemsParameters ();
                break;
			case "showItem":
				$this->showAction ();
				break;
			case "modify":
				$this->modifyAction ();
				break;
			case "deleteItemPost":
				$this->deleteItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "searchContacts":
				$this->searchItemAction ();
				break;
			//
			// Added by Michael. Default navigation mode.
			// If used, change in other plugins.
			//
			case "setModePublic":
				$_SESSION['navigationMode']='public';
				$this->navigationMode ='public';
				$this->getShowItemsParameters ();
				break;
			case "setModePrivate":
				$_SESSION['navigationMode']='private';
				$this->navigationMode ='private';
				$this->getShowItemsParameters ();
				break;
			case "search":
			case "export":
			case "import":
				$this->renderer = $this->getAction().'Contacts';
				break;
			case "addItemPost":
				$this->addItemAction ();
				break;
			case "modifyItemPost":
				$this->modifyItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "move":
				$this->moveAction ();
				break;
			case "moveItem":
				$this->moveItemAction ();
				$this->getShowItemsParameters ();
				break;
			case "importContacts":
				$importType = $_POST['importType'];
				$importFile = $_FILES['importFile'];
				$visibility = $_POST['visibility'];
				$parentId = $_POST['parentId'];
				error_reporting (0);
				if ($importType == 'VCard')
				{
					$parser = new File_IMC_Parse_vCard ();
					$result = $parser->fromFile
						($importFile['tmp_name']);
					$contacts = $this->itemFactory->vcardsToContacts
						($result);
					for ($i=0; $i<count($contacts); $i++)
					{
						$contacts[$i]->parentId = $parentId;
						$contacts[$i]->visibility=$visibility;
						$this->operations->addItem
							($this->getUserName(), $contacts[$i]);
					}
				}
				else if ($importType == 'Opera')
				{
					$contactUtils = new ContactUtils ();
					$contactUtils->importOperaContacts
						($this->getUserName(),
							$importFile ['tmp_name'],
							$this, $parentId, $visibility);
				}
				else if ($importType == 'LDIF')
				{
					$contactUtils = new ContactUtils ();
					$contactUtils->importLDIF
						($this->getUserName(),
							$importFile ['tmp_name'],
							$this, $parentId, $visibility);
				}
				else
				{
					die ('Unsupported import :-'.$importType.'-');
				}
		        $this->getShowItemsParameters ();
				break;
			case "exportContacts":
					$contactUtils = new ContactUtils ();
					$exportType = $_POST['exportType'];
					$parentId = $_POST['parentId'];
					if ($exportType == 'Opera')
					{
						Header ("Content-type: text/plain");
						header('Content-Disposition: attachment; filename="brim-opera-contacts.xml"');
						echo ("Opera Hotlist version 2.0\n\n");

						$contactUtils ->exportOperaContacts
								($this->getUserName(),
								$parentId, $this);
					}
					else if ($exportType == 'VCard')
					{
						Header ("Content-type: text/plain");
						header('Content-Disposition: attachment; filename="brim-vcard-contacts.vcf"');
						$contactUtils -> exportVCards2
								($this->getUserName(),
									$parentId, $this);
					}
					else
					{
							die ('Unsupported type: '.$exportType);
					}
					exit ();
/*
To be removed in version 2.0
*/
			case "setYahooTree":
				$_SESSION['contactTree']='Yahoo';
				$this->getShowItemsParameters ();
				break;
			case "setExplorerTree":
				$_SESSION['contactTree']='Explorer';
				$this->getShowItemsParameters ();
				break;
			case "setLineBasedTree":
				$_SESSION['contactTree']='LineBased';
				$this->getShowItemsParameters ();
				break;
			case "sort":
				$this->sortAction ($this->parentId, $_GET['field'],  $_GET['order']);
//				$this->sortAllAction ($_GET['field'],  $_GET['order']);
				break;
/*
End
*/
			case "modifyPreferencesPre":
				$this->renderObjects =
					$this->preferences->getAllPreferences
					($this->getUserName ());
				$this->renderer = 'modifyPreferences';
				break;
			case "modifyPreferencesPost":
				$this->renderer = 'showContacts';

				$this->preferences->setPreference
					($this->getUserName (), $_POST['name'],
						$_POST['value']);
				$_SESSION['contactTree']=
					$this->preferences->getPreferenceValue
					($this->getUserName (), 'contactTree');
				$_SESSION['contactOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (), 'contactOverlib');
				$_SESSION['contactYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'contactYahooTreeColumnCount');
				//$this->getShowItemsParameters ();
				$this->renderer = 'modifyPreferences';
				$this->renderObjects=$_SESSION;
				break;
			case "multipleSelectPre":
				$_SESSION['contactExpand']='*';
				$this->multipleSelectAction ();
				break;
			case "multipleSelectPost":
				$itemIds = array_keys ($_POST, "itemId");
				if (isset ($_POST['move']))
				{
					$this->moveMultipleAction ($itemIds);
				}
				else if (isset ($_POST['delete']))
				{
					foreach ($itemIds as $itemId)
					{
						$this->operations->deleteItem
							($this->getUserName(), $itemId);
					}
					$this->getShowItemsParameters ();
				}
				break;
			case "moveMultipleItemsPost":
				$itemIds = explode (",", $_GET['itemIds']);
				$parentId = $_GET['parentId'];
				foreach ($itemIds as $itemId)
				{
					$this->operations->moveItem
						($this->getUserName(), $itemId, $parentId);
				}
				$this->getShowItemsParameters ();
				break;
			default:
				if (!isset ($_SESSION['contactTree']))
				{
					$_SESSION['contactTree']=
						$this->preferences->getPreferenceValue
							($this->getUserName (), 'contactTree');
				}
				if (!isset ($_SESSION['contactOverlib']))
				{
					$_SESSION['contactOverlib']=
						$this->preferences->getPreferenceValue
							($this->getUserName (), 'contactOverlib');
				}
				if (!isset ($_SESSION['contactYahooTreeColumnCount']))
				{
					$_SESSION['contactYahooTreeColumnCount']=
						$this->preferences->getPreferenceValue
							($this->getUserName (),
								'contactYahooTreeColumnCount');
				}
				$this->getShowItemsParameters ();
				break;
			}
	}
}
?>
