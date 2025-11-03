<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\PaidToeicController;
use App\Http\Controllers\FreeToeicController;
use App\Http\Controllers\AdminRegistrationController;
use App\Http\Controllers\EducationalStaffController;
use App\Http\Controllers\EducationalStaffRegistrationController;
use App\Http\Controllers\freeRegistController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\StudyProgramController;
use App\Http\Controllers\ToeicScoreController;
use App\Http\Controllers\ToeicRegistrationController;
use App\Http\Controllers\StudentUserController;
// use App\Http\Controllers\EducationalStaffController;

use App\Http\Controllers\ProfileController;
use App\Models\ToeicRegistration;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'postlogin'])->name('login.post');
    Route::get('/signup', [SignupController::class, 'index'])->name('signup.index');
    Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'index'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Routes (accessible by all roles)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('profile.change-password');
        Route::put('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    // Dashboard (accessible by all roles)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Announcements (accessible by all roles)
    Route::prefix('announcement')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('announcement.index');
        Route::middleware('role:admin')->group(function () {
            Route::get('/create', [AnnouncementController::class, 'create'])->name('announcement.create');
            Route::post('/', [AnnouncementController::class, 'store'])->name('announcement.store');
            Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcement.edit');
            Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('announcement.update');
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcement.destroy');
        });
        Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('announcement.show');
    });
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::prefix('freeRegist')->name('freeRegist.')->group(function () {
        Route::get('/', [freeRegistController::class, 'index'])->name('index');
        Route::get('/create', [freeRegistController::class, 'create'])->name('create');
        Route::post('/', [freeRegistController::class, 'store'])->name('store');
        Route::get('/{id}', [freeRegistController::class, 'show'])->name('show');
    });

    Route::get('/paid-toeic/register', [PaidToeicController::class, 'create'])->name('paid-toeic.form');
    Route::post('/paid-toeic/register', [PaidToeicController::class, 'store'])->name('paid-toeic.register');

        //  Route::get('/toeic-scores', [ToeicScoreController::class, 'index'])->name('toeic-scores.index');

             // TOEIC Scores - Student view only
    Route::get('/toeic-scores', [ToeicScoreController::class, 'studentScores'])->name('student.toeic-scores.index');
    // Route::get('/toeic-scores', [ToeicScoreController::class, 'index'])->name('toeic-scores.index');
    //     Route::prefix('toeic-scores')->name('toeic-scores.')->group(function () {
    //     Route::get('/', [ToeicScoreController::class, 'index'])->name('index');
    //     Route::get('/create', [ToeicScoreController::class, 'create'])->name('create');
    //     Route::post('/store', [ToeicScoreController::class, 'store'])->name('store');
    //     Route::get('/{id}/edit', [ToeicScoreController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [ToeicScoreController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [ToeicScoreController::class, 'destroy'])->name('destroy');
    // });

});

/*
|--------------------------------------------------------------------------
| Educational Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:educational_staff'])->group(function () {
    Route::prefix('educational-staff-registration')->group(function () {
        Route::get('/', [EducationalStaffRegistrationController::class, 'index'])->name('educational-staff-registration.index');
        Route::get('/create', [EducationalStaffRegistrationController::class, 'create'])->name('educational-staff-registration.create');
        Route::post('/', [EducationalStaffRegistrationController::class, 'store'])->name('educational-staff-registration.store');
        Route::get('/{id}', [EducationalStaffRegistrationController::class, 'show'])->name('educational-staff-registration.show');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Students Management
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/', [StudentUserController::class, 'index'])->name('index');
        Route::get('/create', [StudentUserController::class, 'create'])->name('create');
        Route::post('/', [StudentUserController::class, 'store'])->name('store');
        Route::get('/{id}', [StudentUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StudentUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StudentUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudentUserController::class, 'destroy'])->name('destroy');
    });

    // Free Registrations Management
    Route::prefix('freeRegist')->name('freeRegist.')->group(function () {
        Route::get('/secondRegistration/{id}', [freeRegistController::class, 'createSecondRegistration'])->name('secondRegistration');
        Route::get('/{id}/ktp', [freeRegistController::class, 'showKtp'])->name('showKtp');
        Route::get('/{id}/edit', [freeRegistController::class, 'edit'])->name('edit');
        Route::put('/{id}', [freeRegistController::class, 'update'])->name('update');
        Route::delete('/{id}', [freeRegistController::class, 'destroy'])->name('destroy');
    });

    // Admin Registrations
    Route::prefix('adminRegist')->name('adminRegist.')->group(function () {
        Route::get('/', [AdminRegistrationController::class, 'index'])->name('index');
        Route::get('/create', [AdminRegistrationController::class, 'create'])->name('create');
        Route::post('/', [AdminRegistrationController::class, 'store'])->name('store');
        Route::get('/{id}/success', [AdminRegistrationController::class, 'success'])->name('success');
        Route::get('/{id}', [AdminRegistrationController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminRegistrationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminRegistrationController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminRegistrationController::class, 'destroy'])->name('destroy');
    });

    // Educational Staff Management
    Route::prefix('educational-staff')->name('educational-staff.')->group(function () {
        Route::get('/', [EducationalStaffController::class, 'index'])->name('index');
        Route::get('/create', [EducationalStaffController::class, 'create'])->name('create');
        Route::post('/', [EducationalStaffController::class, 'store'])->name('store');
        Route::get('/{id}', [EducationalStaffController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [EducationalStaffController::class, 'edit'])->name('edit');
        Route::put('/{id}', [EducationalStaffController::class, 'update'])->name('update');
        Route::delete('/{id}', [EducationalStaffController::class, 'destroy'])->name('destroy');
    });
    // TOEIC Scores Management
    // Route::prefix('toeic-scores')->name('toeic-scores.')->group(function () {
    //     Route::get('/', [ToeicScoreController::class, 'index'])->name('index');
    //     Route::get('/create', [ToeicScoreController::class, 'create'])->name('create');
    //     Route::post('/store', [ToeicScoreController::class, 'store'])->name('store');
    //     Route::get('/{id}/edit', [ToeicScoreController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [ToeicScoreController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [ToeicScoreController::class, 'destroy'])->name('destroy');
    // });

        Route::prefix('admin/toeic-scores')->name('admin.toeic-scores.')->group(function () {
        Route::get('/', [ToeicScoreController::class, 'index'])->name('index');
        Route::get('/create', [ToeicScoreController::class, 'create'])->name('create');
        Route::post('/', [ToeicScoreController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ToeicScoreController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ToeicScoreController::class, 'update'])->name('update');
        Route::delete('/{id}', [ToeicScoreController::class, 'destroy'])->name('destroy');
    });

    // Majors Management
    Route::prefix('majors')->name('majors.')->group(function () {
        Route::get('/', [MajorController::class, 'index'])->name('index');
        Route::get('/create', [MajorController::class, 'create'])->name('create');
        Route::post('/store', [MajorController::class, 'store'])->name('store');
        Route::get('/{id}', [MajorController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MajorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MajorController::class, 'update'])->name('update');
        Route::delete('/{id}', [MajorController::class, 'destroy'])->name('destroy');
    });

    // Study Programs Management
    Route::prefix('study-programs')->name('study-programs.')->group(function () {
        Route::get('/', [StudyProgramController::class, 'index'])->name('index');
        Route::get('/create', [StudyProgramController::class, 'create'])->name('create');
        Route::post('/store', [StudyProgramController::class, 'store'])->name('store');
        Route::get('/{id}', [StudyProgramController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [StudyProgramController::class, 'edit'])->name('edit');
        Route::put('/{id}', [StudyProgramController::class, 'update'])->name('update');
        Route::delete('/{id}', [StudyProgramController::class, 'destroy'])->name('destroy');
    });

    // TOEIC Registrations
    Route::prefix('toeic-registration')->group(function () {
        Route::get('/index', [ToeicRegistrationController::class, 'index'])->name('toeic-registration.index');
        Route::get('/', [ToeicRegistrationController::class, 'create'])->name('toeic-registration.create');
        Route::post('/', [ToeicRegistrationController::class, 'store'])->name('toe-registration.store');
        Route::get('/success/{id}', [ToeicRegistrationController::class, 'success'])->name('toeic-registration.success');
    });
});
