<?php
// API Routes
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\DoctorController;
use App\Http\Controllers\API\PatientController;
use App\Http\Controllers\API\MedicineController;
use App\Http\Controllers\API\AppointmentController;
use App\Http\Controllers\API\PrescriptionController;


// API Resources
Route::apiResource('departments', DepartmentController::class);
Route::apiResource('doctors', DoctorController::class);
Route::apiResource('patients', PatientController::class);
Route::apiResource('medicines', MedicineController::class);
Route::apiResource('appointments', AppointmentController::class);
Route::apiResource('prescriptions', PrescriptionController::class);

// Lịch sử khám bệnh của bệnh nhân
Route::get('/patients/{patientId}/history', [AppointmentController::class, 'getPatientHistory']);

// Lấy các khung giờ trống của bác sĩ trong ngày
Route::get('doctors/{id}/free-slots', [AppointmentController::class, 'getFreeSlots']);

// Đặt lịch hẹn khám bệnh
Route::post('appointments/book', [AppointmentController::class, 'book']);

// Bác sĩ duyệt hoặc từ chối lịch hẹn
Route::post('appointments/{id}/accept', [AppointmentController::class, 'accept']);
Route::post('appointments/{id}/reject', [AppointmentController::class, 'reject']);

Route::get('doctors/{id}/doctordetails', [AppointmentController::class, 'getDoctorDetails']);
