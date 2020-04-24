/*
Form Validation Rules: A collection of useful rules for the 
jQuery form validation plug-in v.0.2
Copyright (C) 2006  J鰎n Zaefferer

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
 * Defines a standard set of useful validation rules.
 * 
 * Can be easily extended by just writing:
 * jQuery.validator.rules.myRule = function(value, element, parameters, validator) {
 *   return something;
 * }
 * If you only need the value parameter, you can define just function(value) {}
 * 
 * If "all kind of text inputs" is mentioned, it refers to input elements
 * of type text, password and file and textareas.
 *
 * The parameters are:
 * @param value
 *    the value of the element, eg. the text of a text input
 * @param element
 *    the input element itself, to check for content of attributes other then value
 * @param parameters
 *    an array of parameters, contains all parameters of a rule if specfied
 *    eg. for length:2:5 parameters[0] is 2 and parameters[1] is 5
 * @param utility
 *    a reference to the validator.utility object to call 
 *    utility methods like getSelectedOptions()
 */
jQuery.validator.rules = {

	/**
	 * Return true if the element is empty.
	 * Works with all kind of text inputs, select and checkbox.
	 * To force a user to select an option from a select box, provide
	 * an empty options like <option value="">Choose...</option>
	 */
	required: function(value, element, parameters, utility) {
		switch( element.nodeName.toLowerCase() ) {
		case 'select':
			var options = utility.getSelectedOptions(element);
			return options.length == 0 || options[0].value.length == 0;
		case 'input':
			switch( element.type.toLowerCase() ) {
			case 'checkbox':
				return !element.checked;
			case 'radio':
				return utility.countChecked(element) == 0;
			default:
				return value.length == 0;
			}
		default:
			return value.length == 0;
		}
	},
	
	/**
	 * Return true, if the element is
	 * - some kind of text input and its value is too short or too long
	 * - a select and has not enough or too many options selected
	 * Works with all kind of text inputs and select.
	 */
	length: function(value, element, parameters, utility) {
		var length = utility.getLength(value, element);
		return length < parameters[0] || length > parameters[1];
	},
	
	/**
	 * Return true, if the element is
	 * - some kind of text input and its value is too short
	 * - a select and has not enough options selected
	 * Works with all kind of text inputs and select.
	 */
	min: function(value, element, parameters, utility) {
		var length = utility.getLength(value, element);
		return length < parameters[0];
	},
	
	/**
	 * Return true, if the element is
	 * - some kind of text input and its value is too short
	 * - a select and has not enough options selected
	 * Works with all kind of text inputs and select.
	 */
	max: function(value, element, parameters, utility) {
		var length = utility.getLength(value, element);
		return length > parameters[0];
	},
	
	/**
	 * Return true, if the value is not a valid email address.
	 * Works with all kind of text inputs.
	 */
	email: function(value) {
		return !value.match(/^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i);
	},
	
	/**
	 * Return true, if the value is not a valid url.
	 * Works with all kind of text inputs.
	 * WARNING: Is not fully implemented yet, eg. it does not allow a query string.
	 * @see http://www.w3.org/Addressing/rfc1738.txt
	 */
	url: function(value) {
		return !value.match(/^(https?|ftp):\/\/[A-Z0-9](\.?[A-Z0-9能謁[A-Z0-9_\-能謁*)*(\/([A-Z0-9能謁[A-Z0-9_\-\.能謁*)?)*(\?([A-Z0-9能謁[A-Z0-9_\-\.%\+=&能謁*)?)?$/i);
	},
	
	/**
	 * Return true, if the value is a valid date.
	 * Works with all kind of text inputs.
	 * WARNING: Limited due to the capability of the JS Date object
	 * Use dateDE to check german dates.
	 */
	date: function(value) {
		return /Invalid|NaN/.test(new Date(value));
	},
	
	/**
	 * Checks a date according to german oldschool standard (not ISO).
	 * eg. 25.03.2006 or 1.5.05
	 * WARNING: Does not make sanity checks, eg. for 50.13.06 or 30.03.05
	 */
	dateDE: function(value) {
		return !value.match(/\d\d?\.\d\d?\.\d\d\d?\d?/);
	},
	
	/**
	 * Return true, if the value is a valid number.
	 * Works with all kind of text inputs.
	 * WARNING: Is not fully implemented yet, eg. is does not allow commas
	 */
	number: function(value) {
		return isNaN(value);
	}
};