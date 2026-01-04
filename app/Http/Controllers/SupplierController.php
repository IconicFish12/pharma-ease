<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Supplier::latest()->paginate(request()->has('paginate', 15));

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Suppplier Data is get Successfully',
                'data' => SupplierResource::collection($data),
                'meta' => [
                    'current_page' => $data->currentPage(),
                    'last_page' => $data->lastPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                ]
            ]);
        }

        return view('admin.supplier.supplier_management', [
            'title' => 'Suppliers List',
            'mainHeader' => 'Suppliers List',
            'subHeader' => 'List dari para pemasok obat pada Apotek Lamtama',
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
   public function store(StoreSupplierRequest $request)
{
    try {
        $data = Supplier::create($request->validated());

        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Supplier data added successfully',
                'data'    => $data
            ], 201);
        }

        return redirect()->back()->with('success', 'Supplier successfully added!');

    } catch (\Exception $e) {
        if ($request->wantsJson()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Failed to add supplier data',
                'errors'  => $e->getMessage()
            ], 500); 
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to add supplier: ' . $e->getMessage());
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
            $supplier->update($request->validated());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Supplier updated successfully',
                    'data' => new SupplierResource($supplier)
                ]);
            }

            return redirect()->back()->with('success', 'Supplier berhasil diperbarui!');
        } catch (\Exception $e) {
            return $this->handleError($request, 'Gagal memperbarui supplier', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $supplier = Supplier::findOrFail($supplier->supplier_id);
            $data = $supplier->delete();

           if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Suppplier Data is deleted Successfully',
                    'data' => $data
                ]);
            }

            request()->session()->flash('success', 'Supplier successfully deleted!');

            return redirect()->back();
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to deletee Suppplier Data',
                    'errors' => $e->getMessage()
                ]);
            }

            return redirect()->back()
                             ->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }
}

