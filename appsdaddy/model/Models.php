<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/14
 * Time: 22:41
 */

include __DIR__ . '/../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;


class Admin extends Model
{
    protected $table = 'admins';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}

class Profile extends Model
{
    protected $table = 'profile';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}

class LocalAuth extends Model
{
    protected $table = 'local_auth';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}

class OAuth extends Model
{
    protected $table = 'oauth';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}


class ApiAuth extends Model
{
    protected $table = 'api_auth';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}

class H5Demo extends Model
{
    protected $table = 'h5_demos';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}

class Timeline extends Model
{
    protected $table = 'timelines';  ///表名默认是user
    protected $primaryKey = 'id'; //默认主键就是id
    //protected $connction = '数据库名'; //如果要连接非默认的数据库，可以指定这个成员
    public $timestamps = false; ///默认会在表中维护update_at和create_at，设置为false表示不使用这两个字段
    protected $guarded = array('*'); ///所有字段都禁止集体赋值，集体赋值允许往模型的构造函数传入一个数组给属性赋值
    //protected $fillable = array('first_name', 'last_name', 'email'); ///guarded的反义词
    protected $softDelete = false;
}