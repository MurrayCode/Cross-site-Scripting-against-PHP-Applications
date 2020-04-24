<?php
/**
 * This file is part of the Brim project.
 * The brim project is located at the following
 * location: {@link http://www.brim-project.org http://www.brim-project.org}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.translate
 * @subpackage i18n
 *
 * @copyright Brim - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary = array ();
}
$dictionary['baseTranslation']='Tradu&#231;&#227;o original';
$dictionary['bothLanguageAndPluginNeeded']='&#201; necess&#609;rio escolher o plugin a traduzir e o idioma da tradu&#231;&#227;o.';
$dictionary['currentTranslation']='Tradu&#231;&#227;o atual';
$dictionary['item_help']='<p>
    O plugin de tradu&#231;&#227;o ajuda voc&#30954; a traduzir o
    sistema para seu idioma ou atualizar uma tradu&#231;&#227;o
    existente.
</p>
<p>
    No subdiret&#243;rio "tools" h&#225; um script chamado <code>dict.sh</code>
    (criado por &#216;yvind Hagen). Esse script configura a
    estrutura de diret&#243;rios necess&#225;ria e ajuda a copiar
    os arquivos para o lugar certo. O script &#233; auto-explicavel.
</p>
<p>
    Se uma tradu&#231;&#227;o existir, o sistema ir&#30945; primeiro
    procurar por um tradu&#231;&#227;o no seu idioma e buscar&#30945;
    o idioma ingl&#234;s[english] caso a tradu&#231;&#43491;o para o
    idioma especificado n&#227;o puder ser acessado.
    Uma tradu&#231;&#227;o incompleta ir&#30945; exibir o texto em ingl&#234;s
    a ser traduzido e a tradu&#231;&#227;o j&#30945; realizada.
</p>

<h2>Como atualizar uma tradu&#231;&#227;o existente?</h2>
<p>
    Atrav&#233;s do plugin de tradu&#231;&#39395;o, selecione o plugin
    que ser&#225; traduzido e o idioma no qual ser&#225; realizada
    a tradu&#231;&#227;o. Ser&#30945; apresentada uma tela contendo o
    Marcador de tradu&#231;&#227;o(uso interno do sistema), a Tradu&#30951;&#227;o
    original(ingl&#234;s), a tradu&#231;&#43491;o atual no idioma selecionado
    (ou em vermelho o texto: \'NOT SET!\' se o item especifico
    ainda n&#227;o possuir uma tradu&#231;&#14819;o) e uma &#225;rea de texto que
    permite alterar ou completar a tradu&#231;&#227;o de cada item.
</p>
<p>
    Quando terminar de realizar a tradu&#231;&#227;o, voc&#30954; ter&#225; a
    op&#231;&#227;o de pr&#30953;-visualizar(preview) o resultado ou realizar
    o download do arquivo de tradu&#231;&#227;o. Realizando o download
    do arquivo voc&#234; ver&#225; um arquivo chamado \'dictionary_XX.php\'
    que precisa ser copiado no diret&#243;rio "lang" do plugin
    traduzido(ou no diret&#243;rio "base" se foi realizada a tradu&#231;&#14819;o
    da estrutura do sistema). O diret&#243;rio e o nome do arquivo
    s&#227;o exibidos no topo da tela de tradu&#231;&#14819;o.
</p>

<h2>Como criar uma nova tradu&#231;&#227;o?</h2>
<p>
    Na tela de sele&#231;&#227;o geral, selecione "New" para
    idioma da tradu&#231;&#227;o. Ser&#30945; apresentada uma tela de
    tradu&#231;&#227;o. Quando terminar a tradu&#30951;&#227;o, clique em pr&#233;-visualizar
    e salve o arquivo com o nome e no caminho informados no topo da
    tela de tradu&#231;&#227;o. Substitua XX pelo c&#30963;digo do idioma. O c&#243;digo
    do idioma &#233; constru&#237;do do seguinte modo: XX_YYY onde XX
    refere-se ao idioma e YYY refere-se ao dialeto (por exemplo: PT_BR
    &#233; portugu&#234;s, dialeto brasileiro).
</p>
<p>
    Agora edite o arquivo \'framework/i18n/languages.php\' e adicione
    o idioma. Caso n&#227;o exista, adicione a bandeira ao diret&#243;rio
    \'framework/view/pics\' com o nome no formato \'flag-XX_YYY.png\'
    e a sele&#231;&#227;o do idioma ir&#30945; automaticamente exibi-la na tela de
    abertura.
</p>';
$dictionary['item_title']='Traduzir';
$dictionary['languageToTranslate']='Idioma da tradu&#231;&#227;o';
$dictionary['percentComplete']='Porcentagem completada';
$dictionary['pluginToTranslate']='Plugin a traduzir';
$dictionary['pluginTranslatorIndicator']='Tradutor do plugin (seu nome)';
$dictionary['saveTranslationToLocation']='Salve seu arquivo como';
$dictionary['stats']='Estat&#237;sticas';
$dictionary['translationFileName']='Arquivo de tradu&#231;&#227;o';
$dictionary['translationKey']='Marcador de tradu&#231;&#227;o';

?>