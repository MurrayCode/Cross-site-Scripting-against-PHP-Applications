<?php

/**
 * The Request cast configuration class knows about the inner details 
 * of a class that needs to be casted.
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following 
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 * 
 * <pre> Enjoy :-) </pre>
 *
 * @author Michael Haussmann - 11/02/2004
 * @package org.brim-project.framework
 * @subpackage util.request
 *
 * @copyright Michael Haussmann
 *
 * @license http://opensource.org/licenses/gpl-license.php 
 * The GNU Public License
 * 
 * @todo Perhaps we can cut this into chunks that reside in the 
 * specific item derivative class and call these functions via a 
 * callback? This should be to have no notion about specific items 
 * outside their plugin directory (ok... and perhaps the menu 
 * structure :-)
 */
class RequestCastConfiguration 
{
	/**
	 * Returns the configuration mapping between a POST and an Object.
	 * (may be refactored as to use an XML config file).
	 *
	 *
	 * @return array
	 * This array is composed like this :
	 * - key : numeric
	 * - values :
	 * - name of the field
	 * - minimal required (1 means mandatory, more than 1 is for arrays)
	 * - maximal required (more than 1 is for arrays)
	 * - type, can be string (everything), alpha, digit, mail. 
	 * - error message if no match on min/max/type
	 * - default value
	 */
	function getFieldMapping($className)
	{
		switch(strtolower($className))
		{
			case strtolower("Bookmark"):
				$config[] = array(
					"name" => "itemId", 
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
		
				$config[] = array(
					"name" => "isParent", 
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");
					
				$config[] = array(
					"name" => "when_created", 
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name", 
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);		

				$config[] = array(
					"name" => "locator", 
					"field" => "locator", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "locatorMissing",
					"default" => null);		

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "type", 
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "Bookmark");
					
				// not used 
				// $config[] = array("category", 0, 1, "string", "", ull);
				// $_SESSION['username']
				return $config;
				//break;
			case strtolower("Contact"):
				$config[] = array(
					"name" => "itemId", 
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
					
				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "isParent", 
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");

				$config[] = array(
					"name" => "when_created", 
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name", 
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);		

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "email1", 
					"field" => "email1", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	
					
				$config[] = array(
					"name" => "email2", 
					"field" => "email2", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "email3", 
					"field" => "email3", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	
					
				$config[] = array(
					"name" => "webaddress1", 
					"field" => "webaddress1", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "webaddress2", 
					"field" => "webaddress2", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	
					
				$config[] = array(
					"name" => "webaddress3", 
					"field" => "webaddress3", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "alias", 
					"field" => "alias", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	
					
				$config[] = array(
					"name" => "birthday", 
					"field" => "birthday", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "mobile", 
					"field" => "mobile", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "faximile", 
					"field" => "faximile", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "tel_work", 
					"field" => "tel_work", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "tel_home", 
					"field" => "tel_home", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "address", 
					"field" => "address", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "organization", 
					"field" => "organization", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "org_address", 
					"field" => "org_address", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "job", 
					"field" => "job", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "type", 
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "Contact");
				return $config;
				//break;
			case strtolower("News"):
				$config[] = array(
					"name" => "itemId", 
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
					
				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "isParent", 
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");
					
				$config[] = array(
					"name" => "when_created", 
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name", 
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);		

				$config[] = array(
					"name" => "locator", 
					"field" => "locator", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "locatorMissing",
					"default" => null);		

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "type", 
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "News");
				return $config;
				//break;
			case strtolower("Event"):
				$config[] = array(
					"name" => "itemId", 
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
					
				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "isParent", 
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");
					
				$config[] = array(
					"name" => "when_created", 
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name", 
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);		

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "location", 
					"field" => "location", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "eventStartDate", 
					"field" => "eventStartDate", 
					"min" => 0,
					"max" => 1,
					"type" => "date",
					"message" => "startDateMissing",
					"default" => null);
					
				$config[] = array(
					"name" => "eventStartTime", 
					"field" => "eventStartTime", 
					"min" => 0,
					"max" => 1,
					"type" => "date",
					"message" => "startTimeMissing",
					"default" => null);
					
				$config[] = array(
					"name" => "eventEndDate", 
					"field" => "eventEndDate", 
					"min" => 0,
					"max" => 1,
					"type" => "date",
					"message" => "endDateMissing",
					"default" => null);
					
				$config[] = array(
					"name" => "eventEndTime", 
					"field" => "eventEndTime", 
					"min" => 0,
					"max" => 1,
					"type" => "date",
					"message" => "endTimeMissing",
					"default" => null);
					
				$config[] = array(
					"name" => "type", 
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "Event");
					
				return $config;
				//break;
			case strtolower("Note"):
				$config[] = array(
					"name" => "itemId",
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
					
				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "isParent",
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");
					
				$config[] = array(
					"name" => "when_created",
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name",
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);		

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "type",
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "Note");
					
				return $config;
				//break;
			case strtolower("Todo"):
				$config[] = array(
					"name" => "itemId", 
					"field" => "itemId", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
					
				$config[] = array(
					"name" => "parentId", 
					"field" => "parentId",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "isParent", 
					"field" => "isParent", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);
				
				$config[] = array(
					"name" => "visibility", 
					"field" => "visibility", 
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");
					
				$config[] = array(
					"name" => "when_created", 
					"field" => "when_created", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);
					
				$config[] = array(
					"name" => "when_modified", 
					"field" => "when_modified", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name", 
					"field" => "name", 
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);

				$config[] = array(
					"name" => "description", 
					"field" => "description", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);	

				$config[] = array(
					"name" => "priority", 
					"field" => "priority", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => "1");

				$config[] = array(
					"name" => "startDate", 
					"field" => "startDate", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "1");

				$config[] = array(
					"name" => "endDate", 
					"field" => "endDate", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "1");

				$config[] = array(
					"name" => "isFinished", 
					"field" => "isFinished", 
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => "0");

				$config[] = array(
					"name" => "status", 
					"field" => "status", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "");

				$config[] = array(
					"name" => "type",
					"field" => "type", 
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => "Todo");
					
				return $config;
				//break;
            case strtolower("Collections"):
				$config[] = array(
					"name" => "itemId",
					"field" => "item_id",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "parentId",
					"field" => "parent_id",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "isParent",
					"field" => "is_parent",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "visibility",
					"field" => "visibility",
					"min" => 0,
					"max" => 1,
					"type" => "alpha",
					"message" => "",
					"default" => "private");

				$config[] = array(
					"name" => "when_created",
					"field" => "when_created",
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "when_modified",
					"field" => "when_modified",
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "name",
					"field" => "name",
					"min" => 1,
					"max" => 1,
					"type" => "string",
					"message" => "nameMissing",
					"default" => null);

				$config[] = array(
					"name" => "description",
					"field" => "description",
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "deleted",
					"field" => "is_deleted",
					"min" => 0,
					"max" => 1,
					"type" => "string",
					"message" => "",
					"default" => null);

				$config[] = array(
					"name" => "isEntry",
					"field" => "is_entry",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "entryId",
					"field" => "entry_id",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "entryType",
					"field" => "entry_type",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				$config[] = array(
					"name" => "size",
					"field" => "size",
					"min" => 0,
					"max" => 1,
					"type" => "digit",
					"message" => "",
					"default" => 0);

				return $config;
                		//break;
		}
	}
}
?>
