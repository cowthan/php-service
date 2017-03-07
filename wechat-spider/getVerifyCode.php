<?php
$code = isset($argv[1]) ? $argv[1] : '';
$time = isset($argv[2]) ? $argv[2] : '';
$tmstmp = microtime(true)*1000 . mt_rand(100,999);
echo $tmstmp;
if(!$code){
    $jpg = getHtml('http://mp.weixin.qq.com/mp/verifycode?cert='.$tmstmp);
    file_put_contents('/home/y/share/htdocs/zhaoyang/verify.jpg',$jpg);
}else{
    $data = array('cert'=>$time ? $time : $tmstmp,'input'=>$code);
    $result = postHtml('http://mp.weixin.qq.com/mp/verifycode',$data);
    var_dump($result);
}

function getHtml($url,$referer=''){
    $cookie_file = __DIR__.'/cookie.tmp';
    if($url=='')
        return '';
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_HEADER, array(
                )); 
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm");
    if($referer)
        curl_setopt($ch,CURLOPT_REFERER,$referer);
    else
        curl_setopt($ch,CURLOPT_REFERER,'');

    $out_put = curl_exec($ch);
    $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $out_put;
    //return ['html'=>$out_put,'code'=>$http_code];
}

function postHtml($url,$data,$referer=''){
    $cookie_file = __DIR__.'/cookie.tmp';
    if($url=='')
        return '';
    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_HEADER, array(
                )); 
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm");
    if($referer)
        curl_setopt($ch,CURLOPT_REFERER,$referer);
    else
        curl_setopt($ch,CURLOPT_REFERER,'');

    $out_put = curl_exec($ch);
    $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $out_put;
    //return ['html'=>$out_put,'code'=>$http_code];
}

