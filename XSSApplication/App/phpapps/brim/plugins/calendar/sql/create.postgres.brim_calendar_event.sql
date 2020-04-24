CREATE TABLE brim_calendar_event (
  item_id serial NOT NULL,
  owner varchar(70) NOT NULL default '',
  parent_id integer NOT NULL default 0,
  is_parent integer default 0,
  name char(70) NOT NULL default '',
  description text,
  visibility char(15) default NULL,
  category char(50) default NULL,
  is_deleted int default NULL,
  when_created timestamp default NULL,
  when_modified timestamp default NULL,
  location text,
  organizer text,
  priority int default 0,
  -- Birthday: freq=Yearly, interval=1, bymonth=4
  -- 2nd tuesday each month: freq=monthly, interval=1, byday=2tu
  --  
  --  Hmm... not sure about the differences between frequency and bywhat
  frequency text,
  event_interval int default 0,
  by_what text,
  by_what_value text,
  event_start_date timestamp default NULL,
  event_end_date timestamp default NULL,
  event_recurring_end_date timestamp default NULL,
  event_colour text default NULL,
  primary key (item_id)
)
