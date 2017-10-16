@extends('layout.menu')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Panel de Admnistración")
@section('contenido')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Panel de Control
                <small>Sistema Integral Universidad Maya</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url("/") }}" class="active"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Gestionar</h3>
                            <p><strong>Personal</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <a href="{{ url("admin/personal/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Accesos</h3>
                            <p><strong>al sistema</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-unlock-alt"></i>
                        </div>
                        <a href="{{ url("admin/accesos/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Reporte de </h3>
                            <p><strong>Fallos</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-bug"></i>
                        </div>
                        <a href="{{ url("admin/bugs/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Movimientos </h3>
                            <p><strong>Sistema</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-random"></i>
                        </div>
                        <a href="{{ url("admin/movimientos/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Registrar </h3>
                            <p><strong>Administrativos</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <a href="{{ url("admin/register/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Registrar </h3>
                            <p><strong>Docentes</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-user-plus"></i>
                        </div>
                        <a href="{{ url("docente/register/") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection