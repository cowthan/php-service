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
pt("源字符串" . $str);
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
pt("源字符串" . $str);
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
pt("源字符串" . $str);
pt('substr_replace($str, "--abcdefg--", 5) = ' . substr_replace($str, "--abcdefg--", 5));
pt('substr_replace($str, "--abcdefg--", -2) = ' . substr_replace($str, "--abcdefg--", -2));
pt('substr_replace($str, "--abcdefg--", -200) = ' . substr_replace($str, "--abcdefg--", -200));
pt('substr_replace($str, "--abcdefg--", 5, 1) = ' . substr_replace($str, "--abcdefg--", 5, 1));
pt('substr_replace($str, "--abcdefg--", 5, -1) = ' . substr_replace($str, "--abcdefg--", 5, -1));pt('substr_replace($str, "--abcdefg--", 0, 0) = ' . substr_replace($str, "--abcdefg--", 0, 0));



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
footer(str_replace("/service/test/", "", $_SERVER['SCRIPT_NAME']));	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	