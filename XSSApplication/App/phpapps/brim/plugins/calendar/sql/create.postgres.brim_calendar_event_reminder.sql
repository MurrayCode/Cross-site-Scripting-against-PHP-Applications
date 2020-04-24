CREATE TABLE brim_calendar_event_reminder (
  item_id serial not null,
  owner char(70) NOT NULL default '',
  parent_id integer NOT NULL default '0',
  is_parent integer default 0,
  name varchar(70) NOT NULL default '',
  description text,
  visibility char(15) default NULL,
  category char(50) default NULL,
  is_deleted integer default 0,
  when_created timestamp,
  when_modified timestamp,
  event_id integer NOT NULL,
  timespan char(1) default 'm',
  reminder_time integer default 0,
  when_sent timestamp,
  primary key (item_id)
)
