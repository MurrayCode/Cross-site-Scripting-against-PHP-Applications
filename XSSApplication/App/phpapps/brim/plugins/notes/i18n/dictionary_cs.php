<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
 * @package org.brim-project.plugins.notes
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

$dictionary['item_title']='Pozn&#225;mky';
$dictionary['modifyNotePreferences']='Upravit p&#345;edvolby pro Pozn&#225;mky';
$dictionary['item_help']='
	<p>
		Plugin Pozn&#225;mky V&#225;m umo&#382;&#328;uje spravovat Va&#353;e
		pozn&#225;mky on-line.
		Plugin m&#367;&#382;e m&#237;t nastaven n&#225;sleduj&#237;c&#237; parametry:
	</p>
	<ul>
		<li><em>N&#225;zev</em>:
			N&#225;zev pozn&#225;mky.
		</li>
		<li><em>Slo&#382;ka/Pozn&#225;mka</em>:
			Indikuje zda-li je polo&#382;ka Slo&#382;ka nebo Pozn&#225;mka.
      Jakmile je jednou nastavena,
			nen&#237; mo&#382;no ji n&#283;kdy zm&#283;nit.
		</li>
		<li><em>Sd&#237;len&#233;/Soukrom&#233;</em>:
      Indik&#225;tor, zdali tato polo&#382;ka je ve&#345;ejn&#225; nebo pouze pro Va&#353;e o&#269;i.
			<br />
			Pokud chcete aby konkr&#233;tn&#237; polo&#382;ka byla ve&#345;ejn&#225;,
			jej&#237; nad&#345;azen&#225; kategorie mus&#237; b&#253;t takt&#233;&#382; ve&#345;ejn&#225;!
			(Nejvy&#353;&#353;&#237; &#250;rove&#328; je automaticky nastavena jako ve&#345;ejn&#225;.)
		</li>
		<li><em>Popis</em>:
			Popis pozn&#225;mky, pokud je t&#345;eba.
		</li>
	</ul>
	<p>
		Podmenu kter&#233; jsou dostupn&#233; pro plugin Pozn&#225;mky jsou
		Akce, Zobrazen&#237;, Volby a N&#225;pov&#283;da.
	</p>
	<h3>Akce</h3>
	<ul>
		<li><em>P&#345;idat</em>:
			Pomoc&#237; t&#233;to akce je zobrazen formul&#225;&#345;, do kter&#233;ho je mo&#382;n&#233;
			vlo&#382;it parametry pozn&#225;mky.
		</li>
		<li><em>V&#237;cen&#225;sobn&#253; v&#253;b&#283;r</em>:
			Tato volba umo&#382;&#328;uje vybrat v&#237;ce pozn&#225;mek nar&#225;z (NEPLAT&#205; pro slo&#382;ky)
			a tyto ozna&#269;en&#233; nar&#225;z smazat nebo je p&#345;esunout do jin&#233; slo&#382;ky.
		</li>
		<li><em>Vyhled&#225;v&#225;n&#237;</em>:
			Pomoc&#237; t&#233;to akce je mo&#382;n&#233; vyhled&#225;vat v pozn&#225;mk&#225;ch podle
			jm&#233;na nebo popisu.
		</li>
	</ul>
	<h3>Zobrazen&#237;</h3>
	<ul>
		<li><em>Rozbalit</em>:
			Touto volbou lze otev&#345;&#237;t v&#353;echny slo&#382;ky a zobrazit v&#353;echny
			dostupn&#233; polo&#382;ky. Volbu lze pou&#382;&#237;t pouze pro stromov&#233; zobrazen&#237;.
		</li>
		<li><em>Sbalit</em>:
			T&#237;mto odkazem se zobraz&#237; pouze polo&#382;ky (pozn&#225;mky nebo slo&#382;ky)
			aktu&#225;ln&#283; vybran&#233; slo&#382;ky.
		</li>
		<li><em>Adres&#225;&#345;ov&#225; struktura</em>:
			Zvolen&#237;m t&#233;to mo&#382;nosti bude struktura zobrazena jako adres&#225;&#345;ov&#225;
			struktura. Toto zobrazen&#237; je podobn&#233; tomu, jak Yahoo! zobrazuje
			svoji adres&#225;&#345;ovou strukturu.
			<br />
			Po&#269;et sloupc&#367; v tomto zobrazen&#237; m&#367;&#382;e b&#253;t nastaven v mo&#382;nostech pozn&#225;mek.
		</li>
		<li><em>Stromov&#225; struktura</em>:
			Pomoc&#237; t&#233;to mo&#382;nosti bude struktura p&#345;epnuta do zobrazen&#237;, kter&#233; je
			zn&#225;m&#233; z Pr&#367;zkumn&#237;ku a mnoha dal&#353;&#237;ch spr&#225;vc&#367; soubor&#367;.
		</li>
		<li><em>Ve&#345;ejn&#233;</em>:
			Zobraz&#237; v&#353;echny ve&#345;ejn&#233; pozn&#225;mky v&#353;ech u&#382;ivatel&#367; spolu s Va&#353;imi
			pozn&#225;mkami (bezohledu zda-li jsou sd&#237;len&#233; nebo vlastn&#237;).
		</li>
		<li><em>Vlastn&#237;</em>:
			Zobraz&#237; pouze Va&#353;e pozn&#225;mky (opak k "Ve&#345;ejn&#233;")
		</li>
	</ul>
	<h3>Volby</h3>
	<ul>
		<li><em>Upravit</em>:
			Zde lze upravit vlastnosti pozn&#225;mek. M&#367;&#382;ete zm&#283;nit po&#269;et sloupc&#367;
			pro adres&#225;&#345;ov&#233; zobrazen&#237; pozn&#225;mek, lze de-/aktivovat Javascriptov&#233;
			informa&#269;n&#237; pop-up okna, kter&#225; se zobrazuj&#237; p&#345;i najet&#237; kurzorem na odkaz.
			Lze tak&#233; p&#345;edvolit zp&#367;sob zobrazen&#237; pozn&#225;mek (adres&#225;&#345;ov&#233; nebo stromov&#233;
			zobrazen&#237;).
		</li>
	</ul>
';
?>