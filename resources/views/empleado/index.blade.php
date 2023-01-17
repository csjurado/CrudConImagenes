@extends('layouts.app')

@section('content')
<div class="container">

    @if(Session::has('mensaje'))
    <div class="alert alert-success alert-dismissible" role="alert">
   
    {{Session::get('mensaje')}}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    </div>
    @endif

 




<a href="{{url('empleado/create')}}" class="btn btn-success">Registrar nuevo empleado </a>
<br>
<br>
<table class="table table-dark">
    <thead class="thead-dark">
        <tr>
            <th>#</th>
            <th>Fotografía</th>  {{-- <!--En la cabecera es decir el thead debemos poner los titulos de las columnas  --> --}}
            <th>Nombre</th>
            <th>Apellido Paterno</th>
            <th>Apellido Materno</th>
            <th>Correo</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @foreach ( $empleados as $empleado ) <!--El for lo utilizamos para trabjaar con los datos la varaible $empleados se encuentra en el EmpleadosController -->
        <tr>
            <td>{{$empleado->id}}</td>
            <!--Foto o el parametro que sea debe estar igual al de la base de datos  -->
            <td>
                <img class ="img-thumbnail immg-fluid"src="{{asset('storage').'/'.$empleado->Foto }}" width="100" alt=" " >
            </td>
            <td>{{$empleado->Nombre}}</td>
            <td>{{$empleado->ApellidoPaterno}}</td>
            <td>{{$empleado->ApellidoMaterno}}</td>
            <td>{{$empleado->Correo}}</td>
            <td>
                
            <a href="{{url('/empleado/'.$empleado->id.'/edit')}}" class ="btn btn-warning">
            Editar 
            </a>
           
            <form action="{{url('/empleado/'.$empleado->id)}}" class ="d-inline" method="post" >  <!--Utilizamos el punto para concatenar  || el método post nos sirve para buscar la informacion-->
            @csrf
            {{method_field('DELETE')}}  <!--Necesitamos el metodo delete para borrar  -->
            <input class ="btn btn-danger"type="submit" onclick="return confirm('¿Estás seguro de que quieres borrar ?')" value="Borrar">

            </form> 
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{!!$empleados->links() !!}
</div>
@endsection