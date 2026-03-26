<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\DoctorCreateUpdateRequest;
use App\Http\Requests\IndexRequest;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

class DoctorController
{
    /**
     * day la function tra ve danh sach cac doctors
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function index(IndexRequest $request): JsonResponse
    {
        $search = request()->input('search');

        $query = Doctor::query()->select(['id','name', 'department_id', 'code']);
        if ($search != null) {
            $query->where('code', $search)
                  ->orWhere('name', 'like', '%' . $search . '%');
        }
        $query->with(['department:id,name']);
        $data = $query->paginate(1);

        return response()->json($data);
    }

    public function store(DoctorCreateUpdateRequest $request)
    {
        $doctor = Doctor::query()->create($request->validated());

        return response()->json($doctor, 201);
    }

    public function show($id)
    {
        $doctor = Doctor::query()->findOrFail($id)->load('department');

        return response()->json($doctor);
    }

    public function update(DoctorCreateUpdateRequest $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update($request->validated());

        return response()->json([
            'message' => 'Doctor updated successfully',
            'data'    => $doctor
        ]);
    }

    public function destroy($id)
    {
        $doctor = Doctor::query()->find($id);
        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor not found'
            ], 404);
        }

        $doctor->update(['is_deleted' => true]);

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ], 204);
    }
}
