create table brim_passwords (
	item_id serial not null,
	owner   char (70) not null,
	parent_id integer not null,
	is_parent integer default 0,
	name text not null,
	description bytea,
	visibility char (15),
	category char (50),
	is_deleted integer default 0,
	when_created timestamp,
	when_modified timestamp,
	login bytea null,
	password bytea not null,
	url bytea not null,
	primary key (item_id)
)
