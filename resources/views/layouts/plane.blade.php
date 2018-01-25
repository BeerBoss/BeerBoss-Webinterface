<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>BeerBoss - @yield('header')</title>
        <link rel="shortcut icon" href="{{asset('assets/beerboss/img/beerglass.png')}}" />
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
        <script src="{{asset('assets/momentjs/js/moment.min.js')}}"></script>
        <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets/adminlte/js/adminlte.min.js')}}"></script>
        <script src="{{asset('assets/chartjs/js/Chart.bundle.min.js')}}"></script>
        <script src="{{asset('assets/vue/js/vue.js')}}"></script>
        <script src="{{asset('assets/vue-resource/js/vue-resource.js')}}"></script>
        <script src="{{asset('assets/vue/js/vue-chartjs.min.js')}}"></script>
        <script src="{{asset('assets/lodash/js/lodash.min.js')}}"></script>
        <script src="{{asset('assets/beerboss/js/main.js')}}"></script>
        @yield('javascript')
    </body>
</html>