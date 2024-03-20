<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliberyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            //validando que no exista un nombre igual en db
            $nombre_conductor = $request->input("nombre_conductor");
            $record= telefono::where("nombre_conductor", $nombre_conductor)->first();
            if($record){
                return response()->json(['status'=>'Conflict','data'=>null,
            'message'=>'Ya existe un telefono...!'],409);
            }
            $telefono = new telefono(); //instancia
            $telefono->nombre_conductor = $request->nombre_conductor;
            if($telefono->save()>0){
                return response()->json(['status'=>'Created','data'=>$telefono,
                'message'=>'telefono registrado...!'],201);
            }else{
                return response()->json(['status'=>'Not Acceptable','data'=>null,
                'message'=>'Error al insertar el numero de telefono...!'],406);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $telefono = telefono::find($id);
            return response()->json($telefono);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $nombre_conductor= $request->input("nombre_conductor");
            $record= telefono::where("nombre_conductor", $nombre_conductor)->first();
            if($record){
                return response()->json(['status'=>'Conflict','data'=>null,
            'message'=>'Ya existe un  numero de telelfono...!'],409);
            }
            $telefono = telefono::findOrFail($id); 
            $telefono->nombre_conductor = $request->nombre_conductor;
            if($telefono->update()>0){
                return response()->json(['status'=>'Accepted','data'=>$telefono,
                'message'=>'numero de telefono Actualizado...!'],202);
            }else{
                return response()->json(['status'=>'Not Acceptable','data'=>null,
                'message'=>'Error al actualizar el numero de telefono...!'],406);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $telefono = telefono::find($id);
            if($telefono->delete() > 0){
                return response()->json(['status'=>'Delete','data'=>null,
                'message'=>'numero de telefono Eliminado...!'],205);
            }else{
                return response()->json(['status'=>'Conflict','data'=>null,
                'message'=>'No se puede eliminar este numero de telefono'],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        } 
    }
}
