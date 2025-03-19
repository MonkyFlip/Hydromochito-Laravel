<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class RegistrosIotController extends Controller
{

    public function __construct()
    {
        // Middleware para verificar la sesión y el rol de administrador
        $this->middleware(function ($request, $next) {
            if (!Session::has('authenticated') || !Session::get('authenticated')) {
                return redirect()->route('login')->withErrors(['auth' => 'Debe iniciar sesión para acceder a esta página.']);
            }

            $userEmail = Session::get('user_email');
            $usuario = DB::table('tb_usuarios')->where('email', $userEmail)->first();

            if (!$usuario || $usuario->id_rol != 1) {
                Session::flash('access_restricted', true);
                return redirect()->route('perfil_usuario.index')->withErrors(['auth' => 'No tienes permiso para acceder a esta página.']);
            }

            return $next($request);
        });
    }

    // Obtener todos los registros de IoT con paginación
    public function index(Request $request)
    {
        $query = $request->input('query', ''); // Obtener la consulta de búsqueda

        $response = Http::get('http://localhost:3000/api/registros_iot/');

        if ($response->successful()) {
            $data = $response->json();

            // Obtener nombres de usuarios a partir de sus IDs
            $usuariosResponse = Http::get('http://localhost:3000/api/registros_usuarios/');
            if ($usuariosResponse->successful()) {
                $usuarios = $usuariosResponse->json();

                // Crear un mapa de ID de usuario a nombre de usuario
                $mapaUsuarios = [];
                foreach ($usuarios as $usuario) {
                    $mapaUsuarios[$usuario['id_usuario']] = $usuario['nombre'];
                }

                foreach ($data as &$registro) {
                    $idUsuario = $registro['id_usuario'];
                    if (isset($mapaUsuarios[$idUsuario])) {
                        $registro['nombre_usuario'] = $mapaUsuarios[$idUsuario];
                    } else {
                        $registro['nombre_usuario'] = 'Desconocido';
                    }
                }

                // Filtrar los registros según la consulta de búsqueda
                if (!empty($query)) {
                    $data = array_filter($data, function ($registro) use ($query) {
                        return strpos($registro['flujo_agua'], $query) !== false ||
                            strpos($registro['nivel_agua'], $query) !== false ||
                            strpos($registro['temp'], $query) !== false ||
                            strpos($registro['energia'], $query) !== false ||
                            strpos($registro['id_usuario'], $query) !== false ||
                            strpos($registro['nombre_usuario'], $query) !== false;
                    });
                }

                // Paginación de 8 en 8
                $currentPage = LengthAwarePaginator::resolveCurrentPage();
                $perPage = 8;
                $currentPageItems = array_slice($data, ($currentPage - 1) * $perPage, $perPage);
                $paginator = new LengthAwarePaginator($currentPageItems, count($data), $perPage, $currentPage, [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]);

                return view('registros_iot', compact('paginator', 'usuarios'));
            } else {
                $error = $usuariosResponse->body();
                return response()->json(['error' => 'Error al consultar los usuarios', 'details' => $error], 500);
            }
        } else {
            $error = $response->body();
            return response()->json(['error' => 'Error al consultar la API', 'details' => $error], 500);
        }
    }

    // Obtener un registro de IoT por su ID
    public function show($id)
    {
        $response = Http::get('http://localhost:3000/api/registros_iot/' . $id);

        if ($response->successful()) {
            $data = $response->json();
            return view('registros_iot', compact('data'));
        } else {
            return response()->json(['error' => 'Error al consultar la API'], 500);
        }
    }

    // Crear un nuevo registro
    public function store(Request $request)
    {
        try {
            $data = [
                'flujo_agua' => $request->input('flujo_agua'),
                'nivel_agua' => $request->input('nivel_agua'),
                'temp' => $request->input('temp'),
                'energia' => $request->input('energia'),
                'id_usuario' => $request->input('id_usuario'),
                'created_at' => now(),
                'updated_at' => now()
            ];

            DB::table('tb_registros_iot')->insert($data);

            return redirect()->route('registros_iot.index')->with(['status' => 'Registro creado exitosamente', 'status_type' => 'success']);
        } catch (\Exception $e) {
            return redirect()->route('registros_iot.index')->with(['status' => 'Error al enviar los datos', 'status_type' => 'error']);
        }
    }

    // Actualizar un registro de IoT
    public function update(Request $request, $id)
    {
        try {
            $data = [
                'flujo_agua' => $request->input('flujo_agua'),
                'nivel_agua' => $request->input('nivel_agua'),
                'temp' => $request->input('temp'),
                'energia' => $request->input('energia'),
                'id_usuario' => $request->input('id_usuario'),
                'updated_at' => now()
            ];

            $response = Http::put("http://localhost:3000/api/registros_iot/{$id}", $data);

            if ($response->successful()) {
                $statusMessage = 'Registro actualizado exitosamente.';
                return redirect()->route('registros_iot.index')->with(['status' => $statusMessage, 'status_type' => 'warning']);
            } else {
                return redirect()->route('registros_iot.index')->with(['status' => 'Error al actualizar el registro', 'status_type' => 'error']);
            }
        } catch (\Exception $e) {
            return redirect()->route('registros_iot.index')->with(['status' => 'Error al actualizar el registro', 'status_type' => 'error']);
        }
    }

    // Eliminar un registro de IoT
    public function destroy($id)
    {
        try {
            DB::table('tb_registros_iot')->where('id_registro', $id)->delete();

            return redirect()->route('registros_iot.index')->with(['status' => 'Registro eliminado exitosamente', 'status_type' => 'orange']);
        } catch (\Exception $e) {
            return redirect()->route('registros_iot.index')->with(['status' => 'Error al eliminar el registro', 'status_type' => 'error']);
        }
    }
}
