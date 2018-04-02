<?php 
include("./header.php");

//============================bbbbbb
/*
-----修改mysql的root密码：
use mysql;
UPDATE user SET Password = PASSWORD('123') WHERE user = 'root';
FLUSH PRIVILEGES;

-----允许root账户从任何主机连接到本mysql
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY '123' WITH GRANT OPTION;
FLUSH   PRIVILEGES;

----创建数据库
CREATE database if not exists dbname CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
use dbname;
show variables like "%char%";
set names utf8;
SET character_set_client='utf8';
SET character_set_connection='utf8';
SET character_set_results='utf8';
SET character_set_server='utf8';

----建表
create table if not exists users(
	id int primary key auto_increment,
	username varchar(200) default '',
	password varchar(200) default '0',
	sid varchar(200) default '',
	company varchar(200) default '',
	realname varchar(200) default '',
	sid varchar(200) NOT NULL,
    Id_P int NOT NULL CHECK (Id_P>0),
    CONSTRAINT chk_Person CHECK (Id_P>0 AND City='Sandnes'),
    PRIMARY KEY (Id_P),
    UNIQUE (Id_P)
    FOREIGN KEY (Id_P) REFERENCES Persons(Id_P)
)engine=innodb default charset=utf8 auto_increment=1

CREATE VIEW view_name AS
SELECT column_name(s)
FROM table_name
WHERE condition

----增删改
drop database dbname;
drop table tablename;

----数据类型
整型都可用UNSIGNED修饰
TINYINT        1字节        (-128，127)          (0，255)            小整数值
SMALLINT       2字节     (-32 768，32 767)       (0，65 535)         大整数值
MEDIUMINT      3字节    (-8 388 608，8 388 607) (0，16 777 215)      大整数值
INT或INTEGER   4字节   (-2 147 483 648，2 147 483 647) (0，4 294 967 295) 大整数值
BIGINT         8字节   (-9 233 372 036 854 775 808，9 223 372 036 854 775 807) (0，18 446 744 073 709 551 615)
FLOAT          4字节
DOUBLE         8字节
DECIMAL 对DECIMAL(M,D) ，如果M>D，为M+2否则为D+2 依赖于M和D的值 依赖于M和D的值 小数值

CHAR(10)        0-255字节          定长字符串
VARCHAR(20)      0-255字节          变长字符串
TINYBLOB     0-255字节        不超过 255 个字符的二进制字符串
TINYTEXT     0-255字节        短文本字符串
BLOB         0-65535字节      二进制形式的长文本数据
TEXT         0-65535字节      长文本数据
MEDIUMBLOB   0-16 777 215字节 二进制形式的中等长度文本数据
MEDIUMTEXT   0-16 777 215字节 中等长度文本数据
LOGNGBLOB    0-4 294 967 295字节 二进制形式的极大文本数据
LONGTEXT     0-4 294 967 295字节 极大文本数据
VARBINARY(M)                   允许长度0-M个字节的定长字节符串，值的长度+1个字节
BINARY(M)    M                 允许长度0-M个字节的定长字节符串

DATE       4        1000-01-01/9999-12-31 YYYY-MM-DD    日期值
TIME       3        '-838:59:59'/'838:59:59' HH:MM:SS    时间值或持续时间
YEAR       1         1901/2155               YYYY       年份值
DATETIME   8       1000-01-01 00:00:00/9999-12-31 23:59:59 YYYY-MM-DD HH:MM:SS 混合日期和时间值
TIMESTAMP  4       1970-01-01 00:00:00/2037 年某时 YYYYMMDD HHMMSS 混合日期和时间值，时间戳

-----查
select * from tname where f=v and f=v order by id desc limit 1, 10;

SELECT DISTINCT Company FROM Orders
SELECT * FROM Persons WHERE FirstName='Thomas' AND LastName='Carter'
SELECT * FROM Persons WHERE firstname='Thomas' OR lastname='Carter'
SELECT Company, OrderNumber FROM Orders ORDER BY Company desc(asc)

SELECT * FROM Persons LIMIT 5

WHERE City LIKE 'N%'
- % 替代一个或多个字符
- _ 替代一个字符
- [charlist] 字符列中的任何单一字符
- [^charlist]或[!charlist]  不在字符列中的任何单一字符

WHERE column_name IN (value1,value2,...)
WHERE column_name BETWEEN value1 AND value2
WHERE Address IS NULL

SELECT po.OrderID, p.LastName, p.FirstName
    FROM Persons AS p, Product_Orders AS po
        WHERE p.LastName='Adams' AND p.FirstName='John'

----join：两个表必须有相同的列及其顺序，及其类型
SELECT Persons.LastName, Persons.FirstName, Orders.OrderNo
FROM Persons, Orders
WHERE Persons.Id_P = Orders.Id_P




 */


///------------------------------------
ptitle("使用PDO访问sqlite数据库");


///下面代码将会打开或者创建一个新的数据库文件，除非路径不存在
@unlink('/home/vagrant/Code/service/lesson/users.db');
$db = new PDO('sqlite:/home/vagrant/Code/service/lesson/users.db');// or die("打不开数据库");
//$db = new PDO('mysql: host=123.4.5.6;dbname=test_db;port=3306;charset=utf8','username','password');
/*
 $db = new PDO('mysql:host=localhost;dbname=test', $user, $pass, array(
    PDO::ATTR_PERSISTENT => true
));
 */

$sql = <<<EOT
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

EOT;

$sql = str_replace('engine=innodb default charset=utf8 auto_increment=1', '', $sql);
$sql = str_replace('id int primary key auto_increment', 'id INTEGER primary key autoincrement', $sql);

if($db->exec($sql)){
    pt("数据库创建成功！");
}else{
    pt($db->errorInfo());
}

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_ASSOC)");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    pt($row['name'] . ': ' . $row['signature']);
}
$stmt->closeCursor();

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_NUM)：下标从1开始算");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
    pt($row[3] . ': ' . $row[8]);
}
$stmt->closeCursor();

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_BOTH)");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
    pt($row[3] . ': ' . $row['signature']);
}
$stmt->closeCursor();

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_OBJ)：返回stdClass类的对象");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_OBJ)) {
    pt($row->name . ': ' . $row->signature);
}
$stmt->closeCursor();

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_LAZY)：返回PDORow类的对象");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_LAZY)) {
    pt($row->name . ': ' . $row->signature);
}
$stmt->closeCursor();

///------------------------------------
ptitle("查询--fetch(PDO::FETCH_BOUND)和bindColmn：绑定结果列");
///每次fetch，都会填充绑定的变量
///这里的bindColumn也可以绑定列号，但他妈的又是从0开始
$stmt = $db->prepare('select * from profile');
$stmt->execute();

$stmt->bindColumn('name', $name);
$stmt->bindColumn(7, $signature);

while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
    pt($name . ': ' . $signature);
}
$stmt->closeCursor();


///------------------------------------
ptitle("查询--fetchAll");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_BOTH);
$stmt->closeCursor();
foreach ($rows as $row) {
    pt($row[3] . ': ' . $row['signature']);
}

///------------------------------------
ptitle("查询--fetchAll：只要指定列");
$stmt = $db->prepare('select * from profile');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_COLUMN, 2);
$stmt->closeCursor();
foreach($rows as $k => $row){
    pt($k . '=>' . $row);
}

///------------------------------------
ptitle("update：问号占位符");
$stmt = $db->prepare('insert into profile (sid, name, signature) values(?, ?, ?)');
$stmt->execute(array('1002', '阿尔萨斯', "而你，终将加冕为王"));
$stmt->execute(array('1003', '萨格拉斯', "燃烧军团最高统帅"));

$stmt = $db->prepare('select * from profile');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_BOTH);
$stmt->closeCursor();
foreach ($rows as $row) {
    pt($row[2] . ': ' . $row['signature']);
}

///------------------------------------
ptitle("update：命名占位符");
$stmt = $db->prepare('insert into profile (sid, name, signature) values(:sid, :name, :signature)');
$stmt->execute(array('sid' =>'1004', 'name' => '希尔瓦娜斯', 'signature' => "部落大酋长，弓箭手"));
$stmt->execute(array('sid' =>'1005', 'name' => '麦迪文', 'signature' => "被萨格拉斯腐蚀，帮助兽人从德拉诺来到了艾泽拉斯"));

$sid = "1006";
$name = "卡德加";
$signature = "神棍无敌";
$stmt->bindParam(':sid', $sid);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':signature', $signature, PDO::PARAM_STR);  //NULL, BOOL, INT, STR, LOB大对象
$stmt->execute();

$stmt = $db->prepare('select * from profile');
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_BOTH);
$stmt->closeCursor();
foreach ($rows as $row) {
    pt($row[2] . ': ' . $row['signature']);
}

///------------------------------------
ptitle("bindParam的第三个参数PARAM_LOB：大对象（流）");
///LOB类型把参数处理成一个流，从而可以高效的将文件，URL的内容填入数据库
///bindParam(':content', $fp, PDO::PARAM_LOB)
//$fp = fopen(..);
//$st->execute();

///------------------------------------
ptitle("数据库错误捕捉：PDO#errorCode和errorInfo");
$stmt = $db->prepare('select ddd from profile');
if(!$stmt){
    pt($db->errorCode());
    pt($db->errorInfo());
}

$stmt = $db->prepare('select name from profileeeee');
if(!$stmt){
    pt($db->errorCode());
    pt($db->errorInfo());
}

///------------------------------------
ptitle("数据库错误捕捉：将数据库错误转换为异常");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try{
    $stmt = $db->prepare('select name from profileeeee');
}catch (Exception $e){
    pt("数据库出错：" . $e->getMessage());
}

///------------------------------------
ptitle("数据库错误捕捉：将数据库错误转换为warning，配合set_exception_handler()");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
function customError($errno, $errstr, $errfile, $errline)
{
    pt("<p style=\"border:1px solid red;color:red;\">");
    pt("----------------<b>自定义错误处理: </b><br /> [$errno] $errstr<br />");;
    pt(" Error on line $errline in $errfile<br />");;
    pt("----------------<br/>");;
    pt("</p>");;
    //die();
}

set_error_handler("customError");

$stmt = $db->prepare('select name from profileeeee');

///------------------------------------
ptitle("聚合函数：count");

$stmt = $db->prepare('select count(*) from profile');
$stmt->execute();
$rows = $stmt->fetch(PDO::FETCH_NUM);
$stmt->closeCursor();
pt('一共' . $rows[0][0] . '行');

////=========================关闭
$db = null;


///------------------------------------
ptitle("DBM数据库");
//$dbh = dba_open(__DIR__ . "/fish.db", "c", "db4") or die($php_errormsg);



//============================oooooo
include("./footer.php");
footer(str_replace(PATH, "", $_SERVER['SCRIPT_NAME']));
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	