<?php

function footer($path){
	global $body, $test, $html;
	$myfile = fopen($path, "r+") or die("Unable to open file!");
	$str = "文件内容: <br />" . htmlspecialchars(fread($myfile, filesize($path)));
	fclose($myfile);

	///找到字符串
	$s_start = "//============================bbbbbb";
	$s_end = "//============================oooooo";
	$start = stripos($str, $s_start, 0) + strlen($s_start);
	$end = stripos($str, $s_end, 0);
	$str = substr($str, $start, $end-$start);

	//$str = syntax_highlight($str);
	
	
	
	_pout("<div style='height:500px'><pre class=\"brush: php;\">" . $str . "</pre></div>");

	
	$html = str_replace("====body====", $body, $html);
	$html = str_replace("====test====", $test, $html);
	echo $html;
	exit();
	
}

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	