<?php

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
#                                                                                           #
#  FILENAME                                                                                 #
#  =============================                                                            #
#  inst.php                                                                                 #
#                                                                                           #
#  DESCRIPTION                                                                              #
#  =============================                                                            #
#  Installation of the DBHcms                                                               #
#                                                                                           #
#############################################################################################
#                                                                                           #
#  CHANGES                                                                                  #
#  =============================                                                            #
#                                                                                           #
#  05.06.2007:                                                                              #
#  -----------                                                                              #
#  Added posibility to choose from three diferent themes. Installation of extensions was    #
#  delegated to each extension.                                                             #
#                                                                                           #
#  28.10.2005:                                                                              #
#  -----------                                                                              #
#  File created                                                                             #
#                                                                                           #
#############################################################################################
# $Id: inst.php 74 2007-10-16 09:25:47Z kaisven $                                          #
#############################################################################################

#############################################################################################
#  SECURITY                                                                                 #
#############################################################################################

	if ((realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))||(!defined('DBHCMS'))) {
		die('	<div style="color: #872626; font-weight: bold;">
						DBHCMS FATAL ERROR - Access denied!
					</div>');
	}

#############################################################################################
#  PERFORM INSTALLATION                                                                     #
#############################################################################################

	if (isset($_POST['dbhcms_perform_installation'])) {
		
		# Check database conection
		if (mysql_connect($_POST['dbhcms_inst_db_server'], $_POST['dbhcms_inst_db_user'], $_POST['dbhcms_inst_db_pass']) == false) {
			dbhcms_p_error('Could not connect to server "'.$_POST['dbhcms_inst_db_server'].'"', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		if (mysql_select_db($_POST['dbhcms_inst_db_database']) == false ) {
			dbhcms_p_error('Could not select database "'.$_POST['dbhcms_inst_db_database'].'"', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		$dbhcms_extensions = array('smilies', 'contact', 'guestbook', 'photoalbum', 'news');
		
		$dbhcms_inst_extensions = 'search';
		foreach ($dbhcms_extensions as $ext) {
			if (isset($_POST['dbhcms_inst_ext_'.$ext])) {
				if ($_POST['dbhcms_inst_ext_'.$ext] == '1') {
					$dbhcms_inst_extensions = $dbhcms_inst_extensions.';'.$ext;
				}
			}
		}
		
		# Define database prefix
		define('DBHCMS_C_INST_DB_PREFIX', $_POST['dbhcms_inst_db_prefix']);
		
		# Define core directory
		define('DBHCMS_C_INST_CORE_DIR', $_POST['dbhcms_inst_core_dir']);
		
		### THEME ###
		
		if (isset($_POST['dbhcms_inst_theme'])) {
			$dbhcms_inst_style = 'style.'.$_POST['dbhcms_inst_theme'].'.css';
		} else {
			$dbhcms_inst_style = 'style.bl.css';
		}
		
		### SMILIES ###
		
		if ((isset($_POST['dbhcms_inst_ext_smilies'])) && ($_POST['dbhcms_inst_ext_smilies'] == '1')) {
			$dbhcms_ext_smilies_ext = 'smilies';
		} else {
			$dbhcms_ext_smilies_ext = '';
		}
		
		### CONTACT ###
		
		if ((isset($_POST['dbhcms_inst_ext_contact'])) && ($_POST['dbhcms_inst_ext_contact'] == '1')) {
			$dbhcms_ext_contact_ext = 'contact';
			$dbhcms_ext_contact_tpl = 'contact.tpl';
		} else {
			$dbhcms_ext_contact_ext = '';
			$dbhcms_ext_contact_tpl = '';
		}
		
		### GUESTBOOK ###
		
		if ((isset($_POST['dbhcms_inst_ext_guestbook'])) && ($_POST['dbhcms_inst_ext_guestbook'] == '1')) {
			$dbhcms_ext_guestbook_ext = 'guestbook';
			$dbhcms_ext_guestbook_tpl = 'guestbook.tpl';
		} else {
			$dbhcms_ext_guestbook_ext = '';
			$dbhcms_ext_guestbook_tpl = '';
		}
		
		### PHOTOALBUM ###
		
		if ((isset($_POST['dbhcms_inst_ext_photoalbum'])) && ($_POST['dbhcms_inst_ext_photoalbum'] == '1')) {
			$dbhcms_ext_photoalbum_ext = 'photoalbum';
			$dbhcms_ext_photoalbum_tpl = 'photoalbum.tpl';
		} else {
			$dbhcms_ext_photoalbum_ext = '';
			$dbhcms_ext_photoalbum_tpl = '';
		}
		
		### NEWS ###
		
		if ((isset($_POST['dbhcms_inst_ext_news'])) && ($_POST['dbhcms_inst_ext_news'] == '1')) {
			$dbhcms_ext_news_ext = 'news';
			$dbhcms_ext_news_tpl = 'news.tpl';
		} else {
			$dbhcms_ext_news_ext = '';
			$dbhcms_ext_news_tpl = '';
		}
		
		### INIT VARS ###
		
		$dbhcms_database_sql['CMS'] = array();
		$dbhcms_database_sql['EXT'] = array();
		
		### SQL ###
		
		### TABLE CMS_CONFIG ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_config` (
												  `cnfg_id` varchar(200) NOT NULL default '',
												  `cnfg_value` text,
												  `cnfg_type` varchar(150) NOT NULL default '',
												  `cnfg_decription` text NOT NULL,
												  PRIMARY KEY  (`cnfg_id`)
												); ");
		array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_config` (`cnfg_id`, `cnfg_value`, `cnfg_type`, `cnfg_decription`) VALUES 
													('availableExtensions', '".$dbhcms_inst_extensions."', 'DT_STRARRAY', 'dbhcms_desc_avaliable_extensions'),
													('cacheEnabled', '0', 'DT_BOOLEAN', 'dbhcms_desc_pagecache'),
													('cacheTime', '1440', 'DT_INTEGER', 'dbhcms_desc_cachetime'),
													('cssDirectory', 'stylesheets/', 'DT_DIRECTORY', 'dbhcms_desc_cssdir'),
													('dateFormatDatabase', 'Y-m-d', 'DT_STRING', 'dbhcms_desc_dateformatdb'),
													('dateFormatOutput', 'd.m.Y', 'DT_STRING', 'dbhcms_desc_dateformatfe'),
													('dateTimeFormatDatabase', 'Y-m-d H:i:s', 'DT_STRING', 'dbhcms_desc_datetimeformatdb'),
													('dateTimeFormatOutput', 'd.m.Y H:i:s', 'DT_STRING', 'dbhcms_desc_datetimeformatfe'),
													('debugModus', '0', 'DT_BOOLEAN', 'dbhcms_desc_debugmodus'),
													('dictionaryLanguages', 'en;de;es', 'DT_LANGARRAY', 'dbhcms_desc_dictlang'),
													('imageDirectory', 'images/', 'DT_DIRECTORY', 'dbhcms_desc_imgdir'),
													('javaDirectory', 'java/', 'DT_DIRECTORY', 'dbhcms_desc_javadir'),
													('moduleDirectory', 'php-module/', 'DT_DIRECTORY', 'dbhcms_desc_phpdir'),
													('rootDirectory', '".substr($_POST['dbhcms_inst_domain_subfolders'], 1)."', 'DT_STRING', 'dbhcms_desc_rootdir'),
													('sessionActiveTime', '3', 'DT_INTEGER', 'dbhcms_desc_sessactivetime'),
													('sessionLifeTime', '30', 'DT_INTEGER', 'dbhcms_desc_sesslifetime'),
													('simulateStaticUrls', '0', 'DT_BOOLEAN', 'dbhcms_desc_staticurls'),
													('superUsers', '".$_POST['dbhcms_inst_superuser_login']."', 'DT_USERARRAY', 'dbhcms_desc_superusers'),
													('templateDirectory', 'templates/', 'DT_DIRECTORY', 'dbhcms_desc_tpldir'),
													('timeFormatDatabase', 'H:i:s', 'DT_STRING', 'dbhcms_desc_timeformatdb'),
													('timeFormatOutput', 'H:i:s', 'DT_STRING', 'dbhcms_desc_timeformatfe');
												");
		
		### TABLE CMS_CACHE ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_cache` (
												  `cach_id` int(11) NOT NULL auto_increment,
												  `cach_sessionid` varchar(250) NOT NULL default '',
												  `cach_page_id` int(11) NOT NULL default '0',
												  `cach_user_id` varchar(10) NOT NULL default '0',
												  `cach_lang` varchar(4) NOT NULL default '',
												  `cach_requesturi` varchar(250) NOT NULL default '',
												  `cach_timestamp` timestamp NOT NULL,
												  PRIMARY KEY  (`cach_id`)
												);");
		
		### TABLE CMS_DICTIONARY ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_dictionary` (
												  `dict_id` int(11) NOT NULL auto_increment,
												  `dict_name` varchar(200) NOT NULL default '',
												  `dict_value` text,
												  `dict_lang` varchar(4) default NULL,
												  PRIMARY KEY  (`dict_id`),
												  KEY `dict_name` (`dict_name`)
												);");
		array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_dictionary` (`dict_name`, `dict_value`, `dict_lang`) VALUES 
													('login', 'Login', 'en'),
													('login', 'Anmelden', 'de'),
													('login', 'Conexión', 'es'),
													('save', 'Save', 'en'),
													('save', 'Speichern', 'de'),
													('save', 'Guardar', 'es'),
													('send', 'Send', 'en'),
													('send', 'Senden', 'de'),
													('send', 'Enviar', 'es'),
													('page', 'Page', 'en'),
													('page', 'Seite', 'de'),
													('page', 'Página', 'es'),
													('logout', 'Logout', 'en'),
													('logout', 'Abmelden', 'de'),
													('logout', 'Desconectar', 'es'),
													('name', 'Name', 'en'),
													('name', 'Name', 'de'),
													('name', 'Nombre', 'es'),
													('male', 'Male', 'en'),
													('male', 'Mänlich', 'de'),
													('male', 'Masculino', 'es'),
													('female', 'Female', 'en'),
													('female', 'Weiblich', 'de'),
													('female', 'Femenino', 'es'),
													('searchstring', 'Search string', 'en'),
													('searchstring', 'Suchzeichenkette', 'de'),
													('searchstring', 'Secuencia de la búsqueda', 'es'),
													('search', 'Search', 'en'),
													('search', 'Suchen', 'de'),
													('search', 'Buscar', 'es'),
													('location', 'Location', 'en'),
													('location', 'Ort', 'de'),
													('location', 'Localización', 'es'),
													('email', 'Email', 'en'),
													('email', 'Email', 'de'),
													('email', 'Email', 'es'),
													('homepage', 'Homepage', 'en'),
													('homepage', 'Homepage', 'de'),
													('homepage', 'Homepage', 'es'),
													('website', 'Website', 'en'),
													('website', 'Webseite', 'de'),
													('website', 'Pagina Web', 'es'),
													('company', 'Company', 'en'),
													('company', 'Firma', 'de'),
													('company', 'Compañía', 'es'),
													('text', 'Text', 'en'),
													('text', 'Text', 'de'),
													('text', 'Texto', 'es'),
													('de', 'German', 'en'),
													('de', 'Deutsch', 'de'),
													('de', 'Alemán', 'es'),
													('en', 'English', 'en'),
													('en', 'Englisch', 'de'),
													('en', 'Inglés', 'es'),
													('es', 'Spanish', 'en'),
													('es', 'Spanisch', 'de'),
													('es', 'Español', 'es'),
													('pt', 'Portuguese', 'en'),
													('pt', 'Portugiesisch', 'de'),
													('pt', 'Portugués', 'es'),
													('language', 'Language', 'en'),
													('language', 'Sprache', 'de'),
													('language', 'Lenguage', 'es'),
													('msg_sendingmsg', 'sending Message', 'en'),
													('msg_sendingmsg', 'sende Nachricht', 'de'),
													('msg_sendingmsg', 'enviado el mensaje', 'es'),
													('msg_msgsent', 'The message was sent!', 'en'),
													('msg_msgsent', 'Die Nachricht wurde gesendet!', 'de'),
													('msg_msgsent', '¡El mensaje fue enviado!', 'es'),
													('msg_noacces', 'Restricted access! Please login.', 'en'),
													('msg_noacces', 'Eingeschränkter Zugang! Bitte anmelden.', 'de'),
													('msg_noacces', 'Acceso restricto! Por favor conectese.', 'es'),
													('msg_profile_saved', 'Profile saved.', 'en'),
													('msg_profile_saved', 'Profil wurde gespeichert.', 'de'),
													('msg_profile_saved', 'El perfil fue guardado.', 'es'),
													('msg_profile_notsaved', 'Profile could not be saved!', 'en'),
													('msg_profile_notsaved', 'Profil konnte nicht gespeichert werden!', 'de'),
													('msg_profile_notsaved', 'El perfil no pudo ser guardado!', 'es'),
													('msg_passwd_saved', 'Password saved.', 'en'),
													('msg_passwd_saved', 'Kennwort wurde gespeichert.', 'de'),
													('msg_passwd_saved', 'Contraseña fue guardada.', 'es'),
													('msg_passwd_notsaved', 'Password could not be saved!', 'en'),
													('msg_passwd_notsaved', 'Kennwort konnte nicht gespeichert werden!', 'de'),
													('msg_passwd_notsaved', 'La contraseña no pudo ser guardada!', 'es'),
													('user', 'User', 'en'),
													('user', 'Benutzer', 'de'),
													('user', 'Usuario', 'es'),
													('password', 'Password', 'en'),
													('password', 'Kennwort', 'de'),
													('password', 'Contraseña', 'es'),
													('rate', 'Rate', 'en'),
													('rate', 'Bewerten', 'de'),
													('rate', 'Evaluar', 'es'),
													('nocmnt', 'no comment', 'en'),
													('nocmnt', 'keine Kommentare', 'de'),
													('nocmnt', 'ningún comentario', 'es'),
													('addcmnt', 'Add comment', 'en'),
													('addcmnt', 'Kommentar hinzufügen', 'de'),
													('addcmnt', 'Añadir comentario', 'es'),
													('comments', 'Comments', 'en'),
													('comments', 'Kommentare', 'de'),
													('comments', 'Comentarios', 'es'),
													('oldpwd', 'Old password', 'en'),
													('oldpwd', 'Altes Kennwort', 'de'),
													('oldpwd', 'Contraseña vieja', 'es'),
													('newpwd', 'New password', 'en'),
													('newpwd', 'Neues Kennwort', 'de'),
													('newpwd', 'Contraseña nueva', 'es'),
													('confpwd', 'Confirm password', 'en'),
													('confpwd', 'Kennwort bestätigen', 'de'),
													('confpwd', 'Confirme la contraseña', 'es'),
													('guestbook_sign', 'Sign Guestbook', 'en'),
													('guestbook_sign', 'Ins Gästebuch eintragen', 'de'),
													('guestbook_sign', 'Firmar libro de visitas', 'es'),
													('photoalbum_presence', 'Presence', 'en'),
													('photoalbum_presence', ' Anwesenheit', 'de'),
													('photoalbum_presence', 'Presencia', 'es'),
													('photoalbum_activities', 'Activities', 'en'),
													('photoalbum_activities', 'Tätigkeiten', 'de'),
													('photoalbum_activities', 'Actividades', 'es'),
													('photoalbum_location', 'Location', 'en'),
													('photoalbum_location', 'Ort', 'de'),
													('photoalbum_location', 'Localización', 'es'),
													('value', 'Value', 'en'),
													('value', 'Wert', 'de'),
													('value', 'Valor', 'es'),
													('insert', 'Insert', 'en'),
													('insert', 'Einfügen', 'de'),
													('insert', 'Insertar', 'es'),
													('system', 'System', 'en'),
													('system', 'System', 'de'),
													('system', 'Sistema', 'es'),
													('settings', 'Settings', 'en'),
													('settings', 'Einstellungen', 'de'),
													('settings', 'Ajustes', 'es'),
													('domains', 'Domains', 'en'),
													('domains', 'Domains', 'de'),
													('domains', 'Dominios', 'es'),
													('applications', 'Applications', 'en'),
													('applications', 'Anwendungen', 'de'),
													('applications', 'Aplicaciones', 'es'),
													('extensions', 'Extensions', 'en'),
													('extensions', 'Extensions', 'de'),
													('extensions', 'Extensiones', 'es'),
													('actions', 'Actions', 'en'),
													('actions', 'Aktionen', 'de'),
													('actions', 'Acciones', 'es'),
													('home', 'Home', 'en'),
													('home', 'Home', 'de'),
													('home', 'Inicio', 'es'),
													('dictionary', 'Dictionary', 'en'),
													('dictionary', 'Wörterbuch', 'de'),
													('dictionary', 'Diccionario', 'es'),
													('pages', 'Pages', 'en'),
													('pages', 'Seiten', 'de'),
													('pages', 'Páginas', 'es'),
													('description', 'Description', 'en'),
													('description', 'Beschreibung', 'de'),
													('description', 'Descripción', 'es'),
													('new', 'New', 'en'),
													('new', 'Neu', 'de'),
													('new', 'Nuevo', 'es'),
													('delete', 'Delete', 'en'),
													('delete', 'Löschen', 'de'),
													('delete', 'Eliminar', 'es'),
													('view', 'View', 'en'),
													('view', 'Sehen', 'de'),
													('view', 'Ver', 'es'),
													('instanceinfo', 'Instance Info', 'en'),
													('instanceinfo', 'Instanz Info', 'de'),
													('instanceinfo', 'Informacion de Instancia', 'es'),
													('dbhcms_adminwelcome', 'Welcome to the DBHcms administration!', 'en'),
													('dbhcms_adminwelcome', 'Willkommen zu die DBHcms administration!', 'de'),
													('dbhcms_adminwelcome', '¡Bienvenido a la administracion del DBHcms!', 'es'),
													('edit', 'Edit', 'en'),
													('edit', 'Bearbeiten', 'de'),
													('edit', 'Editar', 'es'),
													('close', 'Close', 'en'),
													('close', 'Schliessen', 'de'),
													('close', 'Cerrar', 'es'),
													('date', 'Date', 'en'),
													('date', 'Datum', 'de'),
													('date', 'Fecha', 'es'),
													('parameter', 'Parameter', 'en'),
													('parameter', 'Parameter', 'de'),
													('parameter', 'Parámetro', 'es'),
													('type', 'Type', 'en'),
													('type', 'Typ', 'de'),
													('type', 'Tipo', 'es'),
													('dbhcms_desc_pagetemplates', 'HTML template files for the page', 'en'),
													('dbhcms_desc_pagetemplates', 'HTML Template-Dateien für die Seite', 'de'),
													('dbhcms_desc_pagetemplates', 'Archivos HTML como plantillas para la página', 'es'),
													('dbhcms_desc_pagestylesheets', 'CSS files for the page', 'en'),
													('dbhcms_desc_pagestylesheets', 'CSS Dateien für die Seite', 'de'),
													('dbhcms_desc_pagestylesheets', 'Archivos CSS para la página', 'es'),
													('dbhcms_desc_pagejavascripts', 'JAVA files for the page', 'en'),
													('dbhcms_desc_pagejavascripts', 'JAVA Dateien für die Seite', 'de'),
													('dbhcms_desc_pagejavascripts', 'Archivos de JAVA para la página', 'es'),
													('users', 'Users', 'en'),
													('users', 'Benutzer', 'de'),
													('users', 'Usuarios', 'es'),
													('donttranslate', 'Don''t translate', 'en'),
													('donttranslate', 'Nicht übersetzen', 'de'),
													('donttranslate', 'No traducir', 'es'),
													('translatefrom', 'Translate from', 'en'),
													('translatefrom', 'Übersetzten von', 'de'),
													('translatefrom', 'Traducir de', 'es'),
													('results', 'Results', 'en'),
													('results', 'Ergebnisse', 'de'),
													('results', 'Resultados', 'es'),
													('result', 'Result', 'en'),
													('result', 'Ergebniss', 'de'),
													('result', 'Resultado', 'es'),
													('nl', 'Dutch', 'en'),
													('nl', 'Holländisch', 'de'),
													('nl', 'Holandés', 'es'),
													('fr', 'French', 'en'),
													('fr', 'Französisch', 'de'),
													('fr', 'Francés', 'es'),
													('el', 'Greek', 'en'),
													('el', 'Griechisch', 'de'),
													('el', 'Griego', 'es'),
													('it', 'Italian', 'en'),
													('it', 'Italienisch', 'de'),
													('it', 'Italiano', 'es'),
													('zh', 'Chinese', 'en'),
													('zh', 'Chinesisch', 'de'),
													('zh', 'Chino', 'es'),
													('ja', 'Japanese', 'en'),
													('ja', 'Japanisch', 'de'),
													('ja', 'Japonés', 'es'),
													('ko', 'Korean', 'en'),
													('ko', 'Koreanisch', 'de'),
													('ko', 'Coreano', 'es'),
													('ru', 'Russian', 'en'),
													('ru', 'Russisch', 'de'),
													('ru', 'Ruso', 'es'),
													('zt', 'Chinese-Traditional', 'en'),
													('zt', 'Chinesisch-vereinf.', 'de'),
													('zt', 'Chino-Tradicional', 'es'),
													('sex', 'Sex', 'en'),
													('sex', 'Geschlecht', 'de'),
													('sex', 'Sexo', 'es'),
													('level', 'Level', 'en'),
													('level', 'Level', 'de'),
													('level', 'Nivel', 'es'),
													('levels', 'Levels', 'en'),
													('levels', 'Levels', 'de'),
													('levels', 'Niveles', 'es'),
													('menus', 'Menus', 'en'),
													('menus', 'Menüs', 'de'),
													('menus', 'Menus', 'es'),
													('menu', 'Menu', 'en'),
													('menu', 'Menü', 'de'),
													('menu', 'Menu', 'es'),
													('layer', 'Layer', 'en'),
													('layer', 'Ebene', 'de'),
													('layer', 'Capa', 'es'),
													('depth', 'Depth', 'en'),
													('depth', 'Tiefe', 'de'),
													('depth', 'Profundidad', 'es'),
													('showrestrictedpages', 'Show restricted pages', 'en'),
													('showrestrictedpages', 'Zeige eingeschränkte seiten', 'de'),
													('showrestrictedpages', 'Mostrar paginas restringidas', 'es'),
													('hello', 'Hello', 'en'),
													('hello', 'Hallo', 'de'),
													('hello', 'Hola', 'es'),
													('logedinas', 'You are loged in as', 'en'),
													('logedinas', 'Du bist eingelogt als', 'de'),
													('logedinas', 'Estas conectado como', 'es'),
													('welcome', 'Welcome', 'en'),
													('welcome', 'Bienvenido', 'es'),
													('chooselang', 'Choose your language', 'en'),
													('chooselang', 'Wählen Sie Ihre Sprache', 'de'),
													('chooselang', 'Elija su idioma', 'es'),
													('msg_login_ok', 'Login was succesfull!', 'en'),
													('msg_login_ok', 'Anmeldung war erfolgreich!', 'de'),
													('msg_login_ok', '¡Conexión exitosa!', 'es'),
													('msg_login_wrong', 'Login was not succesfull!', 'en'),
													('msg_login_wrong', 'Anmeldung fehlgeschlagen!', 'de'),
													('msg_login_wrong', '¡Conexión no tuvo exito!', 'es'),
													('title', 'Title', 'en'),
													('title', 'Titel', 'de'),
													('title', 'Titulo', 'es'),
													('folder', 'Folder', 'en'),
													('folder', 'Ordner', 'de'),
													('folder', 'Carpeta', 'es'),
													('action', 'Action', 'en'),
													('action', 'Aktion', 'de'),
													('action', 'Accion', 'es'),
													('welcome', 'Willkommen', 'de'),
													('dbhcms_desc_pagephpmodules', 'PHP files for the page', 'en'),
													('dbhcms_desc_pagephpmodules', 'PHP Dateien für die Seite', 'de'),
													('dbhcms_desc_pagephpmodules', 'Archivos PHP para la página', 'es'),
													('dbhcms_desc_langcontent', 'The page content', 'en'),
													('dbhcms_desc_langcontent', 'Der Seiteninhalt', 'de'),
													('dbhcms_desc_langcontent', 'El contenido de la página', 'es'),
													('dbhcms_desc_langjavascripts', 'JAVA files for the page in the selected language', 'en'),
													('dbhcms_desc_langjavascripts', 'JAVA Dateien für die Seite in der ausgewählten Sprache', 'de'),
													('dbhcms_desc_langjavascripts', 'Archivos de JAVA para la página en el idioma escojido', 'es'),
													('dbhcms_desc_langname', 'The name of the page', 'en'),
													('dbhcms_desc_langname', 'Der Name der Seite', 'de'),
													('dbhcms_desc_langname', 'El nombre de la página', 'es'),
													('dbhcms_desc_langphpmodules', 'PHP files for the page in the selected language', 'en'),
													('dbhcms_desc_langphpmodules', 'PHP Dateien für die Seite in der ausgewählten Sprache', 'de'),
													('dbhcms_desc_langphpmodules', 'Archivos PHP para la página en el idioma escojido', 'es'),
													('dbhcms_desc_langstylesheets', 'CSS files for the page in the selected language', 'en'),
													('dbhcms_desc_langstylesheets', 'CSS Dateien für die Seite in der ausgewählten Sprache', 'de'),
													('dbhcms_desc_langstylesheets', 'Archivos CSS para la página en el idioma escojido', 'es'),
													('dbhcms_desc_langtemplates', 'HTML template files for the page in the selected language', 'en'),
													('dbhcms_desc_langtemplates', 'HTML Template-Dateien für die Seite in der ausgewählten Sprache', 'de'),
													('dbhcms_desc_langtemplates', 'Archivos HTML como plantillas para la página en el idioma escojido', 'es'),
													('dbhcms_desc_langurl', 'Prefix for the url of the page', 'en'),
													('dbhcms_desc_langurl', 'Prefix für die url der Seite', 'de'),
													('dbhcms_desc_langurl', 'Prefijo para la url de la página', 'es'),
													('dbhcms_desc_pagedomain', 'Domain in which the page is', 'en'),
													('dbhcms_desc_pagedomain', 'Domain der die Seite zugehört', 'de'),
													('dbhcms_desc_pagedomain', 'Dominio al que pertenese la página', 'es'),
													('dbhcms_desc_pagepapage', 'Parent page', 'en'),
													('dbhcms_desc_pagepapage', 'Übergeordnete Seite', 'de'),
													('dbhcms_desc_pagepapage', 'Página sobreordenada', 'es'),
													('dbhcms_desc_pageposnr', 'Order of the page in the menu', 'en'),
													('dbhcms_desc_pageposnr', 'Reihenfolge der Seite im Menu', 'de'),
													('dbhcms_desc_pageposnr', 'Orden de la pagina en el menu', 'es'),
													('dbhcms_desc_pagehide', 'Shows or hides the page', 'en'),
													('dbhcms_desc_pagehide', 'Anzeigen oder verbergen der Seite', 'de'),
													('dbhcms_desc_pagehide', 'Hace visible o esconde la página', 'es'),
													('dbhcms_desc_pagestart', 'Date and time to publish the page', 'en'),
													('dbhcms_desc_pagestart', 'Datum und Zeit ab wann die Seite angezeigt werden soll', 'de'),
													('dbhcms_desc_pagestart', 'Fecha y hora apartir de cuando la pagina es visible', 'es'),
													('dbhcms_desc_pagestop', 'Date and time to hide the page', 'en'),
													('dbhcms_desc_pagestop', 'Datum und Zeit bis wann die Seite angezeigt werden soll', 'de'),
													('dbhcms_desc_pagestop', 'Fecha y hora hasta cuando la pagina es visible', 'es'),
													('dbhcms_desc_pageinmenu', 'Should the page be visible in a menu?', 'en'),
													('dbhcms_desc_pageinmenu', 'Soll die Seite in ein Menu angezeigt werden?', 'de'),
													('dbhcms_desc_pageinmenu', '¿Es la página visible en un menu?', 'es'),
													('dbhcms_desc_pageext', 'Extensions to be loaded in the page', 'en'),
													('dbhcms_desc_pageext', 'Erweiterungen die für diese Seiten zu laden sind', 'de'),
													('dbhcms_desc_pageext', 'Extensiones para ser cargados en la página', 'es'),
													('dbhcms_desc_pageul', 'User access level for the page', 'en'),
													('dbhcms_desc_pageul', 'Benutzer Berechtigungslevel für die Seite', 'de'),
													('dbhcms_desc_pageul', 'Nivel de acceso para usuarios para la página', 'es'),
													('dbhcms_desc_pagedesc', 'Description of the page', 'en'),
													('dbhcms_desc_pagedesc', 'Beschreibung der Seite', 'de'),
													('dbhcms_desc_pagedesc', 'Descripción de la página', 'es'),
													('msg_session_expired', 'Your session has expired! Please login again.', 'en'),
													('msg_session_expired', 'Ihre Sitzung ist abgelaufen! Bitte melden Sie sich erneut an.', 'de'),
													('msg_session_expired', 'Su sesión expiró! Porfavor conectese nuevamente.', 'es'),
													('messages', 'Messages', 'en'),
													('messages', 'Nachrichten', 'de'),
													('messages', 'Mensajes', 'es'),
													('message', 'Message', 'en'),
													('message', 'Nachricht', 'de'),
													('message', 'Mensaje', 'es'),
													('back', 'Back', 'en'),
													('back', 'Zurück', 'de'),
													('back', 'Atras', 'es'),
													('parameters', 'Parameters', 'en'),
													('parameters', 'Parameter', 'de'),
													('parameters', 'Parámetros', 'es'),
													('details', 'Details', 'en'),
													('details', 'Details', 'de'),
													('details', 'Detalles', 'es'),
													('dbhcms_desc_pageshortcut', 'Set to NULL (0) for no shortcut, else select the page to be linked', 'en'),
													('dbhcms_desc_pageshortcut', 'NULL(0) wählen für keine Verknüpfung, ansonsten die Seite wählen wo verknüpft werden soll', 'de'),
													('dbhcms_desc_pageshortcut', 'Escoja NULL (0) si no desea ningun enlace. Si lo desea, escoja la página con la que se desea enlasar', 'es'),
													('dbhcms_desc_pagelink', 'Leave blank for no link, else type the complete URL for your link. Example: http://www.drbenhur.com', 'en'),
													('dbhcms_desc_pagelink', 'Leer lassen um kein link zu erstellen, ansonsten komplette URL für den Link eingeben: Bespiel: http://www.drbenhur.com', 'de'),
													('dbhcms_desc_pagelink', 'Deje vacio si no desea ningun enlace. Si lo desea, ingrese la URL completa para el enlace: Ejemplo: http://www.drbenhur.com', 'es'),
													('dbhcms_desc_pagetarget', 'Target of the link to the page. Example: \"_blank\" for a new window', 'en'),
													('dbhcms_desc_pagetarget', 'Das Target des link zur Seite. Beispiel: \"_blank\" für ein neues Fenster', 'de'),
													('dbhcms_desc_pagetarget', 'El target del enlace a la pagina. Ejemplo : \"_blank\" para una nueva ventana', 'es'),
													('readmore', 'Read more', 'en'),
													('readmore', 'Mehr lesen', 'de'),
													('readmore', 'Leer más', 'es'),
													('news_subscnl', 'Subscribe Newsletter', 'en'),
													('news_subscnl', 'Newsletter abonnieren', 'de'),
													('news_subscnl', 'Suscribir al newsletter', 'es'),
													('subject', 'Subject', 'en'),
													('subject', 'Betreff', 'de'),
													('subject', 'Asunto', 'es'),
													('news_sendnl', 'Send newsletter', 'en'),
													('news_sendnl', 'Newsletter versenden', 'de'),
													('news_sendnl', 'Enviar newsletter', 'es'),
													('news_unsubscnl', 'Your Subscription to the newsletter is now cancelled', 'en'),
													('news_unsubscnl', 'Das Newsletter wurde abbestellt', 'de'),
													('news_unsubscnl', 'La subscripción al newsletter fue cancelada', 'es'),
													('domain', 'Domain', 'en'),
													('domain', 'Domain', 'de'),
													('domain', 'Dominio', 'es'),
													('days', 'Days', 'en'),
													('days', 'Tage', 'de'),
													('days', 'Días', 'es'),
													('hours', 'Hours', 'en'),
													('hours', 'Stunden', 'de'),
													('hours', 'Horas', 'es'),
													('minutes', 'Minutes', 'en'),
													('minutes', 'Minuten', 'de'),
													('minutes', 'Minutos', 'es'),
													('seconds', 'Seconds', 'en'),
													('seconds', 'Sekunden', 'de'),
													('seconds', 'Segundos', 'es'),
													('and', 'and', 'en'),
													('and', 'und', 'de'),
													('and', 'y', 'es'),
													('myweather', 'My Weather', 'en'),
													('myweather', 'Mein Wetter', 'de'),
													('myweather', 'Mi Clima', 'es'),
													('dbhcms_desc_pagehierarchy', 'The hierarchy of the page', 'en'),
													('dbhcms_desc_pagehierarchy', 'The hierarchy of the page', 'de'),
													('dbhcms_desc_pagehierarchy', 'The hierarchy of the page', 'es'),
													('dbhcms_desc_pagecache', 'Page caching', 'en'),
													('dbhcms_desc_pagecache', 'Page caching', 'de'),
													('dbhcms_desc_pagecache', 'Page caching', 'es'),
													('af', 'Afrikaans', 'de'),
													('af', 'Africaans', 'es'),
													('sq', 'Albanian', 'en'),
													('sq', 'Albanisch', 'de'),
													('sq', 'Albanés', 'es'),
													('eu', 'Basque', 'en'),
													('eu', 'Baskisch', 'de'),
													('eu', 'Vasco', 'es'),
													('bg', 'Bulgarian', 'en'),
													('bg', 'Bulgarisch', 'de'),
													('bg', 'Búlgaro', 'es'),
													('be', 'Byelorussian', 'en'),
													('be', 'Byelorussisch', 'de'),
													('be', 'Byelorussian', 'es'),
													('ca', 'Catalan', 'en'),
													('ca', 'Katalanisch', 'de'),
													('ca', 'Catalán', 'es'),
													('hr', 'Croatian', 'en'),
													('hr', 'Kroatisch', 'de'),
													('hr', 'Croata', 'es'),
													('cs', 'Czech', 'en'),
													('cs', 'Tschechisch', 'de'),
													('cs', 'Checo', 'es'),
													('da', 'Danish', 'en'),
													('da', 'Dänisch', 'de'),
													('da', 'Danés', 'es'),
													('et', 'Estonian', 'en'),
													('et', 'Estnisch', 'de'),
													('et', 'Estonio', 'es'),
													('fo', 'Faeroese', 'en'),
													('fo', 'Faröer', 'de'),
													('fo', 'Faeroese', 'es'),
													('fi', 'Finnish', 'en'),
													('fi', 'Finnisch', 'de'),
													('fi', 'Finlandés', 'es'),
													('gd', 'Gaelic', 'en'),
													('gd', 'Gälisch', 'de'),
													('gd', 'Gaélico', 'es'),
													('gl', 'Galician', 'en'),
													('gl', 'Galician', 'de'),
													('gl', 'Gallego', 'es'),
													('hu', 'Hungarian', 'en'),
													('hu', 'Ungarisch', 'de'),
													('hu', 'Húngaro', 'es'),
													('is', 'Icelandic', 'en'),
													('is', 'Isländisch', 'de'),
													('is', 'Islandés', 'es'),
													('fa', 'Farsi', 'en'),
													('fa', 'Farsi', 'de'),
													('fa', 'Farsi', 'es'),
													('hi', 'Hindi', 'en'),
													('hi', 'Hindi', 'de'),
													('hi', 'Hindi', 'es'),
													('id', 'Indonesian', 'en'),
													('id', 'Indonesisch', 'de'),
													('id', 'Indonesio', 'es'),
													('ga', 'Irish', 'en'),
													('ga', 'Irish', 'de'),
													('ga', 'Irish', 'es'),
													('lv', 'Latvian', 'en'),
													('lv', 'Latvian', 'de'),
													('lv', 'Letón', 'es'),
													('lt', 'Lithuanian', 'en'),
													('lt', 'Litauer', 'de'),
													('lt', 'Lituanes', 'es'),
													('mk', 'Macedonian', 'en'),
													('mk', 'Macedonisch', 'de'),
													('mk', 'Macedónico', 'es'),
													('ms', 'Malaysian', 'en'),
													('ms', 'Malaysisch', 'de'),
													('ms', 'Malasio', 'es'),
													('mt', 'Maltese', 'en'),
													('mt', 'Maltesisch', 'de'),
													('mt', 'Maltés', 'es'),
													('no', 'Norwegian', 'en'),
													('no', 'Norwegisch', 'de'),
													('no', 'Noruego', 'es'),
													('pl', 'Polish', 'en'),
													('pl', 'Polnisch', 'de'),
													('pl', 'Polaco', 'es'),
													('rm', 'Rhaeto-Romanic', 'en'),
													('rm', 'Rhaeto-Romanic', 'de'),
													('rm', 'Rhaeto-Romanic', 'es'),
													('ro', 'Romanian', 'en'),
													('ro', 'Rumänisch', 'de'),
													('ro', 'Rumano', 'es'),
													('sr', 'Serbian', 'en'),
													('sr', 'Serbe', 'de'),
													('sr', 'Servio', 'es'),
													('sk', 'Slovak', 'en'),
													('sk', 'Slowake', 'de'),
													('sk', 'Eslovaco', 'es'),
													('sl', 'Slovenian', 'en'),
													('sl', 'Slowenisch', 'de'),
													('sl', 'Esloveno', 'es'),
													('sb', 'Sorbian', 'en'),
													('sb', 'Sorbian', 'de'),
													('sb', 'Sorbian', 'es'),
													('sv', 'Swedish', 'en'),
													('sv', 'Schwedisch', 'de'),
													('sv', 'Sueco', 'es'),
													('ts', 'Thai', 'en'),
													('ts', 'Siamesisch', 'de'),
													('ts', 'Tailandés', 'es'),
													('tn', 'Tswana', 'en'),
													('tn', 'Tswana', 'de'),
													('tn', 'Tswana', 'es'),
													('tr', 'Turkish', 'en'),
													('tr', 'Türkisch', 'de'),
													('tr', 'Turco', 'es'),
													('uk', 'Ukrainian', 'en'),
													('uk', 'Ukrainisch', 'de'),
													('uk', 'Ucraniano', 'es'),
													('ur', 'Urdu', 'en'),
													('ur', 'Urdu', 'de'),
													('ur', 'Urdu', 'es'),
													('vi', 'Vietnamese', 'en'),
													('vi', 'Vietnamesisch', 'de'),
													('vi', 'Vietnamita', 'es'),
													('zu', 'Zulu', 'en'),
													('zu', 'Zulu', 'de'),
													('zu', 'Zulú', 'es'),
													('af', 'Afrikaans', 'en'),
													('cancel', 'Cancel', 'en'),
													('cancel', 'Cancelar', 'es'),
													('cancel', 'Abbrechen', 'de'),
													('month_1', 'January', 'en'),
													('month_1', 'Enero', 'es'),
													('month_1', 'Januar', 'de'),
													('month_2', 'February', 'en'),
													('month_2', 'Febrero', 'es'),
													('month_2', 'Februar', 'de'),
													('month_3', 'March', 'en'),
													('month_3', 'Marzo', 'es'),
													('month_3', 'März', 'de'),
													('month_4', 'April', 'en'),
													('month_4', 'Abril', 'es'),
													('month_4', 'April', 'de'),
													('month_5', 'May', 'en'),
													('month_5', 'Mayo', 'es'),
													('month_5', 'Mai', 'de'),
													('month_6', 'June', 'en'),
													('month_6', 'Junio', 'es'),
													('month_6', 'Juni', 'de'),
													('month_7', 'July', 'en'),
													('month_7', 'Julio', 'es'),
													('month_7', 'Juli', 'de'),
													('month_8', 'August', 'en'),
													('month_8', 'Agosto', 'es'),
													('month_8', 'August', 'de'),
													('month_9', 'September', 'en'),
													('month_9', 'Septiembre', 'es'),
													('month_9', 'September', 'de'),
													('month_10', 'October', 'en'),
													('month_10', 'Octubre', 'es'),
													('month_10', 'Oktober', 'de'),
													('month_11', 'November', 'en'),
													('month_11', 'Noviembre', 'es'),
													('month_11', 'November', 'de'),
													('month_12', 'December', 'en'),
													('month_12', 'Diciembre', 'es'),
													('month_12', 'Dezember', 'de'),
													('dbhcms_desc_pageschedule', 'Schedule start and stop times', 'en'),
													('dbhcms_desc_pageschedule', 'Schedule start and stop times', 'es'),
													('dbhcms_desc_pageschedule', 'Schedule start and stop times', 'de'),
													('dbhcms_msg_settingssaved', 'The settings have been saved.', 'en'),
													('dbhcms_msg_settingssaved', 'Los ajustes fueron guardados.', 'es'),
													('dbhcms_msg_settingssaved', 'Die Einstellungen wurden gespeichert.', 'de'),
													('dbhcms_msg_settingsnotsaved', 'The settings could not be saved.', 'en'),
													('dbhcms_msg_settingsnotsaved', 'No fue posible guardar los ajustes.', 'es'),
													('dbhcms_msg_settingsnotsaved', 'Die Einstellungen konnten nicht gespeichert werden.', 'de'),
													('votes', 'Votes', 'en'),
													('votes', 'Votos', 'es'),
													('votes', 'Abstimmungen', 'de'),
													('firstpic', 'First picture', 'en'),
													('firstpic', 'Primera foto', 'es'),
													('firstpic', 'Erstes Bild', 'de'),
													('lastpic', 'Last picture', 'en'),
													('lastpic', 'Ultima foto', 'es'),
													('lastpic', 'Letztes Bild', 'de'),
													('nextpic', 'Next picture', 'en'),
													('nextpic', 'Próxima foto', 'es'),
													('nextpic', 'Nächstes Bild', 'de'),
													('previouspic', 'Previous picture', 'en'),
													('previouspic', 'Anterior foto', 'es'),
													('previouspic', 'Vorheriges Bild', 'de'),
													('albums', 'Albums', 'en'),
													('albums', 'Álbumes', 'es'),
													('albums', 'Alben', 'de'),
													('articles', 'Articles', 'en'),
													('articles', 'Articulos', 'es'),
													('articles', 'Artikel', 'de'),
													('article', 'Article', 'en'),
													('article', 'Articulo', 'es'),
													('article', 'Artikel', 'de'),
													('album', 'Album', 'en'),
													('album', 'Album', 'es'),
													('album', 'Album', 'de'),
													('news', 'News', 'en'),
													('news', 'Notícias', 'es'),
													('news', 'Nachrichten', 'de'),
													('add','Add','en'),
													('add','Hinzufügen','de'),
													('add','Añadir','es'),
													('up','Up','en'),
													('up','Hoch','de'),
													('up','Arriba','es'),
													('down','Down','en'),
													('down','Runter','de'),
													('down','Abajo','es'),
													('dbhcms_msg_askdeleteitem','Are you sure you want to delete this item?','en'),
													('dbhcms_msg_askdeleteitem','Sind Sie sicher das Sie diesen eintrag löschen möchten?','de'),
													('dbhcms_msg_askdeleteitem','¿Esta seguro que desea eliminar este ítem?','es'),
													('typecaptcha', 'Enter the code', 'en'),
													('typecaptcha', 'Code eingeben', 'de'),
													('typecaptcha', 'Introduzca el código', 'es'),
													('dbhcms_desc_avaliable_extensions', 'Names of avaliable extensions.', 'en'),
													('dbhcms_desc_avaliable_extensions', 'Namen der verfügbaren extensions.', 'de'),
													('dbhcms_desc_avaliable_extensions', 'Nombres de las extenciones que estan a disposicion.', 'es'),
													('dbhcms_desc_cachetime', 'Time (minutes) that the page cache is saved. After expiration, cache will be rewritten.', 'en'),
													('dbhcms_desc_cachetime', 'Zeit (minuten) das der Seiten-Cache gespeichert werden soll. Nach ablauf der zeit, wird der Seiten-Cache überschrieben.', 'de'),
													('dbhcms_desc_cachetime', 'Tiempo (minutos) que el cache de pagina se debe guardar. Después del tiempo, se rescribe el cache.', 'es'),
													('dbhcms_desc_cssdir', 'Directory in which the CSS files are stored.', 'en'),
													('dbhcms_desc_cssdir', 'Ordner in dem die CSS Dateien gespeichert sind.', 'de'),
													('dbhcms_desc_cssdir', 'Directorio donde están guardados los ficheros CSS.', 'es'),
													('dbhcms_desc_dateformatdb', 'Date format for the database. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_dateformatdb', 'Datum-Formatierung für die Datenbank. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_dateformatdb', 'Formato de fechas para la base de datos. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_timeformatdb', 'Time format for the database. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_timeformatdb', 'Zeit-Formatierung für die Datenbank. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_timeformatdb', 'Formato de horas para la base de datos. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_timeformatfe', 'Time format for the output. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_timeformatfe', 'Zeit-Formatierung für die Ausgabe. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_timeformatfe', 'Formato de horas para mostrar. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_dateformatfe', 'Date format for the output. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_dateformatfe', 'Datum-Formatierung für die Ausgabe. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_dateformatfe', 'Formato de fechas para mostrar. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_datetimeformatfe', 'Date and time format for the output. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_datetimeformatfe', 'Datum und Zeit-Formatierung für die Ausgabe. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_datetimeformatfe', 'Formato de fechas y horas para mostrar. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_datetimeformatdb', 'Date and time format for the database. Equivalent to the \"date()\" function in PHP.', 'en'),
													('dbhcms_desc_datetimeformatdb', 'Datum und Zeit-Formatierung für die Datenbank. Entspricht der PHP funktion \"date()\".', 'de'),
													('dbhcms_desc_datetimeformatdb', 'Formato de fechas y horas para la base de datos. Corresponde a la función \"date()\" de PHP.', 'es'),
													('dbhcms_desc_debugmodus', 'Debug Modus. Set \"True\" to enable and \"False\" to disable it.', 'en'),
													('dbhcms_desc_debugmodus', 'Debug Modus. Auf \"True\" setzen um es zu aktivieren oder auf \"False\" um es zu deaktivieren.', 'de'),
													('dbhcms_desc_debugmodus', 'Modo Debug. Escojer \"True\" para activarlo o \"False\" para desactivarlo.', 'es'),
													('dbhcms_desc_dictlang', 'Languages to be used in the dictionary.', 'en'),
													('dbhcms_desc_dictlang', 'Sprachen die im Wörterbuch verwendet werden sollen.', 'de'),
													('dbhcms_desc_dictlang', 'Idiomas que se usaran en el diccionario.', 'es'),
													('dbhcms_desc_imgdir', 'Directory in which the image files are stored.', 'en'),
													('dbhcms_desc_imgdir', 'Ordner in dem die Bild-Dateien gespeichert sind.', 'de'),
													('dbhcms_desc_imgdir', 'Directorio donde están guardados los ficheros de imagenes.', 'es'),
													('dbhcms_desc_javadir', 'Directory in which the java files are stored.', 'en'),
													('dbhcms_desc_javadir', 'Ordner in dem die Java-Dateien gespeichert sind.', 'de'),
													('dbhcms_desc_javadir', 'Directorio donde están guardados los ficheros de java.', 'es'),
													('dbhcms_desc_phpdir', 'Directory in which the PHP files are stored.', 'en'),
													('dbhcms_desc_phpdir', 'Ordner in dem die PHP-Dateien gespeichert sind.', 'de'),
													('dbhcms_desc_phpdir', 'Directorio donde están guardados los ficheros de PHP.', 'es'),
													('dbhcms_desc_rootdir', 'Root directory of the actual DBHcms installation.', 'en'),
													('dbhcms_desc_rootdir', 'Root Ordner von der aktuellen DBHcms Installation.', 'de'),
													('dbhcms_desc_rootdir', 'Directorio root de la instalacion actual del DBHcms.', 'es'),
													('dbhcms_desc_sessactivetime', 'Time (minutes) in which the session is active. After expiration, session will be deactivated but not yet eliminated. In this state the user is \"absent\".  Countdown begins with the last action of the user.', 'en'),
													('dbhcms_desc_sessactivetime', 'Zeit (minuten) in dem sie Sitzung aktiv ist. Nach ablauf der zeit, wird die Sitzung auf inaktiv gesetzt aber noch nicht gelöscht. In diesem Zustand ist der Benutzer \"abwesend\". Der countdown beginnt mit der letzten Aktion des benutzers.', 'de'),
													('dbhcms_desc_sessactivetime', 'Tiempo (minutos) donde la sessión esta activa. Después del tiempo, se desactiva la session pero no se elimina. En este estado el usuario esta \"ausente\".  El countdown empieza con la ultima accion del usuario.', 'es'),
													('dbhcms_desc_sesslifetime', 'Time (minutes) in which the session is alive. After expiration, session will be eliminated and the user loged out. Countdown begins with the last action of the user.', 'en'),
													('dbhcms_desc_sesslifetime', 'Zeit (minuten) in dem sie Sitzung am leben ist. Nach ablauf der zeit, wird die Sitzung gelöscht und der benutzer ausgelogt. Der countdown beginnt mit der letzten Aktion des benutzers.', 'de'),
													('dbhcms_desc_sesslifetime', 'Tiempo (minutos) donde la sessión esta viva. Después del tiempo, se elimina la sessión y el ususario es desconectado. El countdown empieza con la ultima accion del usuario.', 'es'),
													('dbhcms_desc_staticurls', 'Simulate static URL. Set \"True\" to enable and \"False\" to disable it. After changing this parameter you must generate the .htaccess file using the \"Generate .htaccess\" button in the \"Actions\" section. To enable static URL the Apache module \"mod_rewrite\" must be enabled on your web server.', 'en'),
													('dbhcms_desc_staticurls', 'Statische URL simulieren. Auf \"True\" setzen um es zu aktivieren oder auf \"False\" um es zu deaktivieren. Nach änderung dieses parameter, muss die \".htaccess\" datei neu generiert werden durch die funktion \"Generate .htaccess\" in der Sektion \"Aktionen\". Um Statische URL aktivieren zu können muss der Apache-Modul \"mod_rewrite\" auf dem Webserver aktiviert sein.', 'de'),
													('dbhcms_desc_staticurls', 'Simular URL estática. Escojer \"True\" para activarlo o \"False\" para desactivarlo. Después de cambiar este parámetro, se requiere regenerar el fichero \".htaccess\" por medio de la función \"Generate .htaccess\" en la sección \"Acciones\". Para activar la simulación de URL estática se requiere que el modulo \"mod_rewrite\" de Apache este activado en el servidor web.', 'es'),
													('dbhcms_desc_superusers', 'Users that are allowed to access the administration area.', 'en'),
													('dbhcms_desc_superusers', 'Benutzer die Zugangsberechtigung für den Administrationsbereich haben.', 'de'),
													('dbhcms_desc_superusers', 'Usuarios que tienen el derecho de acceder al área de administración.', 'es'),
													('dbhcms_desc_tpldir', 'Directory in which the template files are stored.', 'en'),
													('dbhcms_desc_tpldir', 'Ordner in dem die Template-Dateien gespeichert sind.', 'de'),
													('dbhcms_desc_tpldir', 'Directorio donde están guardados los ficheros de planillas (Templates).', 'es'),
													('wrongcaptcha', 'The code you entered was wrong. Please try again.', 'en'),
													('wrongcaptcha', 'Der eingegebene Code war falsch. Bitte erneut versuchen.', 'de'),
													('wrongcaptcha', 'El código introducido fue erróneo. Por favor inténtelo de nuevo.', 'es'),
													('empty', 'Empty', 'en'),
													('empty', 'Leeren', 'de'),
													('empty', 'Vaciar', 'es'),
													('additionalparams', 'Additional Parameters', 'en'),
													('additionalparams', 'Zusätzliche Parameter', 'de'),
													('additionalparams', 'Parámetros Adicionales', 'es'),
													('extmanager', 'Extension Manager', 'en'),
													('extmanager', 'Extension Verwaltung', 'de'),
													('extmanager', 'Gestor de Extensiones', 'es'),
													('version', 'Version', 'en'),
													('version', 'Version', 'de'),
													('version', 'Version', 'es'),
													('installed', 'Installed', 'en'),
													('installed', 'Installiert', 'de'),
													('installed', 'Instalado', 'es'),
													('yes', 'Yes', 'en'),
													('yes', 'Ja', 'de'),
													('yes', 'Si', 'es'),
													('not', 'No', 'en'),
													('not', 'Nein', 'de'),
													('not', 'No', 'es'),
													('uninstall', 'Uninstall', 'en'),
													('uninstall', 'Deinstallieren', 'de'),
													('uninstall', 'Deinstalar', 'es'),
													('install', 'Install', 'en'),
													('install', 'Installieren', 'de'),
													('install', 'Instalar', 'es');
													");
		
		### TABLE CMS_DOMAINS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_domains` (
												  `domn_id` int(11) NOT NULL auto_increment,
												  `domn_index_pid` int(11) NOT NULL default '0',
												  `domn_intro_pid` int(11) NOT NULL default '0',
												  `domn_login_pid` int(11) NOT NULL default '0',
												  `domn_logout_pid` int(11) NOT NULL default '0',
												  `domn_ad_pid` int(11) NOT NULL default '0',
												  `domn_err401_pid` int(11) NOT NULL default '0',
												  `domn_err403_pid` int(11) NOT NULL default '0',
												  `domn_err404_pid` int(11) NOT NULL default '0',
												  `domn_name` varchar(250) NOT NULL default '',
												  `domn_subfolders` varchar(250) NOT NULL default '0',
												  `domn_absolute_url` varchar(250) NOT NULL default '',
												  `domn_default_lang` varchar(4) NOT NULL default '',
												  `domn_supported_langs` text NOT NULL,
												  `domn_stylesheets` text NOT NULL,
												  `domn_javascripts` text NOT NULL,
												  `domn_templates` text NOT NULL,
												  `domn_php_modules` text NOT NULL,
												  `domn_extensions` text NOT NULL,
												  `domn_description` text,
												  PRIMARY KEY  (`domn_id`)
												);");
												
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_domains` (`domn_id`, `domn_index_pid`, `domn_intro_pid`, `domn_login_pid`, `domn_logout_pid`, `domn_ad_pid`, `domn_err401_pid`, `domn_err403_pid`, `domn_err404_pid`, `domn_name`, `domn_subfolders`, `domn_absolute_url`, `domn_default_lang`, `domn_supported_langs`, `domn_stylesheets`, `domn_javascripts`, `domn_templates`, `domn_php_modules`, `domn_extensions`, `domn_description`) VALUES 
														(1, 1, 1, 0, 0, 10, 10, 10, 12, '".$_POST['dbhcms_inst_domain_name']."', '".$_POST['dbhcms_inst_domain_subfolders']."', '".$_POST['dbhcms_inst_domain_url']."', 'en', 'de;en;es', '".$dbhcms_inst_style."', '', 'body.tpl', '', '".$dbhcms_ext_smilies_ext."', 'DBHcms - Demo Website');");
		} else {
			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_domains` (`domn_id`, `domn_index_pid`, `domn_intro_pid`, `domn_login_pid`, `domn_logout_pid`, `domn_ad_pid`, `domn_err401_pid`, `domn_err403_pid`, `domn_err404_pid`, `domn_name`, `domn_subfolders`, `domn_absolute_url`, `domn_default_lang`, `domn_supported_langs`, `domn_stylesheets`, `domn_javascripts`, `domn_templates`, `domn_php_modules`, `domn_extensions`, `domn_description`) VALUES 
														(1, 1, 1, 0, 0, 1, 1, 1, 1, '".$_POST['dbhcms_inst_domain_name']."', '".$_POST['dbhcms_inst_domain_subfolders']."', '".$_POST['dbhcms_inst_domain_url']."', 'en', 'de;en;es', '".$dbhcms_inst_style."', '', 'body.tpl', '', '".$dbhcms_ext_smilies_ext."', 'DBHcms - Demo Website');");
		}
		
		### TABLE CMS_MENUS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_menus` (
												  `menu_id` int(11) NOT NULL auto_increment,
												  `menu_name` varchar(250) NOT NULL default '',
												  `menu_type` tinytext NOT NULL,
												  `menu_layer` int(11) NOT NULL default '0',
												  `menu_depth` int(11) NOT NULL default '0',
												  `menu_show_restricted` int(1) NOT NULL default '0',
												  `menu_wrap_all` text NOT NULL,
												  `menu_wrap_normal` text NOT NULL,
												  `menu_wrap_active` text NOT NULL,
												  `menu_wrap_selected` text NOT NULL,
												  `menu_link_normal` text NOT NULL,
												  `menu_link_active` text NOT NULL,
												  `menu_link_selected` text NOT NULL,
												  `menu_description` text NOT NULL,
												  PRIMARY KEY  (`menu_id`),
												  UNIQUE KEY `menu_name` (`menu_name`)
												);");
		array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_menus` (`menu_id`, `menu_name`, `menu_type`, `menu_layer`, `menu_depth`, `menu_show_restricted`, `menu_wrap_all`, `menu_wrap_normal`, `menu_wrap_active`, `menu_wrap_selected`, `menu_link_normal`, `menu_link_active`, `menu_link_selected`, `menu_description`) VALUES 
													(1, 'headline', 'MT_LOCATION', 0, 0, 1, '|', '&bull; | &nbsp;', '&bull; | &nbsp;', '&bull; | &nbsp;', '[pageParamName]', '[pageParamName]', '[pageParamName]', 'Actual location in the headline'),
													(2, 'left', 'MT_ACTIVETREE', 1, 0, 1, '|', '<div class=\"menu_box_item_no_[layer]\"> | </div>', '<div class=\"menu_box_item_act_[layer]\"> | </div>', '<div class=\"menu_box_item_act_[layer]\"> | </div>', '[pageParamName]', '[pageParamName]', '[pageParamName]', 'Left menu'),
													(3, 'top', 'MT_TREE', 1, 1, 1, '&#124;|', '&nbsp;|&nbsp;&#124;', '&nbsp;|&nbsp;&#124;', '&nbsp;|&nbsp;&#124;', '[pageParamName]', '[pageParamName]', '[pageParamName]', 'Top menu'),
													(4, 'footer', 'MT_ACTIVETREE', 0, 0, 1, '|', '<nobr>[|]</nobr> &nbsp;', '<nobr>[|]</nobr> &nbsp;', '<nobr>[|]</nobr> &nbsp;', '[pageParamName]', '[pageParamName]', '[pageParamName]', 'Footer menu');");
		
		### TABLE CMS_PAGEPRMS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_pageprms` (
												  `papa_id` int(11) NOT NULL auto_increment,
												  `papa_page_id` int(11) NOT NULL default '0',
												  `papa_type` varchar(100) NOT NULL default '',
												  `papa_name` varchar(150) NOT NULL default '',
												  `papa_description` text NOT NULL,
												  PRIMARY KEY  (`papa_id`)
												);");
		array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_pageprms` (`papa_id`, `papa_page_id`, `papa_type`, `papa_name`, `papa_description`) VALUES 
													(1, 0, 'DT_TPLARRAY', 'templates', 'dbhcms_desc_langtemplates'),
													(2, 0, 'DT_CSSARRAY', 'stylesheets', 'dbhcms_desc_langstylesheets'),
													(3, 0, 'DT_JSARRAY', 'javascripts', 'dbhcms_desc_langjavascripts'),
													(4, 0, 'DT_MODARRAY', 'modules', 'dbhcms_desc_langphpmodules'),
													(5, 0, 'DT_STRING', 'urlPrefix', 'dbhcms_desc_langurl'),
													(6, 0, 'DT_STRING', 'name', 'dbhcms_desc_langname'),
													(7, 0, 'DT_CONTENT', 'content', 'dbhcms_desc_langcontent');");
		
		### TABLE CMS_PAGES ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_pages` (
												  `page_id` int(11) NOT NULL auto_increment,
												  `page_parent_id` int(11) NOT NULL default '0',
												  `page_domn_id` int(11) NOT NULL default '0',
												  `page_posnr` int(11) NOT NULL default '0',
												  `page_hierarchy` varchar(20) NOT NULL default '',
												  `page_hide` int(1) NOT NULL default '0',
												  `page_cache` int(1) NOT NULL default '1',
												  `page_schedule` int(1) NOT NULL default '0',
												  `page_start` datetime NOT NULL default '0000-00-00 00:00:00',
												  `page_stop` datetime NOT NULL default '0000-00-00 00:00:00',
												  `page_inmenu` int(1) NOT NULL default '1',
												  `page_stylesheets` text,
												  `page_javascripts` text NOT NULL,
												  `page_templates` text NOT NULL,
												  `page_php_modules` text,
												  `page_extensions` text,
												  `page_shortcut` int(11) NOT NULL default '0',
												  `page_link` varchar(250) NOT NULL default '',
												  `page_target` varchar(150) NOT NULL default '',
												  `page_userlevel` char(1) NOT NULL default 'A',
												  `page_last_edited` timestamp NOT NULL,
												  `page_description` text NOT NULL,
												  PRIMARY KEY  (`page_id`),
												  KEY `page_parent_id` (`page_parent_id`)
												);");
		
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
		
			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_pages` (`page_id`, `page_parent_id`, `page_domn_id`, `page_posnr`, `page_hierarchy`, `page_hide`, `page_cache`, `page_schedule`, `page_start`, `page_stop`, `page_inmenu`, `page_stylesheets`, `page_javascripts`, `page_templates`, `page_php_modules`, `page_extensions`, `page_shortcut`, `page_link`, `page_target`, `page_userlevel`, `page_last_edited`, `page_description`) VALUES 
													(-120, -3, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.extmanager.tpl', 'mod.global.php;mod.extmanager.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin -  Extensions Manager'),
													(-110, -3, 0, 110, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.errorlog.tpl', 'mod.global.php;mod.errorlog.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Error Log'),
													(-100, -3, 0, 80, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.ext.tpl', 'mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Extensions'),
													(-90, -3, 0, 100, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.accesslog.tpl', 'mod.global.php;mod.accesslog.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Access Log'),
													(-81, -80, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.menus.edit.tpl', 'mod.global.php;mod.menus.edit.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menu Edit'),
													(-80, -3, 0, 70, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.menus.view.tpl', 'mod.global.php;mod.menus.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menus View'),
													(-71, -70, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.users.edit.tpl', 'mod.global.php;mod.users.edit.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - User Edit'),
													(-70, -3, 0, 30, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.users.view.tpl', 'mod.global.php;mod.users.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Users View'),
													(-60, 0, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.instinfo.tpl', 'mod.global.php;mod.instinfo.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Instance Info'),
													(-50, -3, 0, 50, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.dictionary.tpl', 'mod.global.php;mod.dictionary.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Dictionary'),
													(-41, -40, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.fe.menu.tpl', 'mod.global.php;mod.fe.menu.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - FE Menu'),
													(-40, -3, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, '', '', 'body.frames.fe.tpl', 'mod.global.php;mod.fe.frames.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - FE Frames'),
													(-30, -3, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.settings.tpl', 'mod.global.php;mod.settings.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Settings'),
													(-21, -20, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.domain.edit.tpl', 'mod.global.php;mod.domain.edit.php', '', 0, '', '', '9', NOW(), 'DBHcms Administration - Domain Edit'),
													(-20, -3, 0, 0, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.domain.view.tpl', 'mod.global.php;mod.domain.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Administration - Domains View'),
													(-12, -10, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js;validate.js', 'body.main.tpl;content.page.edit.tpl', 'mod.global.php;mod.page.edit.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Edit'),
													(-11, -10, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.main.tpl;content.treeview.tpl', 'mod.global.php;mod.page.treeview.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Treeview'),
													(-10, -3, 0, 60, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.frames.page.tpl', 'mod.global.php;mod.page.frames.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Frames'),
													(-8, 0, 0, 50, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', '', 'mod.global.php;mod.selector.php', '', 0, '', '_self', '9', NOW(), 'DBHcms Selector'),
													(-7, 0, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.refresh.tpl', '', '', 0, '', '', '9', NOW(), 'DBHcms - Refresh Session'),
													(-6, 0, 0, 30, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.editor.tpl', 'mod.global.php;mod.editor.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Editor'),
													(-5, -3, 0, 90, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.actions.tpl', 'mod.global.php;mod.actions.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Actions'),
													(-4, 0, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.main.tpl;content.login.tpl', 'mod.global.php', '', 0, '', '', 'A', NOW(), 'DBHcms Admin - Login'),
													(-3, -1, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.start.tpl', 'mod.global.php;mod.start.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Index'),
													(-2, -1, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'menu.js', 'body.menu.tpl;content.menu.tpl', 'mod.global.php;mod.menu.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menu'),
													(-1, 0, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.frames.tpl', 'mod.global.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Frames'),
													(-99, 0, 0, 0, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.standard.tpl', 'mod.global.php', '', 0, '', '', 'A', NOW(), 'DBHcms standard page'),
													(1, 0, 1, 10, 'HT_ROOT', 0, 1, 0, NOW(), NOW(), 1, '', '', 'layout.tpl', '', '', 0, '', '_self', 'A', NOW(), 'Homepage'),
													(2, 1, 1, 50, 'HT_HEREDITARY', 0, 1, 0, NOW(), NOW(), 1, '', '', '', '', '', 0, '', '_self', 'A', NOW(), 'About us'),
													(3, 1, 1, 20, 'HT_HEREDITARY', 0, 1, 0, NOW(), NOW(), 1, '', '', '".$dbhcms_ext_photoalbum_tpl."', '', '".$dbhcms_ext_photoalbum_ext."', 0, '', '_self', 'A', NOW(), 'Picture Albums'),
													(4, 1, 1, 10, 'HT_HEREDITARY', 0, 1, 0, NOW(), NOW(), 1, '', '', '".$dbhcms_ext_news_tpl."', '', '".$dbhcms_ext_news_ext."', 0, '', '_self', 'A', NOW(), 'News'),
													(5, 1, 1, 30, 'HT_HEREDITARY', 0, 1, 0, NOW(), NOW(), 1, '', '', '', '', '', 0, '', '_self', 'A', NOW(), 'Links'),
													(6, 0, 1, 40, 'HT_SINGLE', 0, 1, 0, NOW(), NOW(), 1, '', '', 'laynomenu.tpl', '', '', 0, '', '_self', 'A', NOW(), 'Impressum'),
													(7, 0, 1, 30, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, '', '', 'laynomenu.tpl;".$dbhcms_ext_contact_tpl."', '', '".$dbhcms_ext_contact_ext."', 0, '', '_self', 'A', NOW(), 'Contact'),
													(8, 1, 1, 40, 'HT_HEREDITARY', 0, 0, 0, NOW(), NOW(), 1, '', '', '".$dbhcms_ext_guestbook_tpl."', '', '".$dbhcms_ext_guestbook_ext."', 0, '', '_self', 'A', NOW(), 'Guestbook'),
													(9, 1, 1, 60, 'HT_HEREDITARY', 0, 1, 0, NOW(), NOW(), 1, '', '', '', '', '', 0, '', '_self', 'B', NOW(), 'Intern page only for users'),
													(10, 0, 1, 50, 'HT_SINGLE', 0, 1, 0, NOW(), NOW(), 0, '', '', 'laynomenu.tpl', '', '', 0, '', '_self', 'A', NOW(), 'Show this page when access is denied'),
													(11, 0, 1, 20, 'HT_SINGLE', 0, 1, 0, NOW(), NOW(), 1, '', '', 'laynomenu.tpl;search.tpl', '', 'search', 0, '', '_self', 'A', NOW(), 'Search form'),
													(12, 0, 1, 60, 'HT_SINGLE', 0, 1, 0, NOW(), NOW(), 0, '', '', 'laynomenu.tpl;', '', '', 0, '', '_self', 'A', NOW(), 'Error 404 Page');");
		
		} else {

			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_pages` (`page_id`, `page_parent_id`, `page_domn_id`, `page_posnr`, `page_hierarchy`, `page_hide`, `page_cache`, `page_schedule`, `page_start`, `page_stop`, `page_inmenu`, `page_stylesheets`, `page_javascripts`, `page_templates`, `page_php_modules`, `page_extensions`, `page_shortcut`, `page_link`, `page_target`, `page_userlevel`, `page_last_edited`, `page_description`) VALUES
													(-120, -3, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.extmanager.tpl', 'mod.global.php;mod.extmanager.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin -  Extensions Manager'), 
													(-110, -3, 0, 110, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.errorlog.tpl', 'mod.global.php;mod.errorlog.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Error Log'),
													(-100, -3, 0, 80, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.ext.tpl', 'mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Extensions'),
													(-90, -3, 0, 100, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.accesslog.tpl', 'mod.global.php;mod.accesslog.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Access Log'),
													(-81, -80, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.menus.edit.tpl', 'mod.global.php;mod.menus.edit.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menu Edit'),
													(-80, -3, 0, 70, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.menus.view.tpl', 'mod.global.php;mod.menus.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menus View'),
													(-71, -70, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.users.edit.tpl', 'mod.global.php;mod.users.edit.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - User Edit'),
													(-70, -3, 0, 30, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.users.view.tpl', 'mod.global.php;mod.users.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Users View'),
													(-60, 0, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.instinfo.tpl', 'mod.global.php;mod.instinfo.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Instance Info'),
													(-50, -3, 0, 50, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.dictionary.tpl', 'mod.global.php;mod.dictionary.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Dictionary'),
													(-41, -40, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.fe.menu.tpl', 'mod.global.php;mod.fe.menu.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - FE Menu'),
													(-40, -3, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, '', '', 'body.frames.fe.tpl', 'mod.global.php;mod.fe.frames.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - FE Frames'),
													(-30, -3, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.settings.tpl', 'mod.global.php;mod.settings.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Settings'),
													(-21, -20, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.domain.edit.tpl', 'mod.global.php;mod.domain.edit.php', '', 0, '', '', '9', NOW(), 'DBHcms Administration - Domain Edit'),
													(-20, -3, 0, 0, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.domain.view.tpl', 'mod.global.php;mod.domain.view.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Administration - Domains View'),
													(-12, -10, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.page.edit.tpl', 'mod.global.php;mod.page.edit.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Edit'),
													(-11, -10, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.main.tpl;content.treeview.tpl', 'mod.global.php;mod.page.treeview.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Treeview'),
													(-10, -3, 0, 60, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.frames.page.tpl', 'mod.global.php;mod.page.frames.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Page Frames'),
													(-8, 0, 0, 50, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', '', 'mod.global.php;mod.selector.php', '', 0, '', '_self', '9', NOW(), 'DBHcms Selector'),
													(-7, 0, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.refresh.tpl', '', '', 0, '', '', '9', NOW(), 'DBHcms - Refresh Session'),
													(-6, 0, 0, 30, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.editor.tpl', 'mod.global.php;mod.editor.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Editor'),
													(-5, -3, 0, 90, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.actions.tpl', 'mod.global.php;mod.actions.php;mod.result.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Actions'),
													(-4, 0, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.main.tpl;content.login.tpl', 'mod.global.php', '', 0, '', '', 'A', NOW(), 'DBHcms Admin - Login'),
													(-3, -1, 0, 40, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'refresh.js', 'body.main.tpl;content.start.tpl', 'mod.global.php;mod.start.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Index'),
													(-2, -1, 0, 10, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', 'menu.js', 'body.menu.tpl;content.menu.tpl', 'mod.global.php;mod.menu.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Menu'),
													(-1, 0, 0, 20, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.frames.tpl', 'mod.global.php', '', 0, '', '', '9', NOW(), 'DBHcms Admin - Frames'),
													(-99, 0, 0, 0, 'HT_SINGLE', 0, 0, 0, NOW(), NOW(), 1, 'dbhcms.admin.css', '', 'body.standard.tpl', 'mod.global.php', '', 0, '', '', 'A', NOW(), 'DBHcms standard page'),
													(1, 0, 1, 10, 'HT_ROOT', 0, 1, 0, NOW(), NOW(), 1, '', '', 'layout.tpl', '', '', 0, '', '_self', 'A', NOW(), 'Homepage');");
		
		}

		array_push($dbhcms_database_sql['CMS'], "UPDATE `".DBHCMS_C_INST_DB_PREFIX."cms_pages` SET `page_id` = '0', `page_last_edited` = NOW() WHERE `page_id` = -99 LIMIT 1 ;");
		
		### TABLE CMS_PAGEVALS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_pagevals` (
												  `pava_id` int(11) NOT NULL auto_increment,
												  `pava_page_id` int(11) NOT NULL default '0',
												  `pava_name` varchar(250) NOT NULL default '',
												  `pava_value` text NOT NULL,
												  `pava_lang` varchar(4) NOT NULL default '',
												  PRIMARY KEY  (`pava_id`),
												  KEY `pava_page_id` (`pava_page_id`)
												);");
												
												
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
												
			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_pagevals` (`pava_page_id`, `pava_name`, `pava_value`, `pava_lang`) VALUES 
													(-120, 'name', 'BE Extensions Manager', 'en'),
													(-120, 'name', 'BE Extensions Manager', 'es'),
													(-120, 'name', 'BE Extensions Manager', 'de'),
													(-110, 'name', 'BE Error Log', 'en'),
													(-110, 'name', 'BE Error Log', 'es'),
													(-110, 'name', 'BE Error Log', 'de'),
													(-100, 'name', 'BE Extensions', 'en'),
													(-100, 'name', 'BE Extensions', 'es'),
													(-100, 'name', 'BE Extensions', 'de'),
													(-90, 'name', 'BE Access Log', 'en'),
													(-90, 'name', 'BE Access Log', 'es'),
													(-90, 'name', 'BE Access Log', 'de'),
													(-81, 'name', 'BE Menu Edit', 'en'),
													(-81, 'name', 'BE Menu Edit', 'es'),
													(-81, 'name', 'BE Menu Edit', 'de'),
													(-80, 'name', 'BE Menus View', 'en'),
													(-80, 'name', 'BE Menus View', 'es'),
													(-80, 'name', 'BE Menus View', 'de'),
													(-71, 'name', 'BE User Edit', 'en'),
													(-71, 'name', 'BE User Edit', 'es'),
													(-71, 'name', 'BE User Edit', 'de'),
													(-70, 'name', 'BE Users View', 'en'),
													(-70, 'name', 'BE Users View', 'es'),
													(-70, 'name', 'BE Users View', 'de'),
													(-60, 'name', 'BE Instance Info', 'en'),
													(-60, 'name', 'BE Instance Info', 'es'),
													(-60, 'name', 'BE Instance Info', 'de'),
													(-50, 'name', 'BE Dictionary', 'en'),
													(-50, 'name', 'BE Dictionary', 'es'),
													(-50, 'name', 'BE Dictionary', 'de'),
													(-41, 'name', 'FE Menu', 'en'),
													(-41, 'name', 'FE Menu', 'es'),
													(-41, 'name', 'FE Menu', 'de'),
													(-40, 'name', 'FE Frames', 'en'),
													(-40, 'name', 'FE Frames', 'es'),
													(-40, 'name', 'FE Frames', 'de'),
													(-30, 'name', 'BE Settings', 'en'),
													(-30, 'name', 'BE Settings', 'es'),
													(-30, 'name', 'BE Settings', 'de'),
													(-21, 'name', 'BE Domain Edit', 'en'),
													(-21, 'name', 'BE Domain Edit', 'es'),
													(-21, 'name', 'BE Domain Edit', 'de'),
													(-20, 'name', 'BE Domains View', 'en'),
													(-20, 'name', 'BE Domains View', 'es'),
													(-20, 'name', 'BE Domains View', 'de'),
													(-12, 'name', 'BE Page Edit', 'en'),
													(-12, 'name', 'BE Page Edit', 'es'),
													(-12, 'name', 'BE Page Edit', 'de'),
													(-11, 'name', 'BE Page Treeview', 'en'),
													(-11, 'name', 'BE Page Treeview', 'es'),
													(-11, 'name', 'BE Page Treeview', 'de'),
													(-10, 'name', 'BE Page Frames', 'en'),
													(-10, 'name', 'BE Page Frames', 'es'),
													(-10, 'name', 'BE Page Frames', 'de'),
													(-8, 'name', 'BE Selector', 'en'),
													(-8, 'name', 'BE Selector', 'es'),
													(-8, 'name', 'BE Selector', 'de'),
													(-7, 'name', 'BE Refresh', 'en'),
													(-7, 'name', 'BE Refresh', 'es'),
													(-7, 'name', 'BE Refresh', 'de'),
													(-6, 'name', 'BE Editor', 'en'),
													(-6, 'name', 'BE Editor', 'es'),
													(-6, 'name', 'BE Editor', 'de'),
													(-5, 'name', 'BE Actions', 'en'),
													(-5, 'name', 'BE Actions', 'es'),
													(-5, 'name', 'BE Actions', 'de'),
													(-4, 'name', 'BE Login', 'en'),
													(-4, 'name', 'BE Login', 'es'),
													(-4, 'name', 'BE Login', 'de'),
													(-3, 'name', 'BE Home', 'en'),
													(-3, 'name', 'BE Home', 'es'),
													(-3, 'name', 'BE Home', 'de'),
													(-2, 'name', 'BE Main Menu', 'en'),
													(-2, 'name', 'BE Main Menu', 'es'),
													(-2, 'name', 'BE Main Menu', 'de'),
													(-1, 'name', 'BE Frames', 'en'),
													(-1, 'name', 'BE Frames', 'es'),
													(-1, 'name', 'BE Frames', 'de'),
													(0, 'name', 'Standard Page', 'en'),
													(0, 'name', 'Página Estandard', 'es'),
													(0, 'name', 'Standardseite', 'de'),
													(1, 'urlPrefix', 'startseite', 'de'),
													(1, 'name', 'Startseite', 'de'),
													(1, 'content', '<h3><font size=\"2\"><br />\r\nHallo [sessAuthUserRealName] !</font></h3>\r\n<p>\r\nDies ist eine Demonstrationsseite des DBHcms! Ein Beispiel was dieses kleines Content-Managemen-System so&nbsp;alles machen kann ! Viel spass beim testen! <br />\r\n</p>', 'de'),
													(1, 'urlPrefix', 'homepage', 'en'),
													(1, 'name', 'Homepage', 'en'),
													(1, 'content', '<h3><font size=\"2\"><br />\r\nHello [sessAuthUserRealName] !</font></h3>\r\n<p>\r\nThis is a demo-page&nbsp;of the&nbsp;DBHcms! It is a example of what this small Content-Managemen-System&nbsp;can do&nbsp;! Have a lot of fun while testing it! <br />\r\n</p>', 'en'),
													(1, 'urlPrefix', 'inicio', 'es'),
													(1, 'name', 'Inicio', 'es'),
													(1, 'content', '<h3><font size=\"2\"><br />\r\nHola [sessAuthUserRealName] !</font></h3>\r\n<p>\r\nEsta es una pagina de demostracion para el&nbsp;DBHcms! Es un ejemplo de lo que se puede hacer con este&nbsp;Content-Managemen-System&nbsp;peque&ntilde;o&nbsp;!&nbsp;Disfrute su tiempo ensayando este sistema&nbsp;! <br />\r\n</p>', 'es'),
													(2, 'urlPrefix', 'ueber-uns', 'de'),
													(2, 'name', 'Über Uns', 'de'),
													(2, 'urlPrefix', 'about-us', 'en'),
													(2, 'name', 'About Us', 'en'),
													(2, 'urlPrefix', 'sobre-nosostros', 'es'),
													(2, 'name', 'Sobre Nosostros', 'es'),
													(3, 'urlPrefix', 'bilder', 'de'),
													(3, 'name', 'Bilder', 'de'),
													(3, 'urlPrefix', 'pictures', 'en'),
													(3, 'name', 'Pictures', 'en'),
													(3, 'urlPrefix', 'fotos', 'es'),
													(3, 'name', 'Fotos', 'es'),
													(3, 'javascripts', 'photoalbum.de.js', 'de'),
													(3, 'javascripts', 'photoalbum.en.js', 'en'),
													(3, 'javascripts', 'photoalbum.es.js', 'es'),
													(4, 'urlPrefix', 'nachrichten', 'de'),
													(4, 'name', 'Nachrichten', 'de'),
													(4, 'urlPrefix', 'news', 'en'),
													(4, 'name', 'News', 'en'),
													(4, 'urlPrefix', 'noticias', 'es'),
													(4, 'name', 'Notícias', 'es'),
													(4, 'javascripts', 'news.de.js', 'de'),
													(4, 'javascripts', 'news.es.js', 'es'),
													(4, 'javascripts', 'news.en.js', 'en'),
													(5, 'urlPrefix', 'links', 'de'),
													(5, 'name', 'Links', 'de'),
													(5, 'urlPrefix', 'links', 'en'),
													(5, 'name', 'Links', 'en'),
													(5, 'urlPrefix', 'enlaces', 'es'),
													(5, 'name', 'Enlaces', 'es'),
													(6, 'urlPrefix', 'impressum', 'de'),
													(6, 'name', 'Impressum', 'de'),
													(6, 'urlPrefix', 'disclaimer', 'en'),
													(6, 'name', 'Disclaimer', 'en'),
													(6, 'urlPrefix', 'aviso-legal', 'es'),
													(6, 'name', 'Aviso Legal', 'es'),
													(6, 'content', '<p>\r\n<strong>Haftung f&uuml;r Inhalte</strong> \r\n</p>\r\n<p>\r\nDie Inhalte unserer Seiten wurden mit gr&ouml;&szlig;ter Sorgfalt erstellt. 
																	F&uuml;r die Richtigkeit, Vollst&auml;ndigkeit und Aktualit&auml;t der Inhalte k&ouml;nnen wir jedoch keine Gew&auml;hr &uuml;bernehmen. \r\n</p>\r\n<p>\r\n Als 
																	Diensteanbieter sind wir gem&auml;&szlig; &sect; 6 Abs.1 MDStV und &sect; 8 Abs.1 TDG f&uuml;r eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen 
																	verantwortlich. Diensteanbieter sind jedoch nicht verpflichtet, die von ihnen &uuml;bermittelten oder gespeicherten fremden Informationen zu &uuml;berwachen oder nach 
																	Umst&auml;nden zu forschen, die auf eine rechtswidrige T&auml;tigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den 
																	allgemeinen Gesetzen bleiben hiervon unber&uuml;hrt. Eine diesbez&uuml;gliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung 
																	m&ouml;glich. Bei bekannt werden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen. \r\n</p>\r\n<p>\r\n<strong>Haftung f&uuml;r 
																	Links</strong> \r\n</p>\r\n<p>\r\nUnser Angebot enth&auml;lt Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb k&ouml;nnen wir 
																	f&uuml;r diese fremden Inhalte auch keine Gew&auml;hr &uuml;bernehmen. F&uuml;r die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der 
																	Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf m&ouml;gliche Rechtsverst&ouml;&szlig;e &uuml;berpr&uuml;ft. Rechtswidrige 
																	Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer 
																	Rechtsverletzung nicht zumutbar. Bei bekannt werden von Rechtsverletzungen werden wir derartige Links umgehend entfernen. 
																	\r\n</p>\r\n<p>\r\n<strong>Urheberrecht</strong> \r\n</p>\r\n<p>\r\nDie Betreiber der Seiten sind bem&uuml;ht, stets die Urheberrechte anderer zu beachten bzw. auf 
																	selbst erstellte sowie lizenzfreie Werke zur&uuml;ckzugreifen. \r\n</p>\r\n<p>\r\nDie durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten 
																	unterliegen dem deutschen Urheberrecht. Beitr&auml;ge Dritter sind als solche gekennzeichnet. Die Vervielf&auml;ltigung, Bearbeitung, Verbreitung und jede Art der 
																	Verwertung au&szlig;erhalb der Grenzen des Urheberrechtes bed&uuml;rfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien 
																	dieser Seite sind nur f&uuml;r den privaten, nicht kommerziellen Gebrauch gestattet. \r\n</p>\r\n<p>\r\n<strong>Datenschutz </strong>\r\n</p>\r\n<p>\r\nSoweit auf 
																	unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder eMail-Adressen) erhoben werden, erfolgt dies soweit m&ouml;glich stets auf freiwilliger 
																	Basis. Die Nutzung der Angebote und Dienste ist, soweit m&ouml;glich, stets ohne Angabe personenbezogener Daten m&ouml;glich. \r\n</p>\r\n<p>\r\nDer Nutzung von im 
																	Rahmen der Impressumspflicht ver&ouml;ffentlichten Kontaktdaten durch Dritte zur &Uuml;bersendung von nicht ausdr&uuml;cklich angeforderter Werbung und 
																	Informationsmaterialien wird hiermit ausdr&uuml;cklich widersprochen. Die Betreiber der Seiten behalten sich ausdr&uuml;cklich rechtliche Schritte im Falle der 
																	unverlangten Zusendung von Werbeinformationen, etwa durch Spam-Mails, vor. \r\n</p>\r\n<p>\r\nQuelle: <a href=\"http://www.e-recht24.de/muster-disclaimer.htm\">Disclaimer</a> 
																	von eRecht24.de dem Informationsportal zum <a href=\"http://www.e-recht24.de\">Internetrecht</a> \r\n</p>', 'de'),
													(6, 'content', '<strong><br />\r\nLinks</strong><br />\r\n<br />\r\nLinks are provided for information and convenience only. We cannot accept responsibility for the 
																	sites linked to, or the information found there. A link does not imply an endorsement of a site; likewise, not linking to a particular site does not imply lack of 
																	endorsement. <br />\r\n<br />\r\n<strong>Accuracy</strong><br />\r\n<br />\r\nEvery effort is taken to ensure that the information contained in this website is both 
																	accurate and complete. However, medical knowledge is constantly changing and we cannot guarantee that all of the information is accurate and consistent with current 
																	NHS practice. Please contact us if you feel we are providing inaccurate information.<br />\r\n<br />\r\n<strong>Availability</strong><br />\r\n<br />\r\nWe cannot 
																	guarantee uninterrupted access to this website, or the sites to which it links. We accept no responsibility for any damages arising from the loss of use of this 
																	information.<br />\r\n<br />\r\n<strong>Privacy</strong> <br />\r\n<br />\r\nThis web site does not automatically capture or store personal information, other than 
																	logging the users IP address and session info such as the duration of the visit and the type of browser used. This is recognised by the Web server and is only used 
																	for system administration and to provide statistics which are used to evaluate use of the site. We do not use cookies for collecting user information from the site.
																	Sending information to our site You may be asked for personal information if you want to take advantage of specific services such as a contact form, request form, 
																	online forums, subscription to a newsletter etc. In each case we will only use the personal information you provide to deliver the services you have told us you wish 
																	to take part in. The information you submit will be treated in the strictest confidence. However, we cannot guarantee the security of the global internet/email 
																	systems. This means that it is possible (although unlikely) that your enquiry/message and our response may be read by someone other than yourself or ourselves. If you 
																	are concerned about this possible loss of privacy please contact us by alternative means such as post or telephone. <br />\r\n<br />', 'en'),
													(6, 'content', '<p>\r\n<strong>Exenci&oacute;n de responsabilidad<br />\r\n</strong><br />\r\nDRBENHUR.COM no es responsable de todas las informaciones que se presentan en esta Web. 
																	Nuestro objetivo es que las mismas sean precisas y fidedignas y por ello hacemos todo lo posible para corregir todos los errores que llegan a nuestro conocimiento. 
																	DRBENHUR.COM no acepta responsabilidad alguna en relaci&oacute;n a los contenidos publicados en su Web provenientes de fuentes externas, tales como, por ejemplo, las 
																	opiniones personales. <br />\r\n<br />\r\nEl material publicado en esta Web no es necesariamente completo, ni preciso ni est&aacute; siempre actualizado. En muchos casos 
																	sus fuentes proceden de enlaces externos sobre los que DRBENHUR.COM no tiene control alguno y respecto a los que no asume ninguna responsabilidad.<br />\r\n<br />\r\n
																	Nuestro objetivo es reducir al m&iacute;nimo las molestias que puedan derivarse de la aparici&oacute;n de errores t&eacute;cnicos. Sin embargo, DRBENHUR.COM no acepta 
																	responsabilidad alguna en relaci&oacute;n a los problemas que puedan surgir como resultado de la utilizaci&oacute;n de esta Web o de cualquiera de los enlaces externos. 
																	El objetivo de esta nota de exenci&oacute;n de responsabilidad no es en ning&uacute;n caso infringir cualquiera de los requisitos establecidos por el Derecho vigente o 
																	excluir la responsabilidad en casos no previstos por las leyes. \r\n</p>\r\n<p>\r\n</p>', 'es'),
													(7, 'urlPrefix', 'kontakt', 'de'),
													(7, 'name', 'Kontakt', 'de'),
													(7, 'urlPrefix', 'contact', 'en'),
													(7, 'name', 'Contact', 'en'),
													(7, 'urlPrefix', 'contacto', 'es'),
													(7, 'name', 'Contacto', 'es'),
													(7, 'javascripts', 'contact.de.js', 'de'),
													(7, 'javascripts', 'contact.es.js', 'es'),
													(7, 'javascripts', 'contact.en.js', 'en'),
													(8, 'javascripts', 'guestbook.de.js', 'de'),
													(8, 'javascripts', 'guestbook.es.js', 'es'),
													(8, 'javascripts', 'guestbook.en.js', 'en'),
													(8, 'urlPrefix', 'gaestebuch', 'de'),
													(8, 'name', 'Gästebuch', 'de'),
													(8, 'urlPrefix', 'guestbook', 'en'),
													(8, 'name', 'Guestbook', 'en'),
													(8, 'urlPrefix', 'libro-de-visitas', 'es'),
													(8, 'name', 'Libro de visitas', 'es'),
													(9, 'urlPrefix', 'intern', 'de'),
													(9, 'name', 'Intern', 'de'),
													(9, 'urlPrefix', 'intern', 'en'),
													(9, 'name', 'Intern', 'en'),
													(9, 'urlPrefix', 'interno', 'es'),
													(9, 'name', 'Interno', 'es'),
													(10, 'urlPrefix', 'zugriff-verweigert', 'de'),
													(10, 'name', 'Zugriff Verweigert', 'de'),
													(10, 'content', '<blockquote>\r\n	<p>\r\n	<br />\r\n	<br />\r\n	&nbsp;\r\n	</p>\r\n	<p>\r\n	<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">Zugriff Verweigert!</font></font> \r\n	</p>\r\n	<p>\r\n	<font size=\"2\" color=\"#000000\">Bitte melden Sie sich an.</font> <br />\r\n	<br />\r\n	<br />\r\n	</p>\r\n</blockquote>\r\n', 'de'),
													(10, 'urlPrefix', 'access-denied', 'en'),
													(10, 'name', 'Access Denied', 'en'),
													(10, 'content', '<blockquote>\r\n	<p>\r\n	<br />\r\n	<br />\r\n	&nbsp; \r\n	</p>\r\n	<p>\r\n	<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">Access Denied!</font></font> \r\n	</p>\r\n	<p>\r\n	<font size=\"2\" color=\"#000000\">Please login.</font> <br />\r\n	<br />\r\n	<br />\r\n	</p>\r\n</blockquote>\r\n', 'en'),
													(10, 'urlPrefix', 'acceso-negado', 'es'),
													(10, 'name', 'Acceso Negado', 'es'),
													(10, 'content', '<blockquote>\r\n	<p>\r\n	<br />\r\n	<br />\r\n	&nbsp; \r\n	</p>\r\n	<p>\r\n	<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">Acceso Negado!</font></font> \r\n	</p>\r\n	<p>\r\n	<font size=\"2\" color=\"#000000\">Porfavor conectese.</font> <br />\r\n	<br />\r\n	<br />\r\n	</p>\r\n</blockquote>\r\n', 'es'),
													(11, 'urlPrefix', 'suche', 'de'),
													(11, 'name', 'Suche', 'de'),
													(11, 'urlPrefix', 'search', 'en'),
													(11, 'name', 'Search', 'en'),
													(11, 'urlPrefix', 'busqueda', 'es'),
													(11, 'name', 'Busqueda', 'es'),
													(12, 'urlPrefix', 'fehler-404', 'de'),
													(12, 'name', 'Fehler 404', 'de'),
													(12, 'content', '<p>\r\n</p>\r\n<p>\r\n<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">Seite nicht gefunden!</font></font> \r\n</p>\r\n<p>\r\n<font size=\"2\" color=\"#000000\">Die Seite die Sie suchen existiert nicht oder wurde entfernt.</font><br />\r\n<br />\r\n</p>', 'de'),
													(12, 'urlPrefix', 'error-404', 'en'),
													(12, 'name', 'Error 404', 'en'),
													(12, 'content', '<p>\r\n</p>\r\n<p>\r\n<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">Page not found!</font></font> \r\n</p>\r\n<p>\r\n<font size=\"2\" color=\"#000000\">The page you are searching for does not exist or has been removed.</font><br />\r\n<br />\r\n</p>', 'en'),
													(12, 'urlPrefix', 'error-404', 'es'),
													(12, 'name', 'Error 404', 'es'),
													(12, 'content', '<p>\r\n</p>\r\n<p>\r\n<img src=\"images/other/error.gif\" border=\"0\" alt=\" \" width=\"36\" height=\"34\" align=\"middle\" />&nbsp;&nbsp;<font size=\"5\"> <font color=\"#cc3300\">P&aacute;gina no encontrada!</font></font> \r\n</p>\r\n<p>\r\n<font size=\"2\" color=\"#000000\">La p&aacute;gina que busca no existe o fue removida.</font><br />\r\n<br />\r\n</p>', 'es');
												");
		
		} else {
												
			array_push($dbhcms_database_sql['CMS'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_pagevals` (`pava_page_id`, `pava_name`, `pava_value`, `pava_lang`) VALUES
													(-120, 'name', 'BE Extensions Manager', 'en'),
													(-120, 'name', 'BE Extensions Manager', 'es'),
													(-120, 'name', 'BE Extensions Manager', 'de'),
													(-110, 'name', 'BE Error Log', 'en'),
													(-110, 'name', 'BE Error Log', 'es'),
													(-110, 'name', 'BE Error Log', 'de'),
													(-100, 'name', 'BE Extensions', 'en'),
													(-100, 'name', 'BE Extensions', 'es'),
													(-100, 'name', 'BE Extensions', 'de'),
													(-90, 'name', 'BE Access Log', 'en'),
													(-90, 'name', 'BE Access Log', 'es'),
													(-90, 'name', 'BE Access Log', 'de'),
													(-81, 'name', 'BE Menu Edit', 'en'),
													(-81, 'name', 'BE Menu Edit', 'es'),
													(-81, 'name', 'BE Menu Edit', 'de'),
													(-80, 'name', 'BE Menus View', 'en'),
													(-80, 'name', 'BE Menus View', 'es'),
													(-80, 'name', 'BE Menus View', 'de'),
													(-71, 'name', 'BE User Edit', 'en'),
													(-71, 'name', 'BE User Edit', 'es'),
													(-71, 'name', 'BE User Edit', 'de'),
													(-70, 'name', 'BE Users View', 'en'),
													(-70, 'name', 'BE Users View', 'es'),
													(-70, 'name', 'BE Users View', 'de'),
													(-60, 'name', 'BE Instance Info', 'en'),
													(-60, 'name', 'BE Instance Info', 'es'),
													(-60, 'name', 'BE Instance Info', 'de'),
													(-50, 'name', 'BE Dictionary', 'en'),
													(-50, 'name', 'BE Dictionary', 'es'),
													(-50, 'name', 'BE Dictionary', 'de'),
													(-41, 'name', 'FE Menu', 'en'),
													(-41, 'name', 'FE Menu', 'es'),
													(-41, 'name', 'FE Menu', 'de'),
													(-40, 'name', 'FE Frames', 'en'),
													(-40, 'name', 'FE Frames', 'es'),
													(-40, 'name', 'FE Frames', 'de'),
													(-30, 'name', 'BE Settings', 'en'),
													(-30, 'name', 'BE Settings', 'es'),
													(-30, 'name', 'BE Settings', 'de'),
													(-21, 'name', 'BE Domain Edit', 'en'),
													(-21, 'name', 'BE Domain Edit', 'es'),
													(-21, 'name', 'BE Domain Edit', 'de'),
													(-20, 'name', 'BE Domains View', 'en'),
													(-20, 'name', 'BE Domains View', 'es'),
													(-20, 'name', 'BE Domains View', 'de'),
													(-12, 'name', 'BE Page Edit', 'en'),
													(-12, 'name', 'BE Page Edit', 'es'),
													(-12, 'name', 'BE Page Edit', 'de'),
													(-11, 'name', 'BE Page Treeview', 'en'),
													(-11, 'name', 'BE Page Treeview', 'es'),
													(-11, 'name', 'BE Page Treeview', 'de'),
													(-10, 'name', 'BE Page Frames', 'en'),
													(-10, 'name', 'BE Page Frames', 'es'),
													(-10, 'name', 'BE Page Frames', 'de'),
													(-8, 'name', 'BE Selector', 'en'),
													(-8, 'name', 'BE Selector', 'es'),
													(-8, 'name', 'BE Selector', 'de'),
													(-7, 'name', 'BE Refresh', 'en'),
													(-7, 'name', 'BE Refresh', 'es'),
													(-7, 'name', 'BE Refresh', 'de'),
													(-6, 'name', 'BE Editor', 'en'),
													(-6, 'name', 'BE Editor', 'es'),
													(-6, 'name', 'BE Editor', 'de'),
													(-5, 'name', 'BE Actions', 'en'),
													(-5, 'name', 'BE Actions', 'es'),
													(-5, 'name', 'BE Actions', 'de'),
													(-4, 'name', 'BE Login', 'en'),
													(-4, 'name', 'BE Login', 'es'),
													(-4, 'name', 'BE Login', 'de'),
													(-3, 'name', 'BE Home', 'en'),
													(-3, 'name', 'BE Home', 'es'),
													(-3, 'name', 'BE Home', 'de'),
													(-2, 'name', 'BE Main Menu', 'en'),
													(-2, 'name', 'BE Main Menu', 'es'),
													(-2, 'name', 'BE Main Menu', 'de'),
													(-1, 'name', 'BE Frames', 'en'),
													(-1, 'name', 'BE Frames', 'es'),
													(-1, 'name', 'BE Frames', 'de'),
													(0, 'name', 'Standard Page', 'en'),
													(0, 'name', 'Página Estandard', 'es'),
													(0, 'name', 'Standardseite', 'de'),
													(1, 'urlPrefix', 'startseite', 'de'),
													(1, 'name', 'Startseite', 'de'),
													(1, 'urlPrefix', 'homepage', 'en'),
													(1, 'name', 'Homepage', 'en'),
													(1, 'urlPrefix', 'inicio', 'es'),
													(1, 'name', 'Inicio', 'es');
												");
		
		}
		
		### TABLE CMS_USERS ###
		
		array_push($dbhcms_database_sql['CMS'],  "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_users` (
												  `user_id` int(11) NOT NULL auto_increment,
												  `user_login` varchar(100) NOT NULL default '',
												  `user_passwd` varchar(250) NOT NULL default '',
												  `user_name` varchar(200) NOT NULL default '',
												  `user_sex` varchar(30) default NULL,
												  `user_company` varchar(250) default NULL,
												  `user_location` varchar(250) default NULL,
												  `user_email` varchar(250) default NULL,
												  `user_website` varchar(250) default NULL,
												  `user_lang` varchar(4) default NULL,
												  `user_domains` varchar(250) NOT NULL default '',
												  `user_level` varchar(250) NOT NULL default '',
												  PRIMARY KEY  (`user_id`),
												  UNIQUE KEY `user_login` (`user_login`)
												);");
		array_push($dbhcms_database_sql['CMS'],  "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."cms_users` (`user_id`, `user_login`, `user_passwd`, `user_name`, `user_sex`, `user_company`, `user_location`, `user_email`, `user_website`, `user_lang`, `user_domains`, `user_level`) VALUES 
													(1, '".$_POST['dbhcms_inst_superuser_login']."', '".md5($_POST['dbhcms_inst_superuser_passwd'])."', '".$_POST['dbhcms_inst_superuser_name']."', 'ST_MALE', '', '', '', '', '".$_POST['dbhcms_inst_superuser_lang']."', '1', 'A;B;C;D;E;F;G;H;I;J;K;L;M;N;O;P;Q;R;S;T;U;V;W;X;Y;Z;0;1;2;3;4;5;6;7;8;9'),
													(2, 'john', '".md5('john')."', 'John Miller', 'ST_MALE', '', '', '', '', 'en', '1', 'A;B;C');");
		
		### TABLE CMS_VISITS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_visits` (
												  `visit_id` int(11) NOT NULL auto_increment,
												  `visit_sessionid` varchar(255) NOT NULL,
												  `visit_domn_id` int(11) NOT NULL default '0',
												  `visit_httpuseragent` text,
												  `visit_remoteaddr` text,
												  `visit_requesturi` text,
												  `visit_requestmethod` text,
												  `visit_visitdatetime` datetime NOT NULL default '0000-00-00 00:00:00',
												  `visit_origin` text,
												  `visit_history` text,
												  `visit_search_phrase` text,
												  `visit_search_engine` text,
												  `visit_browser_langs` text NOT NULL,
												  PRIMARY KEY  (`visit_id`),
												  KEY `visit_sessionid` (`visit_sessionid`)
												);");
		
		### TABLE CMS_ACCESSLOG ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_accesslog` (
													`aclg_id` INT NOT NULL AUTO_INCREMENT ,
													`aclg_sessionid` TEXT NOT NULL ,
													`aclg_user` VARCHAR( 100 ) NOT NULL ,
													`aclg_action` VARCHAR( 50 ) NOT NULL ,
													`aclg_datetime` DATETIME NOT NULL ,
													PRIMARY KEY ( `aclg_id` )
												);");
		
		### TABLE CMS_SESSIONS ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_sessions` (
												  `sess_nr` int(11) NOT NULL auto_increment,
												  `sess_id` varchar(250) NOT NULL default '',
												  `sess_user` varchar(250) NOT NULL default '',
												  `sess_start` datetime NOT NULL default '0000-00-00 00:00:00',
												  `sess_update` datetime NOT NULL default '0000-00-00 00:00:00',
												  `sess_stop` datetime NOT NULL default '0000-00-00 00:00:00',
												  `sess_active` char(1) NOT NULL default 'Y',
												  `sess_dead` char(1) NOT NULL default 'N',
												  PRIMARY KEY  (`sess_nr`)
												);");
		
		### TABLE CMS_ERRORLOG ###
		
		array_push($dbhcms_database_sql['CMS'], "CREATE TABLE `".DBHCMS_C_INST_DB_PREFIX."cms_errorlog` (
													  `erlg_id` int(11) NOT NULL auto_increment,
													  `erlg_sessionid` varchar(250) NOT NULL default '',
													  `erlg_file` varchar(250) NOT NULL default '',
													  `erlg_class` varchar(250) NOT NULL default '',
													  `erlg_function` varchar(250) NOT NULL default '',
													  `erlg_line` int(11) NOT NULL default '0',
													  `erlg_error` varchar(250) NOT NULL default '',
													  `erlg_isfatal` char(1) NOT NULL default '',
													  `erlg_instinfo` text NOT NULL,
													  `erlg_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
													  PRIMARY KEY  (`erlg_id`)
												);");
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# EXTENSIONS                                                               #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		define('DBHCMS_C_EXT_SETUP', 'INST');
		
		### EXT SEARCH ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/search/ext.search.inst.php');
		
		### EXT CONTACT ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/contact/ext.contact.inst.php');
		
		### EXT GUESTBOOK ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/guestbook/ext.guestbook.inst.php');
		
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
		
			array_push($dbhcms_database_sql['EXT']['guestbook'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_guestbook_entries` (`gben_id`, `gben_domn_id`, `gben_page_id`, `gben_name`, `gben_sex`, `gben_company`, `gben_location`, `gben_email`, `gben_website`, `gben_text`, `gben_date`) VALUES 
																	(1, 1, 8, 'John Miller', 'ST_MALE', 'Systems Inc.', 'Germany', 'test@gmx.net', 'http://www.drbenhur.com', 'Hi! Very nice page!\r\n\r\nGreets :D', NOW()),
																	(2, 1, 8, 'Susi', 'ST_FEMALE', '', '', '', '', 'Some example text of a visitor ;-)', NOW());");
		}
		
		### EXT PHOTOALBUM ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/photoalbum/ext.photoalbum.inst.php');
		
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
		
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albs` (`paal_id`, `paal_domn_id`, `paal_page_id`, `paal_folder`, `paal_thumbnail_img`, `paal_userlevel`, `paal_date`, `paal_rate_1`, `paal_rate_2`, `paal_rate_3`, `paal_rate_4`, `paal_rate_5`) VALUES
																	(1, 1, 3, 'images/albums/caribean/', 'images/albums/caribean.jpg', 'A', '2006-09-01', 0, 0, 0, 0, 0),
																	(2, 1, 3, 'images/albums/venice/', 'images/albums/venice.jpg', 'A', '2005-06-08', 0, 0, 0, 0, 0);");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_albsvals` (`paav_id`, `paav_paal_id`, `paav_name`, `paav_value`, `paav_lang`) VALUES 
																	(1, 1, 'title', 'Die Karibik', 'de'),
																	(2, 1, 'presence', 'Einige Freunde', 'de'),
																	(3, 1, 'activities', 'Beachvolleyball und schwimmen', 'de'),
																	(4, 1, 'location', 'Eine schöne Insel in der Karibik', 'de'),
																	(5, 1, 'title', 'The Caribic', 'en'),
																	(6, 1, 'presence', 'Some friends', 'en'),
																	(7, 1, 'activities', 'Beachvolleyball and swimming', 'en'),
																	(8, 1, 'location', 'A nice Island in the caribic', 'en'),
																	(9, 1, 'title', 'El Caribe', 'es'),
																	(10, 1, 'presence', 'Unos amigos', 'es'),
																	(11, 1, 'activities', 'Beachvolleyball y nadar', 'es'),
																	(12, 1, 'location', 'Una isla bonita en el caribe', 'es'),
																	(25, 2, 'title', 'Venedig', 'de'),
																	(26, 2, 'presence', 'Andere freunde', 'de'),
																	(27, 2, 'activities', 'Die Stadt gesehen', 'de'),
																	(28, 2, 'location', 'Venedig, Italien', 'de'),
																	(29, 2, 'title', 'Venice', 'en'),
																	(30, 2, 'presence', 'Some other friends', 'en'),
																	(31, 2, 'activities', 'Get to know the city', 'en'),
																	(32, 2, 'location', 'Venice, Italy', 'en'),
																	(33, 2, 'title', 'Venecia', 'es'),
																	(34, 2, 'presence', 'Otros amigos', 'es'),
																	(35, 2, 'activities', 'Conocer la ciudad', 'es'),
																	(36, 2, 'location', 'Venecia, Italia', 'es');
																");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_piccomments` (`papc_id`, `papc_paal_id`, `papc_user_id`, `papc_filename`, `papc_username`, `papc_sex`, `papc_email`, `papc_homepage`, `papc_location`, `papc_entrytext`, `papc_datetime`) VALUES 
																	(6, 1, 1, 'ptstvin.jpg', 'John', 'ST_MALE', '', 'http://www.drbenhur.com', 'Germany', 'Very nice !!! :o', '2006-06-21 15:31:04');");
			array_push($dbhcms_database_sql['EXT']['photoalbum'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_photoalbum_pics` (`papi_id`, `papi_paal_id`, `papi_filename`, `papi_userlevel`, `papi_rate_1`, `papi_rate_2`, `papi_rate_3`, `papi_rate_4`, `papi_rate_5`) VALUES 
																		(1, 1, 'caletta.jpg', 'A', 1, 0, 0, 0, 3),
																		(3, 1, 'ptstvin.jpg', 'A', 0, 0, 0, 0, 2),
																		(4, 1, 'sunset.jpg', 'A', 2, 0, 0, 0, 1),
																		(10, 2, 'ven4.jpg', 'A', 0, 0, 0, 1, 0),
																		(11, 2, 'ven5.jpg', 'A', 0, 0, 1, 0, 0),
																		(12, 2, 'ven6.jpg', 'A', 0, 1, 0, 0, 0),
																		(13, 2, 'ven7.jpg', 'A', 0, 0, 1, 0, 0),
																		(14, 2, 'ven8.jpg', 'A', 0, 0, 0, 0, 0),
																		(110, 1, 'imbay.jpg', 'A', 0, 0, 0, 0, 0);
																	");
		}
		
		### EXT SMILIES ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/smilies/ext.smilies.inst.php');
		
		### EXT NEWS ###
		require_once(DBHCMS_C_INST_CORE_DIR.'ext/news/ext.news.inst.php');
		
		if ((isset($_POST['dbhcms_inst_demo_pages'])) && ($_POST['dbhcms_inst_demo_pages'] == '1')) {
		
			array_push($dbhcms_database_sql['EXT']['news'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_news_comments` (`nwcm_id`, `nwcm_nwen_id`, `nwcm_user_id`, `nwcm_username`, `nwcm_sex`, `nwcm_email`, `nwcm_homepage`, `nwcm_location`, `nwcm_entrytext`, `nwcm_datetime`) VALUES 
																(1, 1, 0, 'Jeff', 'ST_MALE', '', '', '', 'This is an example comment for the article', '2006-07-28 09:24:13');");
			array_push($dbhcms_database_sql['EXT']['news'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_news_entries` (`nwen_id`, `nwen_domn_id`, `nwen_page_id`, `nwen_userlevel`, `nwen_date`) VALUES 
																(1, 1, 0, 'A', '2006-07-28 09:05:10');");
			array_push($dbhcms_database_sql['EXT']['news'], "INSERT INTO `".DBHCMS_C_INST_DB_PREFIX."ext_news_entriesvals` (`nwev_id`, `nwev_nwen_id`, `nwev_name`, `nwev_value`, `nwev_lang`) VALUES 
																(1, 1, 'title', 'Titel des Artikels', 'de'),
																(2, 1, 'title', 'Title of the news article', 'en'),
																(3, 1, 'title', 'Titulo del articulo', 'es'),
																(4, 1, 'subtitle', 'Subtitel des Artikels', 'de'),
																(5, 1, 'subtitle', 'Subtitle of the news article', 'en'),
																(6, 1, 'subtitle', 'Subtitulo del articulo', 'es'),
																(7, 1, 'teaser', 'Hier kommt eine kurze Zusammenfassung des Artikels', 'de'),
																(8, 1, 'teaser', 'Here comes a short summary of the article', 'en'),
																(9, 1, 'teaser', 'Aqui viene un corto resumen del articulo', 'es'),
																(10, 1, 'content', '<img src=\"images/other/newstest.gif\" border=\"0\" alt=\"Some Image\" title=\"Some Image\" hspace=\"8\" vspace=\"8\" width=\"189\" height=\"153\" align=\"left\" /><br />\r\n<br />\r\n<br />\r\nHier kommt der Inhalt des Artikels. Es ist m&ouml;glich diesen Inhalt mit dem WYSISWG Editor zu bearbeiten und z.B. Bilder einf&uuml;gen. <br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />', 'de'),
																(11, 1, 'content', '<img src=\"images/other/newstest.gif\" border=\"0\" alt=\"Some Image\" title=\"Some Image\" hspace=\"8\" vspace=\"8\" width=\"189\" height=\"153\" align=\"left\" /><br />\r\n<br />\r\n<br />\r\nHere goes the content of the article. You can edit this with the WYSIWYG editor to for example add pictures. <br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />', 'en'),
																(12, 1, 'content', '<img src=\"images/other/newstest.gif\" border=\"0\" alt=\"Some Image\" title=\"Some Image\" hspace=\"8\" vspace=\"8\" width=\"189\" height=\"153\" align=\"left\" /><br />\r\n<br />\r\n<br />\r\nAqui viene el contenido del articulo. Se puede editar por medio del editor WYSIWYG y por ejemplo a&ntilde;adir imagenes. <br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />\r\n<br />', 'es');
															");
		}
		
		
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		# START INSTALLATION                                                       #
		#==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==##==#
		
		foreach ($dbhcms_database_sql['CMS'] as $sql) {
			mysql_query($sql) or dbhcms_p_error('Error creating database. SQL-Error: '.mysql_error(), true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		foreach ($dbhcms_database_sql['EXT'] as $ext => $tables) {
			if (isset($_POST['dbhcms_inst_ext_'.$ext])) {
				if ($_POST['dbhcms_inst_ext_'.$ext] == '1') {
					foreach ($tables as $sql) {
						mysql_query($sql) or dbhcms_p_error('Error creating database. SQL-Error: '.mysql_error(), true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
					}
				}
			}
		}
		
		if (is_file('config.php')&&(filesize('config.php') > 0)) {
			$dbhcms_config_file = file('config.php');
			$dbhcms_config_file_new = fopen('config.php', "w");
			foreach ($dbhcms_config_file as $dbhcms_config_line) {
				if (substr_count($dbhcms_config_line, 'dbhcms_installed') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_installed = true; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_core_dir') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_core_dir = '".DBHCMS_C_INST_CORE_DIR."'; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_db_server') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_db_server = '".$_POST['dbhcms_inst_db_server']."'; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_db_database') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_db_database = '".$_POST['dbhcms_inst_db_database']."'; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_db_user') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_db_user = '".$_POST['dbhcms_inst_db_user']."'; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_db_pass') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_db_pass = '".$_POST['dbhcms_inst_db_pass']."'; \n");
				} else if (substr_count($dbhcms_config_line, 'dbhcms_db_prefix') > 0) {
					fwrite($dbhcms_config_file_new, '	'.'$'."dbhcms_db_prefix = '".DBHCMS_C_INST_DB_PREFIX."'; \n");
				} else {
					fwrite($dbhcms_config_file_new, $dbhcms_config_line);
				}
			}
			
			fclose($dbhcms_config_file_new);
			
			$phpmyadmin_config_file = fopen(DBHCMS_C_INST_CORE_DIR.'apps/phpmyadmin/config.inc.php', 'w');
			
			fwrite($phpmyadmin_config_file,	"
																				<?php
			
																					# File generated by DBHcms ".mktime()."

																					$"."cfg['blowfish_secret'] = '".dbhcms_f_generate_random_str(16)."';

																					$"."cfg['Servers'][1]['auth_type'] = 'cookie';

																					$"."cfg['Servers'][1]['host'] = '".$_POST['dbhcms_inst_db_server']."';
																					$"."cfg['Servers'][1]['connect_type'] = 'tcp';
																					$"."cfg['Servers'][1]['compress'] = false;
																					$"."cfg['Servers'][1]['extension'] = 'mysql';

																					$"."cfg['Servers'][1]['only_db'] = '".$_POST['dbhcms_inst_db_database']."';

																				?> ");
			
			fclose($phpmyadmin_config_file);
			
			# fill missing values
			$GLOBALS['DBHCMS']['CONFIG']['DB']['prefix'] = DBHCMS_C_INST_DB_PREFIX;
			$GLOBALS['DBHCMS']['CONFIG']['CORE']['supportedLangs'] = array('en', 'es', 'de');
			dbhcms_p_add_missing_pagevals();
			
			
			
		} else {
			dbhcms_p_error('File "config.php" was not found or is empty.', true, __FILE__, __CLASS__, __FUNCTION__, __LINE__);
		}
		
		
	}

#############################################################################################
#  INSTALLATION FORM                                                                        #
#############################################################################################

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title> DBHcms Installation </title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	
	<link rel="STYLESHEET" type="text/css" href="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/css/dbhcms.admin.css">
	
	<script language="JavaScript">
	
		function check(){
		
			var superuser_passwd    		= document.dbhcms_install.dbhcms_inst_superuser_passwd.value;
			var superuser_passwd_confirm   	= document.dbhcms_install.dbhcms_inst_superuser_passwd_confirm.value;
			var superuser_login  			= document.dbhcms_install.dbhcms_inst_superuser_login.value;
	
			var m_superuser_passwd			= "";
			var m_superuser_passwd_confirm	= "";
			var m_superuser_login			= "";
	
	
			if (superuser_passwd == "")
				var m_superuser_passwd = "  -> You have to type a password for the DBHcms superuser ! \n";
	
			if (superuser_passwd != superuser_passwd_confirm)
				var m_superuser_passwd_confirm = "  -> The DBHcms superuser passwords do not match ! \n";
				
			if (superuser_login == "")
				var m_superuser_login = "  -> You have to type a login for the DBHcms superuser ! \n";
				
				
			if ( m_superuser_passwd != "" || m_superuser_passwd_confirm != "" || m_superuser_login != "" ){
				alert("You have following errors in your entries : \n\n" + m_superuser_passwd + m_superuser_passwd_confirm +  m_superuser_login);
				return false;
			}
			else
			{
				return true;		
			}
		}
		
	</script>
	
</head>
<body style="margin: 8px;">
	
	<div id="login_wrapper" style="width:770px;">
		<div id="login_banner" style="width:770px;">
			<div id="login_title" style="width:770px;"><h1 style="color: #000000; font-size: 18pt;">DBHCMS INSTALLATION</h1></div>
		</div>
		<div id="login_form" style="width:770px;">
			<table width="770" cellpadding="8" cellspacing="0" border="0">
				<tr>
					<td bgcolor="#FFFFFF" align="center">

						<?php  if (isset($_POST['dbhcms_perform_installation'])) { ?>
							
							<br />
							<strong>Congratulations!</strong><br />
							<br />
							The DBHcms has been successfully installed in your system! <br /> Have a lot of fun with your brand new content management system :)<br>
							<br>
							<a href="index.php?dbhcms_pid=-1"><u>Click here for the DBHcms Administration.</u></a><br />
							<a href="index.php"><u>Click here for the demo pages.</u></a><br />
							<br />
							
						<?php  } else { ?>
							
							
							
							<form method="post" name="dbhcms_install" onsubmit=" return check(); ">
								
								<input type="Hidden" name="dbhcms_perform_installation" value="1">
								
								<br />
								<h2>Settings</h2>
								<div class="box">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="230">Parameter</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="200">Value</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18">Description</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>DBHcms core directory : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_core_dir" value="<?php echo $GLOBALS['dbhcms_core_dir']; ?>" style="width: 190px;"></td>
											<td align="left">
												Directory where the DBHcms files are.<br>
												Example: <strong>dbhcms/</strong>
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>MySQL database server : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_db_server" value="<?php echo $GLOBALS['dbhcms_db_server']; ?>" style="width: 190px;"></td>
											<td align="left">
												Host-name or IP adress of the MySQL server.
											</td>
										</tr>
										
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>MySQL database name : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_db_database" value="<?php echo $GLOBALS['dbhcms_db_database']; ?>" style="width: 190px;"></td>
											<td align="left">
												Name of the MySQL database.
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>MySQL database user : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_db_user" value="<?php echo $GLOBALS['dbhcms_db_user']; ?>" style="width: 190px;"></td>
											<td align="left">
												User login-name for the MySQL database.
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>MySQL database password : &nbsp; </strong></td>
											<td align="center"><input type="password" name="dbhcms_inst_db_pass" value="<?php echo $GLOBALS['dbhcms_db_pass']; ?>" style="width: 190px;"></td>
											<td align="left">
												User password for the MySQL database.
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>DBHcms table prefix : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_db_prefix" value="<?php echo $GLOBALS['dbhcms_db_prefix']; ?>" style="width: 190px;"></td>
											<td align="left">
												Prefix used for all the tables created by the DBHcms. <br />
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Domain name : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_domain_name" value="localhost" style="width: 190px;"></td>
											<td align="left">
												Domain name or host. <br>
												Example: <strong>www.domain.com</strong> or <strong>127.0.0.1</strong>
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>Domain subfolders : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_domain_subfolders" value="/DBHcms/" style="width: 190px;"></td>
											<td align="left">
												Subfolder relative to the domain. <br>
												Example: <strong>/</strong> for no subfolders or <strong>/sfa/sfb/</strong>
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Domain absolute URL : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_domain_url" value="http://localhost/DBHcms/" style="width: 190px;"></td>
											<td align="left">
												Complete URL of the Website. <br>
												Example: <strong>http://www.domain.com/sfa/sfb/</strong>
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>DBHcms superuser : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_superuser_login" value="admin" style="width: 190px;"></td>
											<td align="left">
												Login-name for the user to acces the back-end (BE) and administrate the DBHcms.
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>DBHcms superuser password : &nbsp; </strong></td>
											<td align="center"><input type="password" name="dbhcms_inst_superuser_passwd" value="" style="width: 190px;"></td>
											<td align="left">
												Password for the DBHcms superuser.
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>Confirm superuser password : &nbsp; </strong></td>
											<td align="center"><input type="password" name="dbhcms_inst_superuser_passwd_confirm" value="" style="width: 190px;"></td>
											<td align="left">
												Confirm the password for the DBHcms superuser.
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>DBHcms superuser name : &nbsp; </strong></td>
											<td align="center"><input type="text" name="dbhcms_inst_superuser_name" value="DBHcms Administrator" style="width: 190px;"></td>
											<td align="left">
												A real name for the DBHcms superuser
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>DBHcms superuser language : &nbsp; </strong></td>
											<td align="center">
									 			<select name="dbhcms_inst_superuser_lang" style="width: 195px;">
													<option value="de">German</option>
													<option value="en" selected>English</option>
													<option value="es">Spanish</option>
												</select>
											</td>
											<td align="left">
												Prefered language when loging in with the DBHcms superuser
											</td>
										</tr>
										
									</table>
								</div>
								<br />
								<h2>Pages</h2>
								<div class="box">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="230">Page</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="200">Install</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18">Description</td>
										</tr>
										
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Demonstration Pages : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_demo_pages" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												If yes, some demonstration pages will be added to your installation. You can modify or delete them afterwards. If not, just an index page will be generated.
											</td>
										</tr>
										
									</table>
								</div>
								<br />
								<h2>Extensions</h2>
								<div class="box">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="230">Extension</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="200">Install</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18">Description</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Contact : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_ext_contact" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												A small contact form that sends e-mails and saves messages.
											</td>
										</tr>
										
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>News : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_ext_news" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												A full featured news system with authentication, comments and newsletter functions.
											</td>
										</tr>
										
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Guestbook : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_ext_guestbook" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												A small guestbook.
											</td>
										</tr>
										<tr bgcolor="#DEDEDE">
											<td align="right"><strong>Photo Album : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_ext_photoalbum" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												A full featured photo album with user authentication, comments and rating functions.
											</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="right"><strong>Smilies : &nbsp; </strong></td>
											<td align="center">
												<select type="text" name="dbhcms_inst_ext_smilies" style="width: 190px;">
													<option value="1" selected>Yes</option>
													<option value="0">No</option>
												</select>
											</td>
											<td align="left">
												Inserts smilies in contents.
											</td>
										</tr>
									</table>
								</div>
								<br />
								<h2>Theme</h2>
								<div class="box">
									<table cellpadding="2" cellspacing="1" border="0" width="100%">
										<tr>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="33%">Sky Theme</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="33%">Waves Theme</td>
											<td align="center" background="<?php echo $GLOBALS['dbhcms_core_dir']; ?>/img/tab_cap.gif" class="cap" height="18" width="33%">Sunset Theme</td>
										</tr>
										<tr bgcolor="#F0F0F0">
											<td align="center">
												<img onclick="document.dbhcms_install.dbhcms_inst_theme[0].checked = true;" src="images/other/theme_bl_tn.jpg" width="197" height="137" alt="" border="0" style="border: 1px solid #000000; cursor: pointer;"><br />
												<input type="radio" name="dbhcms_inst_theme" value="bl" style="border:0px; background-color: transparent;" checked="checked">
											</td>
											<td align="center">
												<img onclick="document.dbhcms_install.dbhcms_inst_theme[1].checked = true;" src="images/other/theme_gr_tn.jpg" width="197" height="137" alt="" border="0" style="border: 1px solid #000000; cursor: pointer;"><br />
												<input type="radio" name="dbhcms_inst_theme" value="gr" style="border:0px; background-color: transparent;">
											</td>
											<td align="center">
												<img onclick="document.dbhcms_install.dbhcms_inst_theme[2].checked = true;" src="images/other/theme_rd_tn.jpg" width="197" height="137" alt="" border="0" style="border: 1px solid #000000; cursor: pointer;"><br />
												<input type="radio" name="dbhcms_inst_theme" value="rd" style="border:0px; background-color: transparent;">
											</td>
										</tr>
									</table>
								</div>
								<br />
								<table align="center" width="750">
									<tr>
										<td colspan="2" align="left">
											<input type="submit" value=" &nbsp;&nbsp;&nbsp; INSTALL NOW -> &nbsp;&nbsp;&nbsp; ">
										</td>
									</tr>
								</table>
								<input type="hidden" name="dbhcms_inst_ext_search" value="1">
							</form>
						<?php  } ?>
					</td>
				</tr>
			</table>
		</div>
		<br />
		<div align="center" style="width:770px;">
			<a target="_blank" href="http://www.drbenhur.com/" style="font-size: 10px; color:#444DFE;"> &copy; 2005-2007 Kai-Sven Bunk <br /> powered by DBHcms </a>
		</div>
	</div>

</body>
</html>

<?php 

	exit; 

### EOF ### (C) 2005-2007 Kai-Sven Bunk #####################################################

?>

