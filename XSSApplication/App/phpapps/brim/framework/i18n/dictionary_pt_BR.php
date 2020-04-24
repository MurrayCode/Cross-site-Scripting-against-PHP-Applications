<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author leroma
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
	$dictionary = array ();
}
$dictionary['about']='Sobre';
$dictionary['about_page']=' <h2>Sobre</h2>
 <p><b>'.$dictionary['programname'].' '.$dictionary['version'].'</b> Esta aplica&#231;&#227;o desenvolvida por '.$dictionary['authorname'].' (email: <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>) '.$dictionary['copyright'].' </p> <p> O objetivo consiste na disponibiliza&#231;&#227;o de um &#30970;nico ambiente de c&#243;digo aberto de trabalho remoto (ex: correio electr&#244;nico, favoritos, tarefas, etc integrado em um s&#15667; ambiente).  </p> <p> Este programa ('.$dictionary['programname'].') &#233; um software livre; pode ser redistribuido e/ou modificado segundo os termos da GNU General Public License publicada pela Free Software Foundation.  clique <a href="doc/gpl.html">aqui</a> para ver a vers&#227;o completa da licen&#39143;a.  A p&#225;gina oficial da aplica&#231;&#6627;o '.$dictionary['programname'].' pode ser encontrada no seguinte endere&#231;o: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> </p> ';
$dictionary['actions']='A&#231;&#245;es';
$dictionary['activate']='Ativar';
$dictionary['add']='Adicionar';
$dictionary['addFolder']='Adicionar uma pasta';
$dictionary['addNode']='Adicionar um item';
$dictionary['adduser']='Adicionar usu&#225;rio';
$dictionary['admin']='Administrador';
$dictionary['adminConfig']='Configura&#231;&#227;o';
$dictionary['admin_email']='Email do administrador';
$dictionary['allow_account_creation']='Permitir aos usu&#225;rios a cria&#231;&#6627;o de contas';
$dictionary['back']='Voltar';
$dictionary['banking']='Informa&#231;&#245;es de E-Banking';
$dictionary['bookmark']='Favorito';
$dictionary['bookmarks']='Favoritos';
$dictionary['calendar']='Agenda';
$dictionary['collapse']='Retrair';
$dictionary['confirm']='Confirmar';
$dictionary['confirm_delete']='Tem certeza que voc&#234; quer excluir?';
$dictionary['contact']='Contato';
$dictionary['contacts']='Contatos';
$dictionary['contents']='Conte&#250;do';
$dictionary['dashboard']='Painel de trabalho';
$dictionary['database']='Banco de dados';
$dictionary['deactivate']='Desativar';
$dictionary['deleteTxt']='Apagar';
$dictionary['delete_not_owner']='Somente o dono deste item pode apag&#225;-lo.';
$dictionary['description']='Descri&#231;&#227;o';
$dictionary['down']='Descer';
$dictionary['email']='Email';
$dictionary['expand']='Expandir';
$dictionary['explorerTree']='Estrutura em &#225;rvore';
$dictionary['exportTxt']='Exportar';
$dictionary['exportusers']='Exportar usu&#225;rio';
$dictionary['file']='Arquivo';
$dictionary['findDoubles']='Achar registros duplicados';
$dictionary['folder']='Pasta';
$dictionary['forward']='Encaminhar';
$dictionary['genealogy']='Genealogia';
$dictionary['help']='Ajuda';
$dictionary['home']='In&#237;cio';
$dictionary['importTxt']='Importar';
$dictionary['importusers']='Importar usu&#225;rios';
$dictionary['input_error']='Por favor verifique os valores do campos de entrada de dados';
$dictionary['installation_path']='Diret&#243;rio de instala&#231;&#14819;o';
$dictionary['installer_exists']='<h2><font color="red">O arquivo de instala&#231;&#227;o ainda existe! Remova-o para continuar.</font></h2>';
$dictionary['inverseAll']='Inverter tudo';
$dictionary['item_count']='Quantidade de itens';
$dictionary['item_help']='	<h1>Ajuda do '.$dictionary['programname'].'</h1>
	<p>
        '.$dictionary['programname'].' possui dois menus, um &#233; chamado "menu do sistema" e
        cont&#233;m v&#225;rias configura&#39015;&#245;es do sistema. O outro &#233; chamado
        "barra de plugins" e cont&#233;m os links para os diferentes plugins
        do sistema. Para obter ajuda especif&#237;ca de um plugin <a href="#plugins">clique aqui.</a>
	</p>
	<p>
        O link Prefer&#234;ncias no menu do sitema direciona para uma tela
        onde voc&#234; pode definir o seu idioma, o tema que prefere
        e alterar suas informa&#231;&#245;es pessoais como senha, email, endere&#32103;o etc.
        Repare que o idioma e o tema n&#227;o podem ser alterados ao mesmo tempo!
	</p>
	<p>
        O link Sobre exibe informa&#231;&#245;es gerais sobre o sistema, incluindo
        o n&#250;mero da vers&#227;o atual.
	</p>
	<p>
        Clicando no link Sair ser&#225; realizada a sa&#237;da do sistema.
        Esse link gera a destrui&#231;&#227;o do cookie que foi criado quando voc&#30954;
        selecionou a op&#231;&#227;o "remember me" na tela de entrada, ent&#30947;o quando
        for usar o sistema precisar&#225; informar novamente seu nome de
        usu&#225;rio e senha antes de usar o '.$dictionary['programname'].'.
	</p>
	<p>
        A se&#231;&#227;o plugins permite ativar e desativar os plugins disponiveis.
        Se desativar um plugin, ele n&#227;o ser&#225; mostrado na sua barra de plugins
        e na se&#231;&#227;o de ajuda.
    </p>';
$dictionary['item_private']='Item privado';
$dictionary['item_public']='Item p&#250;blico';
$dictionary['item_title']='T&#237;tulo do item';
$dictionary['javascript_popups']='Popups';
$dictionary['language']='Idioma';
$dictionary['last_created']='&#218;ltima cria&#1703;&#227;o';
$dictionary['last_modified']='&#218;ltima altera&#1703;&#227;o';
$dictionary['last_visited']='&#218;ltima visita';
$dictionary['license_disclaimer']=' A p&#225;gina oficial do '.$dictionary['programname'].' pode ser encontrada no seguinte endere&#231;o: <a href="'.$dictionary['programurl'].'">'.$dictionary['programurl'].'</a> <br /> '.$dictionary['copyright'].' '.$dictionary['authorname'].' (<a href="'.$dictionary['authorurl'].'" >'.$dictionary['authorurl'].'</a>).  Podem contatar-me atrav&#6633;s de <a href="mailto:'.$dictionary['authoremail'].'">'.$dictionary['authoremail'].'</a>.  <br /> Este programa ('.$dictionary['programname'].') &#233; um software livre; pode ser redistribu&#237;do e/ou modificado segundo os termos da GNU General Public License publicada pela Free Software Foundation.  <br /> Clique <a href="doc/gpl.html">aqui</a> para ver a vers&#39779;o completa da licen&#231;a.  ';
$dictionary['lineBasedTree']='Estrutura em linha';
$dictionary['link']='link';
$dictionary['locator']='URL';
$dictionary['loginName']='Usu&#225;rio';
$dictionary['logout']='Sair';
$dictionary['mail']='Correio';
$dictionary['message']='Mensagem';
$dictionary['modify']='Modificar';
$dictionary['modify_not_owner']='Somente o dono deste item pode modific&#225;-lo.';
$dictionary['month01']='Janeiro';
$dictionary['month02']='Fevereiro';
$dictionary['month03']='Mar&#231;o';
$dictionary['month04']='Abril';
$dictionary['month05']='Maio';
$dictionary['month06']='Junho';
$dictionary['month07']='Julho';
$dictionary['month08']='Agosto';
$dictionary['month09']='Setembro';
$dictionary['month10']='Outubro';
$dictionary['month11']='Novembro';
$dictionary['month12']='Dezembro';
$dictionary['most_visited']='Mais visitada';
$dictionary['move']='Mover';
$dictionary['multipleSelect']='Sele&#231;&#227;o m&#30970;ltipla';
$dictionary['mysqlAdmin']='mySQLAdmin';
$dictionary['name']='Nome';
$dictionary['nameMissing']='O nome precisa ser informado';
$dictionary['new_window_target']='Onde a nova janela ir&#225; abrir';
$dictionary['news']='Not&#237;cias';
$dictionary['no']='N&#227;o';
$dictionary['note']='Nota';
$dictionary['notes']='Notas';
$dictionary['overviewTree']='Estrutura resumida';
$dictionary['password']='Senha';
$dictionary['passwords']='Senhas';
$dictionary['pluginSettings']='Configura&#231;&#245;es dos plugins';
$dictionary['plugins']='Plugins';
$dictionary['preferences']='Prefer&#234;ncias';
$dictionary['priority']='Prioridade';
$dictionary['private']='Privado';
$dictionary['public']='P&#250;blico';
$dictionary['quickmark']='Clique com o BOT&#195;O DIREITO do mouse sobre a seguinte link para a adicionar aos Favoritos do seu <b>browser</b>. <br />Cada vez que voc&#234; utilizar estes favoritos a partir dos favoritos do seu browser, a p&#225;gina na qual voc&#234; se encontra, ser&#6817; automaticamente adicionada aos favoritos do seu '.$dictionary['programname'].'. <br /><br /><font size="-2">Por favor, clique "OK" se o sistema perguntar se voc&#234; deseja adicionar este link.</font><br />';
$dictionary['refresh']='Atualizar';
$dictionary['root']='Raiz';
$dictionary['search']='Procurar';
$dictionary['selectAll']='Selecionar todos';
$dictionary['setModePrivate']='Ver privados';
$dictionary['setModePublic']='Ver p&#250;blicos';
$dictionary['show']='Mostrar';
$dictionary['sort']='Ordenar';
$dictionary['spellcheck']='Verifica&#231;&#227;o ortogr&#30945;fica';
$dictionary['submit']='Enviar';
$dictionary['synchronizer']='Sincronizador';
$dictionary['sysinfo']='SysInfo - Informa&#231;&#245;es do Sistema';
$dictionary['textsource']='C&#243;digo fonte';
$dictionary['theme']='Tema';
$dictionary['title']='T&#237;tulo';
$dictionary['today']='Hoje';
$dictionary['todo']='Tarefa';
$dictionary['todos']='Tarefas';
$dictionary['translate']='Traduzir';
$dictionary['up']='Subir';
$dictionary['user']='Usu&#225;rio';
$dictionary['view']='Ver';
$dictionary['visibility']='Visibilidade';
$dictionary['webtools']='Ferramentas web';
$dictionary['welcome_page']='<h1>Bem-vindo %s </h1><h2>'.$dictionary['programname'].' - um multi algumacoisa </h2> Boobies com p&#233;s azuis, Boobies com p&#233;s vermelhos e Boobies mascarados.';
$dictionary['yahooTree']='Estrutura em &#225;rvore';
$dictionary['yahoo_column_count']='Estrutura em diret&#243;rios';
$dictionary['yes']='Sim';

?>
