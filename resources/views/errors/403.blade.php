@extends('layout.menu')
@section('title',"Menu principal")
@section('contenido')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Error 403
                <small>Sistema Integral Universidad Maya</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">403 error</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-lg-1">&nbsp;</div>
                <div class="col-lg-3">
                    <img src="{{asset("components/dist/img/UMredondo.png")}}" class="img-responsive center-block" >
                </div>
                <div class="col-lg-7">
                    <div class="error-page pull-left">

                        <h2 class="headline text-yellow">403</h2>

                        <div class="error-content">
                            <h2><i class="fa fa-warning text-yellow"></i> Acceso Denegado/Prohibido.</h2>

                            <p>
                                Al parecer no cuentas con los permisos suficientes para acceder a esta página.
                                Puedes <a href="/">regresar al menu principal</a> o comunicate con el
                                administrador web.
                            </p>
                        </div>
                        <!-- /.error-content -->
                    </div>
                    <!-- /.error-page -->
                </div>
            </div>


        </section>
    </div>
@endsection
