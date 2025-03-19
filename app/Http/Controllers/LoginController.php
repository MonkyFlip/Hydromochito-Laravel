<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $usuario = DB::table('tb_usuarios')->where('email', $credentials['email'])->first();

        if (!$usuario) {
            return back()->withErrors(['email' => 'El correo electrónico no está registrado.']);
        }

        $lockout = DB::table('tb_login_attempts')->where('email', $credentials['email'])->first();
        $currentTime = Carbon::now();

        if ($lockout && $lockout->attempts >= 3 && $lockout->lockout_time > $currentTime) {
            $remainingMinutes = $currentTime->diffInMinutes(Carbon::parse($lockout->lockout_time));
            return back()->withErrors(['auth' => "Has excedido el número de intentos. Intenta nuevamente en {$remainingMinutes} minutos."]);
        }

        if (!Hash::check($credentials['password'], $usuario->password)) {
            if ($lockout) {
                $attempts = $lockout->attempts + 1;
                $lockoutTime = $attempts >= 3 ? Carbon::now()->addMinutes(5) : $currentTime;
                DB::table('tb_login_attempts')->where('email', $credentials['email'])->update([
                    'attempts' => $attempts,
                    'lockout_time' => $lockoutTime
                ]);
            } else {
                DB::table('tb_login_attempts')->insert([
                    'email' => $credentials['email'],
                    'attempts' => 1,
                    'lockout_time' => $currentTime
                ]);
            }
            return back()->withErrors(['password' => 'La contraseña es incorrecta.']);
        }

        DB::table('tb_login_attempts')->where('email', $credentials['email'])->delete();

        // Guardar información del usuario en la sesión
        Session::put('authenticated', true);
        Session::put('user_email', $usuario->email);
        Session::put('user_name', $usuario->nombre); // Guardar el nombre del usuario

        if (str_ends_with($usuario->email, '@mony-tek.com')) {
            return redirect()->route('registros_iot.index')->with('success', 'Login exitoso. Bienvenido.'); // Administrador
        } else {
            return redirect()->route('perfil_usuario.index')->with('success', 'Login exitoso. Bienvenido.'); // Usuario
        }
    }

    public function logout(Request $request)
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente.');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'nombre' => 'required|regex:/^[a-zA-Z\s]+$/u|max:255', // Se permite espacio con \s
            'email' => [
                'required',
                'email',
                'unique:tb_usuarios',
                'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,8}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',       // debe contener al menos una letra minúscula
                'regex:/[A-Z]/',       // debe contener al menos una letra mayúscula
                'regex:/[0-9]/',       // debe contener al menos un número
                'regex:/[@$!%*#?&]/'  // debe contener al menos un carácter especial
            ],
        ]);

        $rol = str_ends_with($validatedData['email'], '@mony-tek.com') ? 1 : 2;

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['id_rol'] = $rol;

        DB::table('tb_usuarios')->insert($validatedData);

        return redirect('/login')->with('success', 'Usuario registrado exitosamente. Por favor, inicie sesión.');
    }
}
