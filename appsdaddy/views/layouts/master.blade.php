<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ $assets }}towers/img/favicon.html">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ $assets }}towers/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $assets }}towers/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{ $assets }}towers/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ $assets }}towers/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ $assets }}towers/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{ $assets }}towers/css/style.css" rel="stylesheet">
    <link href="{{ $assets }}towers/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ $assets }}account/js/html5shiv.js"></script>
      <script src="{{ $assets }}account/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
            <div class="sidebar-toggle-box">
                <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
            </div>
            <!--logo start-->
            <a href="#" class="logo">AlphaGo<span> 智能App管理平台</span></a>
            <!--logo end-->
            
            <div class="top-nav ">
                <!--search & user info start-->
                <ul class="nav pull-right top-menu">
                    <li>
                        <input type="text" class="form-control search" placeholder="Search">
                    </li>
                    <!-- user login dropdown start-->
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="{{ $assets }}towers/img/avatar1_small.jpg">
                            <span class="username">Jhon Doue</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="#"><i class=" icon-suitcase"></i>Profile</a></li>
                            <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
                            <li><a href="#"><i class="icon-bell-alt"></i> Notification</a></li>
                            <li><a href="login.html"><i class="icon-key"></i> Log Out</a></li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
                <!--search & user info end-->
            </div>
        </header>
      <!--header end-->
      <!--sidebar start-->
      <aside>
          <div id="sidebar"  class="nav-collapse ">
              <!-- sidebar menu start-->
              <ul class="sidebar-menu">
                  <li class="active">
                      <a class="" href="{{ $base }}admin/home&sid={{$sid}}">
                          <i class="icon-dashboard"></i>
                          <span>项目管理</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="{{ $base }}timeline/list&sid={{$sid}}">
                          <i class="icon-dashboard"></i>
                          <span>Feed流--Timeline</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="{{ $base }}admin/home&sid={{$sid}}">
                          <i class="icon-dashboard"></i>
                          <span>Feed流--Top</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="task_mgmr.html&sid={{$sid}}">
                          <i class="icon-tasks"></i>
                          <span>自动更新</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="user_mgmr.html&sid={{$sid}}">
                          <i class="icon-cogs"></i>
                          <span>日志管理</span>
                      </a>
                  </li>
                  <li class="sub-menu">
                      <a class="" href="admin_mgmr.html&sid={{$sid}}">
                          <i class="icon-book"></i>
                          <span>leak & block</span>
                      </a>
                  </li>
                   <li class="sub-menu">
                      <a class="" href="h5demo_list.html&sid={{$sid}}">
                          <i class="icon-book"></i>
                          <span>H5 Demos</span>
                      </a>
                  </li>
              </ul>
              <!-- sidebar menu end-->
          </div>
      </aside>
      <!--sidebar end-->
      <!--main content start-->
      <section id="main-content">
          <section class="wrapper">
               @yield('content')
          </section>
      </section>
      <!--main content end-->
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ $assets }}towers/js/jquery.js"></script>
    <script src="{{ $assets }}towers/js/jquery-1.8.3.min.js"></script>
    <script src="{{ $assets }}towers/js/bootstrap.min.js"></script>
    <script src="{{ $assets }}towers/js/jquery.scrollTo.min.js"></script>
    <script src="{{ $assets }}towers/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="{{ $assets }}towers/js/jquery.sparkline.js" type="text/javascript"></script>

    <script src="{{ $assets }}towers/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>

    <script src="{{ $assets }}towers/js/owl.carousel.js" ></script>
    <script src="{{ $assets }}towers/js/jquery.customSelect.min.js" ></script>

    <script src="{{ $assets }}towers/layer/layer.js"></script>
    <script src="{{ $assets }}towers/layer/extend/layer.ext.js"></script>

    <!--common script for all pages-->
    <script src="{{ $assets }}towers/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="{{ $assets }}towers/js/sparkline-chart.js"></script>
    <script src="{{ $assets }}towers/js/easy-pie-chart.js"></script>


  


  <script>

      //owl carousel

      $(document).ready(function() {
          $("#owl-demo").owlCarousel({
              navigation : true,
              slideSpeed : 300,
              paginationSpeed : 400,
              singleItem : true

          });
      });

      //custom select box

      $(function(){
          $('select.styled').customSelect();
      });

  </script>


  @yield('script')
  </body>
</html>
