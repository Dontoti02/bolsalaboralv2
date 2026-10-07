# Final Fix Report — 2 code-review findings

Branch: `main` (changes left in working tree, **no commits made**).

## Finding #1 (Important): person update branch drops `study_program_id`

**File:** `app/Imports/UsersImport.php`

**Before (lines 256-265):** the person **update** branch updated only
`document_type / document_number / names / phone / updated_at` — `study_program_id`
from `$p` was never applied, so an existing person never received the program
resolved from the import row.

**After:** `app/Imports/UsersImport.php:258-268` — update payload built in
`$personUpdate`; `study_program_id` added **only when** `$p['study_program_id'] !== null`:

```php
$personUpdate = [
    'document_type' => $p['document_type'],
    'document_number' => $p['document_number'],
    'names' => $p['names'],
    'phone' => $p['phone'],
    'updated_at' => now(),
];
if ($p['study_program_id'] !== null) {
    $personUpdate['study_program_id'] = $p['study_program_id'];
}
DB::table('person')->where('id', $pId)->update($personUpdate);
```

Behavior:
- Program resolved (`!== null`) → update **sets** it.
- Program unknown/absent (null) → key omitted → existing `person.study_program_id` **preserved** (insert path unchanged; `$peopleToInsert` still carries `'study_program_id' => $studyProgramId`).

**Tests added** in `tests/Feature/UsersImportTest.php` (uses existing helpers `makeXlsx`, `header`, `admin`, `import`):
- `test_import_updates_existing_person_study_program` — `tests/Feature/UsersImportTest.php:135` (Test A)
- `test_import_preserves_existing_person_study_program_when_program_unknown` — `tests/Feature/UsersImportTest.php:172` (Test B)
- Import added: `use App\Models\Person;` at `tests/Feature/UsersImportTest.php:5`. Verified `Person::$fillable` includes `document_type, document_number, names, phone, email, study_program_id` before writing the tests.

## Finding #2 (Minor): dead import

**File:** `app/Http/Controllers/AdminController.php`

- Verified first with grep for `Request` scoped to `AdminController.php`: only the `use` statement matched; no type-hints, no `Request::` static calls anywhere else in the file.
- Deleted `use Illuminate\Http\Request;` (was line 9; imports now at `app/Http/Controllers/AdminController.php:5-9`).
- Re-grepped after the edit: **zero** matches for `Request` in the file.

## TDD evidence

**Command (RED and GREEN, same invocation):**

```powershell
$p = @{}; Get-Content .env | ForEach-Object { if ($_ -match '^([A-Z_]+)=(.*)$') { $p[$Matches[1]] = $Matches[2] } }
$env:DB_CONNECTION='mysql'; $env:DB_HOST=$p['DB_HOST']; $env:DB_PORT=$p['DB_PORT']; $env:DB_DATABASE=$p['DB_DATABASE']; $env:DB_USERNAME=$p['DB_USERNAME']; $env:DB_PASSWORD=$p['DB_PASSWORD']
php artisan test --filter=UsersImportTest
```

(DB_* loaded from `.env` into process env without printing values; default sqlite `:memory:` config would skip.)

### RED (tests written first, before any production change)

```
FAIL  Tests\Feature\UsersImportTest
  ✓ import assigns study program to student case insensitive    0.47s
  ✓ import rejects student without program                      0.07s
  ✓ import warns and creates user when program does not exist   0.07s
  ✓ import company ignores program column                       0.07s
  ⨯ import updates existing person study program                0.08s
  ✓ import preserves existing person study program when program unknown  0.08s

FAILED  Tests\Feature\UsersImportTest > import updates existing person study program
Failed asserting that null is identical to 3.
  at tests/Feature/UsersImportTest.php:166

Tests:    1 failed, 5 passed (25 assertions)
```

Why expected: `study_program_id` stayed `null` because the update branch never writes it — exactly Finding #1. Failure is an assertion failure (feature missing), not an error/typo.

Note on Test B: it passes on the pre-fix code (it must — the pre-fix bug is *dropping* an update, not wiping a value). Its job is to guard the required "do NOT overwrite when null" behavior, i.e. it fails against a naive unconditional `'study_program_id' => $p['study_program_id']` fix. Verified against that naive fix mentally: it would set `null` and Test B would fail. So the pair together pins both required behaviors.

### GREEN (after implementing the fix)

```
PASS  Tests\Feature\UsersImportTest
  ✓ import assigns study program to student case insensitive    0.44s
  ✓ import rejects student without program                      0.07s
  ✓ import warns and creates user when program does not exist   0.07s
  ✓ import company ignores program column                       0.08s
  ✓ import updates existing person study program                0.07s
  ✓ import preserves existing person study program when program unknown  0.06s

Tests:    6 passed (25 assertions)
Duration: 0.99s
```

Expected 6 passed (4 existing + 2 new) → matches.

## `php -l` output

```
No syntax errors detected in app\Imports\UsersImport.php
No syntax errors detected in app\Http\Controllers\AdminController.php
No syntax errors detected in tests\Feature\UsersImportTest.php
```

## Self-review + concerns

- Scope respected: only `app/Imports/UsersImport.php`, `app/Http/Controllers/AdminController.php`, `tests/Feature/UsersImportTest.php` edited by this fix. Template / `dashboard.blade.php` / routes untouched (their earlier diffs remain from prior tasks). No git mutations run (only read-only `git status --porcelain`, `git diff --stat`).
- Update path is guarded with `!== null` (not `empty()`), so a program id of `0`/falsy would still be applied; ids are auto-increment so `null` is the only realistic "absent" value.
- Test data uses unique emails (`uniqid()`) and unique DNI numbers to avoid cross-run collisions in the shared MySQL test DB (no `RefreshDatabase` in this suite — tests persist rows; helpers already follow this pattern).
- Test A uses program name `Ingeniería de Sistemas` via `firstOrCreate`, consistent with the existing test.
- Concern (minor): the suite writes to the real MySQL database without truncation; repeated runs accumulate rows. Pre-existing characteristic of this test file, not introduced here.
- Concern (minor): Test B was green pre-fix by design; only Test A produced the RED signal. Documented above.
- All 6 tests pass; both files lint clean.
