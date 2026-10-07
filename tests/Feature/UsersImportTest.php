<?php

namespace Tests\Feature;

use App\Models\Person;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class UsersImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('user') || !Schema::hasTable('study_programs')) {
            $this->markTestSkipped('Las tablas "user" o "study_programs" no existen en este entorno.');
        }
    }

    private function makeXlsx(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($rows as $r => $row) {
            foreach ($row as $c => $value) {
                $sheet->setCellValueByColumnAndRow($c + 1, $r + 1, $value);
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'users_import_') . '.xlsx';
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        return $path;
    }

    private function header(): array
    {
        return ['NOMBRES', 'TIPO DE DOCUMENTO', 'DOCUMENTO', 'TELÉFONO', 'EMAIL', 'ROL', 'PROGRAMA DE ESTUDIO'];
    }

    private function admin(): User
    {
        $admin = User::where('rol_id', 1)->first();

        return $admin ?: User::factory()->create(['rol_id' => 1]);
    }

    private function import(array $rows)
    {
        $path = $this->makeXlsx($rows);

        return $this->actingAs($this->admin())->post('/admin/users/import', [
            'file' => new UploadedFile(
                $path,
                'usuarios.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);
    }

    public function test_import_assigns_study_program_to_student_case_insensitive()
    {
        $program = StudyProgram::firstOrCreate(
            ['name' => 'Ingeniería de Sistemas'],
            ['is_active' => true]
        );
        $email = 'import.student.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Estudiante Import Test', 'DNI', '76543210', '987654321', $email, 'ESTUDIANTE', '  INGENIERÍA de SISTEMAS '],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debió importarse.');
        $this->assertSame($program->id, $user->person->study_program_id);
    }

    public function test_import_rejects_student_without_program()
    {
        $email = 'import.noprog.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Estudiante Sin Programa', 'DNI', '76543211', '987654321', $email, 'ESTUDIANTE', ''],
        ]);

        $response->assertStatus(422);
        $this->assertStringContainsString('obligatorio para estudiantes y egresados', $response->json('message'));
        $this->assertNull(User::where('email', $email)->first(), 'La fila no debió importarse.');
    }

    public function test_import_warns_and_creates_user_when_program_does_not_exist()
    {
        $email = 'import.unknownprog.' . uniqid() . '@talentum.edu.pe';

        $response = $this->import([
            $this->header(),
            ['Egresado Programa Raro', 'DNI', '76543212', '987654322', $email, 'EGRESADO', 'Carrera Inexistente XYZ'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($response->json('warnings'), 'Debió generar advertencia.');
        $this->assertStringContainsString(
            'Carrera Inexistente XYZ',
            implode(' ', $response->json('warnings'))
        );

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user, 'El usuario debió importarse a pesar de la advertencia.');
        $this->assertNull($user->person->study_program_id);
    }

    public function test_import_company_ignores_program_column()
    {
        $email = 'import.company.' . uniqid() . '@ejemplo.com';

        $response = $this->import([
            $this->header(),
            ['Empresa Import SAC', 'RUC', '20551234567', '955111222', $email, 'EMPRESA', 'Administración'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertEmpty($response->json('warnings'), 'A empresas no debe advertírsele por programa.');

        $user = User::where('email', $email)->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->company_id);
    }

    public function test_import_updates_existing_person_study_program()
    {
        $program = StudyProgram::firstOrCreate(
            ['name' => 'Ingeniería de Sistemas'],
            ['is_active' => true]
        );
        $email = 'import.updateprog.' . uniqid() . '@talentum.edu.pe';

        $person = Person::create([
            'document_type' => 'DNI',
            'document_number' => '76543990',
            'names' => 'Persona Existente Programa',
            'phone' => '987000111',
            'email' => $email,
            'study_program_id' => null,
        ]);

        $response = $this->import([
            $this->header(),
            ['Persona Existente Programa', 'DNI', '76543990', '987000111', $email, 'ESTUDIANTE', 'Ingeniería de Sistemas'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);

        $this->assertSame($program->id, $person->fresh()->study_program_id);
    }

    public function test_import_preserves_existing_person_study_program_when_program_unknown()
    {
        $existingProgram = StudyProgram::firstOrCreate(
            ['name' => 'Programa Preexistente Uno'],
            ['is_active' => true]
        );
        $email = 'import.keepprog.' . uniqid() . '@talentum.edu.pe';

        $person = Person::create([
            'document_type' => 'DNI',
            'document_number' => '76543991',
            'names' => 'Persona Con Programa',
            'phone' => '987000222',
            'email' => $email,
            'study_program_id' => $existingProgram->id,
        ]);

        $response = $this->import([
            $this->header(),
            ['Persona Con Programa', 'DNI', '76543991', '987000222', $email, 'ESTUDIANTE', 'Carrera Inexistente XYZ'],
        ]);

        $response->assertStatus(200)->assertJsonPath('success', true);
        $this->assertNotEmpty($response->json('warnings'), 'Debió generar advertencia por programa inexistente.');

        $this->assertSame($existingProgram->id, $person->fresh()->study_program_id);
    }
}
