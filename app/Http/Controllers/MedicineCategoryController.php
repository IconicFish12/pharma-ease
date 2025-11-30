<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use App\Http\Requests\StoreMedicineCategoryRequest;
use App\Http\Requests\UpdateMedicineCategoryRequest;
use App\Http\Resources\MedicineCategoryResource;
class MedicineCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $data = MedicineCategory::latest()->paginate(request()->input('paginate', 15));

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Medicine Category Data fetched Successfully',
                'data' => MedicineCategoryResource::collection($data),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ]
            ]);
        }

        return view('admin.medicine.medicine_category', [
            'title' => 'Medicine Category',
            'mainHeader' => 'Medicine Category',
            'subHeader' => 'Kategori Obat yang tersedia di Apotek Lamtama',
            'dataArr' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicineCategoryRequest $request)
    {
        try {
            $data = MedicineCategory::create($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Category Data is added Successfully',
                    'data' => $data
                ]);
            }

            $request->session()->flash('success', 'Data Berhasil dibuat');

            return back();
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add Medicine Category Data',
                    'errors' => $e->getMessage()
                ]);
            }

            $request->session()->flash('error', 'Failed to add medicine category: ' . $e->getMessage());

            return back()->withInput();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(MedicineCategory $medicineCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicineCategory $medicineCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicineCategoryRequest $request, MedicineCategory $medicineCategory)
    {
        try {
            if ($request->name == $medicineCategory->name && $request->description == $medicineCategory->description) {
                $request->session()->flash('success', 'tidak ada data yang diubah');

                return back();
            }
            $data = $medicineCategory->find($medicineCategory->category_id)->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Category Data is updated Successfully',
                    'data' => $data
                ]);
            }

            $request->session()->flash('success', 'Data Berhasil diubah');

            return back();
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update Medicine Category Data',
                    'errors' => $e->getMessage()
                ]);
            }

            $request->session()->flash('error', 'Failed to update medicine category: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineCategory $medicineCategory)
    {
        try {
            $data = $medicineCategory->destroy($medicineCategory->category_id);

            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Category Data is deleted Successfully',
                    'data' => $data
                ]);
            }

            request()->session()->flash('success', "Data Berhasil dihapus");

            return back();
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete Medicine Category Data',
                    'errors' => $e->getMessage()
                ]);
            }

            return back()->with('error', 'Failed to delete medicine category: ' . $e->getMessage());
        }
    }
}
