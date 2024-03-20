<?php

namespace App\Http\Controllers;

use App\Models\Orden;
use App\Models\Producto;
use Illuminate\Http\Request;

class OrdenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ordenes = Orden::all();
            $response = $ordenes->toArray();
            $i = 0;
            foreach ($ordenes as $orden) {
                $response[$i]['producto'] = $orden->producto->toArray();
                $response[$i]['deliberies'] = $orden->delibery->toArray();
                $i++;
            }
            return response()->json($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.ordenes");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            $errors = 0;
            // creamos una instancia de Producto
            $orden = new Orden();
            $orden->nombre = $request->nombre;
            $orden->talla = $request->talla;
            $orden->precio = $request->precio;
            $orden->clasificacion_id = $request->clasificacion['id'];
            $orden->marca_id = $request->marca['id'];
            if ($orden->save() < 1) {
                $errors++;
            }
            //obtener el detalle de Producto 
            $detalle = $request->Producto;
            foreach ($detalle as $key => $det) {
                //creamos la instancia de Producto
                $orden = new Producto();
                $orden->nombre = $request->nombre;
                $orden->talla = $request->talla;
                $orden->precio = $request->precio;
                $orden->clasificacion_id = $request->clasificacion['id'];
                $orden->marca_id = $request->marca['id'];
                if ($orden->save() < 1) {
                    $errors++;
                }
            }
            if ($errors == 0) {
                return response()->json(['status' => 'Created', 'data' => $orden, 'message' => 'Su orden ha sido registrada'], 201);
            } else {
                return response()->json(['status' => 'Not Acceptable', 'data' => null, 'message' => 'Error al guardar la orden'], 406);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orden = Orden::findOrFail($id);
            $response = $orden->toArray();
            $response["producto"]= $orden->producto->toArray();
            $response["delibery"]= $orden->delibery->toArray();
            return response()->json($response);
        } catch (\Exception $e) {
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
        try {
            $orden = Orden::findOrFail($id);
            $orden->orden = $request->orden;
            if ($orden->update() > 0) {
                return response()->json(['status' => 'Accepted', 'data' => $orden, 'message' => 'La orden fue actualizada'], 202);
            } else {
                return response()->json(['status' => 'Not Acceptable', 'data' => null, 'message' => 'Error no se pudo actualizar la orden'], 406);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $orden = Orden::findOrFail($id);
            //Eliminando las ordenes
            if($orden->delete() > 0){
                return response()->json(['status'=>'Delete','data'=>null,'message'=>'Orden eliminada!'],205);  
            }else{
                return response()->json(["status"=>'Conflict', "data"=>null,"message"=>'La orden no pudo ser eliminada'],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
