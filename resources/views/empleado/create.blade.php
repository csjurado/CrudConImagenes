@extends('layouts.app')

@section('content')
<div class="container">
<form action="{{ url('/empleado') }}" method="post" enctype="multipart/form-data">
    @csrf

@include('empleado.form',['modo'=>'Crear']) <!-- Indica en que blade esta la parte del foumulario que hemos separado-->
</form>
</div>
@endsection