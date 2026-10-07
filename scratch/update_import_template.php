<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

$path = __DIR__ . '/../public/assets/Importacion de usuarios.xlsx';

if (!file_exists($path)) {
    fwrite(STDERR, "No existe la plantilla: {$path}\n");
    exit(1);
}

$spreadsheet = IOFactory::load($path);
$sheet = $spreadsheet->getActiveSheet();

// Encabezado con el mismo estilo que F1
$sheet->setCellValue('G1', 'PROGRAMA DE ESTUDIO');
$sheet->duplicateStyle($sheet->getStyle('F1'), 'G1');

// Ancho de columna
$sheet->getColumnDimension('G')->setWidth(30);

// Ejemplos: fila 2 = ESTUDIANTE, fila 6 = EGRESADO (otros roles dejan G vacío)
$sheet->setCellValue('G2', 'Administración');
$sheet->setCellValue('G6', 'Ingeniería de Sistemas');
$sheet->duplicateStyle($sheet->getStyle('F2'), 'G2');
$sheet->duplicateStyle($sheet->getStyle('F6'), 'G6');

// Texto plano en toda la columna para que Excel no transforme valores
$sheet->getStyle('G2:G1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

$spreadsheet->setActiveSheetIndex(0);
IOFactory::createWriter($spreadsheet, 'Xlsx')->save($path);

echo "Plantilla actualizada: {$path}\n";
