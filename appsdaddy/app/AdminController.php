<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/29
 * Time: 1:09
 */

require_once(__DIR__ . "/../config.php");
require BASE_PATH . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;

require_once(__DIR__ . "/../utils/Utils.php");
require_once(__DIR__ . "/../model/Models.php");

class AdminController{

    public static function getAdminLogin($request){
        return View::views('admin.login', []);
    }
    public static function postAdminLogin($request){
        $notify = '必须填入用户名'; if(Utils::validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入密码'; if(Utils::validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);

        $admin = Admin::query()
            ->where("username", "=", $request->input("username"))
            ->where("password", "=", $request->input('password'))
            ->first();
        if($admin != null){
            return jsonOk(array(
                'sid' => $admin->sid,
            ));
        }else{
            return jsonFail(400, "用户名或密码错误了");
        }
    }
    public static function deleteAdmin($request){
        $notify = '必须填入sid'; if(Utils::validateRequire($request, 'sid', $notify) !== true) return jsonFail(400, $notify);

        $admin = Admin::query()->where("sid", "=", $request->input('sid'))->first();
        if($admin != null){
            $admin->delete();
            return jsonOk(array());
        }else{
            return jsonFail(400, "不存在此用户--" . $request->input('sid'));
        }
    }

    public static function updateAdminPassword($request){
        $notify = '必须填入sid'; if(Utils::validateRequire($request, 'sid', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入原密码'; if(Utils::validateRequire($request, 'oldPassword', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入新密码'; if(Utils::validateRequire($request, 'newPassword', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入新密码2'; if(Utils::validateRequire($request, 'newPassword2', $notify) !== true) return jsonFail(400, $notify);

        if($request->input('newPassword') != $request->input('newPassword2')) return jsonFail(400, "两次密码不一致");
        if($request->input('oldPassword') == $request->input('newPassword')) return jsonFail(400, "新旧密码怎么还一样呢");

        $notify = '密码必须大于6位'; if(Utils::validateLengthMin($request, 'newPassword', 6, $notify) !== true) return jsonFail(400, $notify);

        $admin = Admin::query()->where("sid", "=", $request->input('sid'))->first();
        if($admin != null){
            $admin->password = $request->input('newPassword');
            $admin->updated();
            return jsonOk(array());
        }else{
            return jsonFail(400, "不存在此用户--" . $request->input('sid'));
        }
    }

    public static function postAdminRegist(MyRequest $request){
        $notify = '必须填入用户名'; if(Utils::validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入密码'; if(Utils::validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入姓名'; if(Utils::validateRequire($request, 'realname', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入公司'; if(Utils::validateRequire($request, 'company', $notify) !== true) return jsonFail(400, $notify);

        $notify = '用户名必须是手机号'; if(Utils::validatePattern($request, 'username', PATTERN::PATTERN_PHONE, $notify) !== true) return jsonFail(400, $notify);
        $notify = '密码必须大于6位'; if(Utils::validateLengthMin($request, 'password', 6, $notify) !== true) return jsonFail(400, $notify);
        $notify = '姓名必须大于是1到32位长度'; if(Utils::validateLength($request, 'password', 1, 32, $notify) !== true) return jsonFail(400, $notify);

        //username是否重复
        $admin = Admin::query()->where("username", "=", $request->input("username"))->get();
        if($admin != null && count($admin) > 0){
            return jsonFail(400, "用户已经存在");
        }

        $admin = new Admin();
        $admin->username = $request->input('username');
        $admin->password = $request->input('password');
        $admin->sid = md5(uniqid());
        $admin->company = $request->input('company');
        $admin->realname = $request->input('realname');
        $admin->save();
        return jsonOk([
            "id" => $admin->id,
            "sid" => $admin->sid,
        ]);
    }



}