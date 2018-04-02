<?php 
include("./header.php");
//============================bbbbbb

///------------------------------------
////$fh = fopen('php://output', 'w');
///打开了一个文件，而这个文件指向的是标准输出流，也就是写入到了网页里
ptitle("fputcsv：输出CSV到文件");
//$fh = fopen('php://output', 'w');  //这个代表标准输出流，也就是和echo一样，输出到网页
//fputcsv($fh, array('north'));
//fclose($fh);

///------------------------------------
ptitle("ob_start：拦截标准输出流，并输出到一个字符串中");
ob_start();

echo "--------csv 开始--------" . BR;
$fh = fopen('php://output', 'w');
fputcsv($fh, array('north'));
fclose($fh);
echo BR . "--------csv 结束--------" . BR;

$output = ob_get_contents();
ob_end_clean();
pt($output);

//============================oooooo
include("./footer.php");
footer(str_replace(PATH, "", $_SERVER['SCRIPT_NAME']));
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	