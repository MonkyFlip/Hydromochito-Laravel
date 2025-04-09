<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VisitantesController;
use App\Http\Controllers\PerfilUsuarioController;
use App\Http\Controllers\RegistrosIotController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrosUsuariosController;
use App\Http\Controllers\ReportesUsuariosController;


// Ruta predeterminada para la vista de visitante
Route::get('/', [VisitantesController::class, 'index'])->name('visitantes.index');

// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/register', [LoginController::class, 'register'])->name('register');
Route::post('/password/email', [LoginController::class, 'sendPasswordResetLink'])->name('password.email');

// Rutas protegidas por verificación de sesión en controladores
Route::get('/perfil_usuario/{id}', [PerfilUsuarioController::class, 'index'])->name('perfil_usuario.index');
Route::get('/registros_iot', [RegistrosIotController::class, 'index'])->name('registros_iot.index');
Route::get('/registros_iot/{id}', [RegistrosIotController::class, 'show'])->name('registros_iot.show');
Route::post('/registros_iot', [RegistrosIotController::class, 'store'])->name('registros_iot.store');
Route::put('/registros_iot/{id}', [RegistrosIotController::class, 'update'])->name('registros_iot.update');
Route::delete('/registros_iot/{id}', [RegistrosIotController::class, 'destroy'])->name('registros_iot.destroy');

// Rutas: PDF y Excel (Reporte Completo y Reporte Actual)
Route::get('/reportes/registros_iot/pdf', [ReportesController::class, 'previsualizarReportePDF'])->name('reportes.registros_iot.pdf');
Route::get('/reportes/registros_iot/excel', [ReportesController::class, 'generarReporteExcel'])->name('reportes.registros_iot.excel');

// Rutas para los Reportes Actuales
Route::post('/reporte/previsualizar-pdf', [ReportesController::class, 'previsualizarReporteActualPDF'])->name('reporte.previsualizar-pdf');
Route::post('/reporte/excel', [ReportesController::class, 'generarReporteActualExcel'])->name('reporte.excel');


// Rutas: Importar Reportes - Registros IoT
Route::get('/import', [ReportesController::class, 'showImportForm'])->name('import.form');
Route::post('/import', [ReportesController::class, 'import'])->name('import');


// Rutas: Importar Reportes - Usuarios
Route::get('/import', [ReportesUsuariosController::class, 'showImportForm'])->name('import.form');
Route::post('/import', [ReportesUsuariosController::class, 'import'])->name('import');


// Rutas principales de perfil_usuario
Route::get('/perfil_usuario/{id}', [PerfilUsuarioController::class, 'show'])->name('perfil_usuario.show');
Route::get('/perfil_usuario', [PerfilUsuarioController::class, 'index'])->name('perfil_usuario.index');
Route::post('/perfil_usuario/registros', [PerfilUsuarioController::class, 'registrarIoT'])->name('perfil_usuario.registrar');
Route::delete('/perfil_usuario/registros/{id}', [PerfilUsuarioController::class, 'destroyIoT'])->name('registros_iot.destroy');
// Rutas para reportes PDF
Route::post('/perfil-usuario/previsualizar-pdf', [PerfilUsuarioController::class, 'previsualizarPerfilUsuarioPDF'])->name('reporte.perfil_usuario.pdf');
// Rutas para reportes Excel
Route::get('/perfil-usuario/generar-excel', [PerfilUsuarioController::class, 'generarReportePerfilUsuarioExcel'])->name('reporte.perfil_usuario.excel');
// Ruta para registrar datos enviados desde el ESP32
Route::post('/registrar-desde-esp32', [PerfilUsuarioController::class, 'registrarDesdeESP32'])->name('registrar.desde.esp32');

// Ruta de visitantes
Route::get('/visitante', [VisitantesController::class, 'index'])->name('visitantes.index');


// Rutas para la gestión de usuarios
Route::get('/registros_usuarios', [RegistrosUsuariosController::class, 'index'])->name('registros_usuarios.index'); // Listar usuarios
Route::get('/registros_usuarios/create', [RegistrosUsuariosController::class, 'create'])->name('registros_usuarios.create'); // Formulario para crear nuevo usuario
Route::post('/registros_usuarios', [RegistrosUsuariosController::class, 'store'])->name('registros_usuarios.store'); // Guardar nuevo usuario
Route::get('/registros_usuarios/{id}', [RegistrosUsuariosController::class, 'show'])->name('registros_usuarios.show'); // Ver detalles de un usuario
Route::get('/registros_usuarios/{id}/edit', [RegistrosUsuariosController::class, 'edit'])->name('registros_usuarios.edit'); // Formulario para editar usuario
Route::put('/registros_usuarios/{id}', [RegistrosUsuariosController::class, 'update'])->name('registros_usuarios.update'); // Actualizar usuario
Route::delete('/registros_usuarios/{id}', [RegistrosUsuariosController::class, 'destroy'])->name('registros_usuarios.destroy'); // Eliminar usuario


// Rutas para la gestión de reportes de usuarios

Route::get('/reportes/usuarios/previsualizar', [ReportesUsuariosController::class, 'previsualizarReportePDF'])->name('reporte.usuarios.previsualizar-pdf');
Route::post('/reportes/usuarios/previsualizar-actual', [ReportesUsuariosController::class, 'previsualizarReporteActualPDF'])->name('reporte.usuarios.previsualizar-actual-pdf');
Route::get('/reportes/usuarios/excel', [ReportesUsuariosController::class, 'generarReporteExcel'])->name('reportes.usuarios.excel');
Route::post('/reportes/usuarios/excel', [ReportesUsuariosController::class, 'generarReporteActualExcel'])->name('reporte.usuarios.excel');
Route::post('/usuarios/import', [ReportesUsuariosController::class, 'importarUsuariosDesdeExcel'])->name('usuarios.import');


// Rutas para Graficos de Usuarios
Route::get('/registros_usuarios/grafico-datos', [RegistrosUsuariosController::class, 'obtenerDatosGrafico'])->name('usuarios.grafico.datos');

