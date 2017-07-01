<?php
include_once("./push.php");

$appid = $_POST["appid"];
$secret = $_POST["secret"];
$device = $_POST["device"];
$tags = $_POST["tags"];
$aliases = $_POST["aliases"];
$regid = $_POST["regid"];
$title = $_POST["title"];
$content = $_POST["content"];
$payload = $_POST["payload"];
$platform = $_POST["platform"];

if($platform == "jpush"){
    $appid = "37250f16d832c50f0361d1be";
    $master_secret = "31e114ab551cee492f6abf4b";
}else if($platform == "mipush"){
    $appid = "com.iwomedia.taitai";
    $master_secret = "WANJj+CY3iaqE5QT471M9A==";
}

$res = push($appid, $master_secret, $title, $content, $payload, $regid, $tags, $aliases, false, $platform);
echo json_encode($res);