create database oneline_bbs;
use oneline_bbs;
create table post (
     id int auto_increment primary key,
     name varchar(60),
     comment varchar(60),
     created_at timestamp
     );
select * from post;


create table user (
     id int auto_increment primary key,
     name varchar(60),
     password varchar(60)
     );