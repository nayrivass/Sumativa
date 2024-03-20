<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.clasificasion");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $genero = $request->input("genero");
            $record = Clasificacion::where("genero", $genero)->first();
            if ($record) {
                return response()->json([
                    "status" => 'Conflict', "data" => null,
                    "message" => 'Ya existe una categoria con este nombre'
                ], 409);
            } else {
                $clasificasion = new Clasificacion();
                $clasificasion->genero = $request->genero;
                if ($clasificasion->save() > 0) {
                    return response()->json([
                        "status" => 'Created',
                        "data" => $clasificasion, "message" => 'Categoria registrada'
                    ], 201);
                } else {
                    return response()->json([
                        "status" => 'fail', "data" => null,
                        "message" => "Error al intentar guardar la categoria"
                    ], 409);
                }
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
            $clasificasion = Clasificacion::findOrFail($id);
            return response()->json($clasificasion);
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
            $genero = $request->input("genero");
            $record = Clasificacion::where("genero", $genero)->first();
            if ($record) {
                return response()->json([
                    "status" => 'Conflict', "data" => null,
                    "message" => 'Ya existe una categoria con este nombre'
                ], 409);
            } else {
                $clasificasion = Clasificacion::findOrFail($id);
                $clasificasion->genero= $request->genero;
                if ($clasificasion->update() > 0) {
                    return response()->json([
                        "status" => 'Update',
                        "data" => $clasificasion, "message" => 'Categoria Actualizada'
                    ], 201);
                } else {
                    return response()->json([
                        "status" => 'fail', "data" => null,
                        "message" => "Error al intentar actulizar la categoria"
                    ], 409);
                }
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
        try {
            $clasificasion = Clasificacion::findOrFail($id);
            if ($clasificasion->delete() > 0) {
                return response()->json([
                    "status" => 'Deleted',
                    "data" => null, "message" => 'Categoria eliminada...!'
                ], 205);
            } else {
                return response()->json([
                    "status" => 'Deleted',
                    "data" => null, "message" => 'No se puede eliminar esta categoria'
                ], 205);
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    
    }
}
