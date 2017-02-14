drop database if exists silex_test_users;
create database silex_test_users;

use silex_test_users;

drop table if exists users;
create table users (
	id int unsigned not null primary key auto_increment,
	email varchar(50) not null,
	pass char(32) not null
)engine=innodb;

insert into users(email,pass) values("user@host.com", md5("123456")),
	("user1@host.com", md5("123456")),
	("user2@host.com", md5("123456")),
	("user3@host.com", md5("123456"));