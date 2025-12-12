<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Coach\DashboardController as CoachDashboardController;
use App\Http\Controllers\Member\DashboardController as MemberDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes avec rate limiting
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1'); // 5 tentatives par minute
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Route de redirection après login
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    switch ($user->role) {
        case 'admin':
            return redirect()->route('admin.dashboard');
        case 'coach':
            return redirect()->route('coach.dashboard');
        case 'member':
            return redirect()->route('member.dashboard');
        default:
            return redirect()->route('member.dashboard');
    }
})->middleware('auth')->name('dashboard');

// Admin Routes - Avec middleware role
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Members
    Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);
    Route::get('members/{member}/attendance', [\App\Http\Controllers\Admin\MemberController::class, 'generateQrCode'])->name('members.attendance');
    Route::get('members/{member}/quick-payment', [\App\Http\Controllers\Admin\PaymentController::class, 'create'])->name('members.quick-payment');
    Route::post('members/search-by-phone', [\App\Http\Controllers\Admin\MemberController::class, 'searchByPhone'])->name('members.search-by-phone');
    
    // Coaches
    Route::resource('coaches', \App\Http\Controllers\Admin\CoachController::class);
    
    // Programs
    Route::resource('programs', \App\Http\Controllers\Admin\ProgramController::class);
    Route::get('members/{member}/assign-program', [\App\Http\Controllers\Admin\ProgramController::class, 'assignMemberForm'])->name('programs.assignMemberForm');
    Route::post('members/{member}/assign-program', [\App\Http\Controllers\Admin\ProgramController::class, 'assignMember'])->name('programs.assignMember');
    
    // Classes
    Route::resource('classes', \App\Http\Controllers\Admin\ClassController::class);
    
    // Attendance
    Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/record', [\App\Http\Controllers\Admin\AttendanceController::class, 'showRecord'])->name('attendance.record');
    Route::post('attendance/record', [\App\Http\Controllers\Admin\AttendanceController::class, 'record'])->name('attendance.record');
    Route::get('api/members/search', [\App\Http\Controllers\Admin\AttendanceController::class, 'searchMembers'])->name('api.members.search');
    
    // Equipment
    Route::resource('equipment', \App\Http\Controllers\Admin\EquipmentController::class);
    Route::post('equipment/{equipment}/maintenance', [\App\Http\Controllers\Admin\EquipmentController::class, 'logMaintenance'])->name('equipment.maintenance');
    
    // Payments
    Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);
    Route::post('payments/quick', [\App\Http\Controllers\Admin\PaymentController::class, 'quickPayment'])->name('payments.quick');
    Route::get('members/{member}/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'memberPayments'])->name('members.payments');
    Route::patch('payments/{payment}/accept', [\App\Http\Controllers\Admin\PaymentController::class, 'accept'])->name('payments.accept');
    Route::get('payments-help', function() { return view('admin.payments.help'); })->name('payments.help');
    
    // Challenges
    Route::resource('challenges', \App\Http\Controllers\Admin\ChallengeController::class);
    
    // Reports
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    
    // Settings
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    // Inquiries
    Route::get('inquiries', [\App\Http\Controllers\Admin\InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('inquiries/{inquiry}', [\App\Http\Controllers\Admin\InquiryController::class, 'show'])->name('inquiries.show');
    Route::patch('inquiries/{inquiry}/status', [\App\Http\Controllers\Admin\InquiryController::class, 'updateStatus'])->name('inquiries.update-status');
    Route::delete('inquiries/{inquiry}', [\App\Http\Controllers\Admin\InquiryController::class, 'destroy'])->name('inquiries.destroy');
});

// Coach Routes - Avec middleware role
Route::prefix('coach')->name('coach.')->middleware(['auth', 'role:coach'])->group(function () {
    Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('dashboard');
    
    // Members
    Route::get('members', [\App\Http\Controllers\Coach\MemberController::class, 'index'])->name('members.index');
    Route::get('members/{member}', [\App\Http\Controllers\Coach\MemberController::class, 'show'])->name('members.show');
    Route::get('members/{member}/edit', [\App\Http\Controllers\Coach\MemberController::class, 'edit'])->name('members.edit');
    Route::put('members/{member}', [\App\Http\Controllers\Coach\MemberController::class, 'update'])->name('members.update');
    Route::get('members/{member}/assign-program', [\App\Http\Controllers\Coach\MemberController::class, 'assignProgramForm'])->name('members.assignProgram');
    Route::post('members/{member}/assign-program', [\App\Http\Controllers\Coach\MemberController::class, 'assignProgram']);
    
    // Programs
    Route::resource('programs', \App\Http\Controllers\Coach\ProgramController::class);
    Route::post('programs/{program}/assign', [\App\Http\Controllers\Coach\ProgramController::class, 'assign'])->name('programs.assign');
    
    // Exercises
    Route::resource('exercises', \App\Http\Controllers\Coach\ExerciseController::class);
    
    // Classes
    Route::resource('classes', \App\Http\Controllers\Coach\ClassController::class);
    Route::get('classes/{class}/attendees', [\App\Http\Controllers\Coach\ClassController::class, 'attendees'])->name('classes.attendees');
    Route::post('classes/{class}/check-in', [\App\Http\Controllers\Coach\ClassController::class, 'checkIn'])->name('classes.check-in');
    
    // Attendance
    Route::get('attendance', [\App\Http\Controllers\Coach\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/record', [\App\Http\Controllers\Coach\AttendanceController::class, 'record'])->name('attendance.record');
    Route::post('attendance/scan', [\App\Http\Controllers\Coach\AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('attendance/record', [\App\Http\Controllers\Coach\AttendanceController::class, 'recordManual'])->name('attendance.record');
    Route::get('api/members/search', [\App\Http\Controllers\Coach\AttendanceController::class, 'searchMembers'])->name('api.members.search');
    Route::post('attendance/{attendance}/checkout', [\App\Http\Controllers\Coach\AttendanceController::class, 'checkout'])->name('attendance.checkout');
    Route::post('attendance/member/{member}/checkin', [\App\Http\Controllers\Coach\AttendanceController::class, 'forceCheckIn'])->name('attendance.force-checkin');
    
    // Schedule
    Route::get('schedule', [\App\Http\Controllers\Coach\ScheduleController::class, 'index'])->name('schedule.index');
});

// Member Routes - Avec middleware role
Route::prefix('member')->name('member.')->middleware(['auth', 'role:member'])->group(function () {
    Route::get('/dashboard', [MemberDashboardController::class, 'index'])->name('dashboard');
    
    // Program
    Route::get('program', [\App\Http\Controllers\Member\ProgramController::class, 'index'])->name('program.index');
    Route::post('program/{program}/enroll', [\App\Http\Controllers\Member\ProgramController::class, 'enroll'])->name('program.enroll');
    Route::post('program/exercise/{exercise}/complete', [\App\Http\Controllers\Member\ProgramController::class, 'completeExercise'])->name('program.complete-exercise');
    
    // Workout
    Route::get('workout', [\App\Http\Controllers\Member\WorkoutController::class, 'index'])->name('workout.index');
    Route::post('workout/check-in', [\App\Http\Controllers\Member\WorkoutController::class, 'checkIn'])->name('workout.check-in');
    Route::post('workout/check-out', [\App\Http\Controllers\Member\WorkoutController::class, 'checkOut'])->name('workout.check-out');
    
    // Classes
    Route::resource('classes', \App\Http\Controllers\Member\ClassController::class)->only(['index', 'show', 'store', 'destroy']);
    Route::post('classes/{class}/book', [\App\Http\Controllers\Member\ClassController::class, 'book'])->name('classes.book');
    Route::post('classes/booking/{booking}/cancel', [\App\Http\Controllers\Member\ClassController::class, 'cancel'])->name('classes.cancel');
    
    // Attendance
    Route::get('attendance', [\App\Http\Controllers\Member\AttendanceController::class, 'index'])->name('attendance.index');
    
    // Challenges
    Route::resource('challenges', \App\Http\Controllers\Member\ChallengeController::class)->only(['index', 'show']);
    Route::post('challenges/{challenge}/join', [\App\Http\Controllers\Member\ChallengeController::class, 'join'])->name('challenges.join');
    
    // Progress
    Route::get('progress', [\App\Http\Controllers\Member\ProgressController::class, 'index'])->name('progress.index');
    Route::get('progress/chart', [\App\Http\Controllers\Member\ProgressController::class, 'chart'])->name('progress.chart');
    
    // Profile
    Route::get('profile', [\App\Http\Controllers\Member\ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [\App\Http\Controllers\Member\ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [\App\Http\Controllers\Member\ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::put('profile/membership', [\App\Http\Controllers\Member\ProfileController::class, 'updateMembership'])->name('profile.update-membership');
    
    // Attendance (Self-service check-in)
    Route::get('attendance/qrcode', [\App\Http\Controllers\Member\QrCodeController::class, 'show'])->name('member.attendance');
    // Toggle attendance (replaces QR check-in flow)
    Route::post('attendance/toggle', [\App\Http\Controllers\Member\AttendanceController::class, 'toggle'])->name('attendance.toggle');
    
    // Payments
    Route::get('payments', [\App\Http\Controllers\Member\PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/online', [\App\Http\Controllers\Member\PaymentController::class, 'onlinePayment'])->name('payments.online');
});

// Autres pages publiques
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Demandes de renseignements
Route::get('/demande-renseignements', [\App\Http\Controllers\InquiryController::class, 'create'])->name('inquiry.create');
Route::post('/demande-renseignements', [\App\Http\Controllers\InquiryController::class, 'store'])
    ->middleware('throttle:3,10') // 3 demandes par 10 minutes
    ->name('inquiry.store');

// Profile Routes (pour tous les utilisateurs)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/password', [ProfileController::class, 'password'])->name('password.edit');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
});