<?php 

$html = <<<EOT
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<script type="text/javascript" src="syntax/scripts/shCore.js"></script>
	<script type="text/javascript" src="syntax/scripts/shBrushJScript.js"></script>
	<link type="text/css" rel="stylesheet" href="syntax/styles/shCoreDefault.css"/>
	<script type="text/javascript">SyntaxHighlighter.all();</script>
  </head>
  <body style="background: white; font-family: Helvetica">
    <div class="container" style="width:100%">
		<div class="left" style="padding:10px;border:1px solid green;float:left;width:48%;height:600px;">
		<h3>This is 右边代码的输出</h3><br/>
		<hr/>
		<p>
		====test====
		</p>
		</div>
		<div class="right" style="padding:10px;border:1px solid red;float:right;width:48%;height:600px;">
		<h3>This is 左边输出的代码</h3><br/>
		<hr/>
		<p>
		====body====
		</p>
		</div>
		<div style="clear:both"></div>
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

function pout($msg){
	global $test;
	$test = $msg;
}

function pappend($msg){
	global $test;
	$test = $test . $msg;
}
define("BR", "<br/>");

//============================bbbbbb
///---- code here ----///
pout("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);

pappend("<h1>dsfsdsdfsdfasdf是发顺丰阿的发生萨芬111111111111111111111111111111111</h1>");

pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
pappend("dsfsdfdsf" . BR);
//============================oooooo



function syntax_highlight($code){
  
    // this matches --> "foobar" <--
    $code = preg_replace(
        '/"(.*?)"/U',
        '&quot;<span style="color: #007F00">$1</span>&quot;', $code
    );
  
    // hightlight functions and other structures like --> function foobar() <---
    $code = preg_replace(
        '/(\s)\b(.*?)((\b|\s)\()/U',
        '$1<span style="color: #0000ff">$2</span>$3',
        $code
    );
  
    // Match comments (like /* */):
    $code = preg_replace(
        '/(\/\/)(.+)\s/',
        '<span style="color: #660066; background-color: #FFFCB1;"><i>$0</i></span>',
        $code
    );
  
    $code = preg_replace(
        '/(\/\*.*?\*\/)/s',
        '<span style="color: #660066; background-color: #FFFCB1;"><i>$0</i></span>',
        $code
    );
  
    // hightlight braces:
    $code = preg_replace('/(\(|\[|\{|\}|\]|\)|\->)/', '<strong>$1</strong>', $code);
  
    // hightlight variables $foobar
    $code = preg_replace(
        '/(\$[a-zA-Z0-9_]+)/', '<span style="color: #0000B3">$1</span>', $code
    );
  
    /* The \b in the pattern indicates a word boundary, so only the distinct
    ** word "web" is matched, and not a word partial like "webbing" or "cobweb"
    */
  
    // special words and functions
    $code = preg_replace(
        '/\b(print|echo|new|function)\b/',
        '<span style="color: #7F007F">$1</span>', $code
    );
  
    return $code;
}
  


$myfile = fopen("./index.php", "r+") or die("Unable to open file!");
$str = "文件内容: <br />" . htmlspecialchars(fread($myfile,filesize("./index.php")));
fclose($myfile);

///找到字符串
$s_start = "//============================bbbbbb";
$s_end = "//============================oooooo";
$start = stripos($str, $s_start, 0) + strlen($s_start);
$end = stripos($str, $s_end, 0);
$str = substr($str, $start, $end-$start);

//$str = syntax_highlight($str);

_pout("<pre class=\"brush: js;\">" . $str . "</pre>");


$html = str_replace("====body====", $body, $html);
$html = str_replace("====test====", $test, $html);
echo $html;
exit();
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	