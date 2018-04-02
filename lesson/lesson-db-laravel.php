<?php
include("./header.php");
//============================bbbbbb

///------------------------------------
//$database = [
//    'driver'    => 'mysql',
//    'host'      => 'localhost',
//    'database'  => 'account',
//    'username'  => 'homestead',
//    'password'  => 'secret',
//    'charset'   => 'utf8',
//    'collation' => 'utf8_unicode_ci',
//    'prefix'    => '',
//];

$database = [
    'driver' => 'sqlite',
    'database' => './users.db',
    'prefix' => '',
];

$config = array(
    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */
    'fetch' => PDO::FETCH_CLASS,
    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */
    'default' => 'sqlite',
    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */
    'connections' => array(
        'sqlite' => array(
            'driver' => 'sqlite',
            'database' => __DIR__ . '/./users.db',
            'prefix' => 'l4_',
        ),
        'mysql' => array(
            'driver' => 'mysql',
            'host' => 'localhost',
            'database' => 'laravel-4.1-simple-blog',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
         // 'collation' => 'utf8_unicode_ci',
            'collation' => 'utf8_general_ci',
            'prefix' => 'l4_',
        ),
        'pgsql' => array(
            'driver' => 'pgsql',
            'host' => 'localhost',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
        ),
        'sqlsrv' => array(
            'driver' => 'sqlsrv',
            'host' => 'localhost',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'prefix' => '',
        ),
    ),
    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */
    'migrations' => 'migrations',
    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */
    'redis' => array(
        'cluster' => false,
        'default' => array(
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ),
    ),
);

///------------------------------------
ptitle("Eloquent不会自动创建sqlite数据库文件");

///------------------------------------
ptitle("初始化");

//@unlink('/home/vagrant/Code/service/lesson/users.db');

include __DIR__ . '/vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

$capsule = new Capsule();

// 创建链接
$capsule->addConnection($database);

// 设置全局静态可访问
$capsule->setAsGlobal();

// 启动Eloquent
$capsule->bootEloquent();

///------------------------------------
ptitle("建表");
//http://laravelacademy.org/post/6964.html
Capsule::schema()->drop('users'); //statement('drop table users');
Capsule::schema()->create('users', function($table)
{
    $table->increments('id');
    $table->string('username', 40);
    $table->string('email')->unique();
    //$table->timestamps();///这个会在表中维护update_at和create_at
});

class User extends  Model
{
    protected $table = 'users';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词

}

///------------------------------------
ptitle("insert");
//原生方法
Capsule::table('users')->insert(array(
    array('username' => 'Hello',  'email' => 'hello@world.com'),
    array('username' => 'Carlos',  'email' => 'anzhengchao@gmail.com'),
    array('username' => 'Overtrue',  'email' => 'i@overtrue.me'),
));

//Facade方法

//orm方法
$user = new User();
$user->username = "cowthan";
$user->email = "cowthan@163.com";
$user->save();


//$result = Capsule::table('users')->selectRaw('*')->where('id', '>=', 1)->where('id', '<=', 3)->orderBy('id', 'desc')->get();
//foreach ($result as $row) {
//    pt($row);
//}

$users = User::all();
foreach ($users as $user) {
    pt($user);
}

///------------------------------------
ptitle("update");
//Capsule::table('users')->update(array(
//    array('username' => 'Hello',  'email' => 'hello@world.com'),
//    array('id = 1'),
//));
$user = User::find(1);
if($user != null){
    $user->username = '王二-111';
    $user->save();
}

//$affectedRows = User::where('id', '>', 1)->update(array('status' => 2));

$users = User::all();
foreach ($users as $user) {
    pt($user);
}



///--------------------------------------
ptitle("select：查询所有--User::all()");
$users = User::all();  //获取所有记录
foreach ($users as $user) {
    pt($user);
}

///--------------------------------------
ptitle("select：根据主键查询--User::find(int id)");
$user = User::find(6); //根据主键获取一条记录
if($user != null){
    pt($user);
}else{
    pt("查询结果为null");
}

///--------------------------------------
ptitle("select：根据主键查询，并抛出异常--User::findOrFail(int id)");
try{
    $user = User::findOrFail(6);
}catch (Exception $e){
    pt($e->getMessage());
}

///--------------------------------------
ptitle("select：拼查询");
$users = User::where('id', '>', 1)->take(2)->get();
foreach ($users as $user) {
    pt($user);
}

///--------------------------------------
ptitle("select：拼查询--原生方式");
$users = User::whereRaw('id > ? and username is not null', array(1))->get();
foreach ($users as $user) {
    pt($user);
}

///--------------------------------------
ptitle("select：聚合函数");
$count = User::where('id', '>', 0)->count();
pt("user行数--" . $count);



/*

---------------Laravel中数据库的Facade

DB::insert('insert into users (id, name, email, password) values (?, ?, ? , ? )', [1, 'Laravel','laravel@test.com','123']);
$affected = DB::update('update users set name="LaravelAcademy" where name = ?', ['Academy']);
$deleted = DB::delete('delete from users');
DB::statement('drop table users');
$users = DB::table('users')
    ->select(DB::raw('count(*) as user_count, status'))
    ->where('status', '<>', 1)
    ->groupBy('status')
    ->get();


其他：
1 处理处理的行数过多，可以使用chunk
User::chunk(200, function($users)
{
    foreach ($users as $user)
    {
        //
    }
});

2 可以随意更改连接哪个数据库
$user = User::on('connection-name')->find(1);

3 删除
$user->delete();
User::destroy(1);
User::destroy(array(1, 2, 3));
User::destroy(1, 2, 3);
$affectedRows = User::where('votes', '>', 100)->delete();

4 软删除
class User extends Eloquent {

    protected $softDelete = true;  //当软删除一个模型，它并没有真的从数据库中删除。相反，一个 deleted_at 时间戳在记录中被设置。为一个模型开启软删除，在模型中指定 softDelete 属性

}

在迁移中创建表时，要：$table->softDeletes();
强制软删除的模型到结果集中：$users = User::withTrashed()->where('account_id', 1)->get();
结果集中只包含软删除的模型：$users = User::onlyTrashed()->where('account_id', 1)->get();
恢复一个已被软删除的记录：$user->restore();
User::withTrashed()->where('account_id', 1)->restore();
$user->posts()->restore();

if ($user->trashed())
{
    //这是一条被软删除的记录
}

真正的删除：
$user->forceDelete();
$user->posts()->forceDelete();


5 时间戳

默认情况下，Eloquent 在数据的表中自动地将维护 created_at 和 updated_at 字段

禁用时间戳
class User extends Eloquent {
    public $timestamps = false;
}

定制时间戳格式：
class User extends Eloquent {
    protected function getDateFormat(){
        return 'U';
    }
}




*/
//============================oooooo
include("./footer.php");
footer(str_replace(PATH, "", $_SERVER['SCRIPT_NAME']));
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	