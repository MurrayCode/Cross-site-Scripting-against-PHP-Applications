CREATE TABLE brim_calendar_event (
  item_id int(11) NOT NULL auto_increment,
  owner varchar(70) NOT NULL default '',
  parent_id int(11) NOT NULL default '0',
  is_parent tinyint(1) default 0,
  name varchar(70) NOT NULL default '',
  description text,
  visibility varchar(15) default NULL,
  category varchar(50) default NULL,
  is_deleted tinyint(1) default 0,
  when_created datetime default NULL,
  when_modified datetime default NULL,
  location text,
  organizer text,
  priority tinyint (1) default NULL,
  -- Birthday: freq=Yearly, interval=1, bymonth=4
  -- 2nd tuesday each month: freq=monthly, interval=1, byday=2tu
  --  
  --  Hmm... not sure about the differences between frequency and bywhat
  frequency text,
  event_interval tinyint (1) default 0,
  by_what text,
  by_what_value text,
  event_start_date datetime default NULL,
  event_end_date datetime default NULL,
  event_recurring_end_date datetime default NULL,
  event_colour text default NULL,
  PRIMARY KEY (item_id),
  KEY item_id (item_id)
)
