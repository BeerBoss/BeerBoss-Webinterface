<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>BeerBoss</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/ionicons/css/ionicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/adminlte/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/adminlte/css/skins/skin-yellow.min.css')}}">
        @yield('css')
        <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-yellow sidebar-mini">
        @yield('body')

        <script src="{{asset('assets/jquery/js/jquery.min.js')}}"></script>
        <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/adminlte/js/adminlte.min.js')}}"></script>
        @yield('javascript')
    </body>
</html>