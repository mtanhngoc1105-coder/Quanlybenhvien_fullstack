<?php

namespace App\Http\Controllers\API;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentCreateUpdateRequest;
use App\Http\Requests\BookAppointmentRequest;
use App\Http\Requests\PatientHistoryRequest;

class AppointmentController
{
    // =====================================
    // 1. Lấy danh sách appointment (có phân trang + search)
    // =====================================
    public function index()
    {
        // Lấy từ khóa tìm kiếm (nếu có)
        $search = request()->input('search');

        // Tạo query cơ bản
        $query = Appointment::query()
            ->with(['patient', 'doctor.department']);

       // Nếu có search thì thêm điều kiện lọc
        if ($search != null) {
            // Tìm kiếm theo tên bệnh nhân, tên bác sĩ hoặc ngày hẹn
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->orWhereHas('doctor', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })
            ->orWhere('date', $search);
        }

       // Trả về kết quả phân trang 10 bản ghi mỗi trang
        return response()->json($query->paginate(10));
    }

    // =====================================
    // 2. Tạo mới appointment
    // =====================================
    public function store(AppointmentCreateUpdateRequest $request)
    {
        // Tạo mới appointment từ dữ liệu đã validate
        $appointment = Appointment::create($request->validated());

        // Trả về dữ liệu appointment vừa tạo
        return response()->json($appointment, 201);
    }

    // =====================================
    // 3. Lấy chi tiết appointment theo ID
    // =====================================
    public function show($id)
    {
       // Lấy appointment theo ID kèm quan hệ patient và doctor.department
        $appointment = Appointment::with(['patient', 'doctor.department'])->findOrFail($id);

        // Trả về dữ liệu appointment
        return response()->json($appointment);
    }

    // =====================================
    // 4. Cập nhật appointment theo ID
    // =====================================
    public function update(AppointmentCreateUpdateRequest $request, int $id)
    {
        $appointment = Appointment::findOrFail($id);

        // Cập nhật appointment với dữ liệu đã validate
        $appointment->update($request->validated());

        // Trả về dữ liệu appointment đã cập nhật
        return response()->json([
            'message' => 'Appointment updated successfully',
            'data'    => $appointment
        ]);
    }

    // =====================================
    // 5. Xóa appointment theo ID
    // =====================================
    public function destroy($id)
    {
        Appointment::destroy($id);

        // Trả về phản hồi thành công
        return response()->json([
            'message' => 'Appointment deleted successfully'
        ], 200);
    }

    // =====================================
    // 6. Lấy lịch sử khám bệnh của 1 bệnh nhân
    // =====================================
    public function getPatientHistory(PatientHistoryRequest $request, $patientId)
    {
       // Tạo query lấy lịch sử khám bệnh
        $query = Appointment::with(['doctor.department'])
            ->where('patient_id', $patientId)
            ->whereIn('status', ['pending', 'completed', 'canceled']);

        // Filter theo “from”
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }

        // Filter theo “to”
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        // Lấy kết quả và định dạng lại
        $appointments = $query->get()->map(function ($item) {
            return [
                'date'       => $item->date,
                'time'       => $item->time,
                'doctor'     => $item->doctor->name,
                'department' => $item->doctor->department->name,
                'note'       => $item->note,
            ];
        });

        // Trả về kết quả
        return response()->json([
            'message' => 'Lịch sử khám bệnh của bệnh nhân',
            'data' => $appointments
        ]);
    }

    // =====================================
    // 7. Lấy các khung giờ trống của bác sĩ trong ngày
    // =====================================
    public function getFreeSlots(Request $request, $doctorId)
    {
        // Lấy ngày từ query parameter
        $date = $request->query('date');
        if (!$date) {
            return response()->json(['message' => 'Thiếu tham số date'], 400);
        }

        // Giờ làm việc cố định
        $workHours = [
            '08:00', '09:00', '10:00', '11:00',
            '13:00', '14:00', '15:00', '16:00'
        ];

        // Lấy các giờ đã được đặt trong ngày đó
        $booked = Appointment::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->whereIn('status', ['pending', 'accepted'])
            ->pluck('time')
            ->toArray();

        // Tính các khung giờ trống
        $freeSlots = array_values(array_diff($workHours, $booked));

        // Trả về kết quả
        return response()->json([
            'doctor_id' => $doctorId,
            'date' => $date,
            'free_slots' => $freeSlots
        ]);
    }

    // =====================================
    // 8. Đặt lịch hẹn khám bệnh
    // =====================================
    public function book(BookAppointmentRequest $request)
    {
        // Tạo mới appointment với trạng thái mặc định là 'pending'
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id'  => $request->doctor_id,
            'date'       => $request->date,
            'time'       => $request->time,
            'status'     => 'pending', // Mặc định là chờ duyệt
            'note'       => $request->note
        ]);

        // Trả về kết quả
        return response()->json([
            'message' => 'Đặt lịch thành công, chờ bác sĩ duyệt',
            'data' => $appointment
        ]);
    }

    // =====================================
    // 9. Bác sĩ duyệt lịch
    // =====================================
    public function accept($id)
    {
        $appointment = Appointment::find($id);

        // Nếu không tìm thấy lịch hẹn
        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch'], 404);
        }

        // Cập nhật trạng thái thành 'accepted'
        $appointment->status = 'accepted';
        $appointment->save();

        // Trả về kết quả
        return response()->json([
            'message' => 'Đã duyệt lịch hẹn',
            'data' => $appointment
        ]);
    }

    // =====================================
    // 10. Bác sĩ từ chối lịch
    // =====================================
    public function reject($id)
    {
        $appointment = Appointment::find($id);

        // Nếu không tìm thấy lịch hẹn
        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch'], 404);
        }

        // Cập nhật trạng thái thành 'rejected'
        $appointment->status = 'rejected';
        $appointment->save();

        // Trả về kết quả
        return response()->json([
            'message' => 'Đã từ chối lịch hẹn',
            'data' => $appointment
        ]);
    }
}
