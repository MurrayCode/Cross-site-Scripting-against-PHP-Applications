/*
Form Validation: jQuery form validation plug-in v.0.2
Copyright (C) 2006  Jörn Zaefferer

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

/**
 * Validates either a single form on submit or a list
 * of elements immediately. 
 * Shows and hides error labels accordingly.
 */
jQuery.fn.validate = function(options) {
	var validator = new jQuery.validator(options);
	if( this.is('form') ) {
		// validate the form on submit
		return this.submit(validator.validateForm);
	} else {
		// validate all elements immediately
		this.each(function() {
			var element = this;
			validator.hideElementErrors(element);
			validator.validateElement(element);
		});
		validator.showErrors();
	}
	return this;
};

jQuery.validator = function(options) {
	// store reference to this to be used in callbacks
	var v = this;
	
	this.errorList = {};
	
	this.currentForm;
	
	this.rules = jQuery.validator.rules;
	
	this.utility = jQuery.validator.utility;
	
	/**
	 * Default settings that can be overriden by passing an object
	 * literal to the constructor
	 */
	this.settings = {
		/**
		 * Used to split rule parameters, 
		 * eg. length:2:5
		 */
		parameterDelimiter: ":",
		
		/**
		 * The attribute to search for rules, 
		 * eg. <input id="text" validate="required" /> 
		 */
		rulesAttribute: "validate",
		
		/**
		 * Start and end string to search the class
		 * tag for rules, eg. class="style $v(required) otherstyle"
		 */
		rulesClassStart: "$v(",
		rulesClassEnd: ")",
		
		/**
		 * The delimiter between rules in the attribute or class,
		 * eg. class="style $v(required,email)" or validate="url,min:3".
		 * For consistency, it is used inside both places.
		 */
		rulesDelimiter: ",",
		
		/**
		 * the class used to mark error labels,
		 * eg. <label for="text" class="error">Error text</label>
		 */
		errorLabelClass: "error",
		
		/**
		 * the container to show and hide when 
		 * displaying errors
		 */
		errorContainer: null,
		
		/**
		 * Override to true to prevent form submit.
		 * Very useful to debug rules, a submit would remove
		 * all console output.
		 */
		debug: false,
		
		/**
		 * Wheather to focus the first invalid element.
		 * Can crash browsers when combined with blur-validation.
		 */
		focusInvalidElement: false,
		
		/**
		 * Used by validateForm(), showErrorLabel() and
		 * hideElementErrors() when using an errorContainer.
		 * The callback selects the element that need to be hidden
		 * with the label itself, eg. for this markup:
		 * <div id="errorContainer"><ol>
		 * <li><label for="id" class="error">Error Text</label></li>
		 * </ol></div>
		 * It would be necessary to hide the li, too, not just the label.
		 *
		 * @param context
		 *    a jQuery object that contains the label(s) to hide
		 */
		selectErrorNests: null
		
	};
	
	// override defaults with client settings
	if(options) {
		jQuery.extend(this.settings, options);
	}
	
	if(v.settings.errorContainer) {
		v.settings.errorContainer.hide();
	}
	
	/**
	 * Validates a form. Used as a callback to a submit event.
	 * Prevents the form from being submitted if it is invalid
	 * (or if debug mode is on).
	 */
	v.validateForm = function(submitEvent) {
		if(v.settings.debug) {
			// prevent form submit to be able to see console output
			// must be at the start of the function to work when errors occur
			submitEvent.preventDefault();
		}
		
		// reset errors
		v.errorList = {};
		
		// set a reference to the current form, to be used as a search context
		v.currentForm = this;
		
		var context = this;
		
		var errorContainer = v.settings.errorContainer;
		if(errorContainer) {
			errorContainer.hide();
			context = errorContainer;
		}
		
		// hide all error labels for the form
		var labels = $("label." + v.settings.errorLabelClass, context).hide();
		if( v.settings.selectErrorNests ) {
			v.settings.selectErrorNests(labels).hide();
		}
	
		// select all valid inputs inside the form (no submit or reset buttons)
		// TODO: exchange with :input selector ASAP
		$("input, select, textarea", v.currentForm)
		.not("[@type=submit]")
		.not("[@type=reset]")
		.each(function() {
			// validate every single element
			v.validateElement(this);
		});

		// check if the form is valid and return
		return v.isFormValid();
	};
	
	/**
	 * Searches the given element for rules and then
	 * tests the element to these rules.
	 */
	v.validateElement = function(element) {
		var rules = this.findRules(element);
		for( var i=0, rule; rule = rules[i]; i++ ) {
			try {
				var value = $(element).val();
				if( this.rules[rule.name](value, element, rule.parameters, this.utility) ) {
					// add the error to the array of errors for the element
					var id = ( element.type.toLowerCase().match(/radio|checkbox/) ) ? element.name : element.id;
					if(!id && options.debug) {
						console.error("could not find id/name for element, please check the element (see next line)");
						console.debug(element);
					}
					var list = this.errorList[id] || (this.errorList[id] = []);
					list[list.length] = rule.name;
				}
			} catch(e) {
				if(this.settings.debug) {
					console.error("exception occured when checking element " + element.id
						 + ", check the '" + rule.name + "' test");
				}
				throw e;
			}
		}
	};

};

jQuery.validator.prototype = {

	/**
	 * Searches for all error labels associated
	 * with the given element and hides them.
	 * To hide labels for a form, use hideFormErrors().
	 */
	hideElementErrors: function(element) {
		var errorLabel = $("label." + this.settings.errorLabelClass + "[@for=" + element.id + "]").hide();
		if( this.settings.selectErrorNests ) {
			this.settings.selectErrorNests(errorLabel).hide();
		}
	},
	
	/**
	 * Check if the validated form has errors or not,
	 * if it has, display them.
	 */
	isFormValid: function() {
		var count = 0;
		// iterate over properties and count them
		for( i in this.errorList ) {
			count++;
		}
		if(count == 0) {
			// form has no errors, submit it
			return true;
		} else {
			// form has errors, display them and do not submit
			this.showErrors();
			return false;
		}
	},

	/**
	 * Display an error label for every invalid element.
	 * If there is more than one error, only the label
	 * associated with the first error is displayed.
	 * The first invalid element is also focused.
	 */
	showErrors: function() {
		if(this.settings.errorContainer) {
			this.settings.errorContainer.show();
		}
		first = true;
		for(var elementID in this.errorList) {
			if( first && this.settings.focusInvalidElement ) {
				// focus the first invalid element
				// does not work with elementID being a name
				try {
					$("#"+elementID)[0].focus();
				} catch(e) { if( this.settings.debug ) console.error(e) }
				first = false;
			}
			// display the error label for the first failed test
			this.showErrorLabel(elementID, this.errorList[elementID][0]);
		}
	},
	
	/**
	 * Searches for an error label inside an errorContainer (if specified) or
	 * the current form or, when validating single elements, inside the document.
	 * If errors are not specified for every rule, it searches for a generic error.
	 * Check settings and markup, if the form is invalid, but no error is displayed.
	 */
	showErrorLabel: function(elementID, rule) {
		var context = this.settings.errorContainer || this.currentForm || document;
		var errorLabel = $("label." + this.settings.errorLabelClass, context)
			.filter("[@for=" + elementID + "]")
			.filter("[@" + this.settings.rulesAttribute + "=" + rule + "]");
		if(errorLabel.size() == 0) {
			// no label with a fitting rule attribute found, check for classes
			errorLabel.end().filter("."+rule);
			if(errorLabel.size() == 0) {
				// no label with fitting class either, just find a generic
				// label (with no rule attribute)
				// TODO: does not work when there is a non-generic error with a rule class
				errorLabel.end().not("[@" + this.settings.rulesAttribute + "]")
				if(errorLabel.size() == 0) {
					// nothing found, revert to the first selection
					// displays therefore all error labels
					// if this happens and is not intended, check the markup for
					// missing attributes (id, name, for)
					errorLabel.end().end();
				}
			}
		}
		if(errorLabel.size() == 0) {
			if(this.settings.debug) {
				console.error("could not find any error labels for element " + elementID);
			}
		} else {
			errorLabel.show();
			if( this.settings.selectErrorNests ) {
				this.settings.selectErrorNests(errorLabel).show();
			}
		}
	},
	
	/**
	 * Searches all rules for the given element and returns them as an
	 * array of rule object, each with a name and, if available, an
	 * array of parameters.
	 */
	findRules: function(element) {
		var rulesForElement = this.getAttributeRules(element) || this.getClassNameRules(element) || [];
		var ruleObjects = [];
		for(var i=0, rule; rule = rulesForElement[i]; i++) {
			ruleObjects[i] = {};
			if( rule.indexOf(this.settings.parameterDelimiter) == -1 ) {
				// no parameters given, just take the string as name
				ruleObjects[i].name = rule;
			} else {
				// split the name and parameters (default delimiter is ":")
				var parameters = rule.split(this.settings.parameterDelimiter);
				ruleObjects[i].name = parameters[0];
				// remove the first element (the name) and take the rest as parameters
				ruleObjects[i].parameters = parameters.slice(1);
			}
		}
		return ruleObjects;
	},
	
	/**
	 * Searches the class of an element for validation rules.
	 * Eg. for class="style1 $v(required min:3) style2" an array
	 * with two elements [required, min:3] is returned.
	 * The delimiters have default "$v(" and ")".
	 */
	getClassNameRules: function(element) {
		var className = element.className;
		var start = className.indexOf(this.settings.rulesClassStart);
		var end = className.indexOf(this.settings.rulesClassEnd, start);
		var validate = className.substring(start+this.settings.rulesClassStart.length, end);
		if(!validate)
			return;
		return validate.split(this.settings.rulesDelimiter);
	},

	/**
	 * Searches the rule attribute (default is "validate") of an
	 * element for validation rules.
	 * Eg. for validate="required min:3" an array with two 
	 * elements [required, min:3] is returned.
	 * Returns nothing if no rule is found.
	 */
	getAttributeRules: function(element) {
		var validate = element.getAttribute(this.settings.rulesAttribute);
		if(!validate)
			return;
		return validate.split(this.settings.rulesDelimiter);
	}
	
}

jQuery.validator.utility = {

	countChecked: function(element) {
		var elements = $(element);
		while( !elements.get(0).nodeName.toLowerCase().match(/form|body/) ) {
			elements.parent();
		}
		return elements.find('[@name=' + element.name + ']').filter(':checked').length;
	},
	
	getLength: function(value, element) {
		var length;
		switch( element.nodeName.toLowerCase() ) {
		case 'select':
			length = this.getSelectedOptions(element).length;
			break;
		case 'input':
			switch( element.type.toLowerCase() ) {
			case 'checkbox':
				length = this.countChecked(element);
				break;
			default: 
				length = value.length;
			}
			break;
		default: 
			length = value.length;
		}
		return length;
	},

	/**
	 * Returns an array of all selected options of a
	 * select element. Very useful to validate a select
	 * with multiple="multiple".
	 */
	getSelectedOptions: function(select) {
		return $("option:selected", select).get();
	},
	
	isRadioButtonSelected: function(radio) {
		var elements = document.getElementsByName(radio.name);
		for(var i=0, element; element = elements[i]; i++) {
			if(element.checked) {
				return true;
			}
		}
		return false;
	}
}