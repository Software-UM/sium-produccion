@extends('layout.principal')
@section('title',"Registrar Empleado")
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/daterangepicker/daterangepicker-bs3.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('components/plugins/datepicker/datepicker3.css')}}">
    <!-- Checkbox css -->
    <link rel="stylesheet" href="{{ asset('components/plugins/iCheck/flat/_all.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('components/plugins/select2/select2.min.css') }}">
    <style>
        canvas {
            display: none;
        }

        hr {
            height: 1px;
            margin-left: auto;
            margin-right: auto;
            background-color: #3c8dbc;
            color: #3c8dbc;
            border: 0 none;
        }


    </style>
@endsection
@section('menuLateral')
    <li class="treeview active">
        <a href="#">
            <i class="fa fa-user"></i><span>Pesonal</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li class="active"><a href="{{ url("modules/personal/") }}"><i class="fa fa-arrow-circle-right"></i> Gestión del
                    Personal</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-clock-o"></i><span>Horario</span>
            <i class="fa fa-angle-left pull-right"></i>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url("modules/personal/horario") }}"><i class="fa fa-circle-o"></i> Tipos de Horarios</a></li>
            <li><a href="{{ url("modules/personal/horario/asignacion/add")}}"><i class="fa fa-circle-o"></i> Asignación Horario Personal</a>
            </li>
            <li><a href=" {{ url("modules/personal/horario/tipo")}}"><i class="fa fa-circle-o"></i> Parametros Horario</a></li>
            </li>
        </ul>
    </li>
@endsection
@section('contenido')
    <section class="content-header">
        <h1>
            Personal
            <small>Fotografía</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Modulos</a></li>
            <li><a href="#">Personal</a></li>
            <li class="active">Foto</li>

        </ol>
    </section>
    <section class="content">
        <div class="box box-primary">
            <div class="box-header">
                <h1 class="box-title">Información General</h1>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="col-md-1"></div>
                <div class="col-md-1"></div>
                <div class="col-md-12">
                    <div class="col-md-6">
                        @foreach($empleados as $empleado)
                            <input type="hidden" value="{{$empleado->id}}" id="empleado">
                            <h4>Nombre:</h4>
                            {{$empleado->nombre}}
                            <hr>
                            <h4>Profesión:</h4>
                            {{$empleado->profesion}}
                            <hr>
                            <h4>Puesto:</h4>
                            {{$empleado->descripcion}}
                            <hr>
                            <h4>Departamento:</h4>
                            {{$empleado->depa}}
                            <hr>

                        @endforeach

                    </div>
                    <div class="col-md-5">
                        <button class="btn btn-block btn-social btn-warning" data-toggle="modal"
                                data-target="#modalFoto"><i class="fa fa-plus"></i>Capturar
                            Foto
                        </button>
                    </div>
                    <div class="col-md-5">
                        <canvas id="canvas"></canvas>
                        <img src="{{ asset("components/dist/img/avatar06.png") }}" id="photo" alt="photo">
                        <p>&nbsp;</p>

                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="col-sm-5">&nbsp;</div>
                    <div class="col-sm-2">
                        <button class="btn btn-block btn-social btn-success" id="saveButton"><i class="fa fa-plus"></i>Guardar
                            Foto
                        </button>
                    </div>
                    <div class="col-sm-5">&nbsp;</div>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- Modal Carga Académica -->
        <div class="modal fade" id="modalFoto" role="">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Foto empleado</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-sm-6 col-md-offset-2">
                                <video id="video"></video>
                                <p>&nbsp;</p>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="startbutton" data-dismiss="modal">Tomar Foto
                        </button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@section("js")
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(function () {
            var streaming = false,
                    video = document.querySelector('#video'),
                    cover = document.querySelector('#cover'),
                    canvas = document.querySelector('#canvas'),
                    photo = document.querySelector('#photo'),
                    startbutton = document.querySelector('#startbutton'),
                    width = 400,
                    height = 0;

            navigator.getMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

            navigator.getMedia(
                    {
                        video: true,
                        audio: false
                    },
                    function (stream) {
                        if (navigator.mozGetUserMedia) {
                            video.mozSrcObject = stream;
                        } else {
                            var vendorURL = window.URL || window.webkitURL;
                            video.src = vendorURL ? vendorURL.createObjectURL(stream) : stream;
                        }
                        video.play();
                    },
                    function (err) {
                        console.log("An error occured! " + err);
                    }
            );

            video.addEventListener('canplay', function (ev) {
                if (!streaming) {
                    height = video.videoHeight / (video.videoWidth / width);
                    video.setAttribute('width', width);
                    video.setAttribute('height', height);
                    canvas.setAttribute('width', width);
                    canvas.setAttribute('height', height);
                    streaming = true;
                }
            }, false);
            function takepicture() {
                canvas.width = width;
                canvas.height = height;
                canvas.getContext('2d').drawImage(video, 0, 0, width, height);
                var data = canvas.toDataURL('image/png');
                photo.setAttribute('src', data);
            }

            startbutton.addEventListener('click', function (ev) {
                takepicture();
                ev.preventDefault();
            }, false);

            $("#saveButton").click(function () {
                var data = canvas.toDataURL('image/png');

                var blobBin = atob(data.split(',')[1]);
                var array = [];
                for(var i = 0; i < blobBin.length; i++) {
                    array.push(blobBin.charCodeAt(i));
                }
                var file=new Blob([new Uint8Array(array)], {type: 'image/png'});

                var idEmpleado = $("#empleado").val();
                var formdata = new FormData();
                formdata.append("foto", file);
                formdata.append("id", idEmpleado);
                $.ajax({
                    url: "{{url("modules/personal/foto")}}",
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                }).done(function(respond){
                    alert(respond);
                });
            });
        });
    </script>
@endsection