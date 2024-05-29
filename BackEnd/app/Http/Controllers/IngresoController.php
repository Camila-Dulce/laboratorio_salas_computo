<?php

namespace App\Http\Controllers;

use App\Models\Ingreso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IngresoController extends Controller
{
    // Cargar ingresos del día actual
    public function index()
    {
        $today = Carbon::now()->toDateString();
        $ingresos = Ingreso::whereDate('fechaIngreso', $today)->get();
        return response()->json(['data' => $ingresos]);
    }

    // Consultar ingresos en un rango de fechas
    public function getByDateRange(Request $request)
    {
        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'));
        
        $ingresos = Ingreso::whereBetween('fechaIngreso', [$startDate, $endDate])->get();
        return response()->json(['data' => $ingresos]);
    }

    // Filtrar por código de estudiante, programa o persona que registra el ingreso
    public function filter(Request $request)
    {
        $query = Ingreso::query();

        if ($request->has('codigoEstudiante')) {
            $query->where('codigoEstudiante', $request->input('codigoEstudiante'));
        }

        if ($request->has('idPrograma')) {
            $query->where('idPrograma', $request->input('idPrograma'));
        }

        if ($request->has('idResponsable')) {
            $query->where('idResponsable', $request->input('idResponsable'));
        }

        $ingresos = $query->get();
        return response()->json(['data' => $ingresos]);
    }

    public function show($id)
    {
        $ingreso = Ingreso::find($id);
        if (!$ingreso) {
            return response()->json(['data' => 'No se encuentra registrado el ingreso'], 404);
        }
        return response()->json(['data' => $ingreso]);
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'codigoEstudiante' => 'required|string|max:255',
            'nombreEstudiante' => 'required|string|max:255',
            'horaSalida' => 'nullable|date_format:H:i',
        ]);

        $ingreso = Ingreso::find($id);

        if (!$ingreso) {
            return response()->json(['data' => 'No se encuentra registrado el ingreso'], 404);
        }

        if ($request->has('horaSalida')) {
            $horaSalida = Carbon::parse($request->input('horaSalida'));
            $fechaIngreso = Carbon::parse($ingreso->fechaIngreso);
            $diaSemana = $fechaIngreso->dayName;

            if (!$this->esHorarioPermitido($diaSemana, $horaSalida)) {
                return response()->json(['data' => 'Horario no permitido'], 403);
            }

            $ingreso->horaSalida = $horaSalida;
        }

        $ingreso->codigoEstudiante = $request->input('codigoEstudiante');
        $ingreso->nombreEstudiante = $request->input('nombreEstudiante');
        $ingreso->save(); // Esto actualizará automáticamente el campo `updated_at`

        return response()->json(['data' => $ingreso]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigoEstudiante' => 'required|string|max:255',
            'nombreEstudiante' => 'required|string|max:255',
            'idPrograma' => 'required|integer|exists:programas,id',
            'fechaIngreso' => 'required|date',
            'horaIngreso' => 'required|date_format:H:i',
            'idResponsable' => 'required|integer|exists:responsables,id',
            'idSala' => 'required|integer|exists:salas,id',
        ]);

        $ingreso = new Ingreso();
        $ingreso->codigoEstudiante = $request->input('codigoEstudiante');
        $ingreso->nombreEstudiante = $request->input('nombreEstudiante');
        $ingreso->idPrograma = $request->input('idPrograma');
        $ingreso->fechaIngreso = Carbon::parse($request->input('fechaIngreso'));
        $ingreso->horaIngreso = Carbon::parse($request->input('horaIngreso'));
        $ingreso->idResponsable = $request->input('idResponsable');
        $ingreso->idSala = $request->input('idSala');
        $ingreso->save();

        return response()->json(['data' => $ingreso], 201);
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
            ->where(function($query) use ($hora) {
                $query->where('horaInicio', '<=', $hora)
                      ->where('horaFin', '>=', $hora);
            })
            ->exists();
    }
}