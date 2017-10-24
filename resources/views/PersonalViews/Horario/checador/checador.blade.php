<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('components/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('components/font-awesome-4.6.3/css/font-awesome.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('components/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{ asset('components/dist/css/skins/_all-skins.min.css')}}">
    <title>SIUM | Checador</title>
    <style>
        blockquote{
            border-left: 5px solid #006BFF !important;
        }
    </style>
</head>
<body class="sidebar-mini skin-blue-light">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">UM</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">Checador UM</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        </nav>
    </header>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <blockquote>
                <h4>{{$empleado->getNombre()}} {{$empleado->getApellidos()}}</h4>
                @if ($salon != null)
                    <h4>Salón: {{ $salon }}</h4>
                @endif 
                <label>Hora de registro:</label><p>{{$hora}}</p>
            </blockquote>
        </div>
        <div class="col-sm-12">
            <div class="col-sm-3">
                <!--Empty-->
            </div>
            <div class="col-sm-6">
                <?php
                    switch ($respuesta) {
                        case 1:
                            echo "<h2 class='bg-green'><center><i class='fa fa-check-circle fa-5x' aria-hidden='true'></i><br>";
                            echo "A TIEMPO</center></h2>";
                            break;
                        case 2:
                            echo "<h2 class='bg-blue'><center><i class='fa fa-clock-o fa-5x' aria-hidden='true'></i><br>";
                            echo "RETARDO</center></h2>";
                            break;
                        case 3:
                            echo "<h2 class='bg-red'><center><i class='fa fa-times fa-5x' aria-hidden='true'></i><br>";
                            echo "FUERA DE TIEMPO</center></h2>";
                            break;
                        case 4:
                            echo "<h2 class='bg-orange'><center><i class='fa fa-check-square-o fa-5x' aria-hidden='true'></i><br>";
                            if($salon != null)
                                echo "ENTRADA/SALIDA YA REGISTRADA EN ESTE SALON: ".$salon."</center></h2>";
                            else 
                                echo "ENTRADA/SALIDA YA REGISTRADA</center></h2>";
                            break;
                        case 6:
                            echo "<h2 class='bg-red'><center><i class='fa fa-times fa-5x' aria-hidden='true'></i><br>";
                            echo "ESTE CÓDIGO NO PERTENECE AL USUARIO</center></h2>";
                            break;
                        default:
                            echo "<h2 class='bg-red'><center><i class='fa fa-exclamation-circle  fa-5x' aria-hidden='true'></i> <br> ";
                            echo "NO CUENTA CON UN HORARIO ASIGNADO</center></h2>";
                            break;
                    }
                ?>
            </div>
            <div>
                <!--Empty-->
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('components/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<script src="{{ asset('components/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{ asset('components/dist/js/app.min.js')}}"></script>
</body>
</html>