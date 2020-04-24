<?php
header("Content-Type: text/html; charset=utf-8");
include ('templates/nifty/iconDefinitions.inc');
if (
	isset ($_REQUEST['renderer']) || 
	isset ($_GLOBALS['renderer']) ||
	isset ($_REQUEST['GLOBALS']) ||
	isset ($_REQUEST['_GLOBALS']) ||
	isset ($_FILES['GLOBALS'])
)
{
	die (print_r ("Invalid access"));
}
$renderer = str_replace ("*", "", $renderer);
$renderer = str_replace ("<", "", $renderer);
$renderer = str_replace (">", "", $renderer);
$renderer = str_replace ("..", "", $renderer);
$renderer = str_replace ("//", "", $renderer);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">

	<title><?php echo $dictionary['item_title'] ?></title>
	<link rel="stylesheet" type="text/css"
                href="templates/nifty/template.css" />
	<style type="text/css" media="print"
		>@import "templates/nifty/print.css";</style>
	<?php
		//
		// The plugins DEFAULT stylesheet (plugin specific styles end up
		// here so we can keep the main stylesheet generic
		//
    	$style = 'plugins/'.$pluginName.'/view/template.css';
    	if (file_exists ($style))
    	{
            	echo ('<style type="text/css" media="screen"
            	>@import "'.$style.'";</style>');
    	}
		//
		// A plugin can also provide a specific stylesheet for a template.
		// If this stylesheets exists, load it as well
		//
    	$style = 'templates/nifty/plugins/'.$pluginName.'.css';
    	if (file_exists ($style))
    	{
            	echo ('<style type="text/css" media="screen"
            	>@import "'.$style.'";</style>');
    	}
		//
		// Favicon support
		//
        $defaultFavIcon = 'plugins/'.$pluginName.'/templates/'.
                'default/pics/favicon.ico';
        $templateFavIcon = 'plugins/'.$pluginName.'/templates/'.
                $_SESSION['brimTemplate'].'/pics/favicon.ico';
        if (file_exists ($templateFavIcon))
        {
                echo ('<link rel="Shortcut Icon" type="image/x-ico" ');
                echo ('href="'.$templateFavIcon.'" />');
        }
        else if (file_exists ('$defaultFavIcon'))
        {
                echo ('<link rel="Shortcut Icon" type="image/x-ico" ');
                echo ('href="'.$defaultFavIcon.'" />');
        }
		include "templates/nifty/icons.inc";
	?>


	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib_fade.js"></script>
	<script type="text/javascript" 
			language="JavaScript" src="ext/overlib/overlib_bubble.js"></script>
	<script type="text/javascript" src="ext/jQuery/jquery.js"></script>
	<script type="text/javascript" src="ext/jQuery/jq-corner.js"></script>
	<script type="text/javascript" src="ext/jQuery/jquery.jeditable.js"></script>
	<script type="text/javascript" src="ext/jQuery/iutil.js"></script>
	<script type="text/javascript" src="ext/jQuery/idrag.js"></script>
	<script type="text/javascript" src="ext/jQuery/idrop.js"></script>
	<script type="text/javascript" src="ext/jQuery/ifx.js"></script>
	<script type="text/javascript" src="ext/jQuery/ifxtransfer.js"></script>
    <!--
        Generic Javascript functions
    -->
    <script type="text/javascript"
            language="JavaScript" src="framework/view/javascript/brim.js"></script>
	<script type="text/javascript">
		window.onload=function()
		{
			$("div#menu").corner ("10px");
			$("div#brimMenu").corner ("10px");
			$("div#content").corner ("10px");
			$("div#nav").corner ("10px");
		}
	</script>

	<style type="text/css">
			
		#dropDownMenu 
		{
			border:1px solid #999999;
			border-bottom-width: 1px;
			background-color: #eeeeee;
			z-index: 1;
			position: absolute;
			width: 170px
		}
		
		#dropDownMenu a
		{
			display: block;
			padding: 2px 0px 2px 0px;
			color: #000000;
			text-indent: 15px;
			text-align: left;
			text-decoration: none
		}
		
		#dropDownMenu a:hover
		{
			background-color: #dddddd
		}
	</style>
	<!--[if lt IE 7.]>
		<script defer type="text/javascript" src="ext/pngfix.js"></script>
	<![endif]-->
</head>
<body>
<script type="text/javascript">


/**
 * http://www.quirksmode.org/js/findpos.html
 */
function findPosX(obj)
{
	var curleft = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curleft += obj.offsetLeft;
			obj = obj.offsetParent;
		}
	}
	else if (obj.x)
	{
		curleft += obj.x;
	}
	return curleft;
}

/**
 * http://www.quirksmode.org/js/findpos.html
 */
function findPosY(obj)
{
	var curtop = 0;
	if (obj.offsetParent)
	{
		while (obj.offsetParent)
		{
			curtop += obj.offsetTop;
			obj = obj.offsetParent;
		}
	}
	else if (obj.y)
	{
		curtop += obj.y;
	}
	return curtop;
}


function showMenu (obj, e, menucontents)
{
    noTimeout ();
    if (document.getElementById)
    {
        theDropDownMenu = document.getElementById("dropDownMenu");
    }
    else
    {
        // IE
        theDropDownMenu = dropDownMenu;
    }
    var xOffset = findPosX (obj);
    var yOffset = findPosY (obj) + 15;

    theDropDownMenu.innerHTML=menucontents;
    theDropDownMenu.style.visibility = "visible";

    theDropDownMenu.x = xOffset;
    theDropDownMenu.y = yOffset;
    theDropDownMenu.style.left = xOffset.toString() + "px";
    theDropDownMenu.style.top = yOffset.toString() + "px";
}


function hide ()
{
	if (document.getElementById)
	{
		theDropDownMenu = document.getElementById("dropDownMenu");
	}
	else
	{
		// IE
		theDropDownMenu = dropDownMenu;
	}
	if (theDropDownMenu)
	{
		theDropDownMenu.style.visibility="hidden";
	}
}

function hideMenu ()
{
	timeout = setTimeout ("hide ()", 350);
}

function noTimeout ()
{
	if (typeof timeout != "undefined")
	{
		clearTimeout (timeout);
	}
}


</script>
<div id="loading" style="display:none">
	<img src="framework/view/pics/loading/loading.gif" alt="<?php echo $dictionary['loadingIndication'] ?>">
	<?php echo $dictionary['loadingIndication'] ?>	
</div>
<!--
        Overlib, JavaScript popups
-->
<div id="overDiv"
        style="position:absolute; visibility:hidden; z-index:1000;"></div>

<script type="text/javascript" language="JavaScript">
    var ol_textcolor="#000000";
    var ol_capcolor="#000000";
    var ol_bgcolor="#ffbe4f";
    var ol_fgcolor="#fff4d8";
</script>


<div id="container">

	<div id="left">
	<div id="nav">
		<h2><a href="index.php">[brim-project]</a></h2>
		<ul>
		<?php
			foreach ($menuItems as $menuItem)
			{
				echo '<li><a href="'.$menuItem['href'].'" ';
				echo 'name="'.$menuItem['name'].'" ';
				echo 'id="'.$menuItem['name'].'" ';
				echo '>';
				echo $dictionary[$menuItem['name']];
				echo '</a></li>';
			}
		?>
		</ul>
<form method="POST" action="SearchController.php" name="searchBox">
<?php
        foreach ($menuItems as $menuItem)
        {
                echo ('<input type="hidden" name="search_'.$menuItem['name'].'">');
        }
?>
<input type="hidden" name="action" value="search">
<?php echo $dictionary['search'] ?>:
<input type="text" style="width: 100px;height: 15px;" name="value" id="searchfield"/>
</form>

	</div>
	<div id="empty"></div>
	<div id="brimMenu">
	<ul>
		<?php
			$brimMenu = '';
			foreach ($menu as $crumbs)
			{
				$brimMenu .= '<li><a href="'.$crumbs['href'];
				$brimMenu .= '">'.$dictionary[$crumbs['name']].'</a></li>';
			}
			echo $brimMenu;
		?>
	</ul>
	</div>
	</div>

	
	<div id="dropDownMenu" style="visibility:hidden;" 
			onMouseover="noTimeout ();" 
			onMouseout="hideMenu ();"></div>
	<div id="menu">
		<?php 
			if (isset ($renderActions))
			{
				//
				// Loop over each group
				//
				echo '&nbsp;&nbsp;';
				foreach ($renderActions as $actionGroup)
				{
					$menuAction = '';
					foreach ($actionGroup['contents'] as $action)
					{
						$menuAction .= '<a href='.$action['href'].'>';
						$menuAction .= $dictionary[$action['name']].'</a>';
					}
						echo '
						<a onMouseOver="javascript:showMenu (this, event, \''.$menuAction.'\')" 
							onMouseOut="hideMenu ()">'.$dictionary[$actionGroup['name']].'</a>
						&nbsp;&nbsp;
					';
				}
			}
			else
			{
				echo '&nbsp;';
			}
		?>
	</div>

	<div id="content">
		<h1><?php echo $dictionary['item_title'] ?></h1>
		<?php if (isset ($message)) 
			{
				echo '<h2>'.$dictionary[$message].'</h2>';
			}
		?>
		
		<?php include $renderer; ?>
	</div>
</div>

<script type="text/javascript" language="javascript">
/* This code sets the focus of the cursor for typing in the following order:
  1. The search box at the top
  2. The name field of most plugins (bookmark, contact, etc)
  3. the ID of the name field on the calendar is 'nameProxy' instead of 'name'
  like other plugins
*/
document.getElementById('searchfield').focus();
if (document.getElementById('name'))
{
	document.getElementById('name').focus();
}
if (document.getElementById('nameProxy'))
{
	document.getElementById('nameProxy').focus();
}
</script>
</body>
</html>
