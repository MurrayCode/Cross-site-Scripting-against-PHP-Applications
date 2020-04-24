
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
	# $Id: validate.js 61 2007-02-01 14:17:59Z kaisven $                                        #
	#############################################################################################
	
	*/
	
	function validateStringId(fld, name) {
		
	    var isOk = true;
	    var illegalChars = /\W/;
	 	
	    if (fld.value == "") {
			isOk = false;
	        fld.style.background = 'Yellow'; 
	        alert( " �" + name + "� can not be empty.\n");
			fld.focus(); 
			fld.select();
	    } else if (illegalChars.test(fld.value)) {
			isOk = false;
	        fld.style.background = 'Yellow'; 
	        alert("Invalid entry for �" + name + "� !!!\nOnly letters, numbers, and underscores allowed.\n");
			fld.focus(); 
			fld.select();
	    } else {
	        fld.style.background = 'White';
	    } 
		
	    return isOk;
		
	}
	
	function validateInteger(fld, name) {
		
		var sText = fld.value;
		
	    var isOk = true;
	    var validChars = "0123456789";
		var aChar;
	 
	 	fld.style.background = 'White';
	 
	    for (i = 0; i < sText.length && isOk == true; i++) { 
      		aChar = sText.charAt(i); 
      		if (validChars.indexOf(aChar) == -1) {
         		isOk = false;
				fld.style.background = 'Yellow'; 
				alert("Invalid entry for �" + name + "� !!!\nOnly numbers allowed.\n");
				fld.focus(); 
				fld.select();
         	}
      	}
		
	    return isOk;
		
	}