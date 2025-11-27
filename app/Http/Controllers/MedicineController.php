<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
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
        // dd(Medicine::with(['medicineCategory', 'supplier'])->get()->count());
        return view('admin.medicine.medicine_inventory', [
            'title' => 'Medicine Storage',
            'mainHeader' => 'Medicine Storage',
            'subHeader' => 'Tempat Penyimpanan Obat Apotek Lamtama',
            'dataArr' => Medicine::with(['category', 'supplier'])->paginate(request()->has('paginate') ?? 15),
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
            Medicine::create($request->validated());

            $request->session()->flash('success', 'Medicine added successfully!');
            return back();
        } catch (\Exception $e) {
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
            $medicine->update($request->validated());

            return back()->with('success', 'Medicine updated successfully!');
        } catch (\Exception $e) {
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
            $medicine->delete();

            return back()->with('success', 'Medicine deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete medicine: ' . $e->getMessage());
        }
    }
}
