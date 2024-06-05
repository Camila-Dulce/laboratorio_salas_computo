<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioSala;

class HorarioSalaController extends Controller
{
    public function index()
    {
        $horarios = HorarioSala::all();
        return response()->json(['data' => $horarios]);
    }

    public function store(Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'idSala' => 'required|integer|exists:salas,id',
            'dia' => 'required|string|max:255',
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $horarioSala = new HorarioSala();
        $horarioSala->idSala = $request->input('idSala');
        $horarioSala->dia = $request->input('dia');
        $horarioSala->horaInicio = $request->input('horaInicio');
        $horarioSala->horaFin = $request->input('horaFin');
        $horarioSala->save();

        return response()->json(['data' => $horarioSala], 201);
    }
}
