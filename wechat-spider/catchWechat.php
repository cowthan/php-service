<?php
set_time_limit(0);
error_reporting(E_ALL);
$soft = new CatchWechat();
$soft->run();

class CatchWechat{
   // public $mysqlCli = null;
    public $log_dir = '/var/data/wechat/log/';
    public $log_name = 'access.log';


    public function __construct(){
        //$this->mysqlCli = new MysqlCli(); 
    }

   
    public function run(){
        //$wechat_sql = "SELECT * FROM app_member WHERE v_weixinhao!=''";
        //$wechat_query = $this->mysqlCli->query($wechat_sql);
        if(1 + 1 == 2){
			//echo "just begin";
            $wechat_list = array("巨乳"); //$wechat_query->fetchAll(PDO::FETCH_ASSOC); 
            foreach($wechat_list as $wt){
               // sleep(mt_rand(5,120));
                $member_id = "mem_id"; //$wt['member_id'];
                $member_realname = "real_name"; //$wt['v_penname'];
                //$logstr = 'start:'.$wt['v_weixinhao'].'--';
                //$this->writeLog($logstr);
                $time_stat = explode(microtime(true)*1000,'.');
                $time_stat = $time_stat[0];
                $sogou_url = 'http://weixin.sogou.com/weixin?type=2&ie=utf8&_sug_=n&_sug_type_=&w=01019900&sut=248315&sst0='.$time_stat.'&query='.$wt;
                $sogou_search = getHtml($sogou_url,'http://weixin.sogou.com/');   
                $wechat_preg = '#<li id=\"sogou_.*\">(.*)</li>#Uis';
				$preg_result = array();
                $preg_res = preg_match_all($wechat_preg,$sogou_search,$preg_result); ///匹配了搜出来的所有文章
				
				//print_r($preg_result);
				
                if($preg_res){
                    $url = '';
                    foreach($preg_result[0] as $tmp_wt){   ///对于每一篇文章
					echo "<br/><hr/>";
						/* tmp_wt = 
						<li id="sogou_vr_11002601_box_0" d="ab735a258a90e8e1-6bee54fcbd896b2a-24793f882524010d45e0100cdbb11568">
<div class="img-box">
<a data-z="art" target="_blank" id="sogou_vr_11002601_img_0" href="http://mp.weixin.qq.com/s?src=3&amp;timestamp=1488876576&amp;ver=1&amp;signature=SpotYbHM*Outu2eyjbcu93TQOPZPQtkrSwthqHiMSgjL2RP3fwMGyIWVZkLtAo-Pd2IozCudzhY*AAe6Zy-qi8TPWa6QJI6KSRV2JW5FOqSaw2GCcaLnJfh7GpQQ0tsC3VIkxmZMPQMkjnjALeNqYHRw8pG9N6fbIl1UEcbA8x0=" uigs="article_image_0"><img src="http://img01.sogoucdn.com/net/a/04/link?appid=100520033&amp;url=http://mmbiz.qpic.cn/mmbiz_jpg/EAfSCicu9NAnzPBc7Mckw12d3PH2pCE0AiaDmMGGtzUWAH50lUJPlGo5iaLBsdV9A8oLW9GP3gKaRbZNwAl2auRzQ/0?wx_fmt=jpeg" onload="resizeImage(this,140,105)" onerror="errorImage(this)"></a>
</div>
<div class="txt-box">
<h3>
<a target="_blank" href="http://mp.weixin.qq.com/s?src=3&amp;timestamp=1488876576&amp;ver=1&amp;signature=SpotYbHM*Outu2eyjbcu93TQOPZPQtkrSwthqHiMSgjL2RP3fwMGyIWVZkLtAo-Pd2IozCudzhY*AAe6Zy-qi8TPWa6QJI6KSRV2JW5FOqSaw2GCcaLnJfh7GpQQ0tsC3VIkxmZMPQMkjnjALeNqYHRw8pG9N6fbIl1UEcbA8x0=" id="sogou_vr_11002601_title_0" uigs="article_title_0"><em><!--red_beg-->巨乳<!--red_end--></em>居然还可以这样用?</a>
</h3>
<p class="txt-info" id="sogou_vr_11002601_summary_0">甭管足球篮球乒乓球当然你们最喜欢的肯定还是还是那个球今天X哥带你们文明观球,给你们介绍一部岛国励志青春片&mdash;&mdash;《<em><!--red_beg-->巨乳<!--red_end--></em>排球...</p>
<div class="s-p" t="1484742713">
<a class="account" target="_blank" id="sogou_vr_11002601_account_0" i="oIWsFt8IkbDObs5zC4D2URAElWus" href="http://mp.weixin.qq.com/profile?src=3&amp;timestamp=1488876576&amp;ver=1&amp;signature=5WkmDm-US6w-*xgoD2i-3yjxprNCdtbPbozNQuzt0EUDOucFZzkI5Bhb5D5pQdOyhueOX7A-04KOhvJrBsL-cg==" data-headimage="http://wx.qlogo.cn/mmhead/Q3auHgzwzM7v45FCUNwcb5xpmB2AUqAMylsCSPwfCdCOGZUKK9651g/0" data-isV="1" uigs="article_account_0">我爱XXX</a><span class="s2"><script>document.write(timeConvert('1484742713'))</script></span>
<div class="moe-box">
<span style="display:none;" class="sc"><a data-except="1" class="sc-a" href="javascript:void(0)" uigs="share_0"></a></span><span style="display:none;" class="fx"><a data-except="1" class="fx-a" href="javascript:void(0)" uigs="like_0"></a></span>
</div>
</div>
</div>
</li>
						*/
						//'#<li id=\"sogou_.*\">(.*)</li>#Uis';
                        $preg_wt_name = '/<a data-z=\"art\" target=\"_blank\" id=\"sogou_.*\" href=\"(.*)\" uigs=.*>/';
						$wt_name_result = array();
                        $wt_name_res = preg_match($preg_wt_name , $tmp_wt , $wt_name_result);
						print_r( $wt_name_result);
                      
					   if($wt_name_res){
							
                            $url = $wt_name_result[1];
							//http://mp.weixin.qq.com/s?src=3×tamp=1488879923&ver=1&signature=SpotYbHM*Outu2eyjbcu93TQOPZPQtkrSwthqHiMSgjL2RP3fwMGyIWVZkLtAo-Pd2IozCudzhY*AAe6Zy-qi8TPWa6QJI6KSRV2JW5FOqSaw2GCcaLnJfh7GpQQ0tsCRdHmc-iNng6gf-2CNdZDtAuUC-US2tRDR3vwrADRjDU=
							
							//http://mp.weixin.qq.com/s?src=3&timestamp=1488880044&ver=1&signature=WwFXADq0Gn31yJbWhn4jHZLzRF78RYClpfONIwvOHlZ4JuhPNy3Gj5gHZrd*Eh5aF6c8nBVqlOfJVQRnWWSSBSAMuOkKl9T13MYRCwwcU4MgzYoQ7b96dX2HvO4HRsHb0OX4z7cs8Gg7wz6mgHSbfQDgcfIspIgKq3aQuBuDvhA=
							echo "<br/>" . $url . "<br/>";
							if($url){
								$wechat_page = getHtml($url,$sogou_url);    
								if($wechat_page){
									$preg_article_list = '#msgList.*=.*{(.*)};#Uis';
									$article_list_res = preg_match($preg_article_list,$wechat_page,$article_list_result);
									print_r($article_list_result);
									if($article_list_res){
										$article_list_str = htmlspecialchars_decode('{'.$article_list_result[1].'}');
										$article_list = json_decode($article_list_str,true);
										foreach($article_list['list'] as $art_info){
											$main_art = $art_info['app_msg_ext_info'];
											$logstr = 'success:check fileid '.$main_art['fileid'];
											$this->writeLog($logstr);

											if(!$this->checkSameArt($main_art['fileid']) || $main_art['fileid']==0){
												//微信问题,总有fileid=0
												if($main_art['fileid']!=0){
													$logstr = 'success:start save fileid-'.$main_art['fileid'];
													$this->writeLog($logstr);
													$article['fileid'] = $main_art['fileid'];
													$article['title'] = $main_art['title'];
													$article['content_url'] = $main_art['content_url'];
													$article['cover'] = $main_art['cover'];
													$article['author'] = $member_realname;
													$article['pre_url'] = $url;
													$article['member_id'] = $member_id;
													$article['v_realname'] = $main_art['author'];
													$this->saveArticle($article);
												}
												if(isset($main_art['multi_app_msg_item_list']) &&  !empty($main_art['multi_app_msg_item_list'])){
													$multi_app =  $main_art['multi_app_msg_item_list'];
													foreach($multi_app as $multi){
														if($this->checkSameArt($multi['fileid'])){
															continue; 
														}

														$logstr = 'success:save multi fileid '.$multi['fileid'];
														$this->writeLog($logstr);

														$mul_art = array();
														$mul_art['fileid'] = $multi['fileid'];
														$mul_art['title'] = $multi['title'];
														$mul_art['content_url'] = $multi['content_url'];
														$mul_art['cover'] = $multi['cover'];
														$mul_art['author'] = $member_realname;
														$mul_art['member_id'] = $member_id;
														$mul_art['v_realname'] = $multi['author'];
														$mul_art['pre_url'] = $url;
														$this->saveArticle($mul_art);
													}
												}
											}else{
												$logstr = 'error:fileid'.$main_art['fileid'].'-same'; 
												$this->writeLog($logstr);
											}
										}
									}else{
										$logstr = 'error: get article list failure'; 
										$wechat_list[] = $wt;
									}
								}else{
									$logstr = 'error: get wechat html failure'; 
									$wechat_list[] = $wt;
								}
							}else{
								$logstr = 'error:url match failure'; 
								$wechat_list[] = $wt;
							}
							
                            ///break;
                        }
						
						break;
                    }
					
					
                    
                }else{
                    $logstr = 'error:没搜到文章，关键词：' . $wt;
                    $wechat_list[] = $wt;
                }
                $this->writeLog($logstr); 
                sleepForWhile();
            }
        }
        $this->writeLog('over');

    }
	
	
    
    public function saveArticle($articleInfo){
        $content_url = stripcslashes(htmlspecialchars_decode($articleInfo['content_url']));
        $title = htmlspecialchars_decode($articleInfo['title']);
        $title = str_replace('&nbsp;',' ',$title);
        $author = $articleInfo['author'];
        $cover = stripcslashes(htmlspecialchars_decode($articleInfo['cover']));
        $member_id = $articleInfo['member_id'];
        $fileid = $articleInfo['fileid'];
        $v_realname = $articleInfo['v_realname'];
        $t = date('Y-m-d H:i:s',time());
        $pre_url = $articleInfo['pre_url'];
        if(!is_int(strpos('http',$content_url)))
            $content_url = 'http://mp.weixin.qq.com'.$content_url;
        $cover_url = saveImage($cover);
        if(!$cover_url)
            $cover_url = $cover;
        sleepForWhile();
        $html = getHtml($content_url,$pre_url);
        $preg_content = '#<div.*id="js_content">(.*)<\/div>#Uis';
        if($html){
            $content_res = preg_match($preg_content,$html,$content_result);
            if($content_res){
                $content = $content_result[1]; 
                $content = str_replace('data-src', 'src', $content);
                $imgchuli_c = new ImageConvertorDecorator($content);
                $imgchuli_c->convert();
                $src_c = $imgchuli_c->getData();

                if(is_array($src_c)){
                    foreach ($src_c as $ck=>$c)
                        $content = str_replace($ck, $c, $content);
                }
            }else{
                $content = ''; 
                $logstr = 'error:article content match error'; 
                $this->writeLog($logstr);
            }
        }else{
            $content = '';
            $logstr = 'error:get article content error'; 
            $this->writeLog($logstr);
        }
        $logstr = 'success:save fileid-'.$fileid;
        $this->writeLog($logstr);
        $daogouSql = "INSERT INTO `wh_article_wemedia`(`member_id`,`aw_title`,`aw_cover_url`,`aw_content`,`aw_author`,`true_name`,`aw_instime`,`aw_updtime`,`aw_status`,`aw_fcheck_instime`,`aw_is_pass`,`third_id`,`type`) VALUES(".$member_id.",'".$title."','".$cover_url."','".$content."','".$author."','".$v_realname."','".$t."','".$t."', 8,'".$t."',    0,'".$fileid."','weixin')";
        $this->mysqlCli->exec($daogouSql);
    }


    public function checkSameArt($fileid){
        $this->mysqlCli->exec('use daogouwormhole');
        $checkSql = "SELECT * FROM wh_article_wemedia WHERE type='weixin' AND third_id='{$fileid}'";
        $result = $this->mysqlCli->query($checkSql)->fetch();
        if(!$result)
            return false;
        return true;
    }


    public function writeLog($log){
        $log_path = $this->log_dir . $this->log_name;
        $log_str = '---'.date('Y-m-d H:i:s',time()).'--'.$log; 
        $log_str .= "\r\n";
        echo $log_str;
        file_put_contents($log_path , $log_str , FILE_APPEND);
    }

}

class MysqlCli{

    private $client=null;
    public function __construct(){
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=zhaoy_daogou","root","iwomedia",array(PDO::ATTR_PERSISTENT=>true));
        $pdo -> exec('set names UTF8');
        $this->client = $pdo;
    }

    public function __get($name){
        if($name=='client' && !$this->client){
            $pdo = new PDO("mysql:host=127.0.0.1;dbname=zhaoy_daogou","root","iwomedia",array(PDO::ATTR_PERSISTENT=>true));
            $pdo -> exec('set names UTF8');
            $this->client = $pdo; 
        } 
        return $this->$name;
    }

    private function mysqlConnect(){
        $pdo = new PDO("mysql:host=127.0.0.1;dbname=zhaoy_daogou","root","iwomedia",array(PDO::ATTR_PERSISTENT=>true));
        $pdo -> exec('set names UTF8');
        $this->client = $pdo; 
    }


    public function query($str){
        if(!$this->client)
            $this->mysqlConnect();
        $query = $this->client->query($str); 
        if(!$query){
            $this->mysqlConnect();
            $query = $this->client->query($str); 
        }
        return $query;
    }

    public function exec($str){
        if(!$this->client)
            $this->mysqlConnect();
        $exec = $this->client->exec($str); 
        if(!$exec){
            $this->mysqlConnect();
            $exec = $this->client->exec($str); 
        }
        return $exec;
    }
}

function pr($data,$die=1){
    var_dump($data);
    if($die)
        die();
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
                'CLIENT-IP:207.46.13.144',
                'X-FORWARDED-FOR:207.46.13.144',
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
function sleepForWhile(){
    sleep(mt_rand(10,40));
}

class ImageConvertorDecorator {
    private $_content;
    private static $_data;
    public function __construct($content){
        $this->_content = $content;
    }
    public function convert() {
        $content = $this->_content;
        if (false === stripos($content, '<img')) {
            return $content;
        }
        // 单独拆出来 可以过滤出src src="" src='' 格式
        $content = preg_replace_callback('#<img([^>]+)\/?>#iU', array($this, 'convertCallback'), $content);
        //匹配ifram
        preg_replace_callback('/<iframe ([^>]+)\/?><\/iframe>/iU', array($this, 'iframeCallback'), $content);
        //return $content;
    }
    
    public static function addData($k, $v) {
        self::$_data[$k] = $v;
    }
    public static function getData() {
        return self::$_data;
    }
    public function iframeCallback($match){       
         // 对数据格式化成标准的替换各键值之间的空格
        $tmp = preg_replace('/\s+=\s+/', '=', $match[1]);

        // 单引号格式化为双引号, 追加一个空格
        $tmp = str_replace(['\''], ['"'], $tmp) . ' ';
        
        preg_match('/(?<=src=)["]?([^\s"]+)["]?(?=\s)/i', $tmp, $r);
        $p = parse_url($r[1]);
        $newp = explode('&', $p['query']) ;
        $id = '';       
        foreach ($newp as $v){            
            if(strpos($v, 'vid')!== false){               
                $n = explode('=', $v);
                $id = $n[1];
                break;
            }
        }
        $ifram = '<iframe class="video_iframe" style="   z-index:1;width:100%; " width="100%" frameborder="0" src="https://v.qq.com/iframe/player.html?vid='.$id.'&auto=0" allowfullscreen=""></iframe>';
       
        if (!empty($r)) {
            self::addData( $match[0], $ifram );
        }
       
    }
    public function convertCallback($match) {
      
        // 对数据格式化成标准的替换各键值之间的空格
        $tmp = preg_replace('/\s+=\s+/', '=', $match[1]);

        // 单引号格式化为双引号, 追加一个空格
        $tmp = str_replace(['\''], ['"'], $tmp) . ' ';

        preg_match('/(?<=src=)["]?([^\s"]+)["]?(?=\s)/i', $tmp, $r);

        if (!empty($r)) {
            $newUrl = saveImage($r[1]);
            self::addData( $r[1], $newUrl);
        }
    }

}
function saveImage($url){
    include_once __DIR__.'/newqiniu/NewQiniuUploader.php';
    //解析图片后缀
    $p = parse_url($url);

    $fmt = '';
    if($p){
        $newp = explode('&', $p['query']) ;
        foreach ($newp as $v){

            if(strpos($v, 'wx_fmt')!==FALSE){
                $fmt = str_replace('wx_fmt=', '', $v);;
                break;
            }
        }
    }
    $ex = '';
    switch ($fmt){
        case 'jpeg':
            $ex = 'jpg';
            break;
        case 'png':
            $ex = 'png';
            break;
        case 'gif':
            $ex = 'gif';
            break;
        default :
            $ex = 'jpg';
            break;
    }

    $fileName = time().  rand(10000,99999).'.'.$ex;
    $qiniu = new NewQiniuUploader('daogou');

    $response = $qiniu->fecthImg($url, $fileName);
    if($response){
        return 'http://cdn.carguide.com.cn/'.$fileName;
    }
    return FALSE;
}

