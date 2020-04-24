create table brim_users (
	user_id 		serial ,
	loginname 		char (15) 		not null,
	password 		char (50),
	name 			char (50),
	email 			char (30),
	description 	text,
	when_created 	timestamp,
	last_login 		timestamp,
	primary key (user_id)
)
