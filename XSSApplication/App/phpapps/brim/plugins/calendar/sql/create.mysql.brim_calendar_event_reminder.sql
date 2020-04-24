CREATE TABLE brim_calendar_event_reminder (
  item_id int(11) NOT NULL auto_increment,
  owner varchar(70) NOT NULL default '',
  parent_id int(11) default 0,
  is_parent integer default 0,
  name varchar(70) NOT NULL default '',
  description text,
  visibility varchar(15) default NULL,
  category varchar(50) default NULL,
  is_deleted integer default 0,
  when_created datetime default NULL,
  when_modified datetime default NULL,
  event_id int(11) NOT NULL,
  timespan varchar (1) default 'm',
  reminder_time int(11) default 0,
  when_sent datetime default NULL,
  PRIMARY KEY (item_id),
  KEY item_id (item_id)
)
