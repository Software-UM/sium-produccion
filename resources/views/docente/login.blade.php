<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIUM | Iniciar Sesion</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{ asset('components/bootstrap/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('components/dist/css/AdminLTE.min.css')}}">

    <link rel="stylesheet" href="{{ asset('components/plugins/iCheck/flat/blue.css')}}">

</head>


<body style="background: #d2d6de;">
<header class="main-header" style="background: #3c8dbc; color:#fff;">

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <div class="logo" style="width:400px;">

            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>SIUM</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>SIUM</b> Sistema Integral Universidad Maya</span>

        </div>
        <div class="navbar-custom-menu">

        </div>
    </nav>
</header>
<div class="hold-transition login-page">
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Acceso a Docentes</p>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/docente/login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                    <label for="email" class="col-md-3 control-label">Usuario</label>

                    <div class="col-md-8">
                        <input id="email" type="text" class="form-control" name="usuario" value="{{ old('usuario') }}">

                        @if ($errors->has('usuario'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('usuario') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-3 control-label">Contraseña</label>

                    <div class="col-md-8">
                        <input id="password" type="password" class="form-control" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Recordarme
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-3">
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-btn fa-sign-in"></i> Ingresar
                        </button>

                        <a class="btn btn-link pull-right" href="{{ url('/docente/password/reset') }}">¿Olvidaste tu contraseña?</a>
                        <a class="btn btn-link pull-right" href="{{ url('/') }}">¿Eres Administrativo? Accede Aquí</a>
                    </div>
                </div>
            </form>


            <a href="#"></a>

        </div>
        <!-- /.login-box-body -->
    </div>
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.0 -->
<script src="{{ asset('components/plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('components/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{ asset('components/plugins/iCheck/icheck.min.js')}}"></script>
<script>
    var revisa = true;
    function concatUser() {
        if (revisa) {
            var email = $("#email").val();
            var n = email.indexOf("@");
            if (n < 1) {
                $("#email").val(email + "@universidadmaya.edu.mx");
                revisa = false;
            }
            revisa = false;
        }
    }
</script>
</body>
</html>
