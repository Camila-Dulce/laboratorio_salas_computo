<?php

namespace App\Http\Controllers;

use App\Models\HorarioSala;
use Illuminate\Http\Request;

class HorarioSalaController extends Controller
{
    public function index()
    {
        $horarios = HorarioSala::all();
        return response()->json(['data' => $horarios]);
    }

    public function show($id)
    {
        $horario = HorarioSala::find($id);
        if (!$horario) {
            return response()->json(['data' => 'No se encuentra registrado el horario'], 404);
        }
        return response()->json(['data' => $horario]);
    }

    public function store(Request $request)
    {
        $horario = HorarioSala::create($request->all());
        return response()->json(['data' => $horario]);
    }

    public function update(Request $request, $id)
    {
        $horario = HorarioSala::find($id);
        if (!$horario) {
            return response()->json(['data' => 'No se encuentra registrado el horario'], 404);
        }
        $horario->update($request->all());
        return response()->json(['data' => $horario]);
    }

    public function destroy($id)
    {
        $horario = HorarioSala::find($id);
        if (!$horario) {
            return response()->json(['data' => 'No se encuentra registrado el horario'], 404);
        }
        $horario->delete();
        return response()->json(['data' => 'Horario eliminado']);
    }
}

