
	/*
	
	#############################################################################################
	#                                                                                           #
	#  DBHCMS - Web Content Management System                                                   #
	#                                                                                           #
	#############################################################################################
	#                                                                                           #
	#  COPYRIGHT NOTICE                                                                         #
	#  =============================                                                            #
	#                                                                                           #
	#  Copyright (C) 2005-2007 Kai-Sven Bunk (kaisven@drbenhur.com)                             #
	#  All rights reserved                                                                      #
	#                                                                                           #
	#  This file is part of DBHcms.                                                             #
	#                                                                                           #
	#  DBHcms is free software; you can redistribute it and/or modify it under the terms of     #
	#  the GNU General Public License as published by the Free Software Foundation; either      #
	#  version 2 of the License, or (at your option) any later version.                         #
	#                                                                                           #
	#  The GNU General Public License can be found at http://www.gnu.org/copyleft/gpl.html      #
	#  A copy is found in the textfile GPL.TXT                                                  #
	#                                                                                           #
	#  DBHcms is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;      #
	#  without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR         #
	#  PURPOSE. See the GNU General Public License for more details.                            #
	#                                                                                           #
	#  This copyright notice MUST APPEAR in ALL copies of the script!                           #
	#                                                                                           #
	#############################################################################################
	# $Id: contact.en.js 60 2007-02-01 13:34:54Z kaisven $                                      #
	#############################################################################################

	*/

	function check_entry(){
		
		var	Fullname = document.contactSendForm.contactName.value; 
		var EntryText = document.contactSendForm.contactText.value;
		var Email = document.contactSendForm.contactEmail.value;
		var Captcha = document.contactSendForm.contactCaptcha.value;
			
		var	cFullname = "";
		var cEntryText = "";
		var cEmail = "";
		var cCaptcha = "";
		var cURL = "";

		if (Fullname == "")
			var cFullname = "Please type your name!\n";
			
		if (EntryText == "")
			var cEntryText = "Please type some text!\n";
		
		if (Captcha == "")
			var cCaptcha = "Please enter the code!\n"
				
		if (document.contactSendForm.contactWebsite.value != ""){
			if (document.contactSendForm.contactWebsite.value != "http://"){
				var HpURL = /\w+:\/\/\w+/;    
				if (!document.contactSendForm.contactWebsite.value.match(HpURL)) {                           
					var cURL = "Invalid Homepage! \n";
				}
			}
		}
				
		if (cFullname!="" || cEntryText!="" || cEmail!="" || cURL!="" || cCaptcha!=""){
			alert(cFullname + cEntryText + cEmail + cURL + cCaptcha);
			return false;
		}
		else
		{
			return true;		
		}
	}