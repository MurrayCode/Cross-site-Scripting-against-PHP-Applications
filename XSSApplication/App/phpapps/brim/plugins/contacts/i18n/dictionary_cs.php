<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Ladislav Urbanek
 * @package org.brim-project.plugins.contacts
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

$dictionary['address']='Adresa';
$dictionary['alias']='P&#345;ezd&#237;vka';
$dictionary['birthday']='Narozeniny';
$dictionary['email']='E-mail';
$dictionary['email1']='E-mail 1';
$dictionary['email2']='E-mail 2';
$dictionary['email3']='E-mail 3';
$dictionary['faximile']='Fax';
$dictionary['item_title']='Kontakty';
$dictionary['job']='Zam&#283;stn&#225;n&#237;';
$dictionary['mobile']='Mobil';
$dictionary['modifyContactPreferences']='Upravit p&#345;edvolby pro Kontakty';
$dictionary['org_address']='Adresa organizace';
$dictionary['organization']='Organizace';
$dictionary['tel_home']='Tel. dom&#367;';
$dictionary['tel_work']='Tel. pr&#225;ce';
$dictionary['webaddress']='WWW';
$dictionary['item_help']='
	<p>
		Plugin Kontakty umo&#382;&#328;uje spravovat Va&#353;e kontakty online. N&#225;sleduj&#237;c&#237;
		parametry lze nastavit ka&#382;d&#233;mu kontaktu:
	</p>
	<ul>
		<li><em>N&#225;zev</em>:
			N&#225;zev kontaktu.
		</li>
		<li><em>Slo&#382;ka/Kontakt</em>:
     Indik&#225;tor zdali polo&#382;ka k p&#345;id&#225;n&#237; je slo&#382;ka nebo kontakt.
      Jakmile je tahle volba vybr&#225;na, nem&#367;&#382;e b&#253;t pozd&#283;ji zm&#283;n&#283;na.
		</li>
		<li><em>Ve&#345;ejn&#225;/Soukrom&#225; polo&#382;ka</em>:
      Indik&#225;tor, zdali tato polo&#382;ka je ve&#345;ejn&#225; nebo pouze pro Va&#353;e o&#269;i.
			<br />
			Pokud chcete aby konkr&#233;tn&#237; polo&#382;ka byla ve&#345;ejn&#225;,
			jej&#237; nad&#345;azen&#225; kategorie mus&#237; b&#253;t takt&#233;&#382; ve&#345;ejn&#225;!
			(Nejvy&#353;&#353;&#237; &#250;rove&#328; je automaticky nastavena jako ve&#345;ejn&#225;.)
		</li>
		<li><em>Tel. dom&#367;</em>:
			&#268;&#237;slo dom&#225;c&#237;ho telefonu.
		</li>
		<li><em>Tel. pr&#225;ce</em>:
			Telefon do pr&#225;ce.
		</li>
		<li><em>Fax</em>:
			Faxov&#233; &#269;&#237;slo
		</li>
		<li><em>E-mail 1</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t a&#382; t&#345;i e-mailov&#233; adresy,
			toto je prvn&#237; z nich.
		</li>
		<li><em>Email 2</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t a&#382; t&#345;i e-mailov&#233; adresy,
			toto je druh&#225; z nich.
		</li>
		<li><em>Email 3</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t a&#382; t&#345;i e-mailov&#233; adresy,
			toto je t&#345;et&#237; z nich.
		</li>
		<li><em>WWW 1</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t p&#345;i&#345;azeny a&#382; t&#345;i webov&#233; adresy,
			toto je prvn&#237; z nich.
		</li>
		<li><em>WWW 2</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t p&#345;i&#345;azeny a&#382; t&#345;i webov&#233; adresy,
			toto je druh&#225; z nich.
		</li>
		<li><em>WWW 3</em>:
			Ka&#382;d&#253; kontakt m&#367;&#382;e m&#237;t p&#345;i&#345;azeny a&#382; t&#345;i webov&#233; adresy,
			toto je t&#345;et&#237; z nich.
		</li>
		<li><em>Pracovn&#237; za&#345;azen&#237;</em>:
			Pracovn&#237; za&#345;azen&#237; dan&#233; osoby.
		</li>
		<li><em>P&#345;ezd&#237;vka</em>:
			P&#345;ezd&#237;vka dan&#233; osoby (lze podle n&#237; vyhled&#225;vat).
		</li>
		<li><em>Organizace</em>:
			Organizace/spole&#269;nost, pro kterou osoba pracuje.
		</li>
		<li><em>Adresa</em>:
			Bydli&#353;t&#283; osoby.
		</li>
		<li><em>Adresa organizace</em>:
			S&#237;dlo organizace/spole&#269;nosti, pro kterou osoba pracuje.
		</li>
		<li><em>Popis</em>:
			Popis osoby
		</li>
	</ul>
	<p>
		Podmenu kter&#233; jsou dostupn&#233; pro plugin Novinky jsou:
		Akce, Zobrazen&#237;, T&#345;&#237;dit, Volby.
	</p>
	<h3>Akce</h3>
	<ul>
		<li><em>P&#345;idat</em>:
			Pomoc&#237; t&#233;to akce je zobrazen formul&#225;&#345;, do kter&#233;ho je mo&#382;n&#233;
			vlo&#382;it parametry kontaktu.
		</li>
		<li><em>V&#237;cen&#225;sobn&#253; v&#253;b&#283;r</em>:
			Tato volba umo&#382;&#328;uje vybrat v&#237;ce kontakt&#367; nar&#225;z (NEPLAT&#205; pro slo&#382;ky)
			a tyto ozna&#269;en&#233; nar&#225;z smazat nebo je p&#345;esunout do jin&#233; slo&#382;ky.
		</li>
		<li><em>Importovat</em>:
			Pomoc&#237; t&#233;to volby lze importovat kontatky. V tuto chv&#237;li je mo&#382;n&#233;
			importovat z prohl&#237;&#382;e&#269;e Opera a z form&#225;tu vCard.
			<br />
			B&#283;hem importu je mo&#382;n&#233; zvolit, jestli importovan&#233; polo&#382;ky budou
			ve&#345;ejn&#233; nebo soukrom&#233;. Tato volba se uplatn&#237; pro v&#353;echny importovan&#233;
			polo&#382;ky.
			<br />
			Je mo&#382;n&#233; prov&#233;st import z jednoho konkr&#233;tn&#237;ho adres&#225;&#345;e,
			sta&#269;&#237; vlo&#382;it jeho jm&#233;no a vybrat "Import".
		</li>
		<li><em>Exportovat</em>:
			Touto volbou je mo&#382;n&#233; prov&#233;st export kontakt&#367;. Podporovan&#233;
			jsou form&#225;ty programu Opera a form&#225;t vCard (kter&#253; lze importovat do mnoha
			po&#353;tovn&#237;ch program&#367; a mnoha program&#367; pro spr&#225;vu kontakt&#367;).
		<li><em>Vyhled&#225;v&#225;n&#237;</em>:
			Pomoc&#237; t&#233;to akce je mo&#382;n&#233; vyhled&#225;vat v kontaktech podle jm&#233;na, p&#345;ezd&#237;vky,
			popisu nebo adresy.
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
			Po&#269;et sloupc&#367; v tomto zobrazen&#237; m&#367;&#382;e b&#253;t nastaven v mo&#382;nostech kontakt&#367;.
		</li>
		<li><em>Stromov&#225; struktura</em>:
			Pomoc&#237; t&#233;to mo&#382;nosti bude struktura p&#345;epnuta do zobrazen&#237;, kter&#233; je
			zn&#225;m&#233; z Pr&#367;zkumn&#237;ku a mnoha dal&#353;&#237;ch spr&#225;vc&#367; soubor&#367;.
		</li>
		<li><em>&#344;&#225;dkov&#233; zobrazen&#237;</em>:
			D&#237;ky t&#233;to volb&#283; budou kontakty zobrazeny po &#345;&#225;dc&#237;ch se spoustou detail&#367;.
		</li>
		<li><em>Ve&#345;ejn&#233;</em>:
			Zobraz&#237; v&#353;echny ve&#345;ejn&#233; kontakty v&#353;ech u&#382;ivatel&#367; spolu s Va&#353;imi
			kontakty (bez ohledu zda-li jsou sd&#237;len&#233; nebo vlastn&#237;).
		</li>
		<li><em>Vlastn&#237;</em>:
			Zobraz&#237; pouze Va&#353;e z&#225;lo&#382;ky (opak k "Ve&#345;ejn&#233;")
		</li>
	</ul>
	<h3>T&#345;&#237;dit</h3>
	<ul>
		<li><em>P&#345;ezd&#237;vka</em>:
			T&#345;&#237;dit podle p&#345;ezd&#237;vky.
		</li>
		<li><em>E-mail 1</em>:
			T&#345;&#237;dit kontakty podle prim&#225;rn&#237;ho emailu.
		</li>
		<li><em>Organizace</em>:
			T&#345;&#237;dit podle organizace.
		</li>
	</ul>
	<h3>Volby</h3>
	<ul>
		<li><em>Upravit</em>:
			Zde lze upravit vlastnosti kontakt&#367;. M&#367;&#382;ete zm&#283;nit po&#269;et sloupc&#367;
			pro adres&#225;&#345;ov&#233; zobrazen&#237;, lze de-/aktivovat Javascriptov&#233;
			informa&#269;n&#237; pop-up okna, kter&#225; se zobrazuj&#237; p&#345;i najet&#237; kurzorem na odkaz.
			Lze tak&#233; p&#345;edvolit zp&#367;sob zobrazen&#237; z&#225;lo&#382;ek (adres&#225;&#345;ov&#233;, &#345;&#225;dkov&#233;
			nebo stromov&#233;).
		</li>
	</ul>
';
?>
