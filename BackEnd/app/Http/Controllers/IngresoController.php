<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use App\Models\HorarioSala; 
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    public function index()
    {
        $today = Carbon::now()->toDateString();
        $ingresos = Ingreso::whereDate('fechaIngreso', $today)->get();
        return response()->json(['data' => $ingresos]);
    }

    public function store(Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'codigoEstudiante' => 'required|string|max:255',
            'nombreEstudiante' => 'required|string|max:255',
            'idPrograma' => 'required|integer|exists:programas,id',
            'fechaIngreso' => 'required|date',
            'horaIngreso' => 'required|date_format:H:i',
            'idResponsable' => 'required|integer|exists:responsables,id',
            'idSala' => 'required|integer|exists:salas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $fechaIngreso = Carbon::parse($request->input('fechaIngreso'));
        $horaIngreso = Carbon::parse($request->input('horaIngreso'));
        $diaSemana = $fechaIngreso->englishDayOfWeek;

        if (!$this->esHorarioPermitido($diaSemana, $horaIngreso)) {
            return response()->json(['data' => 'Horario no permitido'], 403);
        }

        if ($this->salaOcupada($request->input('idSala'), $diaSemana, $horaIngreso)) {
            return response()->json(['data' => 'La sala está ocupada'], 403);
        }

        $ingreso = new Ingreso();
        $ingreso->codigoEstudiante = $request->input('codigoEstudiante');
        $ingreso->nombreEstudiante = $request->input('nombreEstudiante');
        $ingreso->idPrograma = $request->input('idPrograma');
        $ingreso->fechaIngreso = $fechaIngreso;
        $ingreso->horaIngreso = $horaIngreso;
        $ingreso->idResponsable = $request->input('idResponsable');
        $ingreso->idSala = $request->input('idSala');
        $ingreso->save();

        return response()->json(['data' => $ingreso], 201);
    }

    public function update($id, Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'codigoEstudiante' => 'required|string|max:255',
            'nombreEstudiante' => 'required|string|max:255',
            'horaSalida' => 'nullable|date_format:H:i',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $ingreso = Ingreso::find($id);
        if (!$ingreso) {
            return response()->json(['data' => 'No se encuentra registrado el ingreso'], 404);
        }

        if ($request->has('horaSalida')) {
            $horaSalida = Carbon::parse($request->input('horaSalida'));
            $fechaIngreso = Carbon::parse($ingreso->fechaIngreso);
            $diaSemana = $fechaIngreso->englishDayOfWeek;

            if (!$this->esHorarioPermitido($diaSemana, $horaSalida)) {
                return response()->json(['data' => 'Horario no permitido'], 403);
            }

            $ingreso->horaSalida = $horaSalida;
        }

        $ingreso->codigoEstudiante = $request->input('codigoEstudiante');
        $ingreso->nombreEstudiante = $request->input('nombreEstudiante');
        $ingreso->save();

        return response()->json(['data' => $ingreso]);
    }

    public function show($id)
    {
        $ingreso = Ingreso::find($id);
        if (!$ingreso) {
            return response()->json(['data' => 'No se encuentra registrado el ingreso'], 404);
        }
        return response()->json(['data' => $ingreso]);
    }

    public function destroy($id)
    {
        $ingreso = Ingreso::find($id);
        if (!$ingreso) {
            return response()->json(['data' => 'No se encuentra registrado el ingreso'], 404);
        }
        $ingreso->delete();
        return response()->json(['data' => 'Ingreso eliminado']);
    }

    public function getByDateRange(Request $request)
    {
        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'));

        $ingresos = Ingreso::whereBetween('fechaIngreso', [$startDate, $endDate])->get();
        return response()->json(['data' => $ingresos]);
    }

    public function filter(Request $request)
    {
        $codigoEstudiante = $request->input('codigoEstudiante');
        $nombreEstudiante = $request->input('nombreEstudiante');
        $idSala = $request->input('idSala');

        $query = Ingreso::query();

        if ($codigoEstudiante) {
            $query->where('codigoEstudiante', $codigoEstudiante);
        }

        if ($nombreEstudiante) {
            $query->where('nombreEstudiante', 'like', '%' . $nombreEstudiante . '%');
        }

        if ($idSala) {
            $query->where('idSala', $idSala);
        }

        $ingresos = $query->get();

        return response()->json(['data' => $ingresos]);
    }

    private function esHorarioPermitido($diaSemana, $hora)
    {
        $horaInicioSemana = Carbon::createFromTime(7, 0);
        $horaFinSemana = Carbon::createFromTime(20, 50);
        $horaInicioSabado = Carbon::createFromTime(7, 0);
        $horaFinSabado = Carbon::createFromTime(16, 30);

        if (in_array($diaSemana, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) {
            return $hora->between($horaInicioSemana, $horaFinSemana);
        } elseif ($diaSemana == 'Saturday') {
            return $hora->between($horaInicioSabado, $horaFinSabado);
        }

        return false;
    }

    private function salaOcupada($idSala, $dia, $hora)
    {
        return HorarioSala::where('idSala', $idSala)
            ->where('dia', $dia)
            ->where(function ($query) use ($hora) {
                $query->where('horaInicio', '<=', $hora)
                      ->where('horaFin', '>=', $hora);
            })
            ->exists();
    }
}

