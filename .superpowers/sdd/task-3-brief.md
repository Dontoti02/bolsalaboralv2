### Task 3: Regenerar la plantilla Excel con la columna PROGRAMA DE ESTUDIO

**Files:**
- Create: `scratch/update_import_template.php`
- Modify: `public/assets/Importacion de usuarios.xlsx` (generado por el script)

**Interfaces:**
- Consumes: plantilla actual (columnas A-F con encabezados `NOMBRES`, `TIPO DE DOCUMENTO`, `DOCUMENTO`, `TELÃ‰FONO`, `EMAIL`, `ROL`; filas 2-6 con ejemplos: 2=ESTUDIANTE, 3=DOCENTE, 4=ADMINISTRADOR, 5=EMPRESA, 6=EGRESADO).
- Produces: plantilla con columna G `PROGRAMA DE ESTUDIO`; leÃ­da por `UsersImport` en Ã­ndice 6 (Task 2).

- [ ] **Step 1: Crear el script de regeneraciÃ³n**

Crear `scratch/update_import_template.php`:

```php
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
$sheet->getStyle('G1')->applyFromArray($sheet->getStyle('F1')->getArrayCopy());

// Ancho de columna
$sheet->getColumnDimension('G')->setWidth(30)->setCustomWidth(true);

// Ejemplos: fila 2 = ESTUDIANTE, fila 6 = EGRESADO (otros roles dejan G vacÃ­o)
$sheet->setCellValue('G2', 'AdministraciÃ³n');
$sheet->setCellValue('G6', 'IngenierÃ­a de Sistemas');
$sheet->getStyle('G2')->applyFromArray($sheet->getStyle('F2')->getArrayCopy());
$sheet->getStyle('G6')->applyFromArray($sheet->getStyle('F6')->getArrayCopy());

// Texto plano en toda la columna para que Excel no transforme valores
$sheet->getStyle('G2:G1000')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);

$spreadsheet->setActiveSheetIndex(0);
IOFactory::createWriter($spreadsheet, 'Xlsx')->save($path);

echo "Plantilla actualizada: {$path}\n";
```

- [ ] **Step 2: Ejecutar el script**

Run: `php scratch/update_import_template.php`
Expected: `Plantilla actualizada: ...Importacion de usuarios.xlsx`

- [ ] **Step 3: Verificar los encabezados resultantes**

Run:
```powershell
php -r "require 'vendor/autoload.php'; $s = PhpOffice\PhpSpreadsheet\IOFactory::load('public/assets/Importacion de usuarios.xlsx')->getActiveSheet(); echo implode(' | ', array_filter($s->rangeToArray('A1:H1')[0], fn($v) => $v !== null)), PHP_EOL; echo 'G2=' . $s->getCell('G2')->getValue() . ' G6=' . $s->getCell('G6')->getValue() . PHP_EOL;"
```
Expected:
```
NOMBRES | TIPO DE DOCUMENTO | DOCUMENTO | TELÃ‰FONO | EMAIL | ROL | PROGRAMA DE ESTUDIO
G2=AdministraciÃ³n G6=IngenierÃ­a de Sistemas
```

- [ ] **Step 4: Verificar que la descarga pÃºblica sirve el archivo nuevo**

Run:
```powershell
(Get-Item "public\assets\Importacion de usuarios.xlsx").LastWriteTime
```
Expected: fecha/hora actual.

---

