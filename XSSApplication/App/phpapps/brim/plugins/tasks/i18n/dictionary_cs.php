<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
 * @package org.brim-project.plugins.tasks
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

$dictionary['complete']='Hotovo';
$dictionary['due_date']='Ukon&#269;en&#237;';
$dictionary['item_title']='&#218;koly';
$dictionary['modifyTaskPreferences']='Upravit p&#345;edvolby pro &#218;koly';
$dictionary['priority1']="Neodkladn&#225;";
$dictionary['priority2']="Vysok&#225;";
$dictionary['priority3']="Norm&#225;ln&#237;";
$dictionary['priority4']="N&#237;zk&#225;";
$dictionary['priority5']="Nepatrn&#225;";
$dictionary['priority']='D&#367;le&#382;itost';
$dictionary['start_date']='Za&#269;&#225;tek';
$dictionary['status']='Stav';
$dictionary['item_help']='
	<p>
		Plugin &#218;koly umo&#382;&#328;uje spravovat Va&#353;e &#250;koly online.
		Lze nastavi n&#225;sleduj&#237;c&#237; parametry:
	</p>
	<ul>
		<li><em>N&#225;zev</em>:
			N&#225;zev &#250;kolu.
		</li>
		<li><em>Slo&#382;ka/&#218;kol</em>:
			Indikuje, zda je polo&#382;ka Slo&#382;ka nebo &#218;kol.
      Jakmile je jednou nastavena, nen&#237; mo&#382;no ji pozd&#283;ji zm&#283;nit.
		</li>
		<li><em>Sd&#237;len&#233;/Soukrom&#233;</em>:
      Indik&#225;tor, zdali tato polo&#382;ka je ve&#345;ejn&#225; nebo pouze pro Va&#353;e o&#269;i.
			<br />
			Pokud chcete aby konkr&#233;tn&#237; polo&#382;ka byla ve&#345;ejn&#225;,
			jej&#237; nad&#345;azen&#225; kategorie mus&#237; b&#253;t takt&#233;&#382; ve&#345;ejn&#225;!
			(Nejvy&#353;&#353;&#237; &#250;rove&#328; je automaticky nastavena jako ve&#345;ejn&#225;.)
		</li>
		<li><em>Hotovo</em>:
			Umo&#382;&#328;uje ur&#269;it, jak velk&#225; &#269;&#225;st &#250;kolu je ji&#382; hotova.
		</li>
		<li><em>D&#367;le&#382;itost</em>:
			Nastavuje d&#367;le&#382;itost &#250;kolu. Lze nastavit n&#225;sleduj&#237;c&#237; hodnoty:
			Neodkladn&#225; (v&#253;choz&#237;), Vysok&#225;, Norm&#225;ln&#237;, N&#237;zk&#225;, Nepatrn&#225;.
		</li>
		<li><em>Stav</em>:
			Lze uv&#233;st dodate&#269;n&#253; stav, kter&#253; m&#367;&#382;e b&#253;t cokoliv si p&#345;ejete.
		</li>
		<li><em>Za&#269;&#225;tek</em>:
			Datum, kdy by m&#283;l &#250;kol za&#269;&#237;t.
		</li>
		<li><em>Ukon&#269;en&#237;</em>:
			P&#345;edpokl&#225;dan&#233; datum, kdy &#250;kol skon&#269;&#237;.
		</li>
		<li><em>Popis</em>:
			Popis &#250;kolu
		</li>
	</ul>
	<p>
		Podmenu kter&#233; jsou dostupn&#233; pro plugin &#218;koly jsou
		Akce, Zobrazen&#237;, T&#345;&#237;dit, Volby a N&#225;pov&#283;da.
	</p>
	<h3>Akce</h3>
	<ul>
		<li><em>P&#345;idat</em>:
			Pomoc&#237; t&#233;to akce je zobrazen formul&#225;&#345;, do kter&#233;ho je mo&#382;n&#233;
			vlo&#382;it parametry &#250;kolu.
		</li>
		<li><em>V&#237;cen&#225;sobn&#253; v&#253;b&#283;r</em>:
			Tato volba umo&#382;&#328;uje vybrat v&#237;ce &#250;kol&#367; nar&#225;z (NEPLAT&#205; pro slo&#382;ky)
			a tyto ozna&#269;en&#233; nar&#225;z smazat nebo je p&#345;esunout do jin&#233; slo&#382;ky.
		</li>
		<li><em>Vyhled&#225;v&#225;n&#237;</em>:
			Pomoc&#237; t&#233;to akce je mo&#382;n&#233; vyhled&#225;vat v &#250;kolech podle
			jm&#233;na, stavu nebo popisu.
		</li>
	</ul>
	<h3>Zobrazen&#237;</h3>
	<ul>
		<li><em>Rozbalit</em>:
			Touto volbou lze otev&#345;&#237;t v&#353;echny slo&#382;ky a zobrazit v&#353;echny
			dostupn&#233; polo&#382;ky. Volbu lze pou&#382;&#237;t pouze pro stromov&#233; zobrazen&#237;.
		</li>
		<li><em>Sbalit</em>:
			T&#237;mto odkazem se zobraz&#237; pouze polo&#382;ky (&#250;koly nebo slo&#382;ky)
			aktu&#225;ln&#283; vybran&#233; slo&#382;ky.
		</li>
		<li><em>Adres&#225;&#345;ov&#225; struktura</em>:
			Zvolen&#237;m t&#233;to mo&#382;nosti bude struktura zobrazena jako adres&#225;&#345;ov&#225;
			struktura. Toto zobrazen&#237; je podobn&#233; tomu, jak Yahoo! zobrazuje
			svoji adres&#225;&#345;ovou strukturu.
			<br />
			Po&#269;et sloupc&#367; v tomto zobrazen&#237; m&#367;&#382;e b&#253;t nastaven v mo&#382;nostech &#250;kol&#367;.
		</li>
		<li><em>P&#345;ehled - strom</em>:
			Tato mo&#382;nost zvol&#237; zobrazen&#237;, kter&#233; je tak trochu kombinac&#237;
			&#345;&#225;dkov&#233;ho zobrazen&#237; a stromov&#233; struktury.
		</li>
		<li><em>&#344;&#225;dkov&#233; zobrazen&#237;</em>:
			D&#237;ky t&#233;to volb&#283; budou &#250;koly zobrazeny po &#345;&#225;dc&#237;ch.
		</li>
		<li><em>Stromov&#225; struktura</em>:
			Pomoc&#237; t&#233;to mo&#382;nosti bude struktura p&#345;epnuta do zobrazen&#237;, kter&#233; je
			zn&#225;m&#233; z Pr&#367;zkumn&#237;ku a mnoha dal&#353;&#237;ch spr&#225;vc&#367; soubor&#367;.
		</li>
		<li><em>Ve&#345;ejn&#233;</em>:
			Zobraz&#237; v&#353;echny ve&#345;ejn&#233; &#250;koly v&#353;ech u&#382;ivatel&#367; spolu s Va&#353;imi
			&#250;koly (bez ohledu zda-li jsou sd&#237;len&#233; nebo vlastn&#237;).
		</li>
		<li><em>Vlastn&#237;</em>:
			Zobraz&#237; pouze Va&#353;e z&#225;lo&#382;ky (opak k "Ve&#345;ejn&#233;")
		</li>
	</ul>
	<h3>T&#345;&#237;dit</h3>
	<ul>
		<li><em>D&#367;le&#382;itost</em>:
			T&#345;&#237;dit podle d&#367;le&#382;itost.
		</li>
		<li><em>Hotovo</em>:
			T&#345;&#237;dit podle velikosti ji&#382; dokonc&#269;en&#233; &#269;&#225;sti &#250;kolu.
		</li>
		<li><em>Za&#269;&#225;tek</em>:
			T&#345;&#237;dit podle po&#269;&#225;tku.
		</li>
		<li><em>Ukon&#269;en&#237;</em>:
			T&#345;&#237;dit podle ukon&#269;en&#237;.
		</li>
	</ul>
	<h3>Volby</h3>
	<ul>
		<li><em>Upravit</em>:
			Zde lze upravit vlastnosti z&#225;lo&#382;ek. M&#367;&#382;ete zm&#283;nit po&#269;et sloupc&#367;
			pro adres&#225;&#345;ov&#233; zobrazen&#237; &#250;kol&#367;, lze de-/aktivovat Javascriptov&#233;
			informa&#269;n&#237; pop-up okna, kter&#225; se zobrazuj&#237; p&#345;i najet&#237; kurzorem na odkaz.
			Lze tak&#233; p&#345;edvolit zp&#367;sob zobrazen&#237; z&#225;lo&#382;ek (adres&#225;&#345;ov&#233;, p&#345;ehled, &#345;&#225;dkov&#233;
			nebo stromov&#233;).
		</li>
	</ul>
';
?>