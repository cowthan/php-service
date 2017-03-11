<?php 

$html = <<<EOT
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>php lessons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<script type="text/javascript" src="syntax/scripts/shCore.js"></script>
	<script type="text/javascript" src="syntax/scripts/shBrushPhp.js"></script>
	<link type="text/css" rel="stylesheet" href="syntax/styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
  </head>
  <body style="background: white; font-family: 微软雅黑;line-height:30px">
    <div class="container" style="width:100%">

    	<div class="sidebar" style="padding:10px;border:1px solid green;float:left;width:15%;">
    		<ul>
    			<li><a href="lesson-string.php">字符串</a></li>
    			<li><a href="lesson-array.php">数组</a></li>
    			<li><a href="lesson-file.php">文件</a></li>
    		</ul>
    	</div>

		<div class="left" style="padding:10px;border:1px solid green;float:left;width:40%;height:600px;">
			<h3>This is 右边代码的输出</h3><br/>
			<hr/>
			<p >
			====test====
			</p>
		</div>
		<div class="right" style="padding:10px;border:1px solid red;float:right;width:40%;height:600px;">
			<h3>This is 左边输出的代码</h3><br/>
			<hr/>
			<p>
			====body====
			</p>
		</div>
		<div style="clear:both;"></div>
    </div> <!-- /container -->
  </body>
</html>
EOT;


//----------------------------
$body = "";

function _pout($msg){
	global $body;
	$body = $msg;
}

function _pappend($msg){
	global $body;
	$body = $body . $msg;
}

$test = "";

function pt1($msg){
	global $test;
	$test = $msg . "<br/>";
}


function to_string($msg, $horizontal = true){
	$s = "";
	if(is_array($msg)){
		$sep = $horizontal ? ", " : ",<br/>";
		if(count($msg) == 0){
			$s = $s . "[]" . "<br/>";
		}else{
			$s = $s . "[" . ($horizontal ? " " : "<br/>");
			foreach($msg as $k => $m){
				$s = $s . (!$horizontal ? "&nbsp;&nbsp;&nbsp;&nbsp;" : "") . $k . ' => ' . to_string($m, $horizontal) . $sep;
			}
			$s = $s . ($horizontal ? " " : "") . "]<br/>";
		}
	}else{
		$s = $msg . "";
	}
	return $s;
}

function pt($msg = "", $horizontal = true){
	global $test;
	$test .= to_string($msg, $horizontal) . "<br/>";
}
function pt0($msg = "", $horizontal = true){
	global $test;
	$test .= to_string($msg, $horizontal);
}


function ptitle($msg = ""){
	global $test;
	$msg = "<h3 style='border:1px solid blue;padding:10px;'>" . $msg . "</h3>";
	$test = $test . $msg . "<br/>";
}

define("BR", "<br/>");
define("PATH", "/service/lesson/");


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	