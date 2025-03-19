<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class ReportesController extends Controller
{
    /**
     * Previsualizar el reporte en PDF (Reporte Completo).
     *
     * @return \Illuminate\Http\Response
     */
    public function previsualizarReportePDF(Request $request)
    {
        // Obtener los registros de la API
        $response = Http::get('http://localhost:3000/api/registros_iot/');
        $registros = $response->successful() ? $response->json() : [];

        // Obtener los usuarios de la API
        $responseUsuarios = Http::get('http://localhost:3000/api/registros_usuarios/');
        $usuarios = $responseUsuarios->successful() ? $responseUsuarios->json() : [];

        // Crear un mapa de ID de usuario a nombre de usuario
        $mapaUsuarios = [];
        foreach ($usuarios as $usuario) {
            $mapaUsuarios[$usuario['id_usuario']] = $usuario['nombre'];
        }

        // Reemplazar el ID de usuario con el nombre de usuario en los registros
        foreach ($registros as &$registro) {
            $registro['nombre_usuario'] = $mapaUsuarios[$registro['id_usuario']] ?? 'N/A';
        }

        // Configurar las columnas
        $columnas = ['ID Registro', 'Flujo Agua', 'Nivel Agua', 'Temp', 'Energía', 'Usuario'];

        // Limpiar los datos
        $registros = $this->limpiarDatos($registros);

        // Configurar la vista para registros_iot
        $view = 'pdf_registros_iot';

        $pdf = Pdf::loadView($view, compact('registros', 'columnas'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_registros_iot.pdf');
    }

    public function generarReporteExcel(Request $request)
    {
        // Obtener los registros de la API
        $response = Http::get('http://localhost:3000/api/registros_iot/');
        $registros = $response->successful() ? $response->json() : [];

        // Limpiar los datos
        $registros = $this->limpiarDatos($registros);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columnas = ['ID Registro', 'Flujo Agua', 'Nivel Agua', 'Temp', 'Energía', 'Usuario'];

        // Aplicar formato a la tabla
        $sheet->fromArray($columnas, null, 'A1');
        $sheet->fromArray($registros, null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $fileName = 'reporte_registros_iot.xlsx';
        $filePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    public function previsualizarReporteActualPDF(Request $request)
    {
        // Obtenemos los datos enviados desde el formulario
        $datos = json_decode($request->input('datos'), true);
        $columnas = json_decode($request->input('columnas'), true);

        // Validar que los datos y columnas no sean nulos o vacíos
        if (!$datos || !$columnas) {
            abort(400, 'Datos o columnas no válidos para el reporte.');
        }

        // Limpiamos los datos para asegurarnos de que están en el formato correcto
        $datos = $this->limpiarDatos($datos);

        // Asignar las variables que la vista requiere
        $registros = $datos; // Renombramos para que coincida con la vista
        $pdf = Pdf::loadView('pdf_registros_iot', compact('registros', 'columnas'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('reporte_actual.pdf');
    }


    public function generarReporteActualExcel(Request $request)
    {
        $datos = json_decode($request->input('datos'), true); // Datos visibles en la vista
        $columnas = json_decode($request->input('columnas'), true); // Encabezados de la tabla

        if (!$datos || !$columnas) {
            return response()->json(['error' => 'Datos no válidos para el reporte'], 400);
        }

        // Limpiar los datos
        $datos = $this->limpiarDatos($datos);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Crear la cabecera
        $sheet->fromArray($columnas, null, 'A1');
        // Crear los datos
        $sheet->fromArray($datos, null, 'A2');

        // Aplicar formato al archivo Excel
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


    //Logica para Importar Registros

    // Método para mostrar el formulario de importación
    public function showImportForm()
    {
        return view('import_form');
    }


    // Método para manejar la importación de registros
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:2048', // Eliminamos "pdf"
        ]);

        $file = $request->file('file');
        $successMessage = '';
        $errorMessage = '';
        $omittedCount = 0;
        $newRecordsCount = 0;

        try {
            // Ahora solo usamos la función para importar desde Excel
            list($successMessage, $omittedCount, $newRecordsCount) = $this->importFromExcel($file);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
        }

        return redirect()->back()->with([
            'success' => $successMessage,
            'error' => $errorMessage,
            'omitted' => $omittedCount,
            'new_records' => $newRecordsCount
        ]);
    }

    private function importFromExcel($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $omittedCount = 0;
        $newRecordsCount = 0;

        $requiredFields = ['id_registro', 'flujo_agua', 'nivel_agua', 'temp', 'energia', 'id_usuario'];
        $header = $rows[0];
        foreach ($requiredFields as $field) {
            if (!in_array($field, $header)) {
                throw new \Exception("El campo {$field} es obligatorio y no se encontró en el archivo.");
            }
        }

        unset($rows[0]);
        foreach ($rows as $row) {
            $exists = DB::table('tb_registros_iot')->where('id_registro', $row[0])->exists();
            if ($exists) {
                $omittedCount++;
                continue;
            }

            DB::table('tb_registros_iot')->insert([
                'id_registro' => $row[0],
                'flujo_agua' => $row[1],
                'nivel_agua' => $row[2],
                'temp' => $row[3],
                'energia' => $row[4],
                'id_usuario' => $row[5],
            ]);
            $newRecordsCount++;
        }

        $successMessage = "Registros importados exitosamente.";
        return [$successMessage, $omittedCount, $newRecordsCount];
    }       
}
