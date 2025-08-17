<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Middleware\VerifyCsrfToken;

Route::middleware('guest')->group(function () {
    Route::get('/', [HomeController::class, 'guestDashboard'])->name('guestDashboard');
});

Route::match(['GET', 'POST'], '/rfid-attendance', [AttendanceController::class, 'markDailyAttendance'])
    ->name('rfid.attendance');

Route::middleware('auth')->group(function () {
    // auth routes
    Route::get('/login', [HomeController::class, 'login'])->name('auth.login');
    Route::get('/register', [HomeController::class, 'register'])->name('auth.register');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware('auth')->group(function () {
    Route::get('faculty/profile', [FacultyController::class, 'profile'])->name('faculty.profile');
    
    Route::get('faculty/dashboard', [App\Http\Controllers\FacultyController::class, 'dashboard'])->name('faculty.dashboard');
    Route::get('faculty/attendance/logs', [App\Http\Controllers\FacultyController::class, 'attendanceLog'])->name('faculty.attendance.logs');
});
Route::middleware('auth', 'admin')->group(function () {
    Route::get('admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    //Manage Faculty
    Route::get('admin/manageFaculty/viewFaculty', [AdminController::class, 'ViewFaculty'])->name('admin.manageFaculty.viewFaculty');
    Route::get('admin/manageFaculty/addFaculty', [AdminController::class, 'addFaculty'])->name('admin.manageFaculty.addFaculty');
    Route::get('admin/manageFaculty/editFaculty/{Faculty_ID}', [AdminController::class, 'editFaculty'])->name('admin.manageFaculty.editFaculty');
    Route::patch('admin/manageFaculty/editFaculty/{Faculty_ID}', [AdminController::class, 'editFacultyPatch'])->name('admin.manageFaculty.editFacultyPatch');
    Route::delete('admin/manageFaculty/deleteFaculty/{Faculty_ID}', [AdminController::class, 'deleteFaculty'])->name('admin.manageFaculty.deleteFaculty');
    //Manage Class Schedule
    Route::post('admin/manageFaculty/addFacultystore', [AdminController::class, 'addFacultyStore'])->name('admin.manageFaculty.addFacultyStore');
    Route::get('admin/manageClassSchedule/addClassSched', [AdminController::class, 'addClassSchedule'])->name('admin.manageClassSchedule.addClassSched');
    Route::post('admin/manageClassSchedule/storeClassSchedule', [AdminController::class, 'storeClassSchedule'])->name('admin.manageClassSchedule.storeClassSchedule');
    Route::get('admin/manageClassSchedule/viewClassSched', [AdminController::class, 'viewClassSchedule'])->name('admin.manageClassSchedule.viewClassSched');
    Route::get('admin/manageClassSchedule/editClassSched/{id}', [AdminController::class, 'editClassSchedule'])->name('admin.manageClassSchedule.editClassSched');
    Route::PUT('admin/manageClassSchedule/editClassSched/{id}', [AdminController::class, 'updateClassSchedule'])->name('admin.manageClassSchedule.updateClassSchedule');
    Route::delete('admin/manageClassSchedule/viewClassSched/delete/{id}', [AdminController::class, 'deleteClassSchedule'])->name('admin.manageClassSchedule.deleteClassSchedule');
    //Daily Attendance
    Route::get('admin/attendance/dailyAttendance', [AdminController::class, 'dailyAttendance'])->name('admin.attendance.dailyAttendance');
    Route::get('admin/attendance/dailyAttendance/count', [AdminController::class, 'count']);
    Route::get('admin/attendance/dailyAttendance/rows', [AdminController::class, 'dailyAttendanceRows'])->name('admin.attendance.rows');
    Route::get('admin/attendance/attendanceReport', [AdminController::class, 'attendanceReport'])->name('admin.attendance.attendanceReport');
    Route::get('/attendance-report/pdf', [AdminController::class, 'downloadAttendanceReport'])->name('attendance.report.pdf');
    Route::get('/attendance-report/csv', [AdminController::class, 'downloadAttendanceCSV'])->name('attendance.report.csv');
    Route::get('admin/login-locations', [App\Http\Controllers\Auth\LoginLocationController::class, 'adminIndex'])->name('admin.login.locations');
    // Admin locations map (removed)
});
// Minimal named dashboard route used by auth scaffolding/tests
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware(['auth']);

require __DIR__.'/auth.php';
