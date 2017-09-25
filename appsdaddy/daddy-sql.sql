-----修改mysql的root密码：
use mysql;
UPDATE user SET Password = PASSWORD('123') WHERE user = 'root';
FLUSH PRIVILEGES;

-----允许root账户从任何主机连接到本mysql
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '123' WITH GRANT OPTION;
FLUSH   PRIVILEGES;



----------appsdaddy
CREATE database if not exists appsdaddy CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
use appsdaddy;

show variables like "%char%";
set names utf8;
SET character_set_client='utf8';
SET character_set_connection='utf8';
SET character_set_results='utf8';
SET character_set_server='utf8';

create table projects(
  id int primary key auto_increment,
  name varchar(200) default '',
  appkey varchar(200) default '',
  logo varchar(200) default ''
)engine=innodb default charset=utf8 auto_increment=1;

insert into projects (name, appkey, logo) values('海淘猎人','99489527','');
insert into projects (name, appkey, logo) values('神回复','22499527','');


create table profile(
    id int primary key auto_increment,
	sid varchar(200) default '',
	name varchar(200) default '',
	nickname varchar(200) default '',
	gender varchar(200) default '',
	age int default 0,
	job varchar(200) default '',
	protrait varchar(200) default '',
	signature varchar(200) default '',
	birth int default 0,
	addr varchar(200) default '',
	create_at varchar(200) default 0,
	update_at varchar(200) default 0,
	delete_at varchar(200) default 0,
	status int default 0,
	isActive int default 0,
	role varchar(50) default ''
)engine=innodb default charset=utf8 auto_increment=1;

create table local_auth(
    id int primary key auto_increment,
	userId int not null,
	username varchar(200) default '',
	password varchar(200) default ''
)engine=innodb default charset=utf8 auto_increment=1;

create table oauth(
    id int primary key auto_increment,
	userId int not null,
	oauthId varchar(200) default '',
	oauthName varchar(200) default '',
	oauthAccessToken varchar(200) default '',
	oauthExpires int default 0
)engine=innodb default charset=utf8 auto_increment=1;

create table api_auth(
    id int primary key auto_increment,
	userId int not null,
	apiKey varchar(200) default '',
	apiSecret varchar(200) default ''
)engine=innodb default charset=utf8 auto_increment=1;

insert into profile (sid, name,  nickname,gender,age,job,protrait,signature,birth,addr,create_at,update_at,status,isActive, role) values('1001', '伊利丹',  '圣光之子', '男', 20000, '恶魔猎手统帅', 'https://ss2.baidu.com/6ONYsjip0QIZ8tyhnq/it/u=1805120669,1087242424&fm=58', '你们这是自寻死路', 0, '扭曲虚空', '0', '0', 0, 0, 'user');
insert into profile (sid, name,  nickname,gender,age,job,protrait,signature,birth,addr,create_at,update_at,status,isActive, role) values('1000', '瓦里安',  '暴风国王', '男', 49, '联盟最高统帅', 'https://imgsa.baidu.com/baike/c0%3Dbaike116%2C5%2C5%2C116%2C38/sign=d4e174f75f2c11dfcadcb771024e09b5/d6ca7bcb0a46f21f0831ed47fe246b600c33ae99.jpg', '我今天可能死在这...', 0, '暴风城皇家堡垒中央大厅', '0', '0', 0, 0, 'user');


create table if not exists h5_demos(
	id int primary key auto_increment,
	ownerId varchar(200) default '',
	demoName varchar(200) default '',
	createTime varchar(200) default '',
	updateTime varchar(200) default '',
	demoImage varchar(200) default '',
	h5Code text,
	cssCode text,
	jsCode text,
	meta text
)engine=innodb default charset=utf8 auto_increment=1;

create table if not exists admins(
	id int primary key auto_increment,
	username varchar(200) default '',
	password varchar(200) default '0',
	sid varchar(200) default '',
	company varchar(200) default '',
	realname varchar(200) default '',
	extra1 varchar(200) default '',
	extra2 varchar(200) default '',
	extra3 varchar(200) default '',
	extra4 varchar(200) default ''
)engine=innodb default charset=utf8 auto_increment=1;

insert into admins (username, password, sid, company, realname) values('jack-daddy','99990529138','112233445544332211','最高管理员','Jack');
insert into admins (username, password, sid, company, realname) values('admin1','99990529138','112233445544332212','普通管理员','Jack');
insert into admins (username, password, sid, company, realname) values('admin2','99990529138','112233445544332213','普通管理员','Jack');

update profile set name='瓦里安.乌瑞恩' where id=1;
delete from profile where id=2;

create table timelines(
    id int primary key auto_increment,
	userId int not null,
	title varchar(200) default '',
	source varchar(200) default '',
	content text,
	picBigs text,
	picThumbs text,
	picMiddles text,
	create_at varchar(200) default '0'
)engine=innodb default charset=utf8 auto_increment=1;


insert into timelines (userId, title, source, content, picBigs) values(1,'测试怎么发朋友圈，或者微博，或者其他任何timeline','来自微博 某大V','今天天气真他妈的好，我出去转了一圈，买了一包烟，拿了一个快递，买了三包方便面，操，真难吃','[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525644696&di=9954d827dfd742cb47a487870bf01e18&imgtype=0&src=http%3A%2F%2Fimg.ycwb.com%2Fnews%2Fattachement%2Fjpg%2Fsite2%2F20130701%2F90fba6018719133b7e6649.jpg,https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525685797&di=eeb3b943e77fc41df306609bd8ddfa90&imgtype=0&src=http%3A%2F%2Fwww.sznews.com%2Fhome%2Fimages%2Fattachement%2Fjpg%2Fsite3%2F20160815%2FIMG7427ea33bc7442122916830.jpg]');
insert into timelines (userId, title, source, content, picBigs) values(1,'测试怎么发朋友圈，或者微博，或者其他任何timeline','来自微博 某大V','今天天气真他妈的好，我出去转了一圈，买了一包烟，拿了一个快递，买了三包方便面，操，真难吃','[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525644696&di=9954d827dfd742cb47a487870bf01e18&imgtype=0&src=http%3A%2F%2Fimg.ycwb.com%2Fnews%2Fattachement%2Fjpg%2Fsite2%2F20130701%2F90fba6018719133b7e6649.jpg,https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525685797&di=eeb3b943e77fc41df306609bd8ddfa90&imgtype=0&src=http%3A%2F%2Fwww.sznews.com%2Fhome%2Fimages%2Fattachement%2Fjpg%2Fsite3%2F20160815%2FIMG7427ea33bc7442122916830.jpg]');


create table loghttp(
  id int primary key auto_increment,
	title varchar(200) default '',
	source varchar(200) default '',
	content text,
	picBigs text,
	picThumbs text,
	picMiddles text,
	create_at varchar(200) default '0'
)engine=innodb default charset=utf8 auto_increment=1;


insert into timelines (userId, title, source, content, picBigs) values(1,'测试怎么发朋友圈，或者微博，或者其他任何timeline','来自微博 某大V','今天天气真他妈的好，我出去转了一圈，买了一包烟，拿了一个快递，买了三包方便面，操，真难吃','[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525644696&di=9954d827dfd742cb47a487870bf01e18&imgtype=0&src=http%3A%2F%2Fimg.ycwb.com%2Fnews%2Fattachement%2Fjpg%2Fsite2%2F20130701%2F90fba6018719133b7e6649.jpg,https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525685797&di=eeb3b943e77fc41df306609bd8ddfa90&imgtype=0&src=http%3A%2F%2Fwww.sznews.com%2Fhome%2Fimages%2Fattachement%2Fjpg%2Fsite3%2F20160815%2FIMG7427ea33bc7442122916830.jpg]');
insert into timelines (userId, title, source, content, picBigs) values(1,'测试怎么发朋友圈，或者微博，或者其他任何timeline','来自微博 某大V','今天天气真他妈的好，我出去转了一圈，买了一包烟，拿了一个快递，买了三包方便面，操，真难吃','[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525644696&di=9954d827dfd742cb47a487870bf01e18&imgtype=0&src=http%3A%2F%2Fimg.ycwb.com%2Fnews%2Fattachement%2Fjpg%2Fsite2%2F20130701%2F90fba6018719133b7e6649.jpg,https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1489525685797&di=eeb3b943e77fc41df306609bd8ddfa90&imgtype=0&src=http%3A%2F%2Fwww.sznews.com%2Fhome%2Fimages%2Fattachement%2Fjpg%2Fsite3%2F20160815%2FIMG7427ea33bc7442122916830.jpg]');



