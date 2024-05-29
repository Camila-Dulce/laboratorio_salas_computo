<?php

namespace App\Http\Controllers;

use App\Models\Contacto;
use Illuminate\Http\Request;

class ContactoController extends Controller
{
    function index()  //trae todos los registros
    {
        $contactos = Contacto::all();  //hace todo el metodo de select * from contactos 
        $data = ['data' => $contactos]; //forma de estructurar la informacion, las encerramos en data
        return response()->json($data);  // respuesta generada del servicio con json 
    }

    function show($id)  // registro puntual (uno solo) y el parametro de consulta es con el id (select * from contactos where = id)
    {
        $contacto = Contacto::find($id);  //ejecucion de consulta, 
        $data = ['data' => $contacto];  // generar respuesta ocn el data correspondiente
        return response()->json($data);
    }

    function store(Request $request)  //crear nuevo registro (insert into...) y forma de capturar la infomacion que llega 
    {
        $datos = $request->all(); // Trae todos los satos que llegan del request
        $contacto = new Contacto();  // creamos objeto contactos poqeu estamos creando uno nuevo
        $contacto->nombre = $datos['nombre'];  // asociamos el atributo de contacto 
        $contacto->email = $datos['email']; // cada una de estas con las columnas de la db y lo de comillas es lo que llega del request
        $contacto->telefono = $datos['telefono'];
        $contacto->save();  //una vez creado el objeto lo guarda y hace el insert into...
        $data = ['data' => $contacto]; // mandamos el registro almacenado, se puede cambiar po un mensaje de datos guardados o algo
        return response()->json($data);  //genera respuesta
    }

    function update($id, Request $request)  // actualizar registro, le pasamos id y request porque es un registro existente 
    {
        $datos = $request->all();  // capturamos datos que llegan del request
        $contacto = Contacto::find($id);  // ya no se crea objeto sino que se busca 
        $contacto->nombre = $datos['nombre']; // asignamos los datos al modelo
        $contacto->email = $datos['email'];
        $contacto->telefono = $datos['telefono'];
        $contacto->save(); // una vez creados los guardamos
        $data = ['data' => $contacto]; 
        return response()->json($data);
    }

    function destroy($id) // eliminar registro y pasamos id del valor
    {
        $contacto = Contacto::find($id);  //consultamos el registro
        if (empty($contacto)) { //va a retornar true si el contacto es nulo, es decir que no existe
            $data = ['data' => 'No se encuentra registrado el contacto'];  // responde con no registrado 
            return response()->json($data, 404); //le pasmaos el dato y el codigo 404 de respuesta
        }
        $contacto->delete();  // de aui para abajo es el else - si no se cumple pues elimina el contacto 
        $data = ['data' => 'Datos eliminados']; // mandamos el mensaje 
        return response()->json($data);
    }
}
