<?php 
include("./header.php");
//============================bbbbbb

///------------------------------------
ptitle("字符串入门");
$x = "--THIS IS FROM X--";
$y = "12345'ddd'---${x}";   ///${x}会被解析成变量
$z = '12345"ddd"---${x}';

pt($x);
pt($y);
pt($z);
pt('字符串长度strlen($x) = ' . strlen($x));

pt();

///------------------------------------
ptitle("here doc");
///here doc：解决长文本，而且可以解析变量
//分隔符可以是任何文本，
//但开始必须以<<<开始，
//结束必须是分隔符+分号，或分隔符单独一行
$m = <<<EOT
	这是一篇长文本，分行的长文本
	长文本
	    长文本
		     长文本<br/>
	\$x = $x;
			 
EOT;
pt($m);
pt();

///------------------------------------
ptitle("now doc");
///now doc：解决长文本，但是不可以解析变量
//因为不能解析$，所以可以包含程序代码
//也不支持转义
$m = <<<'EOT'
	这是一篇长文本，分行的长文本
	长文本
	    长文本
		     长文本<br/>
	\$x = $x;
			 
EOT;
pt($m);
pt();

///------------------------------------
ptitle("字符串的下标访问");
//
$str = "Hilda";
pt($str[0]);
pt();

///------------------------------------
ptitle("strpos：查找子串");
///查找子串，无则返回false，有则返回起始下标
$str = "cccc333@163.com";
$pos = strpos($str, "@");
pt("源字符串：" . $str);
if($pos === false){
	pt("没找到子串@");
}else{
	pt("子串@位置：" . $pos);
}

///------------------------------------
ptitle("substr：抽取子串");
///抽取子串，substr($str, start, len)
///如果都是正数，就是起始和长度，长度超出则取到结尾
///如果len是负数，则最后一个字符是-1，从这开始倒数，len表示的是结束位置
	///负数时，结尾下标是不包含
///如果start是负数，也是从最后一个字符(-1)开始数
///如果start是负数，且超过了开始位置，则start是0
///负数的情况下，如果开始位置在结束为止之后，则；结果为空
$str = "0123456";
pt("源字符串：" . $str);
pt('substr($str, 0, 6) = ' . substr($str, 0, 6));
pt('substr($str, 0, 100) = ' . substr($str, 0, 100));
pt('substr($str, 0, -1) = ' . substr($str, 0, -2));
pt('substr($str, -1, -3) = ' . substr($str, -1, -3));
pt('substr($str, -3, -1) = ' . substr($str, -3, -1));
pt('substr($str, -100, -3) = ' . substr($str, -100, -3));


///------------------------------------
ptitle("substr_replace：按下标替换");
///substr_replace($src, $replacement, $start);从start开始，替换到最后
///substr_replace($src, $replacement, $start, $len);  从start开始，替换len个字符
	///len是负数，也是从后数，且包含
	///start是负数，也是从后数，超出开头，则为0
	///start是0，len是0，表示把从开头开始的0个字符替换为xxx，即插入到开头
$str = "0123456";
pt("源字符串：" . $str);
pt('substr_replace($str, "--abcdefg--", 5) = ' . substr_replace($str, "--abcdefg--", 5));
pt('substr_replace($str, "--abcdefg--", -2) = ' . substr_replace($str, "--abcdefg--", -2));
pt('substr_replace($str, "--abcdefg--", -200) = ' . substr_replace($str, "--abcdefg--", -200));
pt('substr_replace($str, "--abcdefg--", 5, 1) = ' . substr_replace($str, "--abcdefg--", 5, 1));
pt('substr_replace($str, "--abcdefg--", 5, -1) = ' . substr_replace($str, "--abcdefg--", 5, -1));pt('substr_replace($str, "--abcdefg--", 0, 0) = ' . substr_replace($str, "--abcdefg--", 0, 0));

///------------------------------------
ptitle("str_replace：按子串替换");
$str = "012345623456";
pt("源字符串：" . $str);
pt("str_replace('234', '----', \$str) = " . str_replace('234', '----', $str));

///------------------------------------
ptitle("strrev：按字节反转");
$str = "This is a map! 而我，是你二大爷。";  ///所以，注意，汉子的两个字节是一个字，这两个字节也反转了。。。
pt("源字符串：" . $str);
pt(strrev($str));

///------------------------------------
ptitle("explode和preg_split：炸开字符串");
$str = "This is a  map!";  
pt("源字符串：" . $str);
$arr = explode(" ", $str);
pt('explode(" ", $str) = ' . to_string($arr));

$str = "my day: 1. get up 2. get dressed 3. eat toast";
pt("源字符串：" . $str);
$arr = preg_split("/\d\. /", $str);
pt(' preg_split("/\d\. /", $str) = ' . to_string($arr));

pt("PREG_SPLIT_DELIM_CAPTURE：表示将小括号里的分组也放在返回的数组里");
$arr = preg_split("/(\d)\. /", $str, -1, PREG_SPLIT_DELIM_CAPTURE);
pt(' preg_split("/(\d)\. /", $str, -1, PREG_SPLIT_DELIM_CAPTURE) = ' . to_string($arr));


///------------------------------------
///注意，explode和implode一点儿都不智能，不能处理分隔符的连续的情况，不能处理数组元素是null的情况（会当成空串拼上）
ptitle("implode：把数组合成字符串");
$str = "This is a map!";  
pt("源字符串：" . $str);
$arr = explode(' ', $str);
$arr[] = null;$arr[] = 1;
$arr = array_reverse($arr);
$str = implode('--', $arr);
pt($str);

///------------------------------------
ptitle("ucfirst：首单词的首字母大写");
$str = "this is a map!";  
pt("源字符串：" . $str);
pt(ucfirst($str));

///------------------------------------
ptitle("ucwords：所有单词的首字符大写");
$str = "this is a map!";  
pt("源字符串：" . $str);
pt(ucwords($str));

///------------------------------------
ptitle("strtoupper和strtolower，全变大写或小写");
$str = "This iS A map!";  
pt("源字符串：" . $str);
pt(strtoupper($str));
pt(strtolower($str));

///------------------------------------
ptitle("ltrim, rtrim,trim：去除空白字符");
////空白字符包括：换行，回车，空格，水平制表符，垂直制表符
$str = "  This iS A map!	\n   	";
pt("源字符串：---" . $str . "----");
pt('ltrim($str) = ---' . ltrim($str) . '---');
pt('rtrim($str) = ---' . rtrim($str) . '---');
pt('trim($str) = ---' . trim($str) . '---');
pt('trim("1234 aaaa 23-sdf-23", "0..9") = ---' .trim("1234 aaaa 23-sdf-23", "0..9") . '---');
pt('trim("1233445!!!",  "!") = ---' .trim("1233445;", ";") . '---');


///------------------------------------
ptitle("fputcsv：输出CSV到文件");

ob_start();

$sales = array(
	array("东北", "2017-03-10", 12.54),
	array("西北", "2017-03-10", 12.54),
	array("东南", "2017-03-10", 12.54),
	array("西南", "2017-03-10", 12.54),
	array("所有区域", "2017-03-10", 12.54),
);

pt("原数组：");
pt($sales);

echo "--------csv 开始--------" . BR;
$fh = fopen('php://output', 'w') or die("打不开php://output");
foreach ($sales as $sale) {
	fputcsv($fh, $sale) or die("写不进php://output");
}

fclose($fh) or die("关不上php://output");
echo BR . "--------csv 结束--------" . BR;

$output = ob_get_contents();
ob_end_clean();
pt(str_replace("\n", BR, $output));

///------------------------------------
///fgetcsv($fp)：会按行读取，如果平均行长度超过8192，可以自己指定第二个参数
//csv会自动处理字段中的逗号，不会和分隔符逗号混淆，所以你还是应该使用csv函数，而不是自己用explode拆
ptitle("fgetcsv和str_getcsv：读取csv");
$output = <<<EOT
东北,2017-03-10,12.54\n
西北,2017-03-10,12.54\n
东南,2017-03-10,12.54\n
西南,2017-03-10,12.54\n
所有区域,2017-03-10,12.54\n
EOT;

pt0("<table>");
$line = str_getcsv($output, ',');
pt0('<tr>');
for($i = 0; $i < count($line); $i++){
	pt0('<td>' . htmlentities($line[$i]) . '</td>');
}
pt0('</tr>');
pt0("</table>");

pt("===========================");
pt($line);

///------------------------------------
ptitle("str_repeat：字符串重复");
pt('str_repeat("1234--", 3) = ' . str_repeat("1234--", 3));

///------------------------------------
ptitle("pack：格式化数据记录，使每个字段占据固定数目的字符--用空格填充或截取");
////在这里突然发现一个汉字其实占了3个字节？？
///参数A8A10A2表示，第一个字段占8个字节，第二个占10个，第三个占2个（会截断）
foreach ($books as $book) {
	pt(str_replace(" ", "-", pack('A8A10A2', $book[0], $book[1], $book[2])));
}

///------------------------------------
ptitle("str_pad：使用指定字符填充字符串");
$str = "123456789";
pt("源字符串：---" . $str . "----");
pt('str_pad($str, 20, "0") = ' . str_pad($str, 20, "0"));

///------------------------------------
ptitle("wordwrap：让在pre里包含的内容自动换行");
$str = <<<EOT
123\n4567889000009887776
EOT;
pt("<pre>" . wordwrap($str, 5, "--\n", 1) . "</pre>");

///------------------------------------
ptitle("转义：数据库相关转义");
///只要是从用户那里来的输入，都不能信任
///并且用户的输入可能又_，%等，用在like里，需要转义
$str = "a1哈 '--\"_--%--";

$db = new PDO('sqlite:/home/vagrant/Code/service/lesson/users.db');
pt('源字符串：' . $str);
pt('quote转义：' . $db->quote($str));

pt('源字符串：' . $str);
pt('替换_和%：' . strtr($db->quote($str), array('_' => '\_', '%' => '\%')));

pt('加了quote和替换了sql通配符之后：' . "select * from profile where name like " . strtr($db->quote($str), array('_' => '\_', '%' => '\%')));


///------------------------------------
ptitle("生成唯一id");
//uniqid()使用当前时间微妙数和一个随机数来生成一个很难猜的字符串
pt('uniqid() = ' . uniqid());
pt('md5(uniqid()) = ' . md5(uniqid()));

///------------------------------------
ptitle("工具：使用指定字符阶段或填充到指定长度");
function str_pack($s, $len, $fillment){
	return str_pad(substr($s, 0, $len), $len, $fillment);
}

$str = "123456789";
pt("源字符串：---" . $str . "----");
pt('str_pack($str, 3, "0") = ' . str_pack($str, 3, "0"));
pt('str_pack($str, 20, "0") = ' . str_pack($str, 20, "0"));

///------------------------------------
ptitle("算法：生成随机字符串");
function str_rand($len = 32, $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"){
		
	if(!is_int($len) || $len == 0){
		return '';
	}
	$llll = strlen($chars) - 1;
	$str = "";
	for($i = $len; $i > 0; $i--){
		$str .= $chars[mt_rand(0, $llll)];
	}
	return $str;	
}
pt("随机字符串：" . str_rand(5));

///------------------------------------
ptitle("算法：Look and Say");
///每一行都是上一行的描述，第一行是1
pt("每一行都是上一行的描述，第一行是1，第二行是1个1，就是11，第三行是两个1，就是21");
function lookandsay($row_num){
		
	$res = array();
	$res[0] = "3";
	if($row_num == 0) return array();
	if($row_num == 1) return $res;
	
	for($row = 1; $row < $row_num; $row++){
		
		$s = $res[$row-1];
		//pt($row . '---' . $s . '----');
		$lastChar = $s[0];
		$charCount = 1;
		$result = "";
		
		if(strlen($s) == 1) $result = $result . $charCount . $lastChar;
		
		for($i = 1; $i < strlen($s); $i++){
			
			$char = $s[$i];
			//pt('---' . $char);
			if($char == $lastChar){
				$charCount++;
				
			}else{
				$result = $result . $charCount . $lastChar;
				$charCount = 1;
				$lastChar = $char;
			}
			
			if($i == strlen($s) - 1){
					$result = $result . $charCount . $lastChar;
				}
		}
		
		$res[$row] = $result;
		
	}
	
	return $res;
}
$arr = lookandsay(10);
for($row = 0; $row < count($arr); $row++){
	pt($arr[$row]);
}


//============================oooooo
include("./footer.php");
footer(str_replace(PATH, "", $_SERVER['SCRIPT_NAME']));
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	