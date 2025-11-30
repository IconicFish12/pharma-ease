<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Http\Resources\MedicineResource;
use App\Models\MedicineCategory;
use App\Models\Supplier;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Medicine::latest()->with(['category', 'supplier'])
                ->paginate(request()->input('paginate', 15));

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Medicine Data is get Successfully',
                'data' => MedicineResource::collection($data),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ]
            ]);
        }

        return view('admin.medicine.medicine_inventory', [
            'title' => 'Medicine Storage',
            'mainHeader' => 'Medicine Storage',
            'subHeader' => 'Tempat Penyimpanan Obat Apotek Lamtama',
            'dataArr' => $data,
            'categories'=> MedicineCategory::all(),
            'suppliers'=> Supplier::all()
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
    public function store(StoreMedicineRequest $request)
    {
        try {
            $data = Medicine::create($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Data is added Successfully',
                    'data' => $data
                ]);
            }

            $request->session()->flash('success', 'Medicine added successfully!');

            return back();
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to add Medicine Data',
                    'errors' => $e->getMessage()
                ]);
            }

            $request->session()->flash('error', 'Failed to add medicine: ' . $e->getMessage());

            return back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medicine $medicine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicineRequest $request, Medicine $medicine)
    {
        try {
            $medicine = Medicine::findOrFail($medicine->medicine_id);
            $data = $medicine->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Data is updated Successfully',
                    'data' => $data
                ]);
            }

            return back()->with('success', 'Medicine updated successfully!');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update Medicine Data',
                    'errors' => $e->getMessage()
                ]);
            }

            return back()->with('error', 'Failed to update medicine: ' . $e->getMessage())
                         ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicine $medicine)
    {
        try {
            $medicine = Medicine::findOrFail($medicine->medicine_id);
            $data = $medicine->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Medicine Data is deleted Successfully',
                    'data' => $data
                ]);
            }

            request()->session()->flash('success', 'Medicine deleted successfully!');

            return back();
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete Medicine Data',
                    'errors' => $e->getMessage()
                ]);
            }

            return back()->with('error', 'Failed to delete medicine: ' . $e->getMessage());
        }
    }
}
