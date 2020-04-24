
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
	# $Id: news.de.js 60 2007-02-01 13:34:54Z kaisven $                                         #
	#############################################################################################

	*/
	
	function check_comment(){
		
		var	Fullname = document.newsCommentForm.newsName.value; 
		var EntryText = document.newsCommentForm.newsText.value;
		var Email = document.newsCommentForm.newsEmail.value;
			
		var	cFullname = "";
		var cEntryText = "";
		var cEmail = "";
		var cURL = "";

		if (Fullname == "")
			var cFullname = "Gib bitte dein Name ein!\n";
			
		if (EntryText == "")
			var cEntryText = "Gib bitte ein text ein!\n";
				
		if (document.newsCommentForm.newsHomepage.value != ""){
			if (document.newsCommentForm.newsHomepage.value != "http://"){
				var HpURL = /\w+:\/\/\w+/;    
				if (!document.newsCommentForm.newsHomepage.value.match(HpURL)) {                           
					var cURL = "Ung�ltige Homepage! \n";
				}
			}
		}
				
		if (cFullname!="" || cEntryText!="" || cEmail!="" || cURL!=""){
			alert(cFullname + cEntryText + cEmail + cURL);
			return false;
		}
		else
		{
			return true;		
		}
	}