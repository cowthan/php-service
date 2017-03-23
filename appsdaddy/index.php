<?php
require_once("./utils/Utils.php");
require_once("./model/Models.php");
use Illuminate\Database\Capsule\Manager as Capsule;

$route = [
    'get:admin/login' => 'getAdminLogin',    //http://cowthan.com:8089/service/appsdaddy/index.php?r=admin/login.json
    'post:admin/login' => 'postAdminLogin',
    'delete:admin/delete' => 'deleteAdmin',
    'put:admin/updatePassword' => 'updateAdminPassword',
    'post:admin/regist' => 'postAdminRegist', //http://cowthan.com:8089/service/appsdaddy/index.php?r=admin/regist.json
	
	'post:user/login' => 'postUserLogin',  ///username password way:local和oauth, oauthId, oauthName, oauthToken
	'post:user/regist' => 'postUserLocalRegist',
	
	'get:admin/home' => 'getAdminHome',
	
	'get:timeline/list' => 'getTimelineList',
];

//--------------------------------------------
//支持的url：http://domain:port/service/appsdaddy/index.php?r=user/login.json    //.xml   .html
//--------------------------------------------
function dispatch(){
    global $route;
    $request = new MyRequest();
    $method = strtolower($_SERVER['REQUEST_METHOD']);  //get, post, put, delete
    //echo $_SERVER['REQUEST_URI'];  ////service/appsdaddy/index.php?r=login
    if(isset($_GET['r'])){
        $r = $_GET['r'];
        $rs = explode(".", $r);
        $action = $method . ":" . $rs[0];
        if(isset($route[$action])){
            $func = $route[$action];
            try{
                echo $func($request);
            }catch (Exception $e){
                if(isAcccept('json')){
                    echo json([
                        'code' => 500,
                        'msg' => $e->getMessage(),
                    ]);
                }else{
                    echo View::views('errors.500', ['reason' => $e->getMessage()]);
                }
            }
        }else{
            if(isAcccept('json')){
                echo json([
                    'code' => 404,
                    'msg' => '路由中没有注册' . $action,
                ]);
            }else{
                echo View::views('errors.404', ['reason' => '路由中没有注册' . $action]);
            }

        }
    }else{
        if(isAcccept('json')){
            echo json([
                'code' => 404,
                'msg' => '没有在url中指定r值',
            ]);
        }else{
            echo View::views('errors.404', ['reason' => '没有在url中指定r值']);
        }

    }
}



function isAcccept($f){
    $r = $_GET['r'];
    $format = 'html';
    $rs = explode(".", $r);
    if(isset($rs[1])){
        $format = $rs[1];
    }
    return $format == $f;
}

function json($obj){
    return json_encode($obj);
}

function jsonFail($code, $msg){
    return json_encode(array('code' => $code, 'msg' => $msg));
}

function jsonOk($res){
    return json_encode(array('code' => 200, 'result' => $res));
}

DB::init();
dispatch();

///-----------------------------------------------------------------------------------------
///validate相关
///-----------------------------------------------------------------------------------------
function validateRequire(MyRequest $request, $name, $notify){
    if(!$request->has($name) || $request->input($name) == ''){
        return $notify;
    }else{
        return true;
    }
}

function validateLength(MyRequest $request, $name, $min_len, $max_len, $notify){
    if(strlen($request->input($name)) < $min_len || strlen($request->input($name)) > $max_len){
        return $notify;
    }else{
        return true;
    }
}

function validateLengthMin(MyRequest $request, $name, $min_len, $notify){
    if(strlen($request->input($name)) < $min_len ){
        return $notify;
    }else{
        return true;
    }
}

function validateLengthMax(MyRequest $request, $name, $max_len, $notify){
    if(strlen($request->input($name)) > $max_len){
        return $notify;
    }else{
        return true;
    }
}

function validateNumeric(MyRequest $request, $name, $notify){
    if(!is_numeric($request->input($name))){
        return $notify;
    }else{
        return true;
    }
}

function validateNumericRange(MyRequest $request, $name, $min, $max, $notify){
    if(!is_numeric($request->input($name)) || $request->input($name) < $min || $request->input($name) > $max){
        return $notify;
    }else{
        return true;
    }
}




function validatePattern(MyRequest $request, $name, $pattern, $notify){
    if(!preg_match($pattern, $request->input($name))){
        return $notify;
    }else{
        return true;
    }
}
///-----------------------------------------------------------------------------------------
///下面是所有Controller---admin
///-----------------------------------------------------------------------------------------
function getAdminLogin($request){
    return View::views('admin.login', []);
}
function postAdminLogin($request){
    $notify = '必须填入用户名'; if(validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入密码'; if(validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);

    $admin = Admin::query()
            ->where("username", "=", $request->input("username"))
            ->where("password", "=", $request->input('password'))
            ->first();
    if($admin != null){
        return jsonOk(array(
            'sid' => $admin->sid,
        ));
    }else{
        return jsonFail(400, "用户名或密码错误");
    }
}
function deleteAdmin($request){
    $notify = '必须填入sid'; if(validateRequire($request, 'sid', $notify) !== true) return jsonFail(400, $notify);

    $admin = Admin::query()->where("sid", "=", $request->input('sid'))->first();
    if($admin != null){
        $admin->delete();
        return jsonOk(array());
    }else{
        return jsonFail(400, "不存在此用户--" . $request->input('sid'));
    }
}

function updateAdminPassword($request){
    $notify = '必须填入sid'; if(validateRequire($request, 'sid', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入原密码'; if(validateRequire($request, 'oldPassword', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入新密码'; if(validateRequire($request, 'newPassword', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入新密码2'; if(validateRequire($request, 'newPassword2', $notify) !== true) return jsonFail(400, $notify);

    if($request->input('newPassword') != $request->input('newPassword2')) return jsonFail(400, "两次密码不一致");
    if($request->input('oldPassword') == $request->input('newPassword')) return jsonFail(400, "新旧密码怎么还一样呢");

    $notify = '密码必须大于6位'; if(validateLengthMin($request, 'newPassword', 6, $notify) !== true) return jsonFail(400, $notify);

    $admin = Admin::query()->where("sid", "=", $request->input('sid'))->first();
    if($admin != null){
        $admin->password = $request->input('newPassword');
        $admin->updated();
        return jsonOk(array());
    }else{
        return jsonFail(400, "不存在此用户--" . $request->input('sid'));
    }
}

function postAdminRegist(MyRequest $request){
    $notify = '必须填入用户名'; if(validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入密码'; if(validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入姓名'; if(validateRequire($request, 'realname', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入公司'; if(validateRequire($request, 'company', $notify) !== true) return jsonFail(400, $notify);

    $notify = '用户名必须是手机号'; if(validatePattern($request, 'username', PATTERN::PATTERN_PHONE, $notify) !== true) return jsonFail(400, $notify);
    $notify = '密码必须大于6位'; if(validateLengthMin($request, 'password', 6, $notify) !== true) return jsonFail(400, $notify);
    $notify = '姓名必须大于是1到32位长度'; if(validateLength($request, 'password', 1, 32, $notify) !== true) return jsonFail(400, $notify);

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


///-----------------------------------------------------------------------------------------
///下面是所有Controller---user
///-----------------------------------------------------------------------------------------

function postUserLogin($request){
    $notify = '必须指明登录方式'; if(validateRequire($request, 'way', $notify) !== true) return jsonFail(400, $notify); //way:local, oauth

    if($request->input('way') === 'local'){

        $notify = '必须填入用户名'; if(validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
        $notify = '必须填入密码'; if(validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);

        $auth = LocalAuth::query()
            ->where("username", "=", $request->input("username"))
            ->where("password", "=", $request->input('password'))
            ->first();
        if($auth != null){
            $profile = Profile::find($auth->user_id);
            if($profile != null){
                return jsonOk(array(
                    'sid' => $profile->sid,
                ));
            }else{
                return jsonFail(500, "内部错误，" . $request->input("username") . "对应的local_auth->" . $auth->user_id . "不存在一个合法的profile");
            }

        }else{
            return jsonFail(400, "用户名或密码错误");
        }
    }else if($request->input('way') === 'oauth'){
        ///如果oauthId和oauthName同时存在，则之前创建过，否则，去第三方拉取用户信息，存profile和oauth
        return jsonFail(400, "暂时不支持auth方式登录");
    }else{
        return jsonFail(400, "暂时只支持local和auth方式登录");
    }

}
function deleteUser($request){
    $notify = '必须填入sid'; if(validateRequire($request, 'sid', $notify) !== true) return jsonFail(400, $notify);

    $profile = Profile::query()->where("sid", "=", $request->input('sid'))->first();
    if($profile != null){
        $profile->delete();
        LocalAuth::query()->where("user_id", "=", $profile->id)->delete();
        OAuth::query()->where("user_id", "=", $profile->id)->delete();
        ApiAuth::query()->where("user_id", "=", $profile->id)->delete();
        return jsonOk(array());
    }else{
        return jsonFail(400, "不存在此用户--" . $request->input('sid'));
    }
}
function postUserLocalRegist(MyRequest $request){
    $notify = '必须填入用户名'; if(validateRequire($request, 'username', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入密码'; if(validateRequire($request, 'password', $notify) !== true) return jsonFail(400, $notify);
    $notify = '必须填入昵称'; if(validateRequire($request, 'nickname', $notify) !== true) return jsonFail(400, $notify);

    $notify = '用户名必须是手机号'; if(validatePattern($request, 'username', PATTERN::PATTERN_PHONE, $notify) !== true) return jsonFail(400, $notify);
    $notify = '密码必须大于6位'; if(validateLengthMin($request, 'password', 6, $notify) !== true) return jsonFail(400, $notify);
    $notify = '昵称必须大于是1到32位长度'; if(validateLength($request, 'nickname', 1, 32, $notify) !== true) return jsonFail(400, $notify);

    //username是否重复
    $admin = LocalAuth::query()->where("username", "=", $request->input("username"))->get();
    if($admin != null && count($admin) > 0){
        return jsonFail(400, "用户已经存在");
    }

    $user = new Profile();
    $user->nickname = $request->input('nickname');
    $user->sid = md5(uniqid());
    $user->save();

    $auth = new LocalAuth();
    $auth->username = $request->input('username');
    $auth->password = $request->input('password');
    $auth->user_id = $user->id;
    $admin->save();
    return jsonOk([
        "id" => $user->id,
        "sid" => $user->sid,
    ]);
}

function userOAuthCreate($request){

}


///-----------------------------------------------------------------------------------------
///下面是所有Controller---后台主页
///-----------------------------------------------------------------------------------------
function checkLogin(MyRequest $request){
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

function getAdminHome(MyRequest $request){

    $c = checkLogin($request);
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


///-----------------------------------------------------------------------------------------
///下面是所有Controller---feed流
///-----------------------------------------------------------------------------------------
function getTimelineList(MyRequest $request){

    $c = checkLogin($request);
    if($c !== true){
        return $c;
    }

    $page = $request->input("page", 1);
    $pageSize = $request->input("pageSize", 20);
    $total =  Capsule::table("timelines")->count("id");
    $pageCount = ($total % $pageSize == 0) ? $total / $pageSize : ($total - $total%$pageSize)/$pageSize + 1;

    $timelines = Capsule::table("timelines")->orderBy("id", "desc")->skip(($page - 1) * $pageSize)->limit($pageSize)->get();
    //print_r($timelines);

    if($timelines != null && count($timelines) != 0){
        foreach ($timelines as $timeline) {
        }
    }

    if(isAcccept('json')){
        return jsonOk($timelines);
    }else{

        $layout = array(
            //"userId" => array("name" => "作者", "type" => "int", "input" => "text", "isBigData" => false),
            "title" => array("name" => "标题", "type" => "text", "input" => "text", "isBigData" => false),
            "source" => array("name" => "来源", "type" => "text", "input" => "text", "isBigData" => false),
            "content" => array("name" => "内容", "type" => "text", "input" => "text", "isBigData" => true),
            "picBigs" => array("name" => "大图", "type" => "text", "input" => "text", "isBigData" => true),
            "picThumbs" => array("name" => "小图", "type" => "text", "input" => "text", "isBigData" => true),
            "picMiddles" => array("name" => "中图", "type" => "text", "input" => "text", "isBigData" => true),
            "create_at" => array("name" => "时间", "type" => "timestamp", "input" => "text", "isBigData" => false),
        );

        return View::views("feeds.feed_mgmr", array(
            "list" => $timelines,
            "pageCount" => $pageCount,
            "page" => $page,
            "pageSize" => $pageSize,
            "sid" => $request->input("sid"),
            "layout" => $layout,
        ));
    }
}

function postCreateTimeline(MyRequest $request){

}

function putUpdateTimeline(MyRequest $request){

}

function deleteTimeline(MyRequest $request){

}

function putUpdateTimelineXX(MyRequest $request){

}

