@extends('layouts.plane')
@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">

            <!-- Logo -->
            <a href="#" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"><b>B</b>B</span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b>Beer</b>Boss</span>
            </a>

            <!-- Header Navbar -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <!-- Navbar Right Menu -->
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account Menu -->
                        <li class="dropdown user user-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <!-- The user image in the navbar-->
                                <!-- hidden-xs hides the username on small devices so only the image appears. -->
                                <span class="hidden-xs">{{Auth::user()->name}}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- The user image in the menu -->
                                <li class="user-header">
                                    <img src="{{asset('assets/beerboss/img/beerglass.png')}}" class="img-circle" alt="User Image">
                                    <p>
                                        {{Auth::user()->name}}
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{ route('logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- Control Sidebar Toggle Button -->
                        <li>
                            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar user panel (optional) -->
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{asset('assets/beerboss/img/beerglass.png')}}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                        <p>{{Auth::user()->name}}</p>
                        <!-- Status -->
                        <i class="fa fa-envelope"></i> {{Auth::user()->email}}
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                    <li><a href="{{route('home')}}"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
                    <li class="header">Stats</li>
                    <li><a href="{{route('tempStats')}}"><i class="fa fa-line-chart"></i><span>Temperature stats</span></a></li>
                    <li><a href="{{route('connStats')}}"><i class="fa fa-plug"></i><span>Connection stats</span></a></li>
                    <li class="header">Actions</li>
                    <li><a href="{{route('manageProfiles')}}"><i class="fa fa-thermometer-empty"></i><span>Manage profiles</span></a></li>
                    <li class="header">Settings</li>
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <h1>
                    @yield('header')
                </h1>
            </section>

            <!-- Main content -->
            <section class="content container-fluid">
                @yield('content')
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="pull-right hidden-xs">
                Alpha v1
            </div>
            <!-- Default to the left -->
            <strong>Powered by <a href="https://beerboss.github.io">BeerBoss</a>.</strong>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark" id="vue-sidebar">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li class="active"><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Settings tab content -->
                <div class="tab-pane active" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Connection status</h3>
                    <p>
                        <b>@{{ connInfo.hostname }} </b>(<span v-html="status"></span>)<br>
                         @{{ connInfo.ip }}
                    </p>
                    <h3 class="control-sidebar-heading">Temperature status</h3>
                    <p v-if="online">
                        Fridge temperature: @{{ lastTemp.fridgeTemp }} <br>
                        Barrel temperature: @{{ lastTemp.barrelTemp }}
                    </p>
                    <p v-else>
                        Fridge not online!
                    </p>
                    <h3 class="control-sidebar-heading">Beer profile Status</h3>
                    <p v-if="activeProfile">
                        <b>@{{ activeProfile.name }} </b><br>
                        Date started: @{{ activeProfile.dateStarted }}<br>
                        Current day: @{{ activeProfileDay }}<br>
                        Current temperature: @{{ activeProfilePart.desiredTemp }}
                    </p>
                    <p v-else>
                        No active profile setup yet.
                    </p>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
        immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <script>
        var connInfoUrl = '{{route('getConnStats')}}';
        var activeProfileUrl = '{{route('getActiveProfile')}}';
        var lastTempUrl = '{{route('getLastTemp')}}';
    </script>
@stop