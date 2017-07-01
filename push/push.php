<?php

ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);
require_once("./vendor/autoload.php");


function push($app_key, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, $passthrough, $platform){

	$res = "";
	if($platform == "jpush"){
		$res = pushByJpush($app_key, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, $passthrough);
	}else if($platform == "mipush"){
		$res = pushByMiPush($app_key, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, $passthrough);
	}else{
		$res = "不支持的平台--" . $platform;
	}
	return $res;
}

function pushByJpush($app_key, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, $passthrough){
	
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
			echo $e . "---" . $regid;
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

function pushByMiPush($app_key, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, $passthrough){

	$secret = $master_secret; //'WANJj+CY3iaqE5QT471M9A==';
	$package = $app_key;
	///$resid = "KIDChgku66P+RPcaJ99YCZawOR2XS/bWqa5T26Bidh0=";

	// 常量设置必须在new Sender()方法之前调用
	Constants::setPackage($package);
	Constants::setSecret($secret);

	//$aliasList = array('alias1', 'alias2');
	//$title = 'mipush的推送';
	//$desc = $content;
	//$payload = '{"test":1,"ok":"It\'s a string"}';

	$sender = new Sender();

	// message1 演示自定义的点击行为
	$message1 = new Builder();
	$message1->title($title);  // 通知栏的title
	$message1->description($content); // 通知栏的descption
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





