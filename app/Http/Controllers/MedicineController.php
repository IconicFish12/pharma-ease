<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Http\Requests\StoreMedicineRequest;
use App\Http\Requests\UpdateMedicineRequest;
use App\Models\MedicineCategory;
use App\Models\Supplier;

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
            'dataArr' => Medicine::with(['medicineCategory', 'supplier'])->get(),
            'category'=> MedicineCategory::all(),
            'supplier'=> Supplier::all()
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
        dd($request->all());
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicine $medicine)
    {
        //
    }
}
