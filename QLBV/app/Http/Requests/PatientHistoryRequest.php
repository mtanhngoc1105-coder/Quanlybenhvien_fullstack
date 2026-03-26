<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientHistoryRequest extends FormRequest
{
    // ==========================
    // ỦY QUYỀN
    // ==========================
    public function authorize(): bool
    {
        return true;
    }

    // ==========================
    // QUY TẮC XÁC THỰC
    // ==========================
    public function rules(): array
    {
        return [
            // Từ ngày (không bắt buộc)
            // - phải là date hợp lệ
            // - phải <= "to" nếu "to" tồn tại
            'from' => 'nullable|date|before_or_equal:to',

            // Đến ngày (không bắt buộc)
            // - phải là date hợp lệ
            // - phải >= "from" nếu "from" tồn tại
            'to'   => 'nullable|date|after_or_equal:from',
        ];
    }

    // ==========================
    // TÙY CHỈNH MESSAGES LỖI
    // ==========================
    public function messages(): array
    {
        return [
            'from.date' => 'Ngày bắt đầu không hợp lệ.',
            'to.date' => 'Ngày kết thúc không hợp lệ.',

            // Lỗi khi "from" > "to"
            'from.before_or_equal' => 'Ngày bắt đầu phải trước hoặc bằng ngày kết thúc.',

           // Lỗi khi "to" < "from"
            'to.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày bắt đầu.',
        ];
    }
}
