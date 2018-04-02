<?php
define('BASE_PATH', __DIR__);
require BASE_PATH.'/vendor/autoload.php';

class View
{
    const VIEW_PATH = [BASE_PATH.'/views'];
    const CACHE_PATH = BASE_PATH.'/cache';
    public static function getView(){
        $compiler = new \Xiaoler\Blade\Compilers\BladeCompiler(self::CACHE_PATH);
        $engine = new \Xiaoler\Blade\Engines\CompilerEngine($compiler);
        $finder = new \Xiaoler\Blade\FileViewFinder(self::VIEW_PATH);
        $factory = new \Xiaoler\Blade\Factory($engine,$finder);
        return $factory;
    }
}

$ouput = View::getView()->make('welcome', [
    'a' => 'success!',
    'ahhh' => '<span style="color:red">success!</span>',
    'x' => 2,
    'users' => array("mechal", "Jack", "Jel;y"),
    'users2' => array(),
])->render();

echo $ouput;


/*

blade模板

<!-- Stored in resources/views/layouts/master.blade.php -->
<html>
    <head>
        <title>App Name - @yield('title')</title>
    </head>
    <body>
        @section('sidebar')
            This is the master sidebar.
        @show

        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
@section 指令正像其名字所暗示的一样是用来定义一个视图片断（section）的
@yield 指令是用来展示某个指定 section 所代表的内容的

<!-- Stored in resources/views/child.blade.php -->

@extends('layouts.master')

@section('title', 'Page Title')

@section('sidebar')
    @@parent

    <p>This is appended to the master sidebar.</p>
@endsection

@section('content')
    <p>This is my body content.</p>
@endsection


引入子视图
@include 指令允许你方便地在一个视图中引入另一个视图。所有父视图中可用的变量也都可以在被引入的子视图中使用。
<div>
    @include('shared.errors')

    <form>
        <!-- Form Contents -->
    </form>
</div>

还可以向子视图传递数据
@include('view.name', ['some' => 'data'])

loop和include结合 = each
@each('view.name', $jobs, 'job')
@each('view.name', $jobs, 'job', 'view.empty')
参数4是如果$jobs数组为空时加载的视图
相当于：
foreach($jobs as $job){
    include "view.name并传入$job";
}

 */