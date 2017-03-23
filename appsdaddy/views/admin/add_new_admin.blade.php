<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ URL::asset('/') }}/assets/towers/img/favicon.html">

    <title>添加管理员</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ URL::asset('/') }}/assets/towers/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}/assets/towers/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{ URL::asset('/') }}/assets/towers/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ URL::asset('/') }}/assets/towers/css/style.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}/assets/towers/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="{{ URL::asset('/') }}/assets/account/js/html5shiv.js"></script>
    <script src="{{ URL::asset('/') }}/assets/account/js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container">

      <form class="form-signin" action="addAdmin">
        <h2 class="form-signin-heading">sign in now</h2>
        <div class="login-wrap">
            <input type="text" name="username" class="form-control" placeholder="账号" autofocus>
            <input type="password" name="password" class="form-control" placeholder="密码">
            <input type="text" name="realname" class="form-control" placeholder="姓名">
            <input type="text" name="company" class="form-control" placeholder="备注">
            <input type="hidden" name="sid" class="form-control" value="{{$sid}}" />
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>

        </div>

      </form>

    </div>


  </body>
</html>
