<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerfilUsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // Verifica autenticación
            if (!Session::has('authenticated') || !Session::get('authenticated')) {
                return redirect()->route('login')->withErrors(['auth' => 'Debe iniciar sesión para acceder a esta página.']);
            }

            // Obtiene el email del usuario desde la sesión
            $userEmail = Session::get('user_email');

            // Obtiene el usuario de la base de datos
            $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

            // Si el usuario no existe
            if (!$usuario) {
                Session::flash('access_restricted', true);
                return redirect()->route('login')->withErrors(['auth' => 'No tienes permiso para acceder a esta página.']);
            }

            // Traduce el id_rol a un valor legible
            $rol = $usuario->id_rol == 1 ? 'Admin' : ($usuario->id_rol == 2 ? 'Usuario' : 'Sin Rol');

            // Guarda nombre y rol en la sesión
            Session::put('user_name', $usuario->nombre);
            Session::put('user_role', $rol);

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Obtener el email del usuario autenticado
        $userEmail = Session::get('user_email');
        $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

        if (!$usuario) {
            return redirect()->route('login')->withErrors(['auth' => 'No tienes permiso para acceder a esta página.']);
        }

        // Obtener los registros de IoT relacionados con el usuario y paginar
        $registros = DB::table('tb_registros_iot')
            ->where('id_usuario', $usuario->id_usuario)
            ->paginate(8); // Cambiar get() por paginate()

        // Retornar la vista pasando los registros paginados
        return view('perfil_usuario', ['registros' => $registros]);
    }

    public function obtenerRegistrosUsuario(Request $request)
    {
        // Verifica si el usuario está autenticado
        $userEmail = Session::get('user_email');

        // Obtener los registros de IoT relacionados con el usuario autenticado y paginar
        $registros = DB::table('tb_registros_iot')
            ->join('tb_usuarios', 'tb_registros_iot.id_usuario', '=', 'tb_usuarios.id_usuario')
            ->where('tb_usuarios.email', $userEmail)
            ->select(
                'tb_registros_iot.id_registro',
                'tb_registros_iot.flujo_agua',
                'tb_registros_iot.nivel_agua',
                'tb_registros_iot.temp', // Cambiado de 'temperatura' a 'temp'
                'tb_registros_iot.energia'
            )
            ->paginate(8); // Cambiar get() por paginate()

        // Retornar la vista con los registros paginados
        return view('perfil_usuario', ['registros' => $registros]);
    }

    public function registrarIoT(Request $request)
    {
        // Validación
        $request->validate([
            'flujo_agua' => 'required|numeric',
            'nivel_agua' => 'required|numeric',
            'temp' => 'required|numeric',
            'energia' => 'required|string',
        ]);

        // Obtén el usuario autenticado
        $userEmail = Session::get('user_email');
        $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

        if (!$usuario) {
            return redirect()->back()->withErrors('Usuario no autenticado.');
        }

        // Inserción en la base de datos
        DB::table('tb_registros_iot')->insert([
            'flujo_agua' => $request->input('flujo_agua'),
            'nivel_agua' => $request->input('nivel_agua'),
            'temp' => $request->input('temp'),
            'energia' => $request->input('energia'),
            'id_usuario' => $usuario->id_usuario,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Registro agregado correctamente.');
    }

    public function destroyIoT(Request $request, $id)
    {
        // Intenta eliminar el registro de la tabla 'tb_registros_iot'
        $deleted = DB::table('tb_registros_iot')->where('id_registro', $id)->delete();

        if ($deleted) {
            return redirect()->back()->with('success', 'Registro eliminado correctamente.');
        }

        return redirect()->back()->with('error', 'No se pudo eliminar el registro.');
    }

    public function previsualizarPerfilUsuarioPDF(Request $request)
    {
        // Obtener el ID del usuario autenticado
        $userEmail = Session::get('user_email');
        $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado o no válido.'], 401);
        }

        // Obtener todos los registros completos del usuario
        $registros = DB::table('tb_registros_iot')
            ->where('id_usuario', $usuario->id_usuario)
            ->get()
            ->map(function ($registro) {
                return (array) $registro; // Convertimos cada registro a un array
            })
            ->toArray(); // Convertir la colección completa a un array

        // Configurar las columnas para el reporte
        $columnas = ['ID Registro', 'Flujo de Agua', 'Nivel de Agua', 'Temperatura', 'Energía'];

        // Log para depuración
        Log::info('Registros completos procesados para PDF:', $registros);

        // Obtener el nombre del usuario autenticado
        $nombreUsuario = Session::get('user_name', 'Usuario Anónimo');

        // Generar el PDF
        $view = 'pdf_perfil_usuario';
        $pdf = Pdf::loadView($view, compact('registros', 'columnas', 'nombreUsuario'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_perfil_usuario_completo.pdf');
    }

    public function generarReportePerfilUsuarioExcel()
    {
        // Obtener el ID del usuario autenticado
        $userEmail = Session::get('user_email');
        $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no autenticado o no válido.'], 401);
        }

        // Obtener todos los registros completos del usuario
        $registros = DB::table('tb_registros_iot')
            ->where('id_usuario', $usuario->id_usuario)
            ->get()
            ->map(function ($registro) {
                $registro = (array) $registro; // Convertir cada registro a un array
                unset($registro['id_usuario']); // Eliminar el campo id_usuario
                return $registro;
            })
            ->toArray(); // Convertir la colección completa a un array

        // Configurar las columnas para el reporte (omitiendo id_usuario)
        $columnas = ['ID Registro', 'Flujo de Agua', 'Nivel de Agua', 'Temperatura', 'Energía'];

        // Crear archivo Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Establecer los encabezados en la primera fila
        $sheet->fromArray($columnas, null, 'A1');

        // Insertar los datos debajo de los encabezados
        $sheet->fromArray($registros, null, 'A2');

        // Log para depuración
        Log::info('Datos para Excel (sin id_usuario):', $registros);

        // Guardar el archivo Excel
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'reporte_perfil_usuario.xlsx';
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Limpiar los datos enviados o recibidos para el reporte.
     *
     * @param array $datos
     * @return array
     */
    private function limpiarDatos(array $datos)
    {
        return array_map(function ($fila) {
            return [
                'id_usuario' => $fila['id_usuario'] ?? 'N/A',
                'nombre' => $fila['nombre'] ?? 'N/A',
                'correo' => $fila['correo'] ?? 'N/A',
                'rol' => $fila['rol'] ?? 'N/A',
                'estado' => $fila['estado'] ?? 'N/A',
            ];
        }, $datos);
    }
}
