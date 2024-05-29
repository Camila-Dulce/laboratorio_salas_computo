<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    function index()  // Trae todos los registros
    {
        $programas = Programa::all();  // Select * from programas
        $data = ['data' => $programas]; // Estructura la información
        return response()->json($data);  // Respuesta en JSON
    }

    function show($id)  // Trae un registro puntual
    {
        $programa = Programa::find($id);  // Select * from programas where id = $id
        if (empty($programa)) {
            $data = ['data' => 'No se encuentra registrado el programa'];
            return response()->json($data, 404);
        }
        $data = ['data' => $programa];
        return response()->json($data);
    }

    function store(Request $request)  // Crear nuevo registro
    {
        $datos = $request->all();  // Trae todos los datos del request
        $programa = new Programa();  // Crea un nuevo objeto Programa
        $programa->nombre = $datos['nombre'];  
        $programa->save();  // Guarda el objeto en la base de datos
        $data = ['data' => $programa];
        return response()->json($data);
    }

     function update($id, Request $request)  // actualizar registro, le pasamos id y request porque es un registro existente 
    {
        $datos = $request->all();  // capturamos datos que llegan del request
        $programa = Programa::find($id);  // ya no se crea objeto sino que se busca 
        $programa->nombre = $datos['nombre'];

        $programa->save(); // una vez creados los guardamos
        $data = ['data' => $programa]; 
        return response()->json($data);
    }

    function destroy($id)  // Eliminar registro
    {
        $programa = Programa::find($id);  // Consulta el registro
        if (empty($programa)) {  // Verifica si existe
            $data = ['data' => 'No se encuentra registrado el programa'];
            return response()->json($data, 404);
        }
        $programa->delete();  // Elimina el registro
        $data = ['data' => 'Programa eliminado'];
        return response()->json($data);
    }
}
