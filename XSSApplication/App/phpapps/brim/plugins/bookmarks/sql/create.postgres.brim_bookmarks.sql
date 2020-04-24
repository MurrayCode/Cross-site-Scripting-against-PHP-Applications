create table brim_bookmarks (
	item_id serial not null,
	owner   char (70) not null,
	parent_id integer not null,
	is_parent integer default 0,
	name text not null,
	description text,
	visibility char (15),
	category char (50),
	is_deleted integer default 0,
	when_created timestamp,
	when_modified timestamp,
	when_visited timestamp,
	locator text,
	visit_count int,
	favicon text,
	primary key (item_id)
)
