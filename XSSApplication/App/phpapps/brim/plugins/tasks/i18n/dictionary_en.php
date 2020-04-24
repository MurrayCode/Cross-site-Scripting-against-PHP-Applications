<?php
/**
 * This file is part of the Brim project.
 * The brim-project is located at the following
 * location: {@link http://www.brim-project.org/ http://www.brim-project.org/}
 *
 * <pre> Enjoy :-) </pre>
 *
 * @author Barry Nauta
 * @package org.brim-project.plugins.tasks
 * @subpackage tasks
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

$dictionary['complete']='Complete';
$dictionary['due_date']='Due date';
$dictionary['item_title']='Tasks';
$dictionary['modifyTaskPreferences']='Modify preferences for Tasks';
$dictionary['priority1']="Urgent";
$dictionary['priority2']="High";
$dictionary['priority3']="Average";
$dictionary['priority4']="Low";
$dictionary['priority5']="Nice to have";
$dictionary['priority']='Priority';
$dictionary['start_date']='Start date';
$dictionary['status']='Status';
$dictionary['item_help']='
<p>
	The Task plugin allows you to manage your
	tasks online.  The following parameters
	of a Task can be set:
</p>
<ul>
	<li><em>Name</em>:
		The name of the task.
	</li>
	<li><em>Folder/Task</em>:
		Indicator whether the item to add is
		a folder or a task.  Note that
		once this option is set, it cannot be
		changed anymore.
	</li>
	<li><em>Public/private</em>:
		Indicator whether this item is public
		or for your eyes only.
		<br />
		Note that if you want a specific
		item to be public, its parents need
		to be public as well!!!  (The root of
		the structure is public by default)
	</li>
	<li><em>Complete</em>:
		Allows the user to give an estimation
		on how much of this
		task is already completed
	</li>
	<li><em>Priority</em>:
		Set the priority of a task. Either
		Urgent (default), High,
		Average, Low, Nice to have.
	</li>
	<li><em>Status</em>:
		An additional status can be set. This
		can be anything you like.
	</li>
	<li><em>Start date</em>:
		The date on which this task should start.
	</li>
	<li><em>End date</em>:
		The date on which this task should end.
	</li>
	<li><em>Description</em>:
		The description for this task
	</li>
</ul>
<p>
	The submenus that are available for the
	task plugin are Actions, View, Sort,
	Preferences and Help.
</p>
<h3>Actions</h3>
<ul>
	<li><em>Add</em>:
		This action presents the user with a
		input form in which the task parameters
		can be entered.
	</li>
	<li><em>Multiple select</em>:
		This action allows the user
		to select multiple tasks at the same
		time (folders are NOT selectable using
		this option) and either delete them all
		at once or move them all to a specific
		folder.
	</li>
	<li><em>Search</em>:
		This action allows to user to search
		for tasks based on name, status or
		description.
	</li>
</ul>
<h3>View</h3>
<ul>
	<li><em>Expand</em>:
		This action tells the system to open all
		folders and show all items available.
		This is only
		applicable for the Tree structure view.
	</li>
	<li><em>Collapse</em>:
		This action tell the system to show
		only the items (either folders or
		bookmarks) of the current selected folder.
	</li>
	<li><em>Directory structure</em>:
		This action tells the system to switch
		to the directory structure overview.
		This view shows the notes in a way
		that is similar to the way Yahoo! shows
		its directory structure.
		<br />
		The number of columns can for this view
		can be set in the notes specific
		preferences.
	</li>
	<li><em>Overview Tree</em>:
		This action tells the system to show
		yet another kind of overview which is a
		bit of a combination between the
		line-based overview and the tree
		overview.
	</li>
	<li><em>Line based</em>:
		This tells the system to show a
		layout which contains
		details of the tasks per line.
	</li>
	<li><em>Tree structure</em>:
		This action tells the system to switch
		to an overview that is similar to the
		way Explorer and many other file managers
		show the layout of a filesystem.
	</li>
	<li><em>See shared</em>:
		Display all public tasks of all users
		mixed with your tasks (regardless whether
		they are public or private).
	</li>
	<li><em>See owned</em>:
		Show only your tasks (as opposed to
		"see shared")
	</li>
</ul>
<h3>Sort</h3>
<ul>
	<li><em>Priority</em>:
		Sort of the tasks priority.
	</li>
	<li><em>Complete</em>:
		Sort on the completeness percentage
		of tasks.
	</li>
	<li><em>Start date</em>:
		Sort on start dates of tasks.
	</li>
	<li><em>End date</em>:
		Sort on end dates of tasks.
	</li>
</ul>
<h3>Preferences</h3>
<ul>
	<li><em>Modify</em>:
		Modifies your note specific preferences.
		You can modify the column count for tasks
		when the are displayed in the directory
		overview structure, you can modify
		whether you wish javascript popups when
		you hover over the links and you can
		modify what the default view for tasks
		should be (either directory, overview,
		line or tree based).
	</li>
</ul>
';
$dictionary['taskHideCompleted']='Hide completed tasks';
$dictionary['hideCompleted']='Hide completed';
$dictionary['showCompleted']='Show completed';
$dictionary['completedWillDisappearAfterUpdate']='The item you selected now is a hundred percente complete. Additionally, you have selected to hide completed tasks in your preferences, this item will be hidden after the next update';
$dictionary['completedTasks']='Completed tasks';
$dictionary['uncompletedTasks']='Uncompleted tasks';
?>
