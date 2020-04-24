CREATE TABLE brim_bookmarks (
  item_id int(11) NOT NULL auto_increment,
  owner varchar(70) NOT NULL default '',
  parent_id int(11) NOT NULL default '0',
  is_parent tinyint(1) default NULL,
  name text default NULL,
  description text,
  visibility varchar(10) default NULL,
  category varchar(50) default NULL,
  is_deleted tinyint(1) default NULL,
  when_created datetime default NULL,
  when_modified datetime default NULL,
  when_visited datetime default NULL,
  locator text default NULL,
  visit_count int(11) default 0,
  favicon text default NULL,
  PRIMARY KEY  (item_id),
  KEY item_id (item_id)
)
