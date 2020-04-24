
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
	# $Id: datesel.js 68 2007-05-31 20:28:17Z kaisven $                                         #
	#############################################################################################
	
	*/
	
	function checkDate(name) {
		
		// check to make sure that selected date is valid
		var SY = document.getElementById(name+'_year').selectedIndex;
		var SM = document.getElementById(name+'_month').selectedIndex;
		var SD = document.getElementById(name+'_day').selectedIndex;
		
		if (((SM == 3) || (SM == 5) || (SM == 8) || (SM == 10)) && (SD == 30)) {
			errorMsg = "Incorrect date entered. ";
			errorMsg = errorMsg + document.getElementById(name+'_month').options[SM].text;
			errorMsg = errorMsg + " only has 30 days."
			document.getElementById(name+'_day').options[0].selected = true;
			alert(errorMsg);
			return false;
		}
		
		// check February on leap years (only 29 days)
		var leapyear = false;
		var i = parseInt(document.getElementById(name+'_year').options[SY].value);
		
		// check for leapyear - Any year divisible by 4, except those divisible by 100 (but NOT 400)
		if ( (Math.floor(i/4) == (i/4)) && ((Math.floor(i/100) != (i/100)) || (Math.floor(i/400) == (i/400))) )
			leapyear = true;
		else 
			leapyear = false;
		
		if ( leapyear && (SM == 1) && (SD > 28) ){
			errorMsg = "Incorrect date entered.  February only has 29 days in ";
			errorMsg = errorMsg + document.getElementById(name+'_year').options[SY].value;
			document.getElementById(name+'_day').options[0].selected = true;
			alert(errorMsg);
			return false;
		}
		
		// check February for all other years (only 28 days)
		if ( (SM == 1) && (SD > 27) && (leapyear == false) ) {
			errorMsg = "Incorrect date entered.  February only has 28 days in ";
			errorMsg = errorMsg + document.getElementById(name+'_year').options[SY].value;
			document.getElementById(name+'_day').options[0].selected = true;
			alert(errorMsg);
			return false;
		}
	
		return true;
	
	}