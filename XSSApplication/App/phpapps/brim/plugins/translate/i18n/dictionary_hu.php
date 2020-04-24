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
$dictionary['baseTranslation']='Alap ford&#237;t&#225;s';
$dictionary['bothLanguageAndPluginNeeded']='Nyelv &#201;S plugin kiv&#225;laszt&#225;sa sz&#252;ks&#233;ges';
$dictionary['currentTranslation']='Jelenlegi ford&#237;t&#225;s';
$dictionary['item_help']='<p>
	A ford&#237;t&#243; modul, seg&#237;t az adott modult 
	leford&#237;tani, vagy egy m&#225;r k&#233;sz ford&#237;t&#225;st 
	m&#243;dos&#237;tani.
</p>
<p>
	Van egy script a tools alk&#246;nyvt&#225;rban 
	a <code>dict.sh</code> 
	amely seg&#237;t a k&#246;nyvt&#225;rtstrukt&#250;ra l&#233;trehoz&#225;s&#225;ban 
	&#233;s azut&#225;n seg&#237;t a file-ok megfelel&#337; 
	helyre m&#225;sol&#225;s&#225;val. A script "&#246;nmag&#225;&#233;rt besz&#233;l". 
</p>
<p>
	Norm&#225;l haszn&#225;lat eset&#233;n, a modul/program
	megpr&#243;b&#225;lja megkeresni az &#233;ppen haszn&#225;lt nyelvi
	ford&#237;t&#225;st, amennyiben nincsen ilyen, akkor 
	az angol nyelvre v&#225;lt. Ebb&#337;l k&#246;vetkezik 
	teh&#225;t, hogy amennyiben a ford&#237;t&#225;s hi&#225;nyos, 
	megjennek - ahol nincsen ford&#237;t&#225;s - angol 
	sz&#246;vegek is a leford&#237;tottak k&#246;zt.
</p>
<h2>Hogyan m&#243;dos&#237;tsunk egy m&#225;r megl&#233;v&#337; ford&#237;t&#225;son</h2>
<p>
	A ford&#237;t&#243; modulban, v&#225;laszd ki, mind a 
	plugint(modult) mind pedig a nyelvet.
	A k&#246;vetkez&#337; oldalon megjelenik az &#250;n.
	ford&#237;t&#225;si kulcs (a rendszer bels&#337;leg haszn&#225;lja), 
	az alapford&#237;t&#225;s (angol nyelven), az aktu&#225;lis 
	ford&#237;t&#225;s a v&#225;lasztott nyelven (vagy piros 
	bet&#252;kkel \'nincs ilyen!!!\' ha az adott ford&#237;t&#225;s 
	nem l&#233;tezik) &#233;s egy sz&#246;vegdoboz ahov&#225;
	a ford&#237;t&#225;sokat be&#237;rhatod ill. m&#243;dos&#237;thatod.
</p>
<p>
	Ha k&#233;szen vagy a ford&#237;t&#225;ssal, let&#246;ltheted
	a ford&#237;t&#225;st. A file neve \'dictionary_XX.php\' 
	(XX az orsz&#225;gk&#243;d pl. hu, a Magyarorsz&#225;g&#233;) lesz, 
	amelyet a v&#225;lasztott modul, i18n k&#246;nyvt&#225;rba 
	kell lementeni (vagy let&#246;lt&#233;s ut&#225;n bem&#225;solni).
	Amennyiben a keretrendszert ford&#237;tod, akkor a 
	gy&#246;k&#233;r i18n konyvt&#225;r&#225;ba kell ker&#252;lj&#246;n.
	A file neve, valamint hogy hol is kell legyen 
	az oldal tetej&#233;n megtal&#225;lhat&#243;.
</p>
<h2>Hogyan hozzunk l&#233;tre egy &#250;j ford&#237;t&#225;st</h2>
<p>
	A ford&#237;t&#243; modul f&#337;oldal&#225;n a ford&#237;tand&#243; r&#233;szt 
	valamint a piros	\'&#218;j\'-at v&#225;laszd ki. Megint 
	a ford&#237;tand&#243; oldalra &#233;rkezel, ha k&#233;szen van a ford&#237;t&#225;s, 
	hasonl&#243;k&#233;ppen az el&#337;bbiekhez mentsd le amit csin&#225;lt&#225;l, 
	majd nevezd &#225;t, a nyelvi k&#243;dot &#237;rd az XX hely&#233;re.
	A nyelvi k&#243;d a nyelvb&#337;l &#233;s a dialektusb&#243;l 
	&#225;ll &#246;ssze, &#237;gy: XX_YYY ahol is az XX a nyelvet jel&#246;li, 
	az YYY pedig a dialektust (pl. PT_BR a portug&#225;l 
	nyelvet jel&#246;li brazil dialektusban).
	A file neve, valamint hogy hol is kell legyen 
	az oldal tetej&#233;n megtal&#225;lhat&#243;.
</p>
<p>
	Ha k&#233;szen vagy a ford&#237;t&#225;soddal, 
	nyisd megy egy editorral a 
	\'framework/i18n/languages.php\' &#225;llom&#225;nyt 
	&#233;s &#237;rd hozz&#225; azt a nyelvet, amelyet ford&#237;tottad. 
	Azt&#225;n a \'framework/view/pics/flags\' k&#246;nyvt&#225;rban 
	n&#233;zd meg ott van-e az orsz&#225;g z&#225;szl&#243;ja, ha nincs 
	akkor m&#225;sold be valahonnan, &#250;gy hogy 
	\'flag-XX_YYY.png\' legyen a neve, (XX-YYY jelent&#233;s&#233;t l&#225;sd feljebb) 
	&#233;s ez ut&#225;n a nyelvv&#225;laszt&#225;sn&#225;l meg fog jelenni 
	az &#250;jonnan l&#233;trehozott ford&#237;t&#225;s.
</p>';
$dictionary['item_title']='Ford&#237;t&#225;s';
$dictionary['languageToTranslate']='Nyelv';
$dictionary['percentComplete']='K&#233;szenl&#233;ti sz&#225;zal&#233;k';
$dictionary['pluginToTranslate']='Keret/Plugin';
$dictionary['pluginTranslatorIndicator']='Plugin ford&#237;t&#243; (a te neved)';
$dictionary['saveTranslationToLocation']='Ide mentsd a file-t';
$dictionary['stats']='Statisztika';
$dictionary['translationFileName']='A ford&#237;t&#225;s file-neve';
$dictionary['translationKey']='Ford&#237;t&#225;si kulcs';

?>