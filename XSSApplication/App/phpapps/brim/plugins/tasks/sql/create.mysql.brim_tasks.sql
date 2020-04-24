CREATE TABLE brim_tasks (
  item_id int(11) NOT NULL auto_increment,
  owner varchar(70) NOT NULL default '',
  parent_id int(11) NOT NULL default '0',
  is_parent tinyint(1) default NULL,
  name varchar(70) NOT NULL default '',
  description text,
  visibility varchar(15) default NULL,
  category varchar(50) default NULL,
  is_deleted tinyint(1) default 0,
  when_created datetime default NULL,
  when_modified datetime default NULL,
  priority int(11) NOT NULL default '0',
  start_date datetime default NULL,
  end_date datetime default NULL,
  status varchar(70) default NULL,
  percent_complete int(11) default NULL,
  is_finished tinyint(1) default NULL,
  PRIMARY KEY  (item_id),
  KEY itemId (item_id)
)
