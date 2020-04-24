<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
 * @package org.brim-project.plugins.bookmarks
 * @subpackage i18n
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */
if (!isset ($dictionary))
{
	$dictionary=array();
}
$dictionary['charset']='utf-8';

$dictionary['item_quick_help']='Klikn&#283;te na ikonu u slo&#382;ky/polo&#382;ky pro p&#345;esunut&#237;/smaz&#225;n&#237;/editaci odkazu.<br /><br />Pro p&#345;esunut&#237; odkazu do jin&#233; slo&#382;ky nebo Rootu, <br />klin&#283;te na Editovat  => P&#345;esunout => klikn&#283;te na slo&#382;ku, kam chcete odkaz p&#345;esunout.';
$dictionary['item_title']='Z&#225;lo&#382;ka';
$dictionary['locatorMissing'] = 'Odkaz mus&#237; b&#253;t definov&#225;n';
$dictionary['modifyBookmarkPreferences']='Upravit p&#345;edvolby pro Z&#225;lo&#382;ky';
$dictionary['quickmarkExplanation']='Kliknut&#237;m prav&#253;m tla&#269;&#237;tkem my&#353;i na n&#225;sleduj&#237;c&#237; odkaz se p&#345;id&#225; Quickmark do Z&#225;lo&#382;ek/Obl&#237;ben&#253;ch ve Va&#353;em <b>prohl&#237;&#382;e&#269;i</b>. Poka&#382;d&#233;, kdy&#382; pou&#382;ijete Quickmark ve Va&#353;em prohl&#237;&#382;e&#269;i, str&#225;nka, na n&#237;&#382; se nach&#225;z&#237;te, bude automaticky p&#345;id&#225;na do Z&#225;lo&#382;ek v aplikaci Brim.<br /><br /><font size="-2">Pros&#237;m klikn&#283;te na "OK", pokud budete t&#225;z&#225;ni na p&#345;id&#225;n&#237; z&#225;lo&#382;ky - k&#243;d, kter&#253; "vyb&#237;r&#225;" adresu str&#225;nky, kterou chcete p&#345;idat do z&#225;lo&#382;ek, n&#283;kter&#233; prohl&#237;&#382;e&#269;e m&#367;&#382;e "znerv&#243;znit".</font><br />';
$dictionary['showBookmarkDetails'] = 'Uka&#382; detaily odkazu';
$dictionary['sidebar']='Sidebar';
$dictionary['yourPublicBookmarks']='Va&#353;e ve&#345;ejn&#233; z&#225;lo&#382;ky';
$dictionary['item_help']='
	<p>
		Plugin Z&#225;lo&#382;ky V&#225;m dovoluje spravovat Va&#353;e
    z&#225;lo&#382;ky on-line.
	</p>
	<p>
		Klikn&#283;te na ikonu Slo&#382;ka/Polo&#382;ka na za&#269;&#225;tku &#345;&#225;dku pro
		p&#345;esun/odstran&#283;n&#237;/editaci odkazu.
	</p>
	<p>
    Pro p&#345;esunut&#237; odkazu do jin&#233; slo&#382;ky, klikn&#283;te na
    Editovat => P&#345;esunout a klikn&#283;te na slo&#382;ku, kam chcete
    odkaz p&#345;esunout.
	</p>
	<p>
    N&#225;sleduj&#237;c&#237; parametry Z&#225;lo&#382;ky mohou b&#253;t nastaveny:
	</p>
	<ul>
		<li><em>Jm&#233;no</em>:
			Jm&#233;no resp. n&#225;zev odkazu. Nap&#345;.: [nauta.be] pro moji
			osobn&#237; str&#225;nku.
		</li>
		<li><em>Slo&#382;ka/Z&#225;lo&#382;ka</em>:
      Indik&#225;tor zdali polo&#382;ka k p&#345;id&#225;n&#237; je slo&#382;ka nebo z&#225;lo&#382;ka.
      Jakmile je
      tahle volba vybr&#225;na, ji&#382; nikdy nem&#367;&#382;e b&#253;t m&#283;n&#283;na.
		</li>
		<li><em>Ve&#345;ejn&#225;/Soukrom&#225; polo&#382;ka</em>:
      Indik&#225;tor, zdali tato polo&#382;ka je ve&#345;ejn&#225; nebo pouze pro Va&#353;e o&#269;i.
			<br />
			Pokud chcete aby konkr&#233;tn&#237; polo&#382;ka byla ve&#345;ejn&#225;,
			jej&#237; nad&#345;azen&#225; kategorie mus&#237; b&#253;t takt&#233;&#382; ve&#345;ejn&#225;!
			(Nejvy&#353;&#353;&#237; &#250;rove&#328; je automaticky nastavena jako ve&#345;ejn&#225;.)
		</li>
		<li><em>URL</em>:
			URL z&#225;lo&#382;ky. URL mus&#237; za&#269;&#237;nat protokolem (nap&#345;.: http://
      nebo ftp://), aby se z&#225;lo&#382;kou bylo spravn&#283; zach&#225;zeno.
		</li>
		<li><em>Popis</em>:
			Popis z&#225;lo&#382;ky.
		</li>
	</ul>
	<p>
		Podmenu kter&#233; jsou dostupn&#233; pro plugin Z&#225;lo&#382;ky jsou:
		Akce, Zobrazen&#237;, T&#345;&#237;dit, Volby a N&#225;pov&#283;da.
	</p>
	<h3>Akce</h3>
	<ul>
		<li><em>P&#345;idat</em>:
			Pomoc&#237; t&#233;to akce je zobrazen formul&#225;&#345;, do kter&#233;ho je mo&#382;n&#233;
			vlo&#382;it parametry z&#225;lo&#382;ky.
			URL mus&#237; za&#269;&#237;nat platn&#253;m indentifik&#225;torem protokolu
			(nap&#345;. http:// or ftp://)
		</li>
		<li><em>V&#237;cen&#225;sobn&#253; v&#253;b&#283;r</em>:
			Tato volba umo&#382;&#328;uje vybrat v&#237;ce z&#225;lo&#382;ek nar&#225;z (NEPLAT&#205; pro slo&#382;ky)
			a tyto ozna&#269;en&#233; nar&#225;z smazat nebo je p&#345;esunout do jin&#233; slo&#382;ky.
		</li>
		<li><em>Importovat</em>:
			Pomoc&#237; t&#233;to volby lze importovat z&#225;lo&#382;ky. V tuto chv&#237;li je mo&#382;n&#233;
			importovat z prohl&#237;&#382;e&#269;e Opera a z rodinny program&#367; Netscape/Mozilla/Firefox.
			Pokud chcete importovat z&#225;lo&#382;ky z Internet Exploreru, je t&#345;eba je v tomto
			programu nejd&#345;&#237;ve exportovat. T&#237;m vznikne soubor kompatibiln&#237; s programem
			Netscape, tento soubor je pak mo&#382;n&#233; importovat do Brim.
			<br />
			B&#283;hem importu je mo&#382;n&#233; zvolit, jestli importovan&#233; polo&#382;ky budou
			ve&#345;ejn&#233; nebo soukrom&#233;. Tato volba se uplatn&#237; pro v&#353;echny importovan&#233;
			polo&#382;ky.
			<br />
			Je mo&#382;n&#233; prov&#233;st import z jednoho konkr&#233;tn&#237;ho adres&#225;&#345;e,
			sta&#269;&#237; vlo&#382;it jeho jm&#233;no a vybrat "Import".
		</li>
		<li><em>Exportovat</em>:
			Touto volbou je mo&#382;n&#233; prov&#233;st export z&#225;lo&#382;ek. Podporovan&#233; jsou form&#225;ty program&#367;
			Opera, Netscape (dohromady s Mozillou a Firefoxem). Pokud chcete
			exportovat z&#225;lo&#382;ky do Internet Exploreru, je t&#345;eba je nejd&#345;&#237;ve vyexportovat
			do form&#225;tu pro program Netscape a pot&#233; je do Internet Exploreru naimportovat
			z tohoto souboru.
		</li>
		<li><em>Vyhled&#225;v&#225;n&#237;</em>:
			Pomoc&#237; t&#233;to akce je mo&#382;n&#233; vyhled&#225;vat v z&#225;lo&#382;k&#225;ch podle jm&#233;na, URL
			nebo popisu.
		</li>
	</ul>
	<h3>Zobrazen&#237;</h3>
	<ul>
		<li><em>Rozbalit</em>:
			Touto volbou lze otev&#345;&#237;t v&#353;echny slo&#382;ky a zobrazit v&#353;echny
			dostupn&#233; polo&#382;ky. Volbu lze pou&#382;&#237;t pouze pro stromov&#233; zobrazen&#237;.
		</li>
		<li><em>Sbalit</em>:
			T&#237;mto odkazem se zobraz&#237; pouze polo&#382;ky (z&#225;lo&#382;ky nebo slo&#382;ky)
			aktu&#225;ln&#283; vybran&#233; slo&#382;ky.
		</li>
		<li><em>Adres&#225;&#345;ov&#225; struktura</em>:
			Zvolen&#237;m t&#233;to mo&#382;nosti bude struktura zobrazena jako adres&#225;&#345;ov&#225;
			struktura. Toto zobrazen&#237; je podobn&#233; tomu, jak Yahoo! zobrazuje
			svoji adres&#225;&#345;ovou strukturu.
			<br />
			Po&#269;et sloupc&#367; v tomto zobrazen&#237; m&#367;&#382;e b&#253;t nastaven v mo&#382;nostech z&#225;lo&#382;ek.
		</li>
		<li><em>Stromov&#225; struktura</em>:
			Pomoc&#237; t&#233;to mo&#382;nosti bude struktura p&#345;epnuta do zobrazen&#237;, kter&#233; je
			zn&#225;m&#233; z Pr&#367;zkumn&#237;ku a mnoha dal&#353;&#237;ch spr&#225;vc&#367; soubor&#367;.
		</li>
		<li><em>Ve&#345;ejn&#233;</em>:
			Zobraz&#237; v&#353;echny ve&#345;ejn&#233; z&#225;lo&#382;ky v&#353;ech u&#382;ivatel&#367; spolu s Va&#353;imi
			z&#225;lo&#382;kami (bezohledu zda-li jsou sd&#237;len&#233; nebo vlastn&#237;).
		</li>
		<li><em>Vlastn&#237;</em>:
			Zobraz&#237; pouze Va&#353;e z&#225;lo&#382;ky (opak k "Ve&#345;ejn&#233;")
		</li>
	</ul>
	<h3>T&#345;&#237;dit</h3>
	<ul>
		<li><em>Posledn&#237; nav&#353;t&#237;ven&#233;</em>:
			Zobraz&#237; z&#225;lo&#382;ky dle data posledn&#237; n&#225;v&#353;t&#283;vy.

		</li>
		<li><em>Nejnav&#353;t&#283;vovan&#283;j&#353;&#237;</em>:
			Zobraz&#237; z&#225;lo&#382;ky dle n&#225;v&#353;t&#283;vnosti.

		</li>
		<li><em>Posledn&#237; vytvo&#345;en&#233;</em>:
			Zobraz&#237; z&#225;lo&#382;ky dle data vytvo&#345;en&#237;.

		</li>
		<li><em>Posledn&#237; upraven&#233;</em>:
			Zobraz&#237; z&#225;lo&#382;ky dle data posledn&#237; modifikace.

		</li>
	</ul>
	<h3>Volby</h3>
	<ul>
		<li><em>Upravit</em>:
			Zde lze upravit vlastnosti z&#225;lo&#382;ek. M&#367;&#382;ete zm&#283;nit po&#269;et sloupc&#367;
			pro adres&#225;&#345;ov&#233; zobrazen&#237; z&#225;lo&#382;ek, lze de-/aktivovat Javascriptov&#233;
			informa&#269;n&#237; pop-up okna, kter&#225; se zobrazuj&#237; p&#345;i najet&#237; kurzorem na odkaz.
			Lze tak&#233; p&#345;edvolit zp&#367;sob zobrazen&#237; z&#225;lo&#382;ek (adres&#225;&#345;ov&#233; nebo stromov&#233;
			zobrazen&#237;) a zda maj&#237; odkazy b&#253;t otev&#237;r&#225;ny do nov&#233;ho nebo aktu&#225;ln&#237;ho
			okna.
		</li>
		<li><em>Va&#353;e ve&#345;ejn&#233; z&#225;lo&#382;ky</em>:
      Kliknut&#237;m na tenhle odkaz zobraz&#237;te v&#353;echny Va&#353;e ve&#345;ejn&#233; z&#225;lo&#382;ky.
      Str&#225;nka kter&#225; bude otev&#345;ena je ve&#345;ejn&#283; p&#345;&#237;stupn&#225;, tak&#382;e
      m&#367;&#382;ete odkaz poslat komukoliv s k&#253;m chcete sd&#237;let Va&#353;e z&#225;lo&#382;ky
      touhle cestou. Tento odkaz taky m&#367;&#382;e b&#253;t integrov&#225;n do jin&#233;
      webov&#233; str&#225;nky, aby ji o&#382;ivil Va&#353;imi z&#225;lo&#382;kami.
      Brim powered!
			<br />
			Pokud chcete ve&#345;ejnou konkr&#233;tn&#237; polo&#382;ku,
			mus&#237; b&#253;t ve&#345;ejn&#225; i nad&#345;azen&#225; polo&#382;ka (slo&#382;ka).
		</li>
		<li><em>Sidebar</em>:
      Tento odkaz V&#225;s vezme na novou str&#225;nku, kde m&#367;&#382;ete
      integrovat Brim do Va&#353;eho prohl&#237;&#382;e&#269;e (pouze Opera, Mozilla
      Firefox a Netscape).
		</li>
		<li><em>Quickmark</em>:
      Kliknut&#237;m prav&#253;m tla&#269;&#237;tkem my&#353;i na n&#225;sleduj&#237;c&#237; odkaz se p&#345;id&#225;
      Quickmark do Z&#225;lo&#382;ek/Obl&#237;ben&#253;ch ve Va&#353;em <b>prohl&#237;&#382;e&#269;i</b>.
      Poka&#382;d&#233;, kdy&#382; pou&#382;ijete Quickmark ve Va&#353;em prohl&#237;&#382;e&#269;i, str&#225;nka,
      na n&#237;&#382; se nach&#225;z&#237;te, bude automaticky p&#345;id&#225;na do Z&#225;lo&#382;ek v
      aplikaci Brim.
			<br />
      Pros&#237;m klikn&#283;te na "OK", pokud budete t&#225;z&#225;ni na p&#345;id&#225;n&#237; z&#225;lo&#382;ky,
      k&#243;d, kter&#253; "vyb&#237;r&#225;" adresu str&#225;nky, kterou chcete p&#345;idat do z&#225;lo&#382;ek,
      n&#283;kter&#233; prohl&#237;&#382;e&#269;e m&#367;&#382;e "znerv&#243;znit".
		</li>
	</ul>
';
?>
