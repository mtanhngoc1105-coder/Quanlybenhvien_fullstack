<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Appointment;

class BookAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // ==========================
    // VALIDATE INPUT + CHECK TRÙNG
    // ==========================
    
    public function rules(): array
    {
        return [
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'date'      => 'required|date',
            'time'      => [
                'required',

                // Closure validation tự viết để kiểm tra lịch trùng
                function ($attribute, $value, $fail) {
                    // Check lịch trùng cho bác sĩ
                    $exists = Appointment::where('doctor_id', $this->doctor_id)
                        ->where('date', $this->date)
                        ->where('time', $value)
                        ->whereIn('status', ['pending', 'accepted'])
                        ->exists();

                        // Nếu trùng báo lỗi
                    if ($exists) {
                        $fail('Bác sĩ đã có lịch vào giờ này');
                    }
                }
            ],
            // Ghi chú không bắt buộc
            'note' => 'nullable|string'
        ];
    }
}
