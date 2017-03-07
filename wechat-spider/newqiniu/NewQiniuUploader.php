<?php
/**
*  filename NewQiniuUploader.php
 *  
*
*  $$Id: QiniuUploader.php 9312 2015-07-07 09:38:56Z fuq $$
*/
include_once 'autoload.php';
include_once 'Qiniu/Auth.php';
include_once 'Qiniu/Storage/BucketManager.php';

//Yii::import('ext.newqiniu.autoload', true);
//Yii::import('ext.newqiniu.Qiniu.Auth', true);
//Yii::import('ext.newqiniu.Qiniu.Storage.BucketManager', true);

class NewQiniuUploader {
    private $_bucket;
    private $_auth;
    private $_bmgr;
    
    public function __construct($bucket)
    {
        //验证用户
       $auth = new Qiniu\Auth('dTOse9BHmRSLAB1h-GpXcf3xq3x7MIfU6ORMzXZN', 'wj6FJsB518GHnLyqRVAYzm5vHKo5832RJeWhf1bs');
       //
       $bmgr = new \Qiniu\Storage\BucketManager($auth);
      
       $this->_auth = $auth;
       $this->_bmgr = $bmgr;
        $this->_bucket = $bucket;
    }
    
    /**
     * 通过图片URL 下载该图片到七牛
     * @param type $url 图片地址
     * @param type $newName 新图片
     */
    public function fecthImg($url, $newName){
        list($ret, $err) = $this->_bmgr->fetch($url, $this->_bucket, $newName);
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
    }
    
   
}


