<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Tony Perez, Sonja van den Borren
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2007 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
include 'framework/i18n/common.php';
if (!isset ($dictionary))
{
	$dictionary=array();
}

$dictionary['about']='Acerca de';
$dictionary['about_page']=' <h2>Acerca de</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Esta aplicaci&#243;n ha sido escrita por Barry Nauta.  (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> El prop&#243;sito es proveer una aplicaci&#15603;n de c&#243;digo libre con una &#250;nica entrada a un tablero de escritorio (por ejemplo, su correo electr&#16051;nico, v&#237;nculos, cosas por hacer, etc., integrado en un s&#243;lo medio ambiente) </p> <p> Este programa ('.$dictionary['programname'].') ha sido publicado bajo la Licencia Publica General (GNU en ingles).  Haga un click  <a href="doc/gpl.html">aqu&#56557;</a> para ver la versi&#243;n completa de la licencia.  La p&#225;gina de inicio de la aplicaci&#14451;n la puede encontrar en la siguiente direcci&#243;n <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']="Acciones";
$dictionary['activate']='Activar';
$dictionary['add']='A&#241;adir';
$dictionary['addFolder'] = "Agregar una carpeta";
$dictionary['addNode'] = "Agregar un art&#237;culo";
$dictionary['adduser']='A&#241;adir Usuario';
$dictionary['admin']='Administraci&#243;n';
$dictionary['admin_email']='Correo Electr&#243;nico del Administrador';
$dictionary['allow_account_creation']="Permitir Creaci&#243;n de Usuarios";
$dictionary['alphabet']=array ('A','B','C','D','E','F','G','H','I','J','K','L','LL', 'M','N','&#209;', 'O','P','Q','R','S','T','U','V','W','X','Y','Z');
$dictionary['back']='Regresar';
$dictionary['banking']='Contabilidad';
$dictionary['bookmark']='Enlace';
$dictionary['bookmarks']='Enlaces';
$dictionary['calendar']='Calendario';
$dictionary['collapse']='Colapsar';
$dictionary['confirm']='Confirmar';
$dictionary['confirm_delete']='Est&#225; usted seguro que desea eliminar?';
$dictionary['contact']='Contacto';
$dictionary['contacts']='Contactos';
$dictionary['contents']='Contenidos';
$dictionary['deactivate']='Desactivar';
$dictionary['deleteTxt']='Borrar';
$dictionary['delete_not_owner']='No le es permitido eliminar un art&#237;culo que no le pertenece';
$dictionary['description']='Descripci&#243;n';
$dictionary['down']='Abajo';
$dictionary['email']='Correo electr&#243;nico';
$dictionary['expand']='Expandir';
$dictionary['explorerTree']='&#193;rbol Vertical';
$dictionary['exportTxt']='Exportar';
$dictionary['exportusers']='Exportar Usuarios';
$dictionary['file']='Archivo';
$dictionary['folder']='Carpeta';
$dictionary['forward']='Avanzar';
$dictionary['genealogy']='Genealog&#237;a';
$dictionary['help']='Ayuda';
$dictionary['home']='Home';
$dictionary['importTxt']='Importar';
$dictionary['importusers']='Importar Usuarios';
$dictionary['input']='Entrar';
$dictionary['input_error'] = 'Compruebe por favor los campos de la entrada';
$dictionary['installation_path']="Ruta de Instalaci&#243;n";
$dictionary['installer_exists']='<h2><font color="red">Todavia existe el archivo install.php, por favor elim&#237;nelo</font></h2>';
$dictionary['item_private'] = 'Art&#237;culo privado';
$dictionary['item_public'] = 'Art&#237;culo p&#250;blico';
$dictionary['inverseAll']='Invertir orden';
$dictionary['javascript_popups']= 'Ventanas Emergentes Javascript ';
$dictionary['language']='Idioma';
$dictionary['last_created']='&#218;ltimo creado';
$dictionary['last_modified']='&#218;ltimo modificado';
$dictionary['last_visited']='&#218;ltimo visitado';
$dictionary['license_disclaimer']=' '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Puede contactarme en <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Este programa ('.$dictionary['programname'].') es software libre; usted puede redistribuirlo y/o modificarlo bajo los t&#233;rminos de la GNU Licencia Publica General publicada por "Free Software Foundation"; bien sea bajo la versi&#243;n 2 de la licencia, o (seg&#40186;n su criterio) cualquier versi&#243;n posterior.  Haga click <a href="doc/gpl.html">aqu&#237;</a>para ver la licencia completa.  ';
$dictionary['lineBasedTree']='&#193;rbol de Lineas';
$dictionary['link']='Enlace';
$dictionary['loginName']='Nombre de Usuario';
$dictionary['logout']='Salir';
$dictionary['mail']='Correo';
$dictionary['modify']='Modificar';
$dictionary['modify_not_owner']='No le es permitido modificar un art&#237;culo que no le pertenece';
$dictionary['month01']='Enero';
$dictionary['month02']='Febrero';
$dictionary['month03']='Marzo';
$dictionary['month04']='Abril';
$dictionary['month05']='Mayo';
$dictionary['month06']='Junio';
$dictionary['month07']='Julio';
$dictionary['month08']='Agosto';
$dictionary['month09']='Septiembre';
$dictionary['month10']='Octubre';
$dictionary['month11']='Noviembre';
$dictionary['month12']='Diciembre';
$dictionary['most_visited']='El m&#225;s visitado';
$dictionary['move']='Mover';
$dictionary['multipleSelect']='Selecion m&#250;ltiple';
//$dictionary['mysqlAdmin']='MySQL';
$dictionary['nameMissing'] = 'El nombre tiene que ser definido';
$dictionary['name']='Nombre';
$dictionary['news']='Noticias';
$dictionary['new_window_target']='Donde se abre la ventana nueva';
$dictionary['note']='Nota';
$dictionary['notes']='Notas';
$dictionary['overviewTree']='&#193;rbol Descriptivo';
$dictionary['password']='Contrase&#241;a';
$dictionary['passwords']='Contrase&#241;as';
$dictionary['pluginSettings']='Plugins';
$dictionary['plugins']='Plugins';
$dictionary['preferences']='Preferencias';
$dictionary['priority']='Prioridad';
$dictionary['private']='Privado';
$dictionary['public']='P&#250;blico';
$dictionary['quickmark']='A&#241;ada el siguiente enlace a los registros de su navegador. Cada vez que visite una p&#225;gina y llame este v&#6253;nculo espec&#237;fico, la p&#225;gina visitada sera a&#55409;adida autom&#225;ticamente a su "'.$dictionary['programname'].'-registros".<br />';
$dictionary['refresh']='Actualizar';
$dictionary['root']='Ra&#237;z';
$dictionary['search']='Buscar';
$dictionary['selectAll']='Selecionar Todo';
$dictionary['setModePrivate'] = 'Ver privados';
$dictionary['setModePublic'] = 'Ver p&#250;blicos';
$dictionary['show']='Mostrar';
$dictionary['sort']='Ordenar';
$dictionary['submit']='Enviar/Actualizar';
$dictionary['sysinfo']='Informaci&#243;n del Sistema';
$dictionary['theme']='Tema';
$dictionary['title']='T&#237;tulo';
$dictionary['today']='Hoy...';
$dictionary['tasks']='Tareas';
$dictionary['task']='Tarea';
$dictionary['up']='Arriba';
$dictionary['locator']='URL';
$dictionary['user']='Usuario';
$dictionary['view']="Ver";
$dictionary['view']="Ver";
$dictionary['visibility']='Visibilidad';
$dictionary['webtools']='Herramientas &#218;tiles';
$dictionary['welcome_page']='<h1>Bienvenido %s </h1><h2>'.$dictionary['programname'].' - es un algo con algunas cosas</h2>';
$dictionary['yahoo_column_count']='&#193;rbol de Yahoo, Nzmero de Columnas';
$dictionary['yahooTree']='&#193;rbol de Yahoo';
$dictionary['yes']='Si';
$dictionary['item_help']='
	<h1>Ayuda de '.$dictionary['programname'].'</h1>
	<p>
		'.$dictionary['programname'].' tiene dos barras de men&#250;es: una es la barra
		general la cual incluye opciones que afectan a toda
		la aplicaci&#243;n; la otra es la barra de plugins en
		la cual se enlazan los distintos plugins disponibles.
		Para consultar ayuda espec&#237;fica de los plugins, haga
		click <a href="#plugins">aqu&#237;</a>.
	</p>
	<p>
		El enlace de preferencias conduce a una pantalla en la que
		es posible: establecer el idioma que usted desee emplear,
		el tema de la aplicaci&#243;n y opciones personales, tales como:
		contrase&#241;a, direcci&#243;n de correo electr&#7411;nico,
		etc. Observe que no es posible alterar el idioma y el tema
		simult&#225;neamente.
	</p>
	<p>
		El enlace "Acerca de" muestra informaci&#243;n general de la
		aplicaci&#243;n, incluyendo la versi&#243;n de la misma.
	</p>
	<p>
		Hacer click en el enlace Salir lo desconectar&#225; de la
		aplicaci&#243;n. Este enlace tambi&#233;n destruye la "cookie"
		guardada cuando usted selecciona la opci&#243;n
		"Recordarme" al ingresar, de modo que luego tendr&#225; que
		reconectarse (introducir nuevamente su nombre de usuario
	       	y contraser&#241;a) antes de volver a usar '.$dictionary['programname'].'.
	</p>
	<p>
		La secci&#243;n de Plugins le permite activar y desactivar los
		plugins. Si un plugin est&#225; desactivado, no ser&#225; mostrado
		en su barra de plugins ni en la secci&#243;n de ayuda.
	</p>
';
?>
