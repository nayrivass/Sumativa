<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $pedidos = Pedido::all();
            $pedidos = Pedido::orderBy('correlativo','desc')->get();
            $responce = $pedidos->toArray();
            $i=0;
            foreach($pedidos as $pedido){
                $responce[$i]["cliente"] = $pedido->user->toArray();
                //obtenemos el detalle de la Orden
                $detalle = $pedido->detallePedidos->toArray();
                //recorremos el detalle de la orden
                $f = 0;
                foreach($pedido->detallePedido as $item){
                    $detalle[$f]['producto'] = $item->producto->toArray();
                    $detalle[$f]['producto']['marca'] = $item->producto->marca->toArray();
                    $detalle[$f]['producto']['categoria'] = $item->producto->toArray();
                    $f++;
                }
                $responce[$i]["detallePedido"] = $detalle;
                $i++;
            }
            return response()->json($responce);
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
            $errors = 0;
            DB::beginTransaction();
            // creamos una instancia de Orden
            $pedido = new Pedido();
            $pedido->correlativo = $this->getCorrelativo();
            $pedido->fecha = $request->fecha;
            $pedido->status = $request->status;
            $pedido->monto = $request->monto;
            $pedido->user_id = $request->user['id'];
            if($pedido->save() < 1){
                $errors++;
            }
            //obtener el datalle de la orden 
            $detalle = $request->detallePedido;
            foreach($detalle as $key => $det){
                //creamos la instancia de DetalleOrden
                $detallePedido = new DetallePedido();
                $detallePedido->cantidad = $det['cantidad'];
                $detallePedido->precio = $det['precio'];
                $detallePedido->producto_id = $det['producto']['id'];
                $detallePedido->pedido_id = $pedido->id;
                if($detallePedido->save() < 1){
                    $errors++;
                }
            }
            if($errors == 0){
                DB::commit();
                return response()->json(['status'=>'Created','data'=>$pedido,'message'=>'Su orden ha sido registrada...!'],201);
            }else{
                DB::rollBack();
                return response()->json(['status'=>'Not Acceptable','data'=>null,'message'=>'Error al guardar la orden, intente de nuevo...!'],406);
            }


        }catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $pedido = Pedido::find($id);
            $response = $pedido->toArray();
            $response["cliente"] = $pedido->user->toArray();
            //Obtenemos el detalle de la Orden
            $detalle = $pedido->detallePedidos->toArray();
            //recoremos el detalle de la orden
            $i = 0;
            foreach($pedido->detallePedidos as $item){
                $detalle[$i]['producto'] = $item->producto->toArray();
                $detalle[$i]['producto']['clasificacion'] = $item->producto->clasificacion->toArray();
                $detalle[$i]['producto']['marca'] = $item->producto->marca->toArray();
                $detalle[$i]['producto']['categoria'] = $item->producto->toArray();
                $id++;
            }
            $responce['detallePedidos'] = $detalle;
            return response()->json($response);
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
            $pedido = Pedido::find($id);
            if($request->status == 'D'){
                $fechaActual = date('Y-m-d');
                $pedido->fecha_despacho = $fechaActual;
            }
            $pedido->status = $request->status;
            if($pedido->update()>0){
                return response()->json(['status'=>'Accepted','data'=>$pedido,'message'=>'El estado de la Orden ha sido Cambiada'],202);
            }else{
                return response()->json(['status'=>'Not Acceptable','data'=>null,'message'=>'Error al cambiar el estado de la Orden'],406);

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
        //
    }
    private function getCorrelativo(){
        $result = DB::select("SELECT CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0),LPAD(IFNULL(MAX(TRIM(SUBSTRING(correlativo,7,4))),0)+1,4,0)) as correlativo FROM ordenes WHERE SUBSTRING(correlativo,1,6) = CONCAT(TRIM(YEAR(CURDATE())),LPAD(TRIM(MONTH(CURDATE())),2,0))");
        return $result[0]->correlativo;
    }
}
