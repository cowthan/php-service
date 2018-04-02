<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29
 * Time: 1:49
 */

require_once(__DIR__ . "/../config.php");
require BASE_PATH . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

require_once(__DIR__ . "/../utils/Utils.php");
require_once(__DIR__ . "/../model/Models.php");


class HomeController
{
    public static function checkLogin(MyRequest $request){
        if($request->has("sid")){
            $admin = Admin::query()->where("sid", "=", $request->input("sid"))->first();
            if($admin == null){
                return jsonFail(402, "sid无效");
            }else{
                return true;
            }
        }else{
            return jsonFail(401, "需要登录");
        }
    }

    public static function getAdminHome(MyRequest $request){

        $c = HomeController::checkLogin($request);
        if($c !== true){
            return $c;
        }

        return View::views("admin.index", array(
            'userCount' => 100,
            'adminCount' => 100,
            'taskCount' => 100,
            'todayTaskCount' => 100,
            'sid' => $request->input("sid"),
        ));
    }

}