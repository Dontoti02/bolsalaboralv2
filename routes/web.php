<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación públicas
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/registro-empresa', [AuthController::class, 'showLoginForm'])->name('register.company');
Route::post('/register/company', [AuthController::class, 'registerCompany']);
Route::get('/registro-egresado', [AuthController::class, 'showLoginForm'])->name('register.graduate');
Route::post('/register/graduate', [AuthController::class, 'registerGraduate'])->name('register.graduate.post');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobOpportunityController;

Route::middleware(['auth'])->group(function () {
    // Landing Page privada (solo para logueados)
    Route::get('/', [LandingController::class, 'index'])->name('landing');
    Route::get('/buscar-ofertas', [LandingController::class, 'searchOffers'])->name('landing.search');

    // Secure CV Download Route
    Route::get('/applications/{id}/cv', [\App\Http\Controllers\CompanyDashboardController::class, 'downloadApplicationCv'])->name('applications.cv.download');
    Route::post('/clear-password-warning', [LandingController::class, 'clearPasswordWarning'])->name('clear.password.warning');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);

    // Admin Routes (rol_id = 1)
    Route::middleware(['role:1'])->group(function () {
        Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users/filter', [App\Http\Controllers\AdminController::class, 'filterUsers'])->name('admin.users.filter');

        Route::post('/admin/users', [UserController::class, 'store']);
        Route::put('/admin/users/{id}', [UserController::class, 'update']);
        Route::post('/admin/users/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::post('/admin/users/bulk-delete', [UserController::class, 'bulkDelete']);
        Route::post('/admin/users/{id}/change-password', [UserController::class, 'changePassword']);
        Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
        Route::post('/admin/users/import', [UserController::class, 'import']);
        Route::post('/admin/settings', [UserController::class, 'saveSettings']);
        Route::post('/admin/settings/delete-image', [UserController::class, 'deleteSettingsImage']);
        Route::post('/admin/settings/mail', [UserController::class, 'saveMailSettings']);
        Route::post('/admin/settings/mail/test', [UserController::class, 'sendTestMail']);
        Route::post('/admin/profile', [UserController::class, 'updateOwnProfile']);
        Route::post('/admin/profile/password', [UserController::class, 'updateOwnPassword']);

        // Admin Company Management Routes
        Route::get('/admin/companies', [UserController::class, 'listCompanies']);
        Route::post('/admin/companies/bulk-delete', [UserController::class, 'bulkDeleteCompanies']);
        Route::post('/admin/companies', [UserController::class, 'storeCompany']);
        Route::post('/admin/companies/{id}', [UserController::class, 'updateCompany']);
        Route::post('/admin/companies/{id}/toggle-verify', [UserController::class, 'toggleVerifyCompany']);
        Route::post('/admin/companies/{id}/send-verification-email', [UserController::class, 'sendVerificationEmail']);
        Route::delete('/admin/companies/{id}', [UserController::class, 'deleteCompany']);

        // Admin Applications (Postulaciones) Routes
        Route::get('/admin/export/excel', [UserController::class, 'exportExcel'])->name('admin.export.excel');
        Route::get('/admin/applications', [UserController::class, 'listApplications']);
        Route::post('/admin/applications/{id}/status', [UserController::class, 'updateApplicationStatus']);
        Route::post('/admin/applications/bulk-delete', [UserController::class, 'bulkDeleteApplications']);
        Route::delete('/admin/applications/{id}', [UserController::class, 'deleteApplication']);

        // Job Opportunities (Ofertas Laborales) Routes
        Route::get('/admin/offers', [JobOpportunityController::class, 'index']);
        Route::post('/admin/offers', [JobOpportunityController::class, 'store']);
        Route::get('/admin/offers/meta', [JobOpportunityController::class, 'getMetadata']);
        Route::post('/admin/offers/meta/add', [JobOpportunityController::class, 'addMetadataItem']);
        Route::put('/admin/maintainers/{type}/{id}', [JobOpportunityController::class, 'updateMetadataItem']);
        Route::delete('/admin/maintainers/{type}/{id}', [JobOpportunityController::class, 'deleteMetadataItem']);
        Route::get('/admin/offers/{id}', [JobOpportunityController::class, 'show']);
        Route::put('/admin/offers/{id}', [JobOpportunityController::class, 'update']);
        Route::delete('/admin/offers/{id}', [JobOpportunityController::class, 'destroy']);
        Route::post('/admin/offers/bulk-delete', [JobOpportunityController::class, 'bulkDestroy']);
        Route::post('/admin/offers/{id}/toggle-state', [JobOpportunityController::class, 'toggleState']);
        Route::get('/admin/offers/{id}/applicants', [UserController::class, 'getOfferApplicants']);

        // Study Program Routes
        Route::get('/admin/users/search-persons', [App\Http\Controllers\AdminController::class, 'searchPersonUsers'])->name('admin.users.search-persons');
        Route::post('/admin/study-programs/assign', [App\Http\Controllers\AdminController::class, 'assignStudyProgram'])->name('admin.study-programs.assign');

        // Reports Routes (Estudiantes y Egresados)
        Route::get('/admin/reports/data', [\App\Http\Controllers\ReportController::class, 'getReportsData'])->name('admin.reports.data');
        Route::get('/admin/reports/export-excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');
    });
    
    // Rutas comunes de perfil, postulación y CVs para Estudiantes, Docentes y Egresados (rol_id = 2, 3 y 5)
    Route::middleware(['role:2,3,5'])->group(function () {
        Route::post('/student/profile', [\App\Http\Controllers\StudentController::class, 'updateProfile'])->name('student.profile.update');
        Route::post('/student/avatar', [\App\Http\Controllers\StudentController::class, 'updateAvatar'])->name('student.avatar.update');
        Route::post('/student/password', [\App\Http\Controllers\StudentController::class, 'changePassword'])->name('student.password.change');

        // Dashboard y postulaciones
        Route::get('/student/dashboard', fn() => redirect('/'))->name('student.dashboard');
        Route::get('/student/applications', [\App\Http\Controllers\StudentController::class, 'myApplications'])->name('student.applications');
        Route::post('/student/cv/upload', [\App\Http\Controllers\StudentController::class, 'uploadCv'])->name('student.cv.upload');
        Route::post('/student/cv/delete/{id}', [\App\Http\Controllers\StudentController::class, 'deleteCv'])->name('student.cv.delete');
        Route::get('/student/cv/download/{id}', [\App\Http\Controllers\StudentController::class, 'downloadCv'])->name('student.cv.download');
        Route::post('/student/apply/{offer_id}', [\App\Http\Controllers\StudentController::class, 'applyToOffer'])->name('student.apply');
        Route::post('/student/save-offer/{offer_id}', [\App\Http\Controllers\StudentController::class, 'toggleSaveOffer'])->name('student.save.offer');
        Route::get('/student/saved-offers', [\App\Http\Controllers\StudentController::class, 'savedOffers'])->name('student.saved.offers');
    });


    // Company Routes (rol_id = 4)
    Route::middleware(['role:4'])->group(function () {
        Route::get('/company/dashboard', [\App\Http\Controllers\CompanyDashboardController::class, 'showDashboard'])->name('company.dashboard');
        Route::post('/company/profile', [\App\Http\Controllers\CompanyDashboardController::class, 'updateProfile']);
        Route::get('/company/offers', [\App\Http\Controllers\CompanyDashboardController::class, 'listOffers'])->name('company.offers.index');
        Route::post('/company/offers', [\App\Http\Controllers\CompanyDashboardController::class, 'storeOffer'])->name('company.offers.store');
        Route::get('/company/offers/meta', [\App\Http\Controllers\CompanyDashboardController::class, 'getOfferMeta'])->name('company.offers.meta');
        Route::post('/company/offers/meta/add', [\App\Http\Controllers\CompanyDashboardController::class, 'addOfferMetaItem']);
        Route::get('/company/offers/{id}', [\App\Http\Controllers\CompanyDashboardController::class, 'showOffer'])->name('company.offers.show');
        Route::put('/company/offers/{id}', [\App\Http\Controllers\CompanyDashboardController::class, 'updateOffer'])->name('company.offers.update');
        Route::delete('/company/offers/{id}', [\App\Http\Controllers\CompanyDashboardController::class, 'destroyOffer'])->name('company.offers.destroy');
        Route::post('/company/offers/{id}/toggle-state', [\App\Http\Controllers\CompanyDashboardController::class, 'toggleOfferState'])->name('company.offers.toggle-state');
        Route::post('/company/applications/{id}/status', [\App\Http\Controllers\CompanyDashboardController::class, 'updateApplicationStatus'])->name('company.applications.status');
    });

    // Teacher Routes (rol_id = 2)
    Route::middleware(['role:2'])->group(function () {
        Route::get('/teacher/dashboard', fn() => redirect('/'))->name('teacher.dashboard');
    });
});

