<?php

/* Just the stuff that's left to do on this blog.
TODO:
8. Create .sql file to create db tables.
15. If the title is 'Check "this" out', when you edit the article, "this" will no longer be there.
18. Move if/else if/else to switches
19. &lt; comes out as "<" in edit fields... Then saves as "<"
21. Add "clear" to visit stats (could get large/unweildy)
23. Fix Searches with ' or " in them
24. Get thin border on select boxes
26. Get the unicode arrows to work in IE

Done.
17. Fixed the "earlier articles" bug.
16. Improve theming
Made the RSS valid.
Added the allowed tags config variable.
Added a right margin to the Read More links
Made the arrows unicode escaped chars
Improved visits recording.
7. Add link to bottom for SF download site.
20. Look into nr2br funtion. (Only in PHP 5+)
22. Fix enum to drop-down
14. Fix the closing tag issue.
13. Fix the bug where if I set the problems page to not published, nothing in that subsections appears.
25. Get enum select to work on add page (it works on edit)

*/










/***** Site variables. Change these to get your blog working. *****/

$site_title = "ISBlog 0.5"; // This will appear at the top of the blog, and in <title> tag.
$site_description = ""; // A short description, currently only for RSS.
$content_owner = "J. Doe"; // Optional. If included, will appear at the bottom of the page with a generic (C) notice.
$owner_email = ""; // Optional. If included, will appear at the bottom of the page.

// MySQL connection variables.
$database_server = "localhost";
$database_user = "root";
$database_password = "hacklab2019";
$database_name = "insanely_simple_blog";

$site_admin_password = "password"; // This is the password required to make changes to the site.

// Layout, comments, etc. variables.
$allow_comments = "yes"; // Do you want to show/allow comments for each article?
$show_admin_link = "yes"; // Do you want a link into the admin interface? If not, you can reach it through index.php?action=admin .
$articles_per_page = 10; // How many articles appear on each page.
$color_scheme = "light_blue"; // Possible values: blue, red, green, orange, light_blue

$allowed_tags = '<A><I><B><U><OL><UL><LI><TABLE><TR><TD>'; // These are tags that will NOT be removed from comments and content.


/***** End Site Variables *****/










/***** Some useful functions. *****/

// This is just a short-hand or doing a query. Takes in an SQL string, returns the results of the query. Echos an error if there is one.
function query($sql) {
  $results = mysql_query($sql);
  echo mysql_error();
  return $results;
}

// This is pretty lame, but can be handy. It takes in a table name, the id number for the desired record, and the name of the column from which you want the data. It returns the value of that column for that record in that table.
// I added some cases that are specific to the way this funciton is used on this page.
function get_value($table,$id,$column) {
  // This is just in case there is no $id.
  if ($id == 0) {
    switch ($table) {
      case "content":
        switch ($column) {
          case "section":
            return "Main";
          break;
          case "subsection":
            return "Most Recent";
          break;
        }
      break;
    }
  }
  else {
    return mysql_result(query("SELECT $column FROM $table WHERE id=$id"),0,0);
  }
}

// This is a recursive funtion that returns the comments for an article.
function get_comments($article_or_comment, $id) {
  $sql_where = "parent_id=$id";
  if ($article_or_comment == "article") {
    $sql_where = "article_id=$id AND parent_id=0";
  }
  $results = query("SELECT * FROM comments WHERE $sql_where ORDER BY id ASC");
  while ($row = mysql_fetch_assoc($results)) {
    if ($row['id'] == $_GET['new_comment']) { $return_string .= "<a name=\"new_comment\"></a>"; }
    $return_string .= "\n\n<div class=\"block_01\">";
    $return_string .= "<div class=\"heading_02\">".$row['subject']."</div>\nby ".$row['posted_by']." on ".date('m/d/y',$row['date_posted'])."\n<div class=\"article_text\">".prepare_text($row['content'])."<div class=\"read_more\"><a href=\"index.php?id=".$_GET['id']."&reply_to=".$row['id']."#new_message\">Reply</a></div>";
    if($row['id'] == $_GET['reply_to']) { $return_string .= '<a name="new_message"></a>'.show_form(); }
    $return_string .= get_comments('comment', $row['id']);
    $return_string .= "\n</div>\n</div>";
  }
  return $return_string;
}

// This displays the comments form.
function show_form () {
  $return_string = '
<!-- Start comment form -->
<div class="block_01">
<div class="heading_02">
';
  if ($_GET['new_comment'] == 0) { $return_string .= '<a name="new_comment"></a>'; }
  $return_string .= 'Post a comment
</div>
<div class="article_text">
<form name="comment" method="POST" action="index.php?id='.$_GET['id'].'&reply_to='.$_GET['reply_to'].'#new_comment">
<input type="hidden" name="parent_id" value="'.$_GET['reply_to'].'">
<div class="warning">'.$_GET['message'].'</div>
Your Name: <input type="text" class=\"text_field\" name="posted_by" value="'.stripslashes($_POST['posted_by']).'">
<p>Subject: <input type="text" class=\"text_field\" name="subject" size="64" value="'.stripslashes($_POST['subject']).'">
<p>Comment:<br><textarea class=\"text_field\" name="content" cols="55" rows="5" wrap="virtual">'.stripslashes($_POST['content']).'</textarea>
<p><input type="reset" value="Clear Form"> <input type="submit" name="submit" value="Submit Comment">
</form>
</div>
</div>
<!-- End comment form -->';
  return $return_string;
}

// This function takes in text (eg. from a query) and formats it in HTML.
function prepare_text($text) {
  global $allowed_tags;
  $text = str_replace("\n","\n<br>",strip_tags(stripslashes($text),$allowed_tags));
  $text = preg_replace("/([^\"])(http:\/\/[-\/a-zA-Z0-9%_.?&=]*)/","$1<a href=\"$2\">$2</a>",$text);
  $text = close_tags($text);
  return $text;
}

// It could be that an article or the summary will end without closing all the tags. Here's where we'll add closing tags.
function close_tags($text) {
  global $allowed_tags;
  $tags_array = explode(">",trim($allowed_tags));
  // I don't know why, but the above explode returns a blank element at the end of the array. I pop it off here, but's it's not a fix.
  array_pop($tags_array);
  $closing_tags_needed = array();
  foreach($tags_array as $tag) {
    $closing_tag = '</'.substr($tag,1);
    $lower_tag = strtolower($tag);
    $opening_tag_count = preg_match_all("/$lower_tag( |\>)/",strtolower($text),$tmp); // OLD CODE: substr_count(strtolower($text),strtolower($tag.">"));
    $closing_tag_count = substr_count(strtolower($text),strtolower($closing_tag.">"));
    $closing_tags_needed[$tag] = $opening_tag_count - $closing_tag_count; 
    /// DEBUG CODE echo "<br>".substr($tag,1)." :".$opening_tag_count." : ".$closing_tag_count." : ".$closing_tags_needed[$tag];
  }
  foreach ($tags_array as $tag) {
    for ($i=0; $i<$closing_tags_needed[$tag]; $i++) {
      $text =  $text.'</'.substr($tag,1).">";
    }
  }
  return $text;
} // End close tag function

// This writes an RSS file.
function write_rss($site_title,$site_description) {
  $articles = query("SELECT id, section, subsection, author, title, SUBSTRING(content, 1, 256) as content, date_posted FROM content WHERE published='Yes' ORDER BY date_posted DESC LIMIT 10");
  $rss_text = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" xmlns=\"http://purl.org/rss/1.0/\">\r\t<channel rdf:about=\"$_SERVER[SERVER_NAME]\">\r\t\t<title>$site_title</title>\r\t\t<description>$site_description</description>\r\t\t<link>http://127.0.0.1/blog/index.php</link>\r\t\t<items>\r\t\t\t<rdf:Seq>";
  while ($article = mysql_fetch_assoc($articles)) {
    $rss_text .= "\r\t\t\t\t<rdf:li rdf:resource=\"http://$_SERVER[SERVER_NAME]$_SERVER[PHP_SELF]?id=$article[id]\"/>";
  }
  $rss_text .= "\r\t\t\t</rdf:Seq>\r\t\t</items>\r\t</channel>";

  $articles = query("SELECT id, section, subsection, author, title, SUBSTRING(content, 1, 256) as content, date_posted FROM content WHERE published='Yes' ORDER BY date_posted DESC LIMIT 10");
  while ($article = mysql_fetch_assoc($articles)) {
    $rss_text .= "\r\t<item rdf:about=\"http://$_SERVER[SERVER_NAME]$_SERVER[PHP_SELF]?id=$article[id]\">\r\t\t<title>".stripslashes($article[title])."</title>\r\t\t<description>From $article[section] :: $article[subsection], Posted by $article[author] on ".date('m/d/y', $article[date_posted])." - \r\r".stripslashes(str_replace("&","&amp;",strip_tags($article[content])))."...</description>\r\t\t<link>http://$_SERVER[SERVER_NAME]$_SERVER[PHP_SELF]?id=$article[id]</link>\r\t</item>";
  }
  $rss_text .= "\r</rdf:RDF>";
  $current_directory = $_SERVER[DOCUMENT_ROOT].substr($_SERVER[SCRIPT_NAME],0,strrpos($_SERVER[SCRIPT_NAME],"/"))."/";

  // modified from http://us3.php.net/fwrite
  $filename = $current_directory.'rss.xml';

  $tmp = fopen($filename, 'w');
  fclose($tmp);

  if (is_writable($filename)) {
    if (!$handle = fopen($filename, 'w')) {
      echo "Cannot open file ($filename)";
      exit;
    }
    if (fwrite($handle, $rss_text) === FALSE) {
      echo "Cannot write to file ($filename)";
      exit;
    }
    echo "RSS feed was created (http://$_SERVER[SERVER_NAME]".substr($_SERVER[SCRIPT_NAME],0,strrpos($_SERVER[SCRIPT_NAME],"/"))."/rss.xml)";
    fclose($handle);
  }
  else {
    echo "The file $filename is not writable";
  }
}  // End write_rss()

/***** End useful functions *****/










/***** The page gets going here. *****/

// Hook up to database
$db = mysql_connect($database_server, $database_user, $database_password);
mysql_select_db($database_name,$db);
echo mysql_error();

// This just sets some default values.
if (!$_GET['current_subsection']) {
  if (!$_GET['id']) { $_GET['current_subsection'] = 0; }
  else { $_GET['current_subsection'] = $_GET['id']; }
}
if (!$_GET['reply_to']) { $_GET['reply_to'] = 0; }


switch ($_GET['action']) {

  case 'admin':
    // Do nothing (for now).
  break;

  case 'search':
    $current_section_title = 'Search Results';
    $current_subsection_title = stripslashes("'$_POST[term]'");
  break;

  default:
    $current_section_title = get_value('content',$_GET['current_subsection'],'section');
    $current_subsection_title = get_value('content',$_GET['current_subsection'],'subsection');
  break;

}

/***** Record new comments *****/
$_GET['new_comment'] = 0;
if ($_POST['submit']) {
  if ($_POST['posted_by'] == '') { $_GET['message'] = "Sorry, no anonymous postings."; }
  else if ($_POST['subject'] == '') { $_GET['message'] = "Please fill-in a subject for this post."; }
  else if ($_POST['content'] == '') { $_GET['message'] = "You must put something in the body of your comment."; }
  else {
    query("INSERT INTO comments (article_id,posted_by,parent_id,subject,content,date_posted) VALUES (".$_GET['id'].",'".$_POST['posted_by']."',".$_POST['parent_id'].",'".$_POST['subject']."','".$_POST['content']."',".mktime().")");
    $_GET['new_comment'] = mysql_insert_id();
    $_GET['reply_to'] = 0;
    $_POST = array();
  }
}


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo "$site_title :: $current_section_title :: $current_subsection_title" ?></title>

<!-- Begin styles -->
<?php 
// Color schemes:
$colors = array();
function add_scheme($colors_string) {
  $colors_array = array('scheme_name','background','title','line','headline_background','headline','link_hover');
  $incoming_array = explode(",",$colors_string);
  $new_array = array();
  for ($i=0;$i<count($colors_array);$i++) {
    $new_array[$colors_array[$i]] = trim($incoming_array[$i]);
  }
  global $colors;
  $colors[$new_array[scheme_name]] = $new_array;
}

// Format: 'scheme_name, page background color, title color, line color, headline background color, headline color, link hover color'
add_scheme('blue,        FFFFFF,                000000,      5384AB,     5384AB,                    FFFFFF,         5384AB');
add_scheme('orange,      FFFFFF,                000000,      FF9900,     FF9900,                    FFFFFF,         FF9900');
add_scheme('red,         FFFFFF,                000000,      CC0000,     CC0000,                    FFFFFF,         CC0000');
add_scheme('green,       FFFFFF,                000000,      008300,     008300,                    FFFFFF,         008300');
add_scheme('light_blue,  F7F7FF,                000000,      D4D4F7,     FFFFFF,                    7E7EE7,         5384AB');
add_scheme('light_pink,  FFF7F7,                000000,      F7D4D4,     FFFFFF,                    E77E7E,         D66D6D');

?>
<style type="text/css">
body {
  font-family: Sans-serif;
  font-size: 14px;
  color: #000000;
  background-color: #<?php echo $colors[$color_scheme][background]; ?>; 
}

.text_field {
  color: #000000;
  border: 1px solid #<?php echo $colors[$color_scheme][line]; ?>; 
}

select {
  color: #000000;
  border: 1px solid #<?php echo $colors[$color_scheme][line]; ?>; 
}

.search_field {
  color: #000000;
  background-color: #<?php echo $colors[$color_scheme][background]; ?>; 
  border: 1px solid #<?php echo $colors[$color_scheme][line]; ?>; 
}

.button {
  color: #<?php echo $colors[$color_scheme][headline]; ?>;
  background-color: #<?php echo $colors[$color_scheme][background]; ?>; 
  border: 1px solid #<?php echo $colors[$color_scheme][line]; ?>; 
}

.button:hover {
  color: #<?php echo $colors[$color_scheme][title]; ?>;
  background-color: #<?php echo $colors[$color_scheme][background]; ?>; 
  border: 1px solid #<?php echo $colors[$color_scheme][link_hover]; ?>;
}

a {
  color: #545454;
  text-decoration: none;
}

a:hover {
  color: #<?php echo $colors[$color_scheme][link_hover]; ?>;
  border-bottom: 1px solid #545454;
}

div.top {
  font-size: 56pt;
  margin-left: 72pt;
  position: absolute;
  left:0pt;
  top:0pt;
}

div.bottom {
  font-size: 6pt;
  text-align: center;
}

div.heading_01 {
  color: #<?php echo $colors[$color_scheme][title]; ?>;
  font-size: 18pt;
  margin-bottom: 10pt;
}

div.heading_02 {
  font-size: 14pt;
  color: #<?php echo $colors[$color_scheme][headline]; ?>;
  background-color: #<?php echo $colors[$color_scheme][headline_background]; ?>;
  border-bottom: 1px solid #<?php echo $colors[$color_scheme][line]; ?>;
}

div.block_01 {
  width: auto;
  border-top: 1px solid #<?php echo $colors[$color_scheme][line]; ?>;
  border-bottom: 1px solid #<?php echo $colors[$color_scheme][line]; ?>;
  border-left: 1px solid #<?php echo $colors[$color_scheme][line]; ?>;
  border-right: 1px solid #<?php echo $colors[$color_scheme][line]; ?>;
  margin-bottom: 10pt;
  margin-top: 10pt;
  padding-bottom: 5pt;
  background-color: #FFFFFF;
}

div.left {
  position: absolute;
  left:10pt;
  top:144pt;
  width: 144pt;
}

div.right {
	  position: absolute;
  right:10pt;
  top:144pt;
  width: 144pt;
}

div.main_content {
  margin-top: 72pt;
  margin-left: 156pt;
  margin-right: 156pt;
}

div.list_table {
  margin-top: 72pt;
  margin-left: 156pt;
}

div.article_text {
  margin-top: 8pt;
  margin-bottom: 8pt;
  margin-left: 8pt;
  margin-right: 8pt;
}

div.read_more {
  margin-right: 10pt;
  text-align: right;
}

div.warning {
  color: #CC0000;
}

img {
  margin-left: 10px;
  margin-right: 10px;
  margin-top: 10px;
  margin-bottom: 10px;
  border-top: 0px;
  border-bottom: 0px;
  border-left: 0px;
  border-right: 0px;
}

UL {
  margin-left: 15px;
  margin-right: 0px;
  margin-top: 0px;
  margin-bottom: 0px;
}

OL {
  margin-left: 15px;
  margin-right: 0px;
  margin-top: 0px;
  margin-bottom: 0px;
}

LI {
  margin-left: 0px;
  margin-right: 0px;
  margin-top: 0px;
  margin-bottom: 0px;
}
</style>
<!-- End styles -->

</head>

<?php










/***** Admin interface starts here *****/

if ($_GET['action'] == 'admin') { ?>
<body>
<div class="top"><a href="index.php"><?php echo $site_title; ?></a></div>





<!-- ----- List Page ----- -->
<?php
if ($_GET['task'] == 'list') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Listing ".$_GET['table']."</div>";
  $results = query("SELECT * FROM ".$_GET['table']." ORDER BY ".$_GET['orderby']." DESC");
  echo "<table><tr><td>&nbsp;</td>";
  for ($i=0;$i<mysql_num_fields($results);$i++) {
    $field_info = mysql_fetch_field($results, $i);
    echo "<td><a href=\"index.php?action=admin&task=list&table=".$_GET['table']."&orderby=$field_info->name\">".ucwords(str_replace('_',' ',$field_info->name))."</td>";
  }
  while ($row = mysql_fetch_assoc($results)) {
    echo "<tr>\n<tr><td><a href=\"index.php?action=admin&task=edit&table=".$_GET['table']."&id=".$row['id']."\">Edit</a>";
    foreach ($row as $cell) {
      echo "<td>".strip_tags(stripslashes(substr($cell,0,16)))."</td>";
    }
    echo "</tr>";
  }
  echo "</table>";
}
?>





<!-- ----- Edit Page ----- -->
<?php
if ($_GET['task'] == 'edit') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Edit ".$_GET['table']."</div>";
 
  // Got this idea from the comments on http://us2.php.net/mysql_fetch_field
  $columns_array = array();
  $columns = mysql_query("SHOW COLUMNS FROM $_GET[table]");
   while($row = mysql_fetch_object($columns)){
   $columns_array[$row->Field] = $row->Type;
  }

  $results = query("SELECT * FROM ".$_GET['table']." WHERE id=".$_GET['id']);
  echo '<form method="post" action="index.php?action=admin&task=update&table='.$_GET['table'].'&id='.$_GET['id'].'"><table class="left">';
  for ($i=0;$i<mysql_num_fields($results);$i++) {
    $field_info = mysql_fetch_field($results, $i);
    $field_flags = mysql_field_flags($results, $i);
    if ($field_info->name != 'id' && substr($field_info->name,0,4) != 'date' && substr($field_info->name,-2) != 'id') {
      switch (preg_replace("/\(.+\)/","",$columns_array[$field_info->name])) {
        case "int":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td><input type=\"text\" class=\"text_field\" name=\"$field_info->name\" value=\"".mysql_result($results,0,$i)."\"></td></tr>";
        break;
        case "varchar":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td>";
          echo "<input type=\"text\" class=\"text_field\" name=\"$field_info->name\" value=\"".stripslashes(mysql_result($results,0,$i))."\">";
          echo "</td></tr>";
        break;
        case "blob":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td><textarea class=\"text_field\" cols=\"50\" rows=\"20\" name=\"$field_info->name\">".stripslashes(mysql_result($results,0,$i))."</textarea></td></tr>";
        break;
        case "enum":
          $values_array = explode(",",preg_replace("/(set|enum)\((.+)\)/","$2",$columns_array[$field_info->name]));
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td>";
          echo "<select name=\"$field_info->name\" value=\"".stripslashes(mysql_result($results,0,$i))."\">";
          echo "\r<option value=\"".mysql_result($results,0,$i)."\">".mysql_result($results,0,$i)." (current)</option>";
          foreach ($values_array as $value) {
            if (str_replace("'","",$value) != mysql_result($results,0,$i)) {
              $value = str_replace("'","",$value);
              echo "\r<option value=\"$value\">$value</option>";
            }
          }
          echo "</select>";
          echo "</td></tr>";
        break;
      }
    }
  }
  echo '<tr><td>Password:</td><td><input type="password" class="text_field" name="password"><tr><td>&nbsp;</td><td><input type="submit" class="button" value="Submit Changes"></td></tr><tr><td>&nbsp;</td></tr></table></form>';
  echo '</div>';
  echo '<div class="block_01"><div class="heading_02">Delete from '.$_GET['table'].'</div>';
  echo '<form action="index.php?action=admin&task=delete&table='.$_GET['table'].'&id='.$_GET['id'].'" method="post"><div class="article_text"><div class="warning">WARNING: Clicking the button below will completely remove this entry from the database. You will <i>NOT</i> be asked to confirm.</div>Password: <input type="password" class="text_field" name="password"><p><input type="submit" class="button" value="DELETE THIS ENTRY"></div></form>';
}
?>





<!-- ----- Update Page ----- -->
<?php
if ($_GET['task'] == 'update') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Updating ".$_GET['table']."</div>";
  $sql = "UPDATE ".$_GET[table]." SET ";
  while(list($key, $value) = each($HTTP_POST_VARS)) {
    if ($key != "password") {
      $sql .= "$key=";
      if (is_numeric($value)) { $sql .= "$value, "; }
      else { $sql .= "'".addslashes($value)."', "; }
    } 
  }
  $sql .= "date_modified=".mktime()." WHERE id=".$_GET['id'];
  if ($_POST['password'] == $site_admin_password) {
    query($sql);
    echo "<div class=\"article_text\">If there are no errors listed above, the changes were made.<p>";
    write_rss($site_title,$site_description);
    echo "</div>";
  }
  else { echo "<div class=\"warning\">Changes not made. Invalid password.</div>"; }
}
?>




<!-- ----- Delete Page ----- -->
<?php

if ($_GET['task'] == 'delete') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Deleting from ".$_GET['table']."</div>";
  if ($_POST['password'] == $site_admin_password) {
    query("DELETE FROM ".$_GET['table']." WHERE id=".$_GET['id']);
    echo "<div class=\"article_text\">If there are no errors listed above, the entry was deleted.<div class=\"warning\">If you did not mean to delete this entry, hit the 'Back' button now.</div> Your browser probably cached the last page, and you can salvage the content of this entry by copying the text out of the field on that page.<p>";
    write_rss($site_title,$site_description);
    echo "</div></div>";
  }
  else { echo "<div class=\"warning\">Entry not deleted. Invalid password.</div>"; }
}
?>




<!-- ----- Add Page ----- -->
<?php
if ($_GET['task'] == 'add') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Add ".$_GET['table']."</div>";
  $results = query("SELECT * FROM ".$_GET['table']." LIMIT 1");
  // Got this idea from the comments on http://us2.php.net/mysql_fetch_field
  $columns_array = array();
  $columns = mysql_query("SHOW COLUMNS FROM $_GET[table]");
   while($row = mysql_fetch_object($columns)){
   $columns_array[$row->Field] = $row->Type;
  }
  echo '<form method="post" action="index.php?action=admin&task=insert&table='.$_GET['table'].'"><table class="left">';
  for ($i=0;$i<mysql_num_fields($results);$i++) {
    $field_info = mysql_fetch_field($results, $i);
    $field_flags = mysql_field_flags($results, $i);
    if ($field_info->name != 'id' && substr($field_info->name,0,4) != 'date' && substr($field_info->name,-2) != 'id') {
      switch (preg_replace("/\(.+\)/","",$columns_array[$field_info->name])) {
        case "int":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td><input type=\"text\" class=\"text_field\" name=\"$field_info->name\"></td></tr>";
        break;
        case "varchar":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td>";
          echo "<input type=\"text\" class=\"text_field\" name=\"$field_info->name\">";
          echo "</td></tr>";
        break;
        case "blob":
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td><textarea class=\"text_field\" cols=\"50\" rows=\"20\" name=\"$field_info->name\"></textarea></td></tr>";
        break;
        case "enum":
          $values_array = explode(",",preg_replace("/(set|enum)\((.+)\)/","$2",$columns_array[$field_info->name]));
          echo "\r<tr><td>".ucwords(str_replace("_"," ",$field_info->name))."</td><td>";
          echo "<select name=\"$field_info->name\">";
          foreach ($values_array as $value) {
            $value = str_replace("'","",$value);
            echo "\r<option value=\"$value\">$value</option>";
          }
          echo "</select>";
          echo "</td></tr>";
        break;
      }
    }
  }
  echo '<tr><td>Password:</td><td><input type="password" class="text_field" name="password"><tr><td>&nbsp;</td><td><input type="submit" class="button" value="Submit Entry"></td></tr><tr><td>&nbsp;</td></tr></table></form>';
}
?>





<!-- ----- Insert Page ----- -->
<?php
if ($_GET['task'] == 'insert') {
  echo '<div class="list_table">';
  echo '<div class="block_01">';
  echo "<div class=\"heading_02\">Adding entry to ".$_GET['table']."</div>";
  $sql = "INSERT INTO ".$_GET[table]." (";
  while(list($key, $value) = each($HTTP_POST_VARS)) {
    if ($key != "password") { $sql .= "$key, "; } 
  }
  $sql .= " date_posted) VALUES (";
  reset($HTTP_POST_VARS);
  while(list($key, $value) = each($HTTP_POST_VARS)) {
    if ($key != "password") {
      if (is_numeric($value)) { $sql .= "$value, "; }
      else { $sql .= "'".addslashes($value)."', "; }
    } 
  }
  $sql .= mktime().")";
  if ($_POST['password'] == $site_admin_password) {
    query ($sql);
    echo "<div class=\"article_text\">If there are no errors listed above, the changes were made.<p>";
    write_rss($site_title,$site_description);
    echo "</div>";
  }
  else { echo "<div class=\"warning\">Changes not made. Invalid password.</div>"; }
}
?>




<!-- ----- Admin Welcome Page ----- -->
<?php if (!$_GET['task']) { ?>
<div class="main_content">
<div class="block_01">
<div class="heading_02">Administration Interface</div>
<div class="article_text">Welcome to the administration interface for this site. You will need to have an administration password to add or change anything.</div>
<div class="article_text">Please choose something to add or edit from the task menu of the left.</div>
<?php } 
?>





</div>
</div>

<div class="left">
<div class="heading_01">Tasks</a></div>
<div class="block_01"><div class="heading_02">Add</div>
<div class="menu_link"><a href="index.php?action=admin&task=add&table=content">Content</a></div>
<div class="menu_link"><a href="index.php?action=admin&task=add&table=links">Links</a></div></div>
<div class="block_01"><div class="heading_02">Edit</div>
<div class="menu_link"><a href="index.php?action=admin&task=list&table=content&orderby=id">Content</a></div>
<div class="menu_link"><a href="index.php?action=admin&task=list&table=links&orderby=id">Links</a></div>
<div class="menu_link"><a href="index.php?action=admin&task=list&table=comments&orderby=id">Comments</a></div>
</div>
<div class="heading_01">Site Numbers</a></div>
<div class="block_01">
<div class="heading_02">Content Counts</div>
<div class="article_text">
<?php echo mysql_result(query("SELECT COUNT(id) FROM content WHERE published='Yes'"),0,0); ?> Published Articles 
<br><?php echo mysql_result(query("SELECT COUNT(id) FROM content WHERE published='No'"),0,0); ?> Unpublished Articles
<br><?php echo mysql_result(query("SELECT COUNT(id) FROM comments"),0,0); ?> Comments
<br><?php echo mysql_result(query("SELECT COUNT(DISTINCT section) FROM content WHERE published='Yes'"),0,0); ?> Sections
<br><?php echo mysql_result(query("SELECT COUNT(DISTINCT section,subsection) FROM content WHERE published='Yes'"),0,0); ?> Subsections
<br><?php echo mysql_result(query("SELECT COUNT(id) FROM links"),0,0); ?> Links
</div>
</div>
<div class="block_01">
<div class="heading_02">Visits</div>
<div class="article_text">
Unique visitors during the...
<br>... last day: <?php echo mysql_result(query("SELECT COUNT(DISTINCT ip) FROM visits WHERE site='$site_title' AND date>".(mktime() - 86400)),0,0); ?>
<br>... last week: <?php echo mysql_result(query("SELECT COUNT(DISTINCT ip) FROM visits WHERE site='$site_title' AND date>".(mktime() - 604800)),0,0); ?>
<br>... last month: <?php echo mysql_result(query("SELECT COUNT(DISTINCT ip) FROM visits WHERE site='$site_title' AND date>".(mktime() - 2592000)),0,0); ?>
<br>... last year: <?php echo mysql_result(query("SELECT COUNT(DISTINCT ip) FROM visits WHERE site='$site_title' AND date>".(mktime() - 31536000)),0,0); ?>
<p>Total unique visitors: <?php echo mysql_result(query("SELECT COUNT(DISTINCT ip) FROM visits WHERE site='$site_title'"),0,0); ?>
</div>  <!-- End visits box contents -->
</div>  <!-- End visits box -->
<div class="block_01">
<div class="heading_02">Popular Articles</div>
<div class="article_text">
<?php
$popular_articles = query("SELECT A.article_id, COUNT(DISTINCT A.ip) as visits, B.title FROM visits A, content B WHERE A.article_id>0 AND A.article_id=B.id GROUP BY A.article_id ORDER BY visits DESC LIMIT 10");
while ($article = mysql_fetch_assoc($popular_articles)) {
  echo "<a href=\"index.php?id=$article[article_id]\">".stripslashes($article[title])."</a> ($article[visits] views)<br>";
}
?>
<br>
</div>  <!-- End visits box contents -->
</div>  <!-- End visits box -->
</div>  <!-- End left column (Admin page) -->
</body>
</html>
<?php
  Die;
}










/***** Admin interface ends here *****/

// Record the visit to this page.
// It used to only record visitors to the home page (no subsection variable set), but then I thought it would be nice to which articles are being visited. So, it records every visit not to admin every page. 
query("INSERT INTO visits (date,ip,site,article_id) VALUES (".mktime().",'".$_SERVER['REMOTE_ADDR']."','$site_title','$_GET[id]')");

?>

<body>

<!-- Start Page Title -->
<div class="top"><a href="index.php"><?php echo $site_title; ?></a></div>
<!-- End Page Title -->

<!-- Start Main Content -->
<div class="main_content">
<div class="heading_01"><?php echo "$current_section_title :: $current_subsection_title"; ?></div>
<?php

if ($_GET['id'] != "") {

  // Display full content
  $sql = "SELECT * FROM content WHERE published='Yes' AND id=".$_GET['id'];
  $results = query($sql);

  while ($row = mysql_fetch_assoc($results)) {
    echo "\n\n<div class=\"block_01\"><div class=\"heading_02\">".stripslashes($row['title'])."</div>";
    echo "By ".$row['author']." on ".date('m/d/y', $row['date_posted']);
    echo "\n<div class=\"article_text\">".prepare_text($row['content'])."\n";
  }

  if ($allow_comments == 'yes') {
    echo "<h2>Comments:</h2>";
    echo get_comments('article',mysql_result($results,0,0));
?>

<!-- Start comment form -->
<?php if (!$_GET['reply_to']) { echo show_form ($_GET['id'],0,$_POST['posted_by'],$_POST['subject'],$_POST['content']); } ?>
<!-- End comment form -->
<?php
  }
  echo "</div>";
?>



<?php
} // End view full content.

// Start summary view/article list view.
else {

  // Set up the limiting variables
  if (!$_GET[start]) {
    $_GET[start] = 0;
  }

  // Display articles from this section.
  if ($_GET['current_subsection'] != 0) {
    $sql = "SELECT b.* FROM content a, content b WHERE a.id=".$_GET['current_subsection']." AND a.section=b.section AND a.subsection=b.subsection AND a.published='Yes' AND b.published='Yes' ORDER BY date_posted DESC LIMIT $_GET[start],$articles_per_page";
    $possible_records = mysql_result(query("SELECT COUNT(b.id) FROM content a, content b WHERE a.id=".$_GET['current_subsection']." AND a.section=b.section AND a.subsection=b.subsection AND a.published='Yes'"),0,0);
  }
  else if ($_GET[action] == 'search') {
    $sql = "SELECT * FROM content WHERE title LIKE '%$_POST[term]%' OR content LIKE '%$_POST[term]%' AND published='Yes' ORDER BY date_posted DESC";
    $possible_records = 0;
  }
  else {
    $sql = "SELECT * FROM content WHERE published='Yes' ORDER BY date_posted DESC LIMIT $_GET[start],$articles_per_page";
    $possible_records = mysql_result(query("SELECT COUNT(id) FROM content WHERE published='Yes'"),0,0);
  }
  $results = query($sql);

  while ($row = mysql_fetch_assoc($results)) {
    echo "<div class=\"block_01\">\n<div class=\"heading_02\">".stripslashes($row['title'])." - from $row[section] :: $row[subsection]\n</div>";
    if (file_exists("graphics/".strtolower($row[subsection]).".png")) {
      echo "<a href=\"index.php?current_subsection=".$row['id']."\"><img src=\"graphics/$row[subsection].png\" alt=\"".$row['subsection']." subsection\" align=\"left\"></a>";
    }
    else if (file_exists("graphics/".strtolower($row[section]).".png")) {
      echo "<img src=\"graphics/$row[section].png\" alt=\"".$row['section']." section\" align=\"left\">";
    }
    echo "Posted by $row[author] on ".date('m/d/y', $row['date_posted']);
    echo "<div class=\"article_text\">".prepare_text(substr($row[content],0,512)."...")."\n</div>";
    echo "<div class=\"read_more\"><a href=\"index.php?id=".$row['id']."\">Read More (".mysql_result(query("SELECT COUNT(id) FROM comments WHERE article_id=".$row['id']." AND content<>''"),0,0)." comments) &#10132;</a></div>";
    echo "</div>";

  }

  // More articles link
  if (($possible_records-($_GET[start]+$articles_per_page)) > 0) {
    echo "<div class=\"read_more\"><a href=\"index.php?current_subsection=$_GET[current_subsection]&start=".($_GET[start]+$articles_per_page)."\">Earlier Articles (".($possible_records-($_GET[start]+$articles_per_page)).") &#10132;</a></div>";
  }

} // End Else display article list.

?>
</div>
<!-- End main content -->


<!-- Start Sections Menu -->
<div class="left">
<div class="heading_01">Sections</div>
<?php

// Get the sections from the DB.
$sql = "SELECT id, section FROM content WHERE published='Yes' GROUP BY section ORDER BY section ASC;";
$results = query($sql);

while ($row = mysql_fetch_assoc($results)) {
  echo "\n<div class=\"block_01\"><div class=\"heading_02\">".$row['section']."</div>\n";
  $sql = "SELECT id, subsection FROM content WHERE section='".$row['section']."' AND published='Yes' GROUP BY subsection ORDER BY subsection;";
  $sub_results = query($sql);
  while ($sub_row = mysql_fetch_assoc($sub_results)) {
    echo "\n<div class=\"menu_link\"><a href=\"index.php?current_subsection=".$sub_row['id']."\">".$sub_row['subsection']."</a></div>\n";
  }
  echo "</div>";
}
	
?>

<!-- Search Bar -->
<div class="block_01">
<div class="heading_02">Search <?php echo $site_title; ?></div>
<div class="article_text">
<form action="index.php?action=search" method="post">
<p><input type="text" class="search_field" name="term">
<p><input type="submit" value="Search" class="button">
</form>
</div>
</div>
<!-- End Search Bar -->

<!-- Administation Link -->
<?php if ($show_admin_link == 'yes') { ?> <a href="index.php?action=admin">Site administration</a> <?php } ?>

</div>
<!-- End Sections Menu -->

<!-- Start Links Menu -->
<div class="right">
<div class="heading_01">Links</div>
<?php

$sql = "SELECT DISTINCT section FROM links ORDER BY section ASC;";
$results = query($sql);
while ($row = mysql_fetch_assoc($results)) {
  echo "\n<div class=\"block_01\"><div class=\"heading_02\">".$row['section']."</div>\n";
  $sub_results = query("SELECT link_text, url FROM links WHERE section='".$row['section']."' ORDER BY link_text");
  while ($sub_row = mysql_fetch_assoc($sub_results)) {
    echo "\n<div class=\"menu_link\"><a href=\"".$sub_row['url']."\">".$sub_row['link_text']."</a></div>\n";
  }
  echo "</div>";
}

?>
</div>
<!-- End Links Menu -->

<div class="bottom">This page produced with <a href="http://sourceforge.net/projects/insanelysimple2">Insanely Simple Blog</a>, which is licensed under the GPL. 
<?php if ($content_owner != '') { ?>
<br>The content, unless taken from elsewhere, belongs to <?php echo $content_owner; ?>.
<?php } ?>
<?php if ($owner_email != '') { ?>
<br>Contact: <a href="mailto:"<?php echo $owner_email; ?>"><?php echo $owner_email; ?></a>.
<?php } ?>
</div>
</body>
</html>