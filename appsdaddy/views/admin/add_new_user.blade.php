<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>FlatLab - Flat & Responsive Bootstrap Admin Template</title>

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

      <form class="form-signin" action="index.html">
        <h2 class="form-signin-heading">sign in now</h2>
        <div class="login-wrap">
            <input type="text" class="form-control" placeholder="User ID" autofocus>
            <input type="password" class="form-control" placeholder="Password">
            <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right"> <a href="#"> Forgot Password?</a></span>
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
            <p>or you can sign in via social network</p>
            <div class="login-social-link">
                <a href="index.html" class="facebook">
                    <i class="icon-facebook"></i>
                    Facebook
                </a>
                <a href="index.html" class="twitter">
                    <i class="icon-twitter"></i>
                    Twitter
                </a>
            </div>

        </div>

      </form>

    </div>


  </body>
</html>
