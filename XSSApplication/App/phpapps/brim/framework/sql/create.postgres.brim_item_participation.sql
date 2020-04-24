CREATE TABLE brim_item_participation 
(
	item_id serial,
	owner   char (15) not null,
	participator   char (15) not null,
	plugin   char (15) not null,
	participation_rights char (2),
        activation_code char (50) not null default '',
	primary key (item_id, owner, participator, plugin) 
)
