@extends('layout.menu')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('contenido')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú Principal
                <small>Sistema Integral Universidad Maya</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ url("/") }}" class="active"><i class="fa fa-dashboard"></i> Home</a></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Personal</h3>
                                <p><strong>Administrar</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <a href="{{ url("modules/personal/") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->tipo == 1)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Control</h3>
                                <p><strong>Escolar</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <a href="{{ url("modules/escolar/") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->tipo != 0)
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>Carga</h3>
                            <p><strong>Académica</strong></p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-book"></i>
                        </div>
                        <a href="{{ url("modules/personal/horario/grupo") }}" class="small-box-footer">
                            Acceder <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                @endif
                @if (Auth::user()->tipo == 1)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Campus</h3>
                                <p><strong>&nbsp;</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-university"></i>
                            </div>
                            <a href="{{ url("modules/personal/plantel") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2)
                        @if(Auth::user()->id != 9)
                            <div class="col-lg-3 col-xs-6">
                                <!-- small box -->
                                <div class="small-box bg-aqua">
                                    <div class="inner">
                                        <h3>Incidencias</h3>
                                        <p><strong>&nbsp;</strong></p>
                                    </div>
                                    <div class="icon">
                                        <i class="fa fa-exclamation"></i>
                                    </div>
                                    <a href="{{ url("modules/personal/horario/incidencias") }}" class="small-box-footer">
                                        Acceder <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                @endif
                @if (Auth::user()->tipo == 1 || Auth::user()->tipo == 2 || Auth::user()->tipo == 3)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Reportes</h3>
                                <p><strong>&nbsp;</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-area-chart"></i>
                            </div>
                            <a href="{{ url("modules/personal/reportes/") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    @if(Auth::user()->id != 9)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Historial.</h3>
                                <p><strong>De cambios</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-history"></i>
                            </div>
                            <a href="{{ url("modules/personal/history") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                @endif
                @if (Auth::user()->tipo == 1)
                    <div class="col-lg-3 col-xs-6">
                        <!-- small box -->
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3>Config.</h3>
                                <p><strong>&nbsp;</strong></p>
                            </div>
                            <div class="icon">
                                <i class="fa fa-cogs"></i>
                            </div>
                            <a href="{{ url("admin/") }}" class="small-box-footer">
                                Acceder <i class="fa fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </section>
    </div>
@endsection