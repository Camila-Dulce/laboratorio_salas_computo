<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    function index()  // Trae todos los registros
    {
        $salas = Sala::all();  // Select * from salas
        $data = ['data' => $salas]; // Estructura la información
        return response()->json($data);  // Respuesta en JSON
    }

    function show($id)  // Trae un registro puntual
    {
        $sala = Sala::find($id);  // Select * from salas where id = $id
        if (empty($sala)) {
            $data = ['data' => 'No se encuentra registrada la sala'];
            return response()->json($data, 404);
        }
        $data = ['data' => $sala];
        return response()->json($data);
    }

    function store(Request $request)  // Crear nuevo registro
    {
        $datos = $request->all();  // Trae todos los datos del request
        $sala = new Sala();  // Crea un nuevo objeto Sala
        $sala->nombre = $datos['nombre'];  // Asocia atributos de la sala
        $sala->save();  // Guarda el objeto en la base de datos
        $data = ['data' => $sala];
        return response()->json($data);
    }

    function update($id, Request $request)  // Actualizar registro
    {
        $datos = $request->all();  // Captura datos del request
        $sala = Sala::find($id);  // Busca el registro existente
        $sala->nombre = $datos['nombre'];  // Asigna nuevos datos
        $sala->save();  // Guarda los cambios
        $data = ['data' => $sala];
        return response()->json($data);
    }

    function destroy($id)  // Eliminar registro
    {
        $sala = Sala::find($id);  // Consulta el registro
        if (empty($sala)) {  // Verifica si existe
            $data = ['data' => 'No se encuentra registrada la sala'];
            return response()->json($data, 404);
        }
        $sala->delete();  // Elimina el registro
        $data = ['data' => 'Sala eliminada'];
        return response()->json($data);
    }
}
