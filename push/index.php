<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="viewport" content="width=device-width" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex,nofollow" />
	<title>推送测试</title>
	<link rel='stylesheet' id='open-sans-css'  href='https://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&#038;subset=latin%2Clatin-ext&#038;ver=4.5.3' type='text/css' media='all' />
<link rel='stylesheet' id='buttons-css'  href='./assets/buttons.min.css?ver=4.5.3' type='text/css' media='all' />
<link rel='stylesheet' id='install-css'  href='./assets/install.min.css?ver=4.5.3' type='text/css' media='all' />
</head>
<body class="wp-core-ui">
<p id="logo"><a href="" tabindex="-1">推送测试</a></p>
<h1 class="screen-reader-text">那啥</h1>
<form method="post" action="index.php">
	<p id="res" style="border: 1px solid red;padding:3px;">以下内容务必填写清楚，而且要确定后台已经配置好了key</p>

	<!-- <table class="form-table">
		<tr>
			<th scope="row"><label for="key">key</label></th>
			<td><input name="key" id="key" type="text" size="25" value="37250f16d832c50f0361d1be" /></td>
			<td>后台key，小米推送则是包名，小米只能推安卓</td>
		</tr>
		<tr>
			<th scope="row"><label for="secret">secret</label></th>
			<td><input name="secret" id="secret" type="text" size="25" value="31e114ab551cee492f6abf4b" /></td>
			<td>后台secret</td>
		</tr>
		<tr>
			<th scope="row"><label for="regid">reg id</label></th>
			<td><input name="regid" id="regid" type="text" size="25" value="170976fa8a8c872bdb9" autocomplete="off" /></td>
			<td>极光的Regitration Id</td>
		</tr>
		<tr>
			<th scope="row"><label for="content">发送内容</label></th>
			<td><input name="content" id="content" type="text" size="25" value="测试推送，1122334455-aabbccdd--嘿嘿嘿嘿嘿嘿嘿" /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="platform">平台</label></th>
			<td><input name="platform" id="platform" type="text" value="jpush" size="25" /></td>
			<td>极光jpush, 小米mipush</td>
		</tr>
	</table> -->

	<table class="form-table">
		<tr>
			<th scope="row"><label for="appid">App Id</label></th>
			<td><input name="appid" id="appid" type="text" size="25" value="" /></td>
			<td>后台key，小米推送则是包名</td>
		</tr>
		<tr>
			<th scope="row"><label for="secret">Master Secret</label></th>
			<td><input name="secret" id="secret" type="text" size="25" value="" /></td>
			<td>后台secret</td>
		</tr>
		<tr>
			<th scope="row"><label for="regid">reg id</label></th>
			<td><input name="regid" id="regid" type="text" size="25" value="KIDChgku66P+RPcaJ99YCZawOR2XS/bWqa5T26Bidh0=" autocomplete="off" /></td>
			<td>优先级第一，单设备推送</td>
		</tr>
		<tr>
			<th scope="row"><label for="aliases">Aliases</label></th>
			<td><input name="aliases" id="aliases" type="text" size="25" value="" autocomplete="off" /></td>
			<td>优先级第二，按别名推送，英文逗号分隔，如 alias1,alias2</td>
		</tr>
		<tr>
			<th scope="row"><label for="tags">Tags</label></th>
			<td><input name="tags" id="tags" type="text" size="25" value="" autocomplete="off" /></td>
			<td>优先级第三，按tag推送，英文逗号分隔，如 tag,tag2,tag3</td>
		</tr>


		<tr>
			<th scope="row"><label for="title">标题</label></th>
			<td><input name="title" id="title" type="text" size="25" value="测试推送--title" /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="content">发送内容</label></th>
			<td><input name="content" id="content" type="text" size="25" value="测试推送，1122334455-aabbccdd--嘿嘿嘿嘿嘿嘿嘿" /></td>
			<td></td>
		</tr>
		<tr>
			<th scope="row"><label for="payload">payload</label></th>
			<td><input name="payload" id="payload" type="text" size="25" value='{"type":1,"content":"payload内容"}' /></td>
			<td></td>
		</tr><tr>
			<th scope="row"><label for="device">android还是ios</label></th>
			<td><input name="device" id="device" type="text" size="25" value='android' /></td>
			<td>填android或者ios</td>
		</tr>
		<tr>
			<th scope="row"><label for="platform">平台</label></th>
			<td><input name="platform" id="platform" type="text" value="mipush" size="25" /></td>
			<td>极光jpush, 小米mipush</td>
		</tr>
	</table>

	<p class="step"><input id="submit" name="submit" type="button" value="开始推送" class="button button-large" /></p>
</form>
<p style="border: 1px solid green;padding:3px;margin-top:5px"> 极光文档：http://docs.jiguang.cn/jpush/client/Android/android_api/</p>
<p style="border: 1px solid green;padding:3px;margin-top:5px"> 小米文档：http://dev.xiaomi.com/doc/p=6421/index.html</p>
<script type='text/javascript' src='./assets/jquery.js'></script>
<script type='text/javascript' src='./assets/jquery-migrate.min.js?ver=1.4.1'></script>
<script type='text/javascript' src='./assets/jquery.min.js'></script>
<script type='text/javascript' src='./assets/language-chooser.min.js?ver=4.5.3'></script>
<script>

	$(document).ready(function(){
		$("#submit").click(function(){

			$("#res").text("正在发送...");

			$.ajax({
				url:'api.php',
				type:'POST', //GET
				async:true,    //或false,是否异步
				data:{
					appid: $("#appid").val(),
					secret: $("#secret").val(),
					device: $("#device").val(),
					tags: $("#tags").val(),
					aliases: $("#aliases").val(),
					regid: $("#regid").val(),
					title: $("#title").val(),
					content: $("#content").val(),
					payload: $("#payload").val(),
					platform: $("#platform").val()
				},
				timeout:5000,    //超时时间
				dataType:'text',    //返回的数据格式：json/xml/html/script/jsonp/text
				success:function(data){
					$("#res").text(data);
				}
			});

		});
	});

</script>
</body>
</html>


