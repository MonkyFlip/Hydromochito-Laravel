<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RegistrosUsuariosController extends Controller
{
    // Middleware para proteger la vista
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

    // Listar usuarios con paginación y filtrado
    public function index(Request $request)
    {
        $query = $request->input('query', ''); // Obtener la consulta de búsqueda (vacío por defecto)

        // Obtener los registros desde la base de datos
        $usuarios = DB::table('tb_usuarios')
            ->when(!empty($query), function ($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%') // Buscar por nombre
                    ->orWhere('email', 'LIKE', '%' . $query . '%') // Buscar por email
                    ->orWhere('id_usuario', 'LIKE', '%' . $query . '%') // Buscar por ID
                    ->orWhere(function ($subQuery) use ($query) { // Buscar por rol
                        $subQuery->where('id_rol', 'LIKE', '%' . $query . '%')
                            ->orWhereRaw(
                                "CASE 
                                   WHEN ? LIKE '%Administrador%' THEN id_rol = 1 
                                   WHEN ? LIKE '%Usuario Normal%' THEN id_rol = 2 
                               END",
                                [$query, $query]
                            );
                    });
            })
            ->paginate(8)
            ->appends(['query' => $query]); // Mantener el filtro activo en los enlaces de paginación

        // Definir las columnas
        $columnas = ['ID Usuario', 'Nombre', 'Email', 'Rol'];

        // Retornar la vista con los registros y las columnas
        return view('registros_usuarios', compact('usuarios', 'columnas'));
    }

    // Mostrar el formulario para crear un nuevo usuario
    public function create()
    {
        return view('registros_usuarios.create');
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'email' => 'required|email|max:50|unique:tb_usuarios',
            'password' => 'required|min:8',
            'id_rol' => 'required|in:1,2', // 1=Admin, 2=Usuario Normal
        ]);

        DB::table('tb_usuarios')->insert([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Encriptar contraseña
            'id_rol' => $request->id_rol,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('registros_usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Mostrar el formulario para editar un usuario existente
    public function edit($id)
    {
        $usuario = DB::table('tb_usuarios')->where('id_usuario', $id)->first();
        if (!$usuario) {
            return redirect()->route('registros_usuarios.index')->withErrors('Usuario no encontrado.');
        }
        return view('registros_usuarios.edit', compact('usuario'));
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:50',
            'email' => 'required|email|max:50|unique:tb_usuarios,email,' . $id . ',id_usuario',
            'password' => 'nullable|min:8', // Si la contraseña no se actualiza, puede ser nula
            'id_rol' => 'required|in:1,2', // 1=Admin, 2=Usuario Normal
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'id_rol' => $request->id_rol,
            'updated_at' => now(),
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password); // Encriptar nueva contraseña
        }

        DB::table('tb_usuarios')->where('id_usuario', $id)->update($data);

        return redirect()->route('registros_usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        DB::table('tb_usuarios')->where('id_usuario', $id)->delete();
        return redirect()->route('registros_usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    public function obtenerDatosGrafico()
    {
        $adminCount = DB::table('tb_usuarios')->where('id_rol', 1)->count();
        $userCount = DB::table('tb_usuarios')->where('id_rol', 2)->count();

        return response()->json([
            'labels' => ['Administrador', 'Usuario Normal'],
            'data' => [$adminCount, $userCount]
        ]);
    }
}
