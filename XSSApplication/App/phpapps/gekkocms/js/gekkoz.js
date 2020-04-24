//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
// Baby Gekko content management system - Copyright (C) Baby Gekko, Inc.
// http://www.babygekko.com
// This is a SHARED SOURCE, NOT OPEN SOURCE (GPL).
// You may use this software commercially, but you are not allowed to create a fork or create a derivative of this software
// Please read the license for details
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++//
YAHOO.namespace('gekko');
var Gekko = YAHOO.gekko;
//==================================================================================================================
var $gid = YAHOO.util.Dom.get;
var $gclass = YAHOO.util.Dom.getElementsByClassName;
var $cookie = YAHOO.util.Cookie;
var $extend_class = YAHOO.lang.extend;
var $gekkoapps = [];

//==================================================================================================================
var $onload = function (function_name)
{
	YAHOO.util.Event.addListener(window, "load", function_name);	
}
//==================================================================================================================
var $ondomready = function (function_name)
{
    YAHOO.util.Event.onDOMReady(function_name);
}
//==================================================================================================================
var $register_gekko_app = function(appname, obj) // this is for v1.2 actually
{
    $gekkoapps[appname] = obj; 
}
//==================================================================================================================
Gekko.FormValidation = function ()
{}
//==================================================================================================================
Gekko.FormValidation.Initialize = function()
{
	var frms = document.getElementsByTagName('form');
	var gekko_validator = new Gekko.FormValidation();
	for (var i = 0; i < frms.length; i++)
	{
		YAHOO.util.Event.addListener(frms[i], "submit", gekko_validator.checkForm, gekko_validator, true);
	}
}
//==================================================================================================================
Gekko.FormValidation.prototype.getLabelForInput = function (e, id)
{
	var labels = document.getElementsByTagName("LABEL");
	var lookup = {};
	for (var i = 0; i < labels.length; i++);
	{
	  lookup[labels[i].htmlFor] = label;
	}
}
//==================================================================================================================
Gekko.FormValidation.prototype.checkForm = function (e, id)
{
	var errs = new Array();
        var i=0;
        
	//this function is called when a form is submitted.
	if (typeof (e) == "string")
	{
		//the id was supplied, get the object reference
		e = this._get_element_by_id(e);
		if (!e)
		{
			return true;
		}
	}

	var elm = e;
	if (!e.nodeName)
	{
		//was fired by yahoo
		elm = (e.srcElement) ? e.srcElement : e.target;
	}
	if (elm.nodeName.toLowerCase() != 'form')
	{
		elm = this.searchUp(elm, 'form');
	}

	var all_valid = true;

	//access form elements
	//inputs
	var f_in = elm.getElementsByTagName('input');
	//selects
	var f_sl = elm.getElementsByTagName('select');
	//textareas
	var f_ta = elm.getElementsByTagName('textarea');

	//check inputs
	for (i = 0; i < f_in.length; i++)
	{
		if (f_in[i].type.toLowerCase() != 'submit' && f_in[i].type.toLowerCase() != 'button' && f_in[i].type.toLowerCase() != 'hidden')
		{
			if (this.isVisible(f_in[i]))
			{

				var cname = ' ' + f_in[i].className.replace(/^\s*|\s*$/g, '') + ' ';
				cname = cname.toLowerCase();
				var inv = f_in[i].value.trim();
				var t = f_in[i].type.toLowerCase();
				var cext = '';

				if (t == 'text' || t == 'password')
				{
					//text box
					var valid = this.checkField(cname, f_in[i]);
					
				}
				else if (t == 'radio' || t == 'checkbox')
				{
					// radio or checkbox
					var valid = this.checkRadioOrCheckBoxes(cname, f_in[i], f_in);
					cext = '-cr';
				}
				else
				{
					var valid = true;
				}

				if (valid)
				{
					this.removeClassName(f_in[i], 'validation-failed' + cext);
					this.addClassName(f_in[i], 'validation-passed' + cext);
				}
				else
				{
					//prana
					if (cext == '-cr')
					{
						this.removeClassName(f_in[i].parentNode, 'validation-passed' + cext);
						this.addClassName(f_in[i].parentNode, 'validation-failed' + cext);
					}
					else
					{
						this.removeClassName(f_in[i], 'validation-passed' + cext);
						this.addClassName(f_in[i], 'validation-failed' + cext);
					}
					//try to get title
					if (f_in[i].getAttribute('title'))
					{
						errs[errs.length] = f_in[i].getAttribute('title');
					}
					all_valid = false;
				}
			}
		}
	} //end for i
	//check textareas
	for (i = 0; i < f_ta.length; i++)
	{
		if (this.isVisible(f_ta[i]))
		{
			var cname = ' ' + f_ta[i].className.replace(/^\s*|\s*$/g, '') + ' ';
			cname = cname.toLowerCase();
			var valid = this.checkField(cname, f_ta[i]);

			if (valid)
			{
				this.removeClassName(f_ta[i], 'validation-failed');
				this.addClassName(f_ta[i], 'validation-passed');
			}
			else
			{
				this.removeClassName(f_ta[i], 'validation-passed');
				this.addClassName(f_ta[i], 'validation-failed');
				//try to get title
				if (f_ta[i].getAttribute('title'))
				{
					errs[errs.length] = f_ta[i].getAttribute('title');
				}
				all_valid = false;
			}
		}
	} //end for i
	//check selects
	for (i = 0; i < f_sl.length; i++)
	{
		if (this.isVisible(f_sl[i]))
		{
			var cname = ' ' + f_sl[i].className.replace(/^\s*|\s*$/g, '') + ' ';
			cname = cname.toLowerCase();
			var valid = this.checkDropDownSelection(cname, f_sl[i]);
			if (valid)
			{
				this.removeClassName(f_sl[i], 'validation-failed-sel');
				this.addClassName(f_sl[i], 'validation-passed-sel');
			}
			else
			{
				this.removeClassName(f_sl[i], 'validation-passed-sel');
				this.addClassName(f_sl[i], 'validation-failed-sel');
				//try to get title
				if (f_sl[i].getAttribute('title'))
				{
					errs[errs.length] = f_sl[i].getAttribute('title');
				}
				all_valid = false;
			}
		}
	} //end for i

	if (!all_valid)
	{
		if (errs.length > 0)
		{
			alert("We have found the following error(s):\n\n  * " + errs.join("\n  * ") + "\n\nPlease check the fields and try again");
		}
		else
		{
			alert('Some required values are not correct. Please check the items in red.');
		}
		YAHOO.util.Event.stopEvent(e);
	}
	return all_valid;
}
//==================================================================================================================
Gekko.FormValidation.prototype.checkField = function (c, e)
{
	var valid = true;
	var t = e.value.trim();

	//search for required
	if (c.indexOf(' required ') != -1 && t.length == 0)
	{
		//required found, and not filled in
		valid = false;
	}

	//check length
	if (c.indexOf(' required ') != -1)
	{
		//check for minlength.
		var m = e.getAttribute('minlength');
		if (m && Math.abs(m) > 0)
		{
			if (e.value.length < Math.abs(m))
			{
				valid = false;
			}
		}
	}

	//search for validate-
	if (c.indexOf(' validate-number ') != -1 && isNaN(t) && t.match(/[^\d]/))
	{
		//number bad
		valid = false;
	}
	else if (c.indexOf(' validate-digits ') != -1 && t.replace(/ /, '').match(/[^\d]/))
	{
		//digit bad
		valid = false;
	}
	else if (c.indexOf(' validate-alpha ') != -1 && !t.match(/^[a-zA-Z]+$/))
	{
		//alpha bad
		valid = false;
	}
	else if (c.indexOf(' validate-alphanum ') != -1 && t.match(/\W/))
	{
		//alpha bad
		valid = false;
	}
	else if (c.indexOf(' validate-filename ') != -1 && !t.match(/^[a-zA-Z0-9_\-.]+$/))
	{ // prana
		//filename bad
		valid = false;
	}
	else if (c.indexOf(' validate-date ') != -1)
	{
		var d = new date(t);
		if (isNaN(d))
		{
			//date bad
			valid = false;
		}
	}
	else if (c.indexOf(' validate-email ') != -1 && !t.match(/\w{1,}[@][\w\-]{1,}([.]([\w\-]{1,})){1,3}$/))
	{
		//email bad
		valid = false;
		if (c.indexOf(' required ') == -1 && t.length == 0)
		{
			valid = true;
		}
	}
	else if (c.indexOf(' validate-url ') != -1 && !t.match(/^(http|https|ftp):\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)(:(\d+))?\/?/i))
	{
		//url bad
		valid = false;
	}
	else if (c.indexOf(' validate-http-domain ') != -1 && !t.match("^(http|https)?:(//[^/]+)+$(:\d+)?\/?$"))
	{
		//url bad
		valid = false;
		if (c.indexOf(' required ') == -1 && t.length == 0) valid = true;

	}
	else if (c.indexOf(' validate-https-domain ') != -1 && !t.match("^(https)?:(//[^/]+)+$(:\d+)?\/?$"))
	{
		//url bad
		valid = false;
		if (c.indexOf(' required ') == -1 && t.length == 0) valid = true;

	}
	else if (c.indexOf(' validate-date-au ') != -1 && !t.match(/^(\d{2})\/(\d{2})\/(\d{4})$/))
	{
		valid = false;
	}
	else if (c.indexOf(' validate-currency-dollar ') != -1 && !t.match(/^\$?\-?([1-9]{1}[0-9]{0,2}(\,[0-9]{3})*(\.[0-9]{0,2})?|[1-9]{1}\d*(\.[0-9]{0,2})?|0(\.[0-9]{0,2})?|(\.[0-9]{1,2})?)$/))
	{
		valid = false;
	}
	else if (c.indexOf(' validate-regex ') != -1)
	{
		var r = RegExp(e.getAttribute('regex'));
		if (r && !t.match(r))
		{
			valid = false;
		}
	}

	return valid;
}

//==================================================================================================================
//  checkRadioOrCheckBoxes
//	c = className
//	e = this element, radio or checkbox
//	f = input fields dom element
//------------------------------------------------------------------------------------------------------------------
Gekko.FormValidation.prototype.checkRadioOrCheckBoxes = function (c, e, f)
{
	var valid = true;

	//search for required
	if (c.indexOf(' validate-one-required ') != -1)
	{
		//required found
		//check if other checkboxes or radios have been selected.
		valid = false;
		for (var i = 0; i < f.length; i++)
		{
			if (f[i].name.toLowerCase() == e.name.toLowerCase() && f[i].checked)
			{
				valid = true;
				break;
			}
		}
	}

	return valid;
}

//==================================================================================================================
Gekko.FormValidation.prototype.checkDropDownSelection = function (c, e)
{
	var valid = true;
	//search for validate-
	if (c.indexOf(' validate-not-first ') != -1 && e.selectedIndex == 0)
	{
		valid = false;
	}
	else if (c.indexOf(' validate-not-empty ') != -1 && e.options[e.selectedIndex].value.length == 0)
	{
		valid = false;
	}
	return valid;
}

//==================================================================================================================
Gekko.FormValidation.prototype.addClassName = function (e, t)
{
	if (typeof e == "string")
	{
		e = this._get_element_by_id(e);
	}
	//code to change and replace strings
	var ec = ' ' + e.className.replace(/^\s*|\s*$/g, '') + ' ';
	var nc = ec;
	t = t.replace(/^\s*|\s*$/g, '');
	//check if not already there
	if (ec.indexOf(' ' + t + ' ') == -1)
	{
		//not found, add it
		nc = ec + t;
	}
	//return the changed text!
	e.className = nc.replace(/^\s*|\s*$/g, ''); //trimmed whitespace
	return true;
}

//==================================================================================================================
Gekko.FormValidation.prototype.removeClassName = function (e, t)
{
	if (typeof e == "string")
	{
		e = this._get_element_by_id(e);
	}
	//code to change and replace strings
	var ec = ' ' + e.className.replace(/^\s*|\s*$/g, '') + ' ';
	var nc = ec;
	t = t.replace(/^\s*|\s*$/g, '');
	//check if not already there
	if (ec.indexOf(' ' + t + ' ') != -1)
	{
		//found, so lets remove it
		nc = ec.replace(' ' + t.replace(/^\s*|\s*$/g, '') + ' ', ' ');
	}
	//return the changed text!
	e.className = nc.replace(/^\s*|\s*$/g, ''); //trimmed whitespace
	return true;
}

//==================================================================================================================
Gekko.FormValidation.prototype.isVisible = function (e)
{
	//returns true is should be visible to user.
	if (typeof e == "string")
	{
		e = this._get_element_by_id(e);
	}
	while (e.nodeName.toLowerCase() != 'body' && e.style.display.toLowerCase() != 'none' && e.style.visibility.toLowerCase() != 'hidden')
	{
		e = e.parentNode;
	}
	if (e.nodeName.toLowerCase() == 'body')
	{
		return true;
	}
	else
	{
		return false;
	}
}


//==================================================================================================================
Gekko.FormValidation.prototype.searchUp = function (elm, findElm, debug)
{
	//this function searches the dom tree upwards for the findElm node starting from elm.
	//check if elm is reference
	if (typeof (elm) == 'string')
	{
		elm = this._get_element_by_id(elm);
	}
	//search up
	//get the parent findElm
	while (elm && elm.parentNode && elm.nodeName.toLowerCase() != findElm && elm.nodeName.toLowerCase() != 'body')
	{
		elm = elm.parentNode;
	}
	return elm;
}

//==================================================================================================================
Gekko.FormValidation.prototype._get_element_by_id = function (e)
{
	if (typeof (e) != 'string') return e;
	if (document.getElementById) e = document.getElementById(e);
	else if (document.all) e = document.all[e];
	else e = null;
	return e;
}

//==================================================================================================================
//          _
//         : `            _..-=-=-=-.._.--.
//          `-._ ___,..-'" -~~`         __')
//              `'"---'"`>>"'~~"~"~~>>'`
// =====================```========```======== 
//==================================================================================================================
Gekko.DateTimePicker = function ()
{
	this.init();
}

//____________________________________________________________________________
Gekko.DateTimePicker.init = function ()
{

	var mycontainer = document.createElement("div");
	mycontainer.setAttribute("id", "gekko_calendar_container");
	mycontainer.setAttribute("class", "yui-pe-content");
	document.body.appendChild(mycontainer);

	mycontainer.innerHTML = '<div class="hd">Please select date/time</div>' + '<div class="bd">' + '<div id="gekko_date_container"></div>' + '<div style="clear:both"></div>' + '<div class="gekko_time_container"><strong>Time (24hr): </strong>' + '<input class="gekko_calendar_time" id="gekko_calendar_hour" type="text" size="2" maxlength="2" onKeyPress="return Gekko.DateTimePicker.validateNumbersOnly(event);" />:' + '<input class="gekko_calendar_time" id="gekko_calendar_min" type="text" size="2" maxlength="2" onKeyPress="return Gekko.DateTimePicker.validateNumbersOnly(event);" />:' + '<input class="gekko_calendar_time" id="gekko_calendar_sec" type="text" size="2" maxlength="2" onKeyPress="return Gekko.DateTimePicker.validateNumbersOnly(event);" />' + '</div>' + '</div>';

	Gekko.DateTimePicker.dateTimeDialog = new YAHOO.widget.SimpleDialog("gekko_calendar_container", {
		width: "200px",
		fixedcenter: true,
		visible: false,
		draggable: false,
		constraintoviewport: true,
		buttons: [
		{
			text: "OK",
			handler: function ()
			{
				Gekko.DateTimePicker.handleSelectDateTime();
			},
			isDefault: true
		}, {
			text: "Cancel",
			handler: function ()
			{
				Gekko.DateTimePicker.dateTimeDialog.hide();
			}
		}]
	});

	var keyEsc = new YAHOO.util.KeyListener(document, {
		keys: 27
	}, {
		fn: Gekko.DateTimePicker.dateTimeDialog.hide,
		scope: Gekko.DateTimePicker.dateTimeDialog,
		correctScope: true
	}, "keyup");
	var keyEnter = new YAHOO.util.KeyListener(document, {
		keys: 13
	}, {
		fn: Gekko.DateTimePicker.handleSelectDateTime,
		scope: Gekko.DateTimePicker.dateTimeDialog,
		correctScope: true
	}, "keyup");

	Gekko.DateTimePicker.dateTimeDialog.cfg.queueProperty("keylisteners", keyEnter);
	Gekko.DateTimePicker.dateTimeDialog.cfg.queueProperty("keylisteners", keyEsc);

	Gekko.DateTimePicker.datePicker = new YAHOO.widget.Calendar("gekko_calendar", "gekko_date_container", {
		close: false,
		mindate: '01/01/1900',
		maxdate: '12/31/2099',
		iframe: false,
		hide_blank_weeks: true
	});
	Gekko.DateTimePicker.datePicker.cfg.setProperty("DATE_FIELD_DELIMITER", "-");
	Gekko.DateTimePicker.datePicker.cfg.setProperty("DATE_RANGE_DELIMITER", "~");
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MDY_DAY_POSITION", 3);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MDY_MONTH_POSITION", 2);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MDY_YEAR_POSITION", 1);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MD_DAY_POSITION", 2);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MD_MONTH_POSITION", 1);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MY_YEAR_POSITION", 1);
	Gekko.DateTimePicker.datePicker.cfg.setProperty("MY_MONTH_POSITION", 2);

	Gekko.DateTimePicker.datePicker.render();
	Gekko.DateTimePicker.dateTimeDialog.render();
	document.getElementById('gekko_calendar_hour')
	document.getElementById('gekko_calendar_hour').style.width = '2em';
	document.getElementById('gekko_calendar_min').style.width = '2em';
	document.getElementById('gekko_calendar_sec').style.width = '2em';
}
//____________________________________________________________________________
Gekko.DateTimePicker.handleSelectDateTime = function (type, args, obj)
{
	var error_str = '';
	if (document.getElementById('gekko_calendar_hour').value > 23) error_str = "* Invalid hour\n";
	if (document.getElementById('gekko_calendar_min').value > 59) error_str = "* Invalid minute\n";
	if (document.getElementById('gekko_calendar_sec').value > 59) error_str = "* Invalid second\n";
	if (error_str != '')
	{
		alert(error_str);
		return false;
	}
	var dates = Gekko.DateTimePicker.datePicker.getSelectedDates();
	var date = dates[0];


	//var year = date[0], month = date[1], day = date[2];
	var year = date.getFullYear();
	var month = Gekko.DateTimePicker.zeroPad(date.getMonth() + 1);
	var day = Gekko.DateTimePicker.zeroPad(date.getDate());
	var hrs = Gekko.DateTimePicker.zeroPad(document.getElementById('gekko_calendar_hour').value);
	var mins = Gekko.DateTimePicker.zeroPad(document.getElementById('gekko_calendar_min').value);
	var secs = Gekko.DateTimePicker.zeroPad(document.getElementById('gekko_calendar_sec').value);
	Gekko.DateTimePicker.currentField.value = year + "-" + month + "-" + day + " " + hrs + ':' + mins + ':' + secs;
	Gekko.DateTimePicker.dateTimeDialog.hide();
}
//____________________________________________________________________________
Gekko.DateTimePicker.zeroPad = function (s)
{
	if (s.toString().length < 2) s = '0' + s;
	return s;
}

//____________________________________________________________________________
Gekko.DateTimePicker.getLeft = function (el)
{
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while (el)
	{
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
//____________________________________________________________________________
Gekko.DateTimePicker.getTop = function (el)
{
	var tmp = el.offsetTop;
	el = el.offsetParent
	while (el)
	{
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}
//____________________________________________________________________________
Gekko.DateTimePicker.validateNumbersOnly = function (e)
{
	var unicode = e.charCode ? e.charCode : e.keyCode;
	if (unicode != 8)
	{
		if (unicode < 48 || unicode > 57) return false;
	}
	return true;
}
//____________________________________________________________________________
Gekko.DateTimePicker.analyzePHPDate = function (oData)
{
	if (oData != null)
	{
		if (oData != '0000-00-00 00:00:00')
		{
			var parts = oData.split(' ');
			var datePart = parts[0].split('-');
			if (parts.length > 1)
			{
				var timePart = parts[1].split(':');
				var x = new Date(datePart[0], datePart[1] - 1, datePart[2], timePart[0], timePart[1], timePart[2]);
				return x;
			}
			else
			{
				var x = new Date(datePart[0], datePart[1] - 1, datePart[2]);
				return x;
			}
		}
		else return new Date(1900, 0, 1);
	}
	else return new Date(1900, 0, 1);
};

//____________________________________________________________________________
Gekko.DateTimePicker.handleUpdate = function ()
{
	if (Gekko.DateTimePicker.currentField.value != "")
	{
		var oldval = Gekko.DateTimePicker.currentField.value;
		var selectedDate = Gekko.DateTimePicker.analyzePHPDate(oldval);

		if (selectedDate == 'Invalid Date' || selectedDate.getFullYear() == 1900) selectedDate = new Date();
		Gekko.DateTimePicker.datePicker.select(selectedDate);
		var str = selectedDate.getFullYear() + "-" + (selectedDate.getMonth() + 1);
		Gekko.DateTimePicker.datePicker.cfg.setProperty("pagedate", str);
		Gekko.DateTimePicker.datePicker.render();
		document.getElementById('gekko_calendar_hour').value = Gekko.DateTimePicker.zeroPad(selectedDate.getHours());
		document.getElementById('gekko_calendar_min').value = Gekko.DateTimePicker.zeroPad(selectedDate.getMinutes());
		document.getElementById('gekko_calendar_sec').value = Gekko.DateTimePicker.zeroPad(selectedDate.getSeconds());

	}
}

//____________________________________________________________________________
Gekko.DateTimePicker.showCalendar = function (elem1)
{
	var elem = document.getElementById(elem1);

	Gekko.DateTimePicker.currentField = elem;
	Gekko.DateTimePicker.handleUpdate();

	var x = Gekko.DateTimePicker.getLeft(elem);
	var y = Gekko.DateTimePicker.getTop(elem) + elem.offsetHeight;

	Gekko.DateTimePicker.dateTimeDialog.show();
	Gekko.DateTimePicker.dateTimeDialog.cfg.setProperty("xy", [x, y]);
}
// \ \ \ \ \ \ \ \ \| |\ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \| |\ \ \ \ \ \ \ \ \ 
/// / / / / / / / / | | / / / / / / __  / / / / / / / / | | / / / / / / / / /
// \ \ \ \ \ \ \ \ \| |\ \ \ \ \   /..\  ` ` \ \ \ \ \ \| |\ \ \ \ \ \ \ \ \ 
//------------------' `---------- (    ) \|/ -----------' `------------------
// ,------------------------- _\___>  <__//` ------------------------------. 
// |/ / / / / / / / / / / / / >,---.   ,-'  / / / / / / / / / / / / / / / /| 
// | \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ |  . \  \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ \ | 
// |/ / / / / / / / / / / / / / /  `. `. \   ., / / / / / / / / / / / / / /| 
// | \ \ \ \ \ \ \ \ \ \ \ \ \ \ \  |  `. | \||_ \ \ \ \ \ \ \ \ \ \ \ \ \ | 
// |/ / / / / / / / / / / / / / / / `.  : |__||   / / / / / / / / / / / / /| 
// | \ \ \ \ \ \ \ \ \ \ \ \ \ \ \  __> `.,---'  \ \ \ \ \ \ \ \ \ \ \ \ \ | 
// |/ / / / / / / / / / / / / / /  |.--'\`.\  / / / / / / / / / / / / / / /| 
// `------------------------------ _\\   \`.| -----------------------------' 
//------------------. ,------------ /|\ - |:| ----------. ,------------------
// \ \ \ \ \ \ \ \ \| |\ \ \ \ \ \ ' `    |:|  \ \ \ \ \| |\ \ \ \ \ \ \ \ \ 
/// / / / / / / / / | | / / / / / / / / / |:| / / / / / | | / / / / / / / / /
// \ \ \ \ \ \ \ \ \| |\ \ \ \ \ \ \ \ \  |:/  \ \ \ \ \| |\ \ \ \ \ \ \ \ \ 
/// / / / / / / / / | | / /  --.________,-_/  / / / / / | | / / / / / / / / /
// \ \ \ \ \ \ \ \ \| |\ \ \ \ \ ```-----' \ \ \ \ \ \ \| |\ \ \ \ \ \ \ \ \ 
/// / / / / / / / / | | / / / / / / / / / / / / / / / / | | / / / / / / / \ \ 
Gekko.SimpleDatePicker = function ()
{
	this.init();
}
Gekko.SimpleDatePicker.init = function()
{
	var mycontainer = document.createElement("div");
	mycontainer.setAttribute("id", "gekko_calendar_container");
	document.body.appendChild(mycontainer);


	Gekko.SimpleDatePicker.container = document.getElementById('gekko_calendar_container');
	Gekko.SimpleDatePicker.container.style.position = 'absolute';
	Gekko.SimpleDatePicker.container.style.display = 'none';	
	Gekko.SimpleDatePicker.container.style.zIndex = '32768';
	
	Gekko.SimpleDatePicker.datePicker = new YAHOO.widget.Calendar("gekko_calendar","gekko_calendar_container", { title:"Choose a date:", close:true,mindate:'01/01/1899', maxdate:'12/31/2099' } );
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("DATE_FIELD_DELIMITER", "-"); 
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("DATE_RANGE_DELIMITER", "~"); 	
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MDY_DAY_POSITION", 3);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MDY_MONTH_POSITION", 2);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MDY_YEAR_POSITION", 1);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MD_DAY_POSITION", 2);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MD_MONTH_POSITION", 1);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MY_YEAR_POSITION", 1);
	Gekko.SimpleDatePicker.datePicker.cfg.setProperty("MY_MONTH_POSITION", 2);
	
	Gekko.SimpleDatePicker.datePicker.hideEvent.subscribe(Gekko.SimpleDatePicker.handleClose); 
	Gekko.SimpleDatePicker.datePicker.selectEvent.subscribe(Gekko.SimpleDatePicker.handleSelect, Gekko.SimpleDatePicker.datePicker, true);
	Gekko.SimpleDatePicker.datePicker.render();
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.getLeft = function(el) {
	var tmp = el.offsetLeft;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetLeft;
		el = el.offsetParent;
	}
	return tmp;
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.getTop = function(el)  {
	var tmp = el.offsetTop;
	el = el.offsetParent
	while(el) {
		tmp += el.offsetTop;
		el = el.offsetParent;
	}
	return tmp;
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.handleSelect = function(type,args,obj) {
	var dates = args[0];
	var date = dates[0];
	var year = date[0], month = date[1], day = date[2];
	Gekko.SimpleDatePicker.currentField.value = year + "-" + month + "-" + day;
	Gekko.SimpleDatePicker.datePicker.hide();	
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.handleUpdate = function() {
	if (Gekko.SimpleDatePicker.currentField.value != "")
	{
		var  oldval = Gekko.SimpleDatePicker.currentField.value;
		Gekko.SimpleDatePicker.datePicker.select(Gekko.SimpleDatePicker.currentField.value);
		var selectedDates = Gekko.SimpleDatePicker.datePicker.getSelectedDates();
		if (selectedDates.length > 0 && selectedDates != 'Invalid Date') {
			var firstDate = selectedDates[0];
			
			Gekko.SimpleDatePicker.datePicker.cfg.setProperty("pagedate", (firstDate.getFullYear() + "-" + firstDate.getMonth()+1));
			Gekko.SimpleDatePicker.datePicker.render();
		} else {

			Gekko.SimpleDatePicker.datePicker.select(Gekko.SimpleDatePicker.datePicker.today);
			var selectedDates = Gekko.SimpleDatePicker.datePicker.getSelectedDates();
			Gekko.SimpleDatePicker.currentField.value  = oldval;			
		}
	}
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.showCalendar = function(elem)
{
	Gekko.SimpleDatePicker.container = document.getElementById('gekko_calendar_container');
	Gekko.SimpleDatePicker.currentField = elem;
	var height = elem.offsetHeight;
	Gekko.SimpleDatePicker.container.style.display = 'block';
	Gekko.SimpleDatePicker.container.style.left = Gekko.SimpleDatePicker.getLeft(elem) +'px';
	Gekko.SimpleDatePicker.container.style.top = Gekko.SimpleDatePicker.getTop(elem) + height + 'px';	
	Gekko.SimpleDatePicker.handleUpdate();
	Gekko.SimpleDatePicker.datePicker.show();
	
}
//____________________________________________________________________________

Gekko.SimpleDatePicker.handleClose = function()
{
  Gekko.SimpleDatePicker.container = document.getElementById("gekko_calendar_container");
  Gekko.SimpleDatePicker.container.style.display = 'none';
}
//    _.-~~-.__
// _-~ _-=-_   ''-,,
//('___ ~~~   0     ~''-_,,,,,,,,,,,,,,,,
// \~~~~~~--'                            '''''''--,,,,
//  ~`-,_      ()                                     '''',,,
//       '-,_      \                           /             '', _~/|
//  ,.       \||/~--\ \_________              / /______...---.  ;  /
//  \ ~~~~~~~~~~~~~  \ )~~------~`~~~~~~~~~~~( /----         /,'/ /
//   |   -           / /                      \ \           /;/  /
//  / -             / /                        / \         /;/  / -.
// /         __.---/  \__                     /, /|       |:|    \  \
///_.~`-----~      \.  \ ~~~~~~~~~~~~~---~`---\\\\ \---__ \:\    /  /
//                  `\\\`                     ' \\' '    --\'\, /  /
//                                               '\,        ~-_'''"
//
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
var suggestShortcutFilenameToAnotherTextField = function (longname, target)
{
	var newstr = suggestShortcutFilename(longname);
	var the_target = document.getElementById(target);
	if (the_target)
	{
		if (the_target.value == '') document.getElementById(target).value = newstr;

	}
}
/////////////////////////////////
var suggestShortcutFilename = function (longname)
{
	var txt = longname;

	txt = txt.replace(/[^0-9,a-z,A-Z]/gi, "_");
	txt = txt.replace(/_+/gi, "-");
	txt = txt.toLowerCase();
	return txt;
}
/////////////////////////////////
var getURLQueryString = function ()
{
	var array = window.location.search.substring(1).split(/&/);
	/* URLs can be like either
	"sample.html?test1=hi&test2=bye" or
	"sample.html?test1=hi;test2=bye" */
	var parsed_get = {};
	for (var i = 0; i < array.length; i++)
	{
		var assign = array[i].indexOf('=');
		if (assign == -1)
		{
			parsed_get[array[i]] = true; //if no value, treat as boolean
		}
		else
		{
			parsed_get[array[i].substring(0, assign)] = array[i].substring(assign + 1);
		}
	}
	return parsed_get;
}
/////////////////////////////////
var gekko_toggle_readpermission_check_everyone = function ()
{
	var checkall = document.getElementById('chk_permission_read_everyone');
	var toggle = checkall.checked;
	var chkselections = document.getElementsByName('permission_read[]');
	for (i = 0; i < chkselections.length; i++) chkselections[i].checked = toggle;
}

/////////////////////////////////
var gekko_toggle_readpermission_check = function (itemid)
{
	var checkall = document.getElementById('chk_permission_read_everyone');
	var chkselections = document.getElementsByName('permission_read[]');
	var allchecked = true;
	var checked = itemid.checked;
	if (checkall.checked && !itemid.checked) checkall.checked = false;
	else
	{
		for (i = 0; i < chkselections.length; i++)
		{
			if (!chkselections[i].checked)
			{
				allchecked = false;
				break;
			} // end if
		} // end for
		checkall.checked = allchecked;
	}
}

/////////////////////////////////
function getRadioCheckedValue(element_name)
{
	if (!element_name) return "";
	var radioObj = document.getElementsByName(element_name);
	var radioLength = radioObj.length;
	if (radioLength == undefined)
	{
		if (radioObj.checked) return radioObj.value;
		else return "";
	}
	else
	{
		for (var i = 0; i < radioLength; i++)
		if (radioObj[i].checked) return radioObj[i].value;
	}
	return "";
}
/////////////////////////////////
var gekko_validate_zip_file = function (fld)
{
	if (fld.value.length == 0)
	{
		alert('File cannot be empty. Please select a file before clicking the upload button');
		return false;
	}
	else if (!/(\.zip)$/i.test(fld.value))
	{
		alert("Invalid file type. Accepted file type: ZIP format");
		fld.form.reset();
		fld.focus();
		return false;
	}
	return true;
}

/////////////////////////////////
var gekko_validate_image_file = function (fld)
{
	if (!/(\.bmp|\.gif|\.jpg|\.jpeg)$/i.test(fld.value))
	{
		alert("Invalid image file type.");
		fld.form.reset();
		fld.focus();
		return false;
	}
	return true;
}

/////////////////////////////////
var gekko_editor_cancel_edit = function (nowarning)
{
	if (nowarning != 1)
	{

		if (!confirm("Are you sure you want to navigate away from the editing page?\nYou cannot undo this operation as your data will not be saved")) return false;
	}
	var previous_page_total = history.length;
	if (previous_page_total > 1) history.go(-1);
	else
	{
		var str = getURLQueryString();
		if (str != null)
		{
			//var uri = new Object();
			//getURL(uri);
			var app = str['app'];
			var new_url = 'http://' + window.location.hostname + '/admin/index.php?app=' + app;
			window.location = new_url;
		}
		else alert('Error, cannot leave this page');
	}
}

/////////////////////////////////
var gekko_editor_cancel_return_to_main_app = function (nowarning)
{
	if (nowarning != 1)
	{

		if (!confirm("Are you sure you want to navigate away from the editing page?\nYou cannot undo this operation as your data will not be saved")) return false;
	}
	var str = getURLQueryString();
	if (str != null)
	{
		//var uri = new Object();
		//getURL(uri);
		var app = str['app'];
		var new_url = 'http://' + window.location.hostname + '/admin/index.php?app=' + app;
		window.location = new_url;
	}
	else alert('Error, cannot leave this page');
}

/////////////////////////////////
var gekko_editor_revert_confirmation = function ()
{
	return confirm("Are you sure you want to revert the changes and start over the editing?\nYou cannot undo this operation as your data will not be saved");
}

/////////////////////////////////
var frontend_cancel_button = function (nowarning)
{
	if (nowarning != 1)
	{

		if (!confirm("Are you sure you want to cancel?")) return false;
	}
	var previous_page_total = history.length;
	if (previous_page_total > 1) history.go(-1);
}
/////////////////////////////////
var getURL = function (uri)
{
	uri.dir = location.href.substring(0, location.href.lastIndexOf('\/'));
	uri.dom = uri.dir;
	if (uri.dom.substr(0, 7) == 'http:\/\/') uri.dom = uri.dom.substr(7);
	uri.path = '';
	var pos = uri.dom.indexOf('\/');
	if (pos > -1)
	{
		uri.path = uri.dom.substr(pos + 1);
		uri.dom = uri.dom.substr(0, pos);
	}
	uri.page = location.href.substring(uri.dir.length + 1, location.href.length + 1);
	pos = uri.page.indexOf('?');
	if (pos > -1)
	{
		uri.page = uri.page.substring(0, pos);
	}
	pos = uri.page.indexOf('#');
	if (pos > -1)
	{
		uri.page = uri.page.substring(0, pos);
	}
	uri.ext = '';
	pos = uri.page.indexOf('.');
	if (pos > -1)
	{
		uri.ext = uri.page.substring(pos + 1);
		uri.page = uri.page.substr(0, pos);
	}
	uri.file = uri.page;
	if (uri.ext != '') uri.file += '.' + uri.ext;
	if (uri.file == '') uri.page = 'index';
	uri.args = location.search.substr(1).split("?");
	return uri;
}


///////////////////////////////////////
$onload(Gekko.FormValidation.Initialize);