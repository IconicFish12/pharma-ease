<?php

namespace App\Http\Controllers;

use App\Models\MedicineCategory;
use App\Http\Requests\StoreMedicineCategoryRequest;
use App\Http\Requests\UpdateMedicineCategoryRequest;

class MedicineCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.medicine.medicine_category', [
            'title' => 'Medicine Category',
            'mainHeader' => 'Medicine Category',
            'subHeader' => 'Kategori Obat yang tersedia di Apotek Lamtama',
            'dataArr' => MedicineCategory::all(),
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
        if($request->validated()){
            $data = $request->all();

            MedicineCategory::create([
                'name' => $data['name'],
                'description' => $data['description']
            ]);

            $request->session()->flash('success', 'Data Berhasil dibuat');

            return back();
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
        if ($request->validated()) {
            if ($request->name == $medicineCategory->name && $request->description == $medicineCategory->description) {
                $request->session()->flash('success', 'tidak ada data yang diubah');

                return back();
            }

            $data = $request->all();


            $medicineCategory->find($medicineCategory->category_id)->update([
                'name' => $data['name'],
                'description' => $data['description']
            ]);

            $request->session()->flash('success', 'Data Berhasil diubah');

            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineCategory $medicineCategory)
    {
        if ($medicineCategory->destroy($medicineCategory->category_id)) {
            request()->session()->flash('success', "Data Berhasil dihapus");

            return back();
        }
    }
}
