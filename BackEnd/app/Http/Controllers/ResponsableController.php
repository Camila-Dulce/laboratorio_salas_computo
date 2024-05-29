<?php

namespace App\Http\Controllers;

use App\Models\Responsable;
use Illuminate\Http\Request;

class ResponsableController extends Controller
{
    function index()  // Trae todos los registros
    {
        $responsables = Responsable::all();  // Select * from responsables
        $data = ['data' => $responsables]; // Estructura la información
        return response()->json($data);  // Respuesta en JSON
    }

    function show($id)  // Trae un registro puntual
    {
        $responsable = Responsable::find($id);  // Select * from responsables where id = $id
        if (empty($responsable)) {
            $data = ['data' => 'No se encuentra registrado el responsable'];
            return response()->json($data, 404);
        }
        $data = ['data' => $responsable];
        return response()->json($data);
    }

    function store(Request $request)  // Crear nuevo registro
    {
        $datos = $request->all();  // Trae todos los datos del request
        $responsable = new Responsable();  // Crea un nuevo objeto Responsable
        $responsable->nombre = $datos['nombre'];  // Asocia atributos del responsable
        $responsable->save();  // Guarda el objeto en la base de datos
        $data = ['data' => $responsable];
        return response()->json($data);
    }

    function update($id, Request $request)  // Actualizar registro
    {
        $datos = $request->all();  // Captura datos del request
        $responsable = Responsable::find($id);  // Busca el registro existente
        $responsable->nombre = $datos['nombre'];  // Asigna nuevos datos
        $responsable->save();  // Guarda los cambios
        $data = ['data' => $responsable];
        return response()->json($data);
    }

    function destroy($id)  // Eliminar registro
    {
        $responsable = Responsable::find($id);  // Consulta el registro
        if (empty($responsable)) {  // Verifica si existe
            $data = ['data' => 'No se encuentra registrado el responsable'];
            return response()->json($data, 404);
        }
        $responsable->delete();  // Elimina el registro
        $data = ['data' => 'Responsable eliminado'];
        return response()->json($data);
    }
}
