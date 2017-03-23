<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="{{ URL::asset('/') }}/assets/towers/img/favicon.html">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ URL::asset('/') }}/assets/towers/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}/assets/towers/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{ URL::asset('/') }}/assets/towers/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href="{{ URL::asset('/') }}/assets/towers/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css" rel="stylesheet" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ URL::asset('/') }}/assets/towers/css/owl.carousel.css" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{ URL::asset('/') }}/assets/towers/css/style.css" rel="stylesheet">
    <link href="{{ URL::asset('/') }}/assets/towers/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="{{ URL::asset('/') }}/assets/account/js/html5shiv.js"></script>
      <script src="{{ URL::asset('/') }}/assets/account/js/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

  <section id="main-content">
      <section class="wrapper">
           @yield('body')
      </section>
  </section>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery-1.8.3.min.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/bootstrap.min.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery.scrollTo.min.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery.sparkline.js" type="text/javascript"></script>

    <script src="{{ URL::asset('/') }}/assets/towers/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js"></script>

    <script src="{{ URL::asset('/') }}/assets/towers/js/owl.carousel.js" ></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/jquery.customSelect.min.js" ></script>

    <script src="{{ URL::asset('/') }}/assets/towers/layer/layer.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/layer/extend/layer.ext.js"></script>

    <!--common script for all pages-->
    <script src="{{ URL::asset('/') }}/assets/towers/js/common-scripts.js"></script>

    <!--script for this page-->
    <script src="{{ URL::asset('/') }}/assets/towers/js/sparkline-chart.js"></script>
    <script src="{{ URL::asset('/') }}/assets/towers/js/easy-pie-chart.js"></script>

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
