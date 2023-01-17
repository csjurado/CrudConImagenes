<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $datos['empleados']=Empleado::paginate(3);
        return view('empleado.index',$datos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('empleado.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
            'Foto'=>'required|max:10000|mimes:jpeg,png,jpg',
        ];
       
        $mensaje=[
            'required'=>'El :attribute es requerido',
            'Foto.required'=> 'La foto es requerida'
        ];

        $this->validate($request,$campos,$mensaje);
        // $datosEmpleado = request()->all();
        $datosEmpleado = $request->except('_token'); // le quita la información al token para que lo demsa inserte a la base de datos 
        if($request->hasfile('Foto')){
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
            /*En esta caso esta utilizando todo la informacion pero se centra en la foto y el arhcivo foto la guarada en la direciicón que le este almacenando */
            /*Este codigo es para el Cloudinary */
            $file = request()->file('Foto');
            $obj = Cloudinary::upload($file->getRealPath(),['folder'=>'uploads']);
            // $imagen = $obj->getPublicId();
            $url = $obj->getSecurePath();
        }
        Empleado::insert($datosEmpleado);
        //return response()->json($datosEmpleado);
        return redirect('empleado')->with('mensaje','Empelado agregado con Exito!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function show(Empleado $empleado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $empleado =Empleado::findOrFail($id); // Buscamos la informacion 
        return view('empleado.edit',compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $campos=[
            'Nombre'=>'required|string|max:100',
            'ApellidoPaterno'=>'required|string|max:100',
            'ApellidoMaterno'=>'required|string|max:100',
            'Correo'=>'required|email',
        ];
       
        $mensaje=[
            'required'=>'El :attribute es requerido',
        ];
        if($request->hasfile('Foto')){
            $campos=['Foto'=>'required|max:10000|mimes:jpeg,png,jpg',];
            $mensaje=['Foto.required'=> 'La foto es requerida'];
        }

        $this->validate($request,$campos,$mensaje);

        $datosEmpleado = $request->except(['_token','_method']); // Recibimos los datos a excepcion del toker y el método 

        if($request->hasfile('Foto')){
            $empleado =Empleado::findOrFail($id); // Buscamos la informacion  de acuerdo al id 
            Storage::delete('public/'.$empleado->Foto);// Borrarmos la foto anterior 
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
            
            /*En esta caso esta utilizando todo la informacion pero se centra en la foto y el arhcivo foto la guarada en la direciicón que le este almacenando */
            /*Este codigo es para el cloudinary*/
            $file = request()->file('Foto');
            $obj = Cloudinary::upload($file->getRealPath(),['folder'=>'uploads']);
            $url = $obj->getSecurePath(); 
            //Empleado::upload('Foto'->$url); Posible solución entrega un string 
            //Storage::disk('public')->delete($datosEmpleado->Foto);

            
        }

        Empleado::where('id','=',$id)->update($datosEmpleado); // Buscamos el registro cuando el id  sea igual al id que ya tenia, se actualiza

        $empleado =Empleado::findOrFail($id); // Buscamos la informacion  de acuerdo al id 
        //return view('empleado.edit',compact('empleado'));
        return redirect('empleado')->with('mensaje','Empleado modificado con exito ');// Redirige a la vista empleado aunque no hace falta poner empleado/index si tienes duda úedes cambiar por empleado/create
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Empleado  $empleado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $empleado =Empleado::findOrFail($id); // Buscamos la informacion  de acuerdo al id 
        if(Storage::delete('public/'.$empleado->Foto)){
            Empleado::destroy($id);
        }
        
        return redirect('empleado')->with('mensaje','Empleado borrado con exito ');// Redirige a la vista empleado aunque no hace falta poner empleado/index si tienes duda úedes cambiar por empleado/create
    }
}
