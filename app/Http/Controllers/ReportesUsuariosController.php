<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ReportesUsuariosController extends Controller
{
    public function previsualizarReportePDF()
    {
        // Obtener los registros de usuarios desde la API
        $response = Http::get('http://localhost:3000/api/registros_usuarios/');
        $usuarios = $response->successful() ? $response->json() : [];

        // Configurar las columnas (Omitiendo las fechas)
        $columnas = ['ID Usuario', 'Nombre', 'Email', 'Rol'];

        // Limpiar los datos
        $registros = $this->limpiarDatos($usuarios);

        // Generar PDF
        $pdf = Pdf::loadView('pdf_usuarios', compact('registros', 'columnas'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_usuarios_completo.pdf', ['Attachment' => false]);
    }

    public function generarReporteExcel()
    {
        // Obtener los registros de usuarios desde la API
        $response = Http::get('http://localhost:3000/api/registros_usuarios/');
        $usuarios = $response->successful() ? $response->json() : [];

        // Limpiar los datos
        $usuarios = $this->limpiarDatos($usuarios);

        // Configurar las columnas
        $columnas = ['ID Usuario', 'Nombre', 'Email', 'Rol']; // Omitir contraseñas y fechas

        // Eliminar las columnas no deseadas de los datos
        $usuarios = array_map(function ($usuario) {
            unset($usuario['password'], $usuario['created_at'], $usuario['updated_at']); // Quitar contraseñas y fechas
            return $usuario;
        }, $usuarios);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Crear cabecera y datos
        $sheet->fromArray($columnas, null, 'A1'); // Cabecera
        $sheet->fromArray($usuarios, null, 'A2'); // Registros

        // Configurar el archivo para descarga
        $writer = new Xlsx($spreadsheet);
        $fileName = 'reporte_usuarios.xlsx';
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    public function previsualizarReporteActualPDF(Request $request)
    {
        // Obtener datos enviados desde el formulario
        $datos = json_decode($request->input('datos'), true); // Registros enviados filtrados o paginados
        $columnas = json_decode($request->input('columnas'), true); // Nombres de las columnas

        // Validar que los datos y columnas no sean nulos o vacíos
        if (!$datos || !$columnas) {
            abort(400, 'Datos o columnas no válidos para el reporte.');
        }

        // Configurar las columnas relevantes (Omitiendo fechas)
        $columnas = ['ID Usuario', 'Nombre', 'Email', 'Rol'];

        // Limpiar los datos
        $registros = $this->limpiarDatos($datos);

        // Generar PDF
        $pdf = Pdf::loadView('pdf_usuarios', compact('registros', 'columnas'))
            ->setPaper('letter', 'landscape'); // Establecer el formato horizontal

        // Enviar el PDF para visualizar en otra pestaña
        return $pdf->stream('reporte_usuarios_actual.pdf', ['Attachment' => false]);
    }

    public function generarReporteActualExcel(Request $request)
    {
        $datos = json_decode($request->input('datos'), true); // Registros actuales enviados desde el formulario
        $columnas = json_decode($request->input('columnas'), true); // Columnas enviadas desde el formulario

        // Validar los datos
        if (!$datos || !$columnas) {
            return response()->json(['error' => 'Datos no válidos para el reporte'], 400);
        }

        // Eliminar las columnas no deseadas de los datos
        $datos = array_map(function ($dato) {
            unset($dato['password'], $dato['created_at'], $dato['updated_at']); // Quitar contraseñas y fechas
            return $dato;
        }, $datos);

        // Crear el archivo Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Crear cabecera y datos
        $sheet->fromArray($columnas, null, 'A1'); // Cabecera
        $sheet->fromArray($datos, null, 'A2'); // Registros

        // Configurar el archivo para descarga
        $writer = new Xlsx($spreadsheet);
        $fileName = 'reporte_actual.xlsx';
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    private function limpiarDatos(array $datos)
    {
        return array_map(function ($fila) {
            return array_map(function ($valor) {
                return is_array($valor) ? json_encode($valor) : (string) $valor;
            }, $fila);
        }, $datos);
    }

    public function import(Request $request)
    {
        // Validar que el archivo sea válido (formato .xlsx o .csv)
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048',
        ]);

        $file = $request->file('file');
        $successMessage = '';
        $errorMessage = '';
        $omittedCount = 0; // Contador de registros omitidos
        $newRecordsCount = 0; // Contador de registros nuevos insertados
        $importAttempted = false; // Bandera para saber si se intentó una importación

        try {
            // Leer y procesar el archivo
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Validar la cabecera
            $requiredFields = ['id_usuario', 'nombre', 'email', 'password', 'id_rol'];
            $header = $rows[0]; // Primera fila (cabecera)

            foreach ($requiredFields as $field) {
                if (!in_array($field, $header)) {
                    throw new \Exception("El campo {$field} es obligatorio y no se encontró en el archivo.");
                }
            }

            // Procesar registros (Ignorar la cabecera)
            unset($rows[0]);
            foreach ($rows as $row) {
                $importAttempted = true; // Indica que se intentó importar
                $data = array_combine($header, $row);

                // Validar campos obligatorios
                if (!isset($data['id_usuario'], $data['nombre'], $data['email'], $data['password'], $data['id_rol'])) {
                    $omittedCount++;
                    continue;
                }

                // Comprobar si el registro ya existe
                $exists = DB::table('tb_usuarios')->where('id_usuario', $data['id_usuario'])->orWhere('email', $data['email'])->exists();
                if ($exists) {
                    $omittedCount++;
                    continue;
                }

                // Insertar el nuevo registro con la contraseña encriptada
                DB::table('tb_usuarios')->insert([
                    'id_usuario' => $data['id_usuario'],
                    'nombre' => $data['nombre'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']), // Encriptar la contraseña
                    'id_rol' => $data['id_rol'], // 1 = Administrador, 2 = Usuario Normal
                ]);
                $newRecordsCount++;
            }

            $successMessage = "Registros importados exitosamente: {$newRecordsCount}. Omitidos: {$omittedCount}.";
        } catch (\Exception $e) {
            $errorMessage = 'Error al procesar el archivo: ' . $e->getMessage();
        }

        return redirect()->back()->with([
            'success' => $successMessage,
            'error' => $errorMessage,
            'new_records' => $newRecordsCount,
            'omitted' => $omittedCount,
            'import_done' => true, // Asegúrate de que siempre sea true si hubo intento de importación
        ]);
    }
}
