<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categorias = Categoria::all();
            return response()->json($categorias);
        }catch(\Exception $e){
            return $e->getMessage();
        }
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
            $tipo = $request->input("tipo");
            $record = Categoria::where("tipo",$tipo)->first();
            if($record){
                return response()->json(["status"=> 'Conflict',"data"=>null,"message"=>'Ya existe una Categoria con este Nombre'],409);
            }else{
                $categoria = new Categoria();
                $categoria->tipo = $request->tipo;
                if( $categoria->save() > 0){
                    return response()->json(["status"=>'Created',"data"=> $categoria,"message"=> 'Categoria Registrada'],201);
                }else{
                    return response()->json(["status"=>'fail',"data"=>null,"message"=>"Error al Intentar guardar la Categoria"],409);
                }  
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
            $categoria = Categoria::findOrfail($id);
            return response()->json($categoria);
        }catch(\Exception $e) {
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
            $tipo = $request->input("tipo");
            $record = Categoria::where("tipo", $tipo)->first();
            if($record){
                return response()->json(["status"=> 'Conflict', "data"=> null,"message"=>'Ya existe una categoria con este Nombre'],409);
            }else{
                $categoria = Categoria::findOrfile($id);
                $categoria->tipo = $request->tipo;
                if($categoria->update() >0){
                    return response()->json(["status"=> 'Updated',"data"=> $categoria,"message"=>'Categoria Actualizada...!'],202);
                }else{
                    return response()->json(["status"=> 'fail',"data"=>null,"message"=>"Error al intenar guardar la categoria"],409); 
                }
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
            $categoria = Categoria::findOrfail($id);
            if($categoria->delete()>0){
                return response()->json(["status"=>'Delete',"data"=>null,"message"=>'Categoria Eliminada...!'],205);
            }else{
                return response()->json(["status"=>'Conflict',"data"=>null,"message"=>'No se puede eliminar esta Categoria'],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    
    }
}
