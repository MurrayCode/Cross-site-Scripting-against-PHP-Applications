create table brim_plugin_settings (
	item_id integer auto_increment not null primary key,
	owner   char (70) not null,
	parent_id integer not null,
	is_parent integer default 0,
	name text not null,
	description text,
	visibility char (15),
	category char (50),
	is_deleted integer default 0,
	when_created DATETIME,
	when_modified DATETIME,
	value char (50),
	key (item_id)
)
