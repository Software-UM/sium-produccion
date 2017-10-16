@extends('layout.principal')
@section('campus') {{$plantel->nombre}} @endsection
@section('title',"Menu principal")
@section('menuLateral')
    <li class="treeview">
        <a href="/modules/escolar/materia">
            <i class="fa fa-book"></i></i> <span>Materias</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/carrera">
            <i class="fa fa-graduation-cap"></i></i> <span>Carreras</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/grupos">
            <i class="fa fa-list-ul"></i></i> <span>Grupos</span></i>
        </a>
    </li>
    <li class="treeview">
        <a href="/modules/escolar/ciclos">
            <i class="fa fa-calendar"></i></i> <span>Ciclos</span></i>
        </a>
    </li>
@endsection

@section('contenido')
    <section class="content-header">
        <h1>
            Control
            <small>Escolar</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li class="active">Control</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Módulo de Control Escolar</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

            </div>
            <!-- /.box-body -->
        </div>
    </section>
@endsection