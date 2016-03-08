<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $page_title or "Weboffice" }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/dist/css/skins/_all-skins.min.css") }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/iCheck/flat/blue.css") }}">
  
  <link rel="stylesheet" href="{{ asset("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker-bs3.css") }}">
  
  <!-- Custom css -->
  <link rel="stylesheet" href="{{ asset("/assets/css/weboffice.css") }}">
  
  @yield('css')  
  
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include('/layouts/_header')
  @include('/layouts/_main_sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        {{ $page_title or "* isdat weboffice" }}
        <small>{{ $page_description or null }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	  @yield('content')    
    </section>
  </div>
  <!-- /.content-wrapper -->
  @include('/layouts/_footer')

  <!-- Control Sidebar -->
  @include('/layouts/_control_sidebar')
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset ("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Bootstrap 3.3.5 -->
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/dist/js/app.min.js") }}"></script>
<!-- Slimscroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.js"></script>

<!-- Date range picker scripts -->
<script type="text/javascript" src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.min.js") }}"></script>
<script type="text/javascript" src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/daterangepicker.js") }}"></script>

<!-- Application specific scripts -->
<script src="{{ asset ("/assets/js/modal-confirm.js") }}"></script>
<script src="{{ asset ("/assets/js/date-range.js") }}"></script>
<script src="{{ asset ("/assets/js/relation-project-select.js") }}"></script>

@yield('js')  

<script type="text/javascript">
    // date range picker configuration:
    var dateRangeConfig = {
        startDate: moment("{{ $daterangeStart->format('Y-m-d') }}"),
        endDate: moment("{{ $daterangeEnd->format('Y-m-d') }}"),
        linkTitle: "{{ $daterangeStart->toFormattedDateString() }} - {{ $daterangeEnd->toFormattedDateString() }}",
        URL: "{{ route('daterange') }}",
        firstDate: moment("{{ $daterangeFirst->format('Y-m-d') }}"),
    };

    var token = "{{ csrf_token() }}";
</script>

</body>
</html>
