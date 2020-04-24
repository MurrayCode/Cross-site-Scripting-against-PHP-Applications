<?php

/**
 * The template file that draws the layout to add shared events
 *
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta - 29 May 2006
 * @package org.brim-project.plugins.calendar
 * @subpackage view
 *
 * @copyright [brim-project.org] - Copyright (c) 2003 - 2006 Barry Nauta
 *
 * @license http://opensource.org/licenses/gpl-license.php
 * The GNU Public License
 */

$browserUtils = new BrowserUtils();
if (!$browserUtils->browserIsExplorer())
{
	$ajaxified = true;
}
else 
{
	$ajaxified = false;
}
?>

<?php if ($isItemOwner && $ajaxified)
{
?>

<script type="text/javascript">

	
	function deleteParticipator (participator)
	{
		var theData = "plugin=calendar&ajax=true";
		theData += "&function=deleteParticipator";
		theData += "&participator="+participator;
		theData += "&itemId=<?php echo $renderObjects->itemId ?>";
		theData += "&PHPSESSID=<?php echo session_id (); ?>";
		$.ajax ({
			type:"POST",
			url:"index.php",
			data:theData,
			success:function(data)
			{
				participationStatus (data);
			}
		});

	}

	function addParticipator()
	{
		var selectBox = document.getElementById ("addParticipator");
		var participator = selectBox.options[selectBox.selectedIndex].value;
		var theData = "plugin=calendar&ajax=true";
		theData += "&function=addParticipator";
		theData += "&participator="+participator;
		theData += "&itemId=<?php echo $renderObjects->itemId ?>";
		theData += "&PHPSESSID=<?php echo session_id (); ?>";
		$.ajax ({
			type:"POST",
			url:"index.php",
			data:theData,
			success:function(data)
			{
				participationStatus (data);
			}
		});
	}
	
	function participationStatus (data)
	{
		//alert (data);
		var status = (eval('(' + data + ')'));
		//alert (status.participators);
		//var status = data.parseJSON();
		var participators = status['participators'];
		var nonParticipators = status['nonParticipators'];
		var participatorString = "";
		if (participators == null || participators.length == 0)
		{
			participatorString = '<p><?php echo $dictionary ['none'] ?></p>';
		}
		else
		{
			participatorString += '<table>';
			for (i=0; i<participators.length; i++)
			{
				(i%2==0)?class='even':class='odd';
				participatorString += "<tr class=\""+class+"\">";
				participatorString += "<td><a href=\"javascript:deleteParticipator ('";
				participatorString += participators[i]['loginName'];
				participatorString += "')\">";
				participatorString += "<?php echo preg_replace ( '/"/', '\\"', $icons['delete']); ?>";
				participatorString += "</a></td>";
				participatorString += "<td>"+participators[i]['name']+"</td>";
				participatorString += "<td>("+participators[i]['loginName']+")</td>"
				participatorString += "</tr>";
			}
			participatorString += '</table>';
		}
		document.getElementById ('participatingUsers').innerHTML=participatorString;
		nonParticipatorString = "";
		if (nonParticipators == null || nonParticipators.length == 0)
		{
			nonParticipatorString = '<p><?php echo $dictionary ['none'] ?></p>';
		}
		else
		{
			nonParticipatorString += '<p><select id="addParticipator">';
			for (i=0; i<nonParticipators.length; i++)
			{
				nonParticipatorString += "<option value=\"";
				nonParticipatorString += nonParticipators[i]['loginName'];
				nonParticipatorString += "\">"+nonParticipators[i]['name']+"&nbsp;(";
				nonParticipatorString += nonParticipators[i]['loginName']+")</option>";
			}
			nonParticipatorString += '</select>';
			nonParticipatorString += "<input type=\"submit\" name=\"submit\" ";
			nonParticipatorString += "value=\"<?php echo $dictionary['add'] ?>\" "; 
			nonParticipatorString += "onclick=\"javascript:addParticipator()\"/></p>";
		}
		document.getElementById ('nonParticipatingUsers').innerHTML=nonParticipatorString;
	}

</script>
<?php
}
?>

<div id="participationDetail" style="display:none">
<h2><?php echo $dictionary['participatingUsers'] ?></h2>
<div id="participatingUsers">
<?php	
	//
	// Show all participating users
	//
	if (!isset ($participatingUsers) ||
		count ($participatingUsers) == 0)
	{
		echo '<p>'.$dictionary ['none'].'</p>';
	}
	else 
	{
		echo '<table>';
		$i=0;
		foreach ($participatingUsers as $user)
		{
			($i++%2==0)?$class='even':$class='odd';
			echo '<tr class="'.$class.'">';
			if ($isItemOwner)
			{
				echo '<td>';
				if ($ajaxified)
				{
					echo '<a href="';
					echo 'javascript:deleteParticipator (\''.$user->loginName.'\')';
					echo '">';
				}
				else 
				{
					echo '<a href="';
					echo 'index.php?plugin=calendar&amp;action=deleteParticipator';
					echo '&participator='.$user->loginName;
					echo '&amp;itemId='.$renderObjects->itemId.'" ';
					echo 'onclick="javascript:return confirm (\'';
					echo $dictionary['confirm_delete'];
					echo '\');">';
				}
				echo $icons['delete'].'</a>';
				echo '</td>';
			}
			echo '<td>'.$user->name.'</td>';
			echo '<td>('.$user->loginName.')</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
?>
</div> 
<!-- participating users -->


<?php if ($isItemOwner)
{
	//
	// Only show the non-participating users and the 
	// add-button if we own this item
	//
	echo '<h2>'.$dictionary['nonParticipatingUsers'].'</h2>
		<div id="nonParticipatingUsers">';
	if (!isset ($nonParticipatingUsers) ||
		count ($nonParticipatingUsers) == 0)
	{
		echo '<p>'.$dictionary ['none'].'</p>';
	}
	else 
	{
		if (!$ajaxified)
		{
			?>
				<form method="POST" 
	 				action="index.php"
					name="reminderForm">
				<input type="hidden" name="plugin" value="calendar" />
				<input type="hidden" name="action" value="addParticipator" />
				<input type="hidden" name="eventId" id="eventId" 
				<?php if (isset ($renderObjects)) {
					echo 'value="'.$renderObjects->itemId.'" ';
				} ?>
				/>
			<?php
		}
		echo '<p><select id="addParticipator" name="addParticipator">';
		foreach ($nonParticipatingUsers as $user)
		{
			echo'<option value="'.$user->loginName.'">';
			echo $user->name.'&nbsp;('.$user->loginName.')</option>';
		}
		echo '</select>';
		echo '<input type="submit" name="submit" value="'.$dictionary['add'].'" 
			onclick="javascript:addParticipator()"/></p>';
		
		if (!$ajaxified)
		{
			echo '</form>';
		}
		
	}
	echo '</div>';
}
?>
</div> 
<!-- participationDetail -->
