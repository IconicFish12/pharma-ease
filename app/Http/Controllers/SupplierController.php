<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.supplier.supplier_management', [
            'title' => 'Suppliers List',
            'mainHeader' => 'Suppliers List',
            'subHeader' => 'List dari para pemasok obat pada Apotek Lamtama',
            'dataArr' => Supplier::paginate(request()->has('paginate') ?? 15),
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
    public function store(StoreSupplierRequest $request)
    {
        try {
            Supplier::create($request->validated());

            return redirect()->back()
                             ->with('success', 'Supplier successfully added!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Failed to add supplier: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            $data = $request->all();

            $supplier->find($supplier->supplier_id)->update([
                'supplier_name' => $data['supplier_name'],
                'address' => $data['address'],
                'contact_person' => $data['contact_person'],
                'phone_number' => $data['phone_number'],
            ]);

            return redirect()->back()
                             ->with('success', 'Supplier successfully updated!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Failed to update supplier: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier = Supplier::findOrFail($supplier->supplier_id);
            $supplier->delete();

            return redirect()->route('admin.suppliers-data')
                             ->with('success', 'Supplier successfully deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }
}
