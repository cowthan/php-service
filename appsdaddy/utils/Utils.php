<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/14
 * Time: 17:24
 */

require_once(__DIR__ . "/../config.php");
require BASE_PATH . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;


class View
{
    const VIEW_PATH = [BASE_PATH.'/views'];
    const CACHE_PATH = BASE_PATH.'/cache';
    private static function getView(){
        $compiler = new \Xiaoler\Blade\Compilers\BladeCompiler(self::CACHE_PATH);
        $engine = new \Xiaoler\Blade\Engines\CompilerEngine($compiler);
        $finder = new \Xiaoler\Blade\FileViewFinder(self::VIEW_PATH);
        $factory = new \Xiaoler\Blade\Factory($engine,$finder);
        return $factory;
    }

    public static function views($view, $data = []){
        $data['assets'] = URL::AssetsBase();
        $data['base'] = BASE_URL;
        $ouput = self::getView()->make($view, $data)->render();
        return $ouput;
    }
}

class MyRequest{

    function __construct(){

    }

    function method(){
        return strtolower($_SERVER['REQUEST_METHOD']);

    }

    function isMethod($m){
        return $this->method() == $m;
    }

    function input($name, $default = ''){
        if(isset($_GET[$name])){
            return $_GET[$name];
        }else if(isset($_POST[$name])){
            return $_POST[$name];
        }else if(isset($_REQUEST[$name])){
            return $_REQUEST[$name];
        }else{
            return $default;
        }
    }

    function has($name){
        return isset($_GET[$name]) || isset($_POST[$name]) || isset($_REQUEST[$name]);
    }

}

class URL{
    public static function AssetsBase(){
        return ASSETS_PATH;
    }
}

class Json{

}

class DB{
    public static function init(){
        global $config;
        $capsule = new Capsule();
        $capsule->addConnection($config['connections']['mysql']); // 创建链接
        $capsule->setAsGlobal();  // 设置全局静态可访问
        $capsule->bootEloquent(); // 启动Eloquent

    }
}

class Pattern{
    const PATTERN_EMAIL = '/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i';
    const PATTERN_NAME = '/^[a-zA-Z0-9_]$/';
    const PATTERN_PHONE = '/^1[34578]{1}\d{9}$/';

}