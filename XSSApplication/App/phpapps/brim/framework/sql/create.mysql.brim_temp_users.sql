create table brim_temp_users (
	user_id integer auto_increment not null primary key,
	loginname   char (15) not null,
	password char (50),
	name char (50),
	email char (30),
	description text,
	when_created DATETIME,
	last_login DATETIME,
	temp_password char (50)
)
