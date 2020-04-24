<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
 * @package org.brim-project.plugins.passwords
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

$dictionary['item_title']='Hesla';
$dictionary['modifyPasswordPreferences']='Upravit p&#345;edvolby pro Hesla';

$dictionary['item_help']='
	<p>
		Plugin Hesla umo&#382;&#328;uje uchov&#225;vat p&#345;&#237;stupov&#233; &#250;daje online.
		Je mo&#382;n&#233; nastavit n&#225;sleduj&#237;c&#237; parametry:
	</p>
	<ul>
		<li><em>Jm&#233;no</em>:
			Jm&#233;no p&#345;&#237;stupov&#233;ho &#250;daje.
		</li>
		<li><em>Slo&#382;ka/Heslo</em>:
			Ukazatel, zda se jedn&#225; o slo&#382;ku nebo &#250;daj. Tuto polo&#382;ku po nastaven&#237;
			nen&#237; mo&#382;n&#233; v budoucnu dodate&#269;n&#283; zm&#283;nit.
		</li>
		<li><em>Popis</em>:
			Popis tohoto p&#345;&#237;stupov&#233;ho &#250;daje. Toto pole bude za&#353;ifrov&#225;no pomoc&#237; hesla,
			kter&#233; bude uvedeno p&#345;i p&#345;id&#225;v&#225;n&#237; z&#225;znamu a do datab&#225;ze bude ulo&#382;en tento
			za&#353;ifrovan&#253; &#250;daj.
		</li>
	</ul>
	<p>
		Podmenu, kter&#225; jsou dostupn&#225; pro plugin Hesla jsou Akce, Zobrazen&#237;,
		P&#345;edvolby a N&#225;pov&#283;da.
	</p>
	<h3>Akce</h3>
	<ul>
		<li><em>P&#345;idat</em>:
			Pomoc&#237; t&#233;to akce je zobrazen formul&#225;&#345;, do kter&#233;ho je mo&#382;n&#233;
			vlo&#382;it parametry hesla.
		</li>
		<li><em>V&#237;cen&#225;sobn&#253; v&#253;b&#283;r</em>:
			Tato volba umo&#382;&#328;uje vybrat v&#237;ce hesel nar&#225;z (NEPLAT&#205; pro slo&#382;ky)
			a tyto ozna&#269;en&#233; nar&#225;z smazat nebo je p&#345;esunout do jin&#233; slo&#382;ky.
		</li>
		<li><em>Vyhled&#225;v&#225;n&#237;</em>:
			Pomoc&#237; t&#233;to akce je mo&#382;n&#233; vyhled&#225;vat v heslech podle jm&#233;na.
		</li>
	</ul>
	<h3>Zobrazen&#237;</h3>
	<ul>
		<li><em>Rozbalit</em>:
			Touto volbou lze otev&#345;&#237;t v&#353;echny slo&#382;ky a zobrazit v&#353;echny
			dostupn&#233; polo&#382;ky. Volbu lze pou&#382;&#237;t pouze pro stromov&#233; zobrazen&#237;.
		</li>
		<li><em>Sbalit</em>:
			T&#237;mto odkazem se zobraz&#237; pouze polo&#382;ky (hesla nebo slo&#382;ky)
			aktu&#225;ln&#283; vybran&#233; slo&#382;ky.
		</li>
		<li><em>Adres&#225;&#345;ov&#225; struktura</em>:
			Zvolen&#237;m t&#233;to mo&#382;nosti bude struktura zobrazena jako adres&#225;&#345;ov&#225;
			struktura. Toto zobrazen&#237; je podobn&#233; tomu, jak Yahoo! zobrazuje
			svoji adres&#225;&#345;ovou strukturu.
			<br />
			Po&#269;et sloupc&#367; v tomto zobrazen&#237; m&#367;&#382;e b&#253;t nastaven v mo&#382;nostech hesel.
		</li>
		<li><em>Stromov&#225; struktura</em>:
			Pomoc&#237; t&#233;to mo&#382;nosti bude struktura p&#345;epnuta do zobrazen&#237;, kter&#233; je
			zn&#225;m&#233; z Pr&#367;zkumn&#237;ku a mnoha dal&#353;&#237;ch spr&#225;vc&#367; soubor&#367;.
		</li>
	</ul>
	<h3>P&#345;edvolby</h3>
	<ul>
		<li><em>Upravit</em>:
			Zde lze upravit vlastnosti hesel. M&#367;&#382;ete zm&#283;nit po&#269;et sloupc&#367;
			pro adres&#225;&#345;ov&#233; zobrazen&#237; hesel, lze de-/aktivovat Javascriptov&#233;
			informa&#269;n&#237; pop-up okna, kter&#225; se zobrazuj&#237; p&#345;i najet&#237; kurzorem na odkaz.
			Lze tak&#233; p&#345;edvolit zp&#367;sob zobrazen&#237; hesel (adres&#225;&#345;ov&#233; nebo stromov&#233;
			zobrazen&#237;).
		</li>
	</ul>
';
?>