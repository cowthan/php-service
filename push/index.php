<?php

/*
太太学院  极光
AppKey 37250f16d832c50f0361d1be
Master Secret  31e114ab551cee492f6abf4b

小米
secret：WANJj+CY3iaqE5QT471M9A==
*/
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once("./vendor/autoload.php");
 
function pushByJpush($app_key, $master_secret, $regid, $content){
	
	$client = new \JPush\Client($app_key, $master_secret);
	
	$pusher = $client->push();
		
		$pusher->setPlatform('all');
		//$push->setPlatform('ios', 'android');
		//$push->setPlatform(['ios', 'android']);
		
		$pusher->addRegistrationId($regid);
		//$pusher->addAllAudience();
		//$push->addTag('tag1');
		//$push->addTag(['tag1', 'tag2']);
		//addAlias()
		//addRegistrationId(), addTagAnd() 
		
		$pusher->setNotificationAlert($content);
		//$push->setNotificationAlert('alert');
		/*
		iOS Notification

		// iosNotification($alert = '', array $notification = array())
		// 数组 $notification 的键支持 'sound', 'badge', 'content-available', 'mutable-content', category', 'extras' 中的一个或多个

		// 调用示例
		$push->iosNotification();
		// OR
		$push->iosNotification('hello');
		// OR
		$push->iosNotification('hello', [
		  'sound' => 'sound',
		  'badge' => '+1',
		  'extras' => [
			'key' => 'value'
		  ]
		]);
		*/
		
		try {
			$pusher->send();
			return "ok";
		} catch (\JPush\Exceptions\JPushException $e) {
			// try something else here
			return $e;
		}
}


define('MiPush_Root', dirname(__FILE__) . '/vendor/');
function mipushAutoload($classname) {
    $parts = explode('\\', $classname);
    $path = MiPush_Root . implode('/', $parts) . '.php';
    if (file_exists($path)) {
        include($path);
    }
}

spl_autoload_register('mipushAutoload');

use xmpush\Builder;
use xmpush\HttpBase;
use xmpush\Sender;
use xmpush\Constants;
use xmpush\Stats;
use xmpush\Tracer;
use xmpush\Feedback;
use xmpush\DevTools;
use xmpush\Subscription;
use xmpush\TargetedMessage;

function pushByMiPush($app_key, $master_secret, $regid, $content){

	$secret = $master_secret; //'WANJj+CY3iaqE5QT471M9A==';
	$package = $app_key;
	///$resid = "KIDChgku66P+RPcaJ99YCZawOR2XS/bWqa5T26Bidh0=";

	// 常量设置必须在new Sender()方法之前调用
	Constants::setPackage($package);
	Constants::setSecret($secret);

	//$aliasList = array('alias1', 'alias2');
	$title = 'mipush的推送';
	$desc = $content;
	$payload = '{"test":1,"ok":"It\'s a string"}';

	$sender = new Sender();

	// message1 演示自定义的点击行为
	$message1 = new Builder();
	$message1->title($title);  // 通知栏的title
	$message1->description($desc); // 通知栏的descption
	$message1->passThrough(0);  // 这是一条通知栏消息，如果需要透传，把这个参数设置成1,同时去掉title和descption两个参数
	$message1->payload($payload); // 携带的数据，点击后将会通过客户端的receiver中的onReceiveMessage方法传入。
	$message1->extra(Builder::notifyForeground, 1); // 应用在前台是否展示通知，如果不希望应用在前台时候弹出通知，则设置这个参数为0
	$message1->notifyId(2); // 通知类型。最多支持0-4 5个取值范围，同样的类型的通知会互相覆盖，不同类型可以在通知栏并存
	$message1->build();
	// $targetMessage = new TargetedMessage();
	// $targetMessage->setTarget("KIDChgku66P+RPcaJ99YCZawOR2XS/bWqa5T26Bidh0=", TargetedMessage::TARGET_TYPE_REGID); // 设置发送目标。可通过regID,alias和topic三种方式发送
	//$targetMessage->setMessage($message1);
	try {
			$res = $sender->send($message1, $regid);
			//print_r($res);
			if($res->getErrorCode() == 0) return "ok";
			else return $res->getRaw();
		} catch (Exception $e) {
			// try something else here
			return $e;
		}
	

}
	
	
	$res = "";
	if(isset($_POST['submit'])) {
		$platform = $_POST["platform"];
		$key = $_POST["key"];
		$secret = $_POST["secret"];
		$regid = $_POST["regid"];
		$content = $_POST["content"];
		
		if($platform == "jpush"){
			$res = pushByJpush($key, $secret, $regid, $content);
		}else if($platform == "mipush"){
			$res = pushByMiPush($key, $secret, $regid, $content);
		}
		
		
		

	}

	?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title>推送测试</title>
	<link rel='stylesheet' id='open-sans-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&#038;subset=latin%2Clatin-ext&#038;ver=4.5.3' type='text/css' media='all' />
<link rel='stylesheet' id='buttons-css'  href='./assets/buttons.min.css?ver=4.5.3' type='text/css' media='all' />
<link rel='stylesheet' id='install-css'  href='./assets/install.min.css?ver=4.5.3' type='text/css' media='all' />
</head>
<body class="wp-core-ui">
<p id="logo"><a href="" tabindex="-1">推送测试</a></p>
<h1 class="screen-reader-text">那啥</h1>
<form method="post" action="index.php">
	<p style="border: 1px solid red;padding:3px;"><?php echo $res; ?></p>
	<p style="border: 1px solid green;padding:3px;margin-top:5px"> 极光文档：http://docs.jiguang.cn/jpush/client/Android/android_api/</p>
	<!-- <table class="form-table">
		<tr>
			<th scope="row"><label for="key">key</label></th>
			<td><input name="key" id="key" type="text" size="25" value="37250f16d832c50f0361d1be" /></td>
			<td>后台key，小米推送则是包名，小米只能推安卓</td>
		</tr>
		<tr>
			<th scope="row"><label for="secret">secret</label></th>
			<td><input name="secret" id="secret" type="text" size="25" value="31e114ab551cee492f6abf4b" /></td>
			<td>后台secret</td>
		</tr>
		<tr>
			<th scope="row"><label for="regid">reg id</label></th>
			<td><input name="regid" id="regid" type="text" size="25" value="170976fa8a8c872bdb9" autocomplete="off" /></td>
			<td>极光的Regitration Id</td>
		</tr>
		<tr>
			<th scope="row"><label for="content">发送内容</label></th>
			<td><input name="content" id="content" type="text" size="25" value="测试推送，1122334455-aabbccdd--嘿嘿嘿嘿嘿嘿嘿" /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="platform">平台</label></th>
			<td><input name="platform" id="platform" type="text" value="jpush" size="25" /></td>
			<td>极光jpush, 小米mipush</td>
		</tr>
	</table> -->

	<table class="form-table">
		<tr>
			<th scope="row"><label for="key">key</label></th>
			<td><input name="key" id="key" type="text" size="25" value="com.iwomedia.taitai" /></td>
			<td>后台key，小米推送则是包名，小米只能推安卓</td>
		</tr>
		<tr>
			<th scope="row"><label for="secret">secret</label></th>
			<td><input name="secret" id="secret" type="text" size="25" value="WANJj+CY3iaqE5QT471M9A==" /></td>
			<td>后台secret</td>
		</tr>
		<tr>
			<th scope="row"><label for="regid">reg id</label></th>
			<td><input name="regid" id="regid" type="text" size="25" value="KIDChgku66P+RPcaJ99YCZawOR2XS/bWqa5T26Bidh0=" autocomplete="off" /></td>
			<td>极光的Regitration Id</td>
		</tr>
		<tr>
			<th scope="row"><label for="content">发送内容</label></th>
			<td><input name="content" id="content" type="text" size="25" value="测试推送，1122334455-aabbccdd--嘿嘿嘿嘿嘿嘿嘿" /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="platform">平台</label></th>
			<td><input name="platform" id="platform" type="text" value="mipush" size="25" /></td>
			<td>极光jpush, 小米mipush</td>
		</tr>
	</table>

	<p class="step"><input name="submit" type="submit" value="开始推" class="button button-large" /></p>
</form>
<script type='text/javascript' src='./assets/jquery.js?ver=1.12.4'></script>
<script type='text/javascript' src='./assets/jquery-migrate.min.js?ver=1.4.1'></script>
<script type='text/javascript' src='./assets/language-chooser.min.js?ver=4.5.3'></script>
</body>
</html>


