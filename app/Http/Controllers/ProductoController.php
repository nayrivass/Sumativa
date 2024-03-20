<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Producto;
use Faker\Provider\Image;
use Illuminate\Http\Request;

class ProductoController extends Controller
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
        try{
            $productos = Producto::all();
            //lo vamos a convertir en un arreglo
            $response = $productos->toArray();
            $i=0;
            foreach($productos as $producto){
                $response[$i]["clasificacion"]= $producto->clasificacion->array();
                $response[$i]["categoria"]= $producto->categoria->toArray();
                $response[$i]["marca"]= $producto->marca->toArray();
                $response[$i]["imagenes"] = $producto->imagenes->toArray();
                $i++;
            }
            return response()->json($response);
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
           
            $record = Producto::where("nombre",$request->input("nombre"))->first();
            if($record){
                return response()->json(['status'=>'Conflict','data'=>null,'message'=>'Ya existe un producto con este nombre, digite otro...!'],409);
            }
            //creamos una instacnia de producto y llenamos el objeto
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->talla = $request->talla;
            $producto->color = $request->color;
            $producto->precio = $request->precio;
            $producto->stock = $request->stock;
            $producto->estado = $request->estado;
            $producto->clasificacion_id = $request->clasificacion_id;
            $producto->categoria_id = $request->categoria_id;
            $producto->marca_id = $request->marca_id;
            $result = $producto->save();
            //verificando si el producto trae imagenes
            if($request->hasfile('imagenes')){
                foreach($request->file('imagenes') as $imagen){
                    //obtenemos el nombre original de la imagen y generando un nombre unico
                    $imageName = time() .'_'. $imagen->getClientOriginalName();
                    //subiendo la imagen a la carpeta publica
                    $imagen->move(public_path('imagenes/productos/'), $imageName);

                    //creando instancia de imagen para guardar en la tabla imagenes
                    $img = new Imagen();
                    $img->nombre = $imageName;
                    $img->producto_id = $producto->id;
                    $img->save();
                }   
            }
            if($result > 0){
                $newProd = $this->show($producto->id);
                return response()->json(['status'=>'Created','data'=>$newProd,'message'=>'Producto Registardo con Exito...!'],201);
            }else{
                    return response()->json(['status'=>'Not Acceptable','data'=>null,'message'=>'Error al insertar el Registro'],406);
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
            $producto = Producto::findOrFail($id);
            $response = $producto->toArray();
            $response["Clasificacion"]= $producto->clasificacion->toArray();
            $response["Categoria"]= $producto->categoria->toArray();
            $response["marca"]= $producto->marca->toArray();
            $response["imagenes"]= $producto->imagenes->toArray();
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

            $record = Producto::where("nombre",$request->input("nombre"))->first();
            if($record){
                return response()->json(['status'=>'Conflict','data'=>null,'message'=>'Ya existe una marcacon este nombre, digite otro...!'],409);
            }
           //obtenemos el producto por el id del parametro y asignamos nuevos valores

            $producto = Producto::findOrFail($id);
            $producto->nombre = $request->nombre;
            $producto->descripcion = $request->descripcion;
            $producto->talla = $request->talla;
            $producto->color = $request->color;
            $producto->precio = $request->precio;
            $producto->stock = $request->stock;
            $producto->estado = $request->estado;
            $producto->clasificacion_id = $request->clasificacion['id'];
            $producto->categoria_id = $request->categoria['id'];
            $producto->marca_id = $request->marca['id'];
            if ($producto->update()>0){
                return response()->json(['status'=>'Accepted','data'=>$producto,'message'=>'Producto Actualizado...!'],202);
            }else{
                return response()->json(['status'=>'Not Acceptable','data'=>null,'message'=>'Error al actualizar el registro'],406);
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
            $producto = Producto::findOrFail($id);
            //Eliminando las imagenes fisicas
            foreach($producto->imagenes as $image){
                $imagePath = public_path(). '/imagenes/productos/' . $image->nombre;
                unlink($imagePath);
            }
            $producto->imagenes()->delete();
            if($producto->delete() > 0){
                return response()->json(['status'=>'Delete','data'=>null,'message'=>'Producto Eliminado...!'],205);  
            }else{
                return response()->json(["status"=>'Conflict', "data"=>null,"message"=>'No se puede eliminar este producto'],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
