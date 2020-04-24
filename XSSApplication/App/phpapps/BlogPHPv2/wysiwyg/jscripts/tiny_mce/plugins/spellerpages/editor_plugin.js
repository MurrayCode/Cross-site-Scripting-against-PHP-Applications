/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('spellerpages', 'en,da'); // <- Add a comma separated list of all supported languages


function TinyMCE_spellerpages_getInfo() {
	return {
		longname : 'SpellerPages',
		author : 'jtabraham, jizzy',
		authorurl : 'http://sourceforge.net/tracker/index.php?func=detail&aid=1234943&group_id=103281&atid=738747',
		infourl : 'http://sourceforge.net/tracker/index.php?func=detail&aid=1234943&group_id=103281&atid=738747',
		version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
	};
};


/**
 * Returns the HTML contents of the spellerpages control.
 */
function TinyMCE_spellerpages_getControlHTML(control_name) {
	switch (control_name) {
		case "spellerpages":
			return '<img id="{$editor_id}_spellerpages" src="{$pluginurl}/images/spellerpages.gif" title="{$lang_spellerpages_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceSpellerpages\', true);" />';
	}
	return "";
}

/**
 * Executes the mceSpellerpages command.
 */
function TinyMCE_spellerpages_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceSpellerpages":
			var template = new Array();

			template['file'] = '../../plugins/spellerpages/tinyMCE_spellerpages.html'; // Relative to theme
			template['width'] = 500;
			template['height'] = 500;
			template['status'] = 'yes';

			// Language specific width and height addons
			template['width'] += tinyMCE.getLang('lang_spellerpages_delta_width', 0);
			template['height'] += tinyMCE.getLang('lang_spellerpages_delta_height', 0);

			tinyMCE.openWindow(template, {editor_id : editor_id,status:'yes',resizable:'yes'});

		  return true;
	}

	// Pass to next handler in chain
	return false;
}
;

function TinyMCE_spellerpages_reloadit(editor_id)
{
  tinyMCE.updateContent(tinyMCE.getInstanceById(editor_id).formElement.id);
}
