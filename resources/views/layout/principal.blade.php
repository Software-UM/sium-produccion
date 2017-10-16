<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>SIUM | @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('components/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('components/font-awesome-4.6.3/css/font-awesome.min.css')}}">
@yield('css')
<!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('components/dist/css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('components/dist/css/skins/_all-skins.min.css')}}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset('components/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <link rel="stylesheet" href="{{ asset('components/plugins/sweetalert2/sweetalert2.min.css') }}">
    <style type="text/css">
        .sidebar-menu .treeview-menu > li.active{
            background-color: rgba(60, 141, 188,0.2);
        }
        .sidebar-menu > ul >li.active{
            background-color: rgba(60, 141, 188,0.2);
        }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition sidebar-mini skin-blue-light">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url("/")}}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>S</b>UM</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>S</b>IUM</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Control Sidebar Toggle Button -->
                    <li>
                        <a href="#" ><i class="fa fa-university"></i> Campus Activo: @yield('campus')</a>
                    </li>
                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{{ asset('components/dist/img/avatar5.png')}}" class="user-image"
                                 alt="User Image">
                            <span class="hidden-xs">{{ Auth::user()->email }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="{{ asset('components/dist/img/avatar5.png')}}" class="img-circle"
                                     alt="User Image">

                                <p>
                                    {{ Auth::user()->email }}
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Mi perfil</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat"><i
                                                class="fa fa-btn fa-sign-out"></i>Cerrar Sesión</a>
                                    <!--<a href="#" class="btn btn-default btn-flat">Cerrar sesión</a>-->
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset('components/dist/img/avatar5.png')}}" class="img-circle" alt="User Image">
                </div>
                <div class="pull-left info">
                    <p>{{ Auth::user()->email }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            </form>
            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header">Menú de navegación</li>
                <li>
                    <a href="{{url("/")}}">
                        <i class="fa fa-home"></i> <span>Menu Principal</span></i>
                    </a>

                </li>
                @yield('menuLateral')
            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('contenido')
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>SIUM - Versión</b> 0
        </div>
        <strong>Copyright &copy; <a href="http://www.universidadmaya.edu.mx">Departamento de Sistemas</a>.</strong>
    </footer>

    <!-- Este solo se habiltara para el super usuario -->
    <!-- The Right Sidebar -->
    <aside class="control-sidebar control-sidebar-light">
        <!-- Content of the sidebar goes here -->
    </aside>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<script src="{{ asset('components/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<script src="{{ asset('components/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('components/plugins/validator/WebNotifications.js') }}"></script>
@if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2)
    @if(Auth::user()->id != 9)
        <script >
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var w;

            if(typeof(Worker) !== "undefined") {
                if(typeof(w) == "undefined") {
                    w = new Worker("{{ asset('components/plugins/validator/worker.js') }}");
                }
                w.onmessage = function(event) {
                    showNotification();
                    //document.getElementById("result").innerHTML = event.data;
                };
            } else {
                document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Workers...";
            }


            function stopWorker() {
                w.terminate();
                w = undefined;
            }

            var n = 1;

            function showNotification() {
                $.ajax({
                    type: "GET",
                    url: "{{url('modules/personal/horario/logclases')}}",
                    data: {
                    },
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal(
                            'Ups! Error!',
                            errorThrown,
                            'error'
                        );

                    },
                    success: function (data, textStatus, jqXHR) {
                        //console.log(data);
                        if(data["success"]){
                            var notif = showWebNotification('Atención!',
                                'Hay Actualizaciones nuevas en las cargas académicas',
                                "{{ asset('components/dist/img/logotipo.png') }}", null, 30000);
                            notif.addEventListener("click", Notification_OnEvent);
                            n++;
                        }
                    }
                });

            }

            function Notification_OnEvent(event) {
                var notif = event.currentTarget;
                $.ajax({
                    type: "post",
                    url: "{{url('modules/personal/horario/logclases')}}",
                    data: {
                    },
                    dataType: "json",
                    error: function (jqXHR, textStatus, errorThrown) {
                        swal(
                            'Ups! Error!',
                            errorThrown,
                            'error'
                        );

                    },
                    success: function (data, textStatus, jqXHR) {
                        //console.log(data);
                        if(data["success"]){
                            console.log("todo bien");
                            window.location = "{{url('modules/personal/history')}}";
                        }
                    }
                });
            }
        </script>
    @endif
@endif
@yield('js')
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('components/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('components/dist/js/app.min.js')}}"></script>
<script>
    $(function () {
        $('input').keyup(function () {
            this.value = this.value.toLocaleUpperCase();
        });
    });
</script>

</body>
</html>
