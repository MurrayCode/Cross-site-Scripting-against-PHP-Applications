<?php
/**
 * Este archivo forma parte del proyecto Brim
 *El proyecto Brim se encuentra en la siguiente 
 * direcci&oacute;n: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Disfrute :-) </pre>
 *
 * @author Diego Carrasco
 * @package org.brim-project.framework
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2007 Barry Nauta
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
$dictionary['about_page']=' <h2>Acerca de</h2> <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Esta aplicacion ha sido escrita por Barry Nauta.  (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> El proposito es proveer una aplicacion de codigo libre con una unica entrada a un tablero de escritorio (por ejemplo, su correo electronico, vinculos, cosas por hacer, etc, integrado en un solo medio ambiente) </p> <p> Este programa ('.$dictionary['programname'].') ha sido publicado bajo la Licencia Publica General (GNU en ingles).  Haga un clic  <a href="documentation/gpl.html">aqui</a> para ver la version completa de la licencia.  La pagina de inicio de la aplicacion la puede encontrar en la siguiente direccion <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']='Acciones';
$dictionary['activate']='Activar';
$dictionary['add']='A&ntilde;adir';
$dictionary['addFolder']='Agregar una carpeta';
$dictionary['addNode']='Agregar un art&iacute;culo';
$dictionary['adduser']='A&ntilde;adir Usuario';
$dictionary['admin']='Administraci&oacute;n';
$dictionary['adminConfig']='Configuraci&oacute;n';
$dictionary['admin_email']='Correo del administrador';
$dictionary['allow_account_creation']='Permitir creaci&oacute;n de cuentas de usuario';
$dictionary['back']='Regresar';
$dictionary['banking']='E-bank';
$dictionary['bookmark']='Favorito';
$dictionary['bookmarks']='Favoritos';
$dictionary['calendar']='Calendario';
$dictionary['cancel']='Cancelar';
$dictionary['charset']='iso-8859-1';
$dictionary['checkbook']='Talonario';
$dictionary['collapse']='Colapsar';
$dictionary['collections']='Colecciones';
$dictionary['confirm']='Confirmar';
$dictionary['confirm_delete']='¿Est&aacute; usted seguro que desea suprimir?';
$dictionary['contact']='Contacto';
$dictionary['contacts']='Contactos';
$dictionary['contents']='Contenidos';
$dictionary['dashboard']='Escritorio';
$dictionary['database']='Base de datos';
$dictionary['dateFormat']='Formato de Fecha';
$dictionary['deactivate']='Desactivar';
$dictionary['defaultTxt']='Predeterminado';
$dictionary['deleteTxt']='Borrar';
$dictionary['delete_not_owner']='No se permite suprimir un art&iacute;culo del cual usted no se due&ntilde;o.';
$dictionary['Almac&eacute;n']='Depot';
$dictionary['description']='Descripci&oacute;n';
$dictionary['deselectAll']='Deseleccionar todo';
$dictionary['down']='Abajo';
$dictionary['email']='Correo electr&oacute;nico';
$dictionary['expand']='Expandir';
$dictionary['explorerTree']='Arbol del Explorador';
$dictionary['exportTxt']='Exportar';
$dictionary['exportusers']='Exportar Usuarios';
$dictionary['file']='Fichero';
$dictionary['findDoubles']='Encontrar dobles';
$dictionary['folder']='Carpeta';
$dictionary['formError']='Los dator entregados contienen errores';
$dictionary['forward']='Avanzar';
$dictionary['genealogy']='Genealog&iacute;a';
$dictionary['gmail']='GMail';
$dictionary['help']='Ayuda';
$dictionary['home']='Inicio';
$dictionary['importTxt']='Importar';
$dictionary['importusers']='Importar Usuarios';
$dictionary['input']='Entrada (Input)';
$dictionary['input_error']='Por favor compruebe  los campos de la entrada.';
$dictionary['installation_path']='Ruta de la instalaci&oacute;n';
$dictionary['installer_exists']='El archivo de instalaci&oacute;n aun existe! Por favor remuevalo.';
$dictionary['inverseAll']='Invertir todo';
$dictionary['item_count']='N&uacute;mero de &Iacute;tems';
$dictionary['item_private']='Art&iacute;culo privado';
$dictionary['item_public']='Art&iacute;culo p&uacute;blico';
$dictionary['javascript_popups']='Javascript popups';
$dictionary['language']='Lenguaje';
$dictionary['last_created']='&Uacute;ltimo creado';
$dictionary['last_modified']='&Uacute;ltimo modificado';
$dictionary['last_visited']='&Utilde;ltimo visitado';
$dictionary['lineBasedTree']='Linea basado';
$dictionary['link']='v&iacute;nculo';
$dictionary['locator']='URL';
$dictionary['loginName']='Usuario';
$dictionary['logout']='Salir';
$dictionary['mail']='Correo';
$dictionary['message']='Mensaje';
$dictionary['modify']='Modificar';
$dictionary['modify_not_owner']='No se permite modificar un art&iacute;culo que usted no posea';
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
$dictionary['most_visited']='El m&aacute;s visitado';
$dictionary['move']='Mover';
$dictionary['multipleSelect']='Seleci&oacute;n m&uacute;ltiple';
$dictionary['mysqlAdmin']='MySql';
$dictionary['name']='Nombre';
$dictionary['nameMissing']='El nombre tiene que ser definido';
$dictionary['new_window_target']='Donde se abre la nueva ventana';
$dictionary['news']='Noticias';
$dictionary['no']='No';
$dictionary['note']='Nota';
$dictionary['notes']='Notas';
$dictionary['overviewTree']='&Aacute;rbol de visi&oacute;n general';
$dictionary['password']='Contrase&ntilde;a';
$dictionary['passwords']='Contrase&ntilde;as';
$dictionary['pluginSettings']='Ajustes de los Plugins';
$dictionary['plugins']='Plugins';
$dictionary['polardata']='PolarData';
$dictionary['preferedIconSize']='Tama&ntilde;o de &iacute;conos preferido';
$dictionary['preferences']='Preferencias';
$dictionary['priority']='Prioridad';
$dictionary['private']='Privado';
$dictionary['public']='P&uacute;blico';
$dictionary['quickmark']='A&ntilde;ada el vinculo siguiente a sus registros en su browser. Cada vez que visite una pagina y llame este vinculo especifico, la pagina visitada sera automaticamente a&ntilde;adida a su '.$dictionary['programname'].'-registros.<br />';
$dictionary['refresh']='Refrescar';
$dictionary['root']='Raiz';
$dictionary['search']='Buscar';
$dictionary['select']='Seleccionar';
$dictionary['selectAll']='Seleccionar todo';
$dictionary['setModePrivate']='Vea privados';
$dictionary['setModePublic']='Vea p&uacute;blico';
$dictionary['show']='Mostrar';
$dictionary['showTips']='Mostrar Tips';
$dictionary['sort']='Clase';
$dictionary['spellcheck']='Corrector ortogr&aacute;fico';
$dictionary['submit']='Enviar';
$dictionary['synchronizer']='Sincronizador';
$dictionary['sysinfo']='Sysinfo';
$dictionary['task']='Tarea';
$dictionary['tasks']='Tareas';
$dictionary['textsource']='Fuente del Texto';
$dictionary['theme']='Tema';
$dictionary['tip']='Tip';
$dictionary['title']='T&iacute;tulo';
$dictionary['today']='Hoy';
$dictionary['translate']='Traducir';
$dictionary['up']='Arriba';
$dictionary['user']='Usuario';
$dictionary['view']='Vista';
$dictionary['visibility']='Visibilidad';
$dictionary['webtools']='Herramientas Web';
$dictionary['welcome_page']='
<h1>Bienvenido a MIP %s </h1><h2>, el organizador personal. </h2>';
$dictionary['yahooTree']='Arbol de Yahoo';
$dictionary['yahoo_column_count']='Arbol de Yahoo cuenta de la columna';
$dictionary['yes']='Si';

?>
