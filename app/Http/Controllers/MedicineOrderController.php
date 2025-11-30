<?php

namespace App\Http\Controllers;

use App\Models\MedicineOrder;
use App\Http\Requests\StoreMedicineOrderRequest;
use App\Http\Requests\UpdateMedicineOrderRequest;
use App\Models\Medicine;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MedicineOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = MedicineOrder::with(['user', 'supplier', 'medicines'])
                ->paginate(request()->has('paginate') ?? 15)
                ->toResourceCollection();

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Medicine Order Data fetched successfully',
                $data
            ]);
        }

        return view('admin.medicine.medicine_order', [
            'title' => 'Medicine Order Data',
            'mainHeader' => 'Medicine Order List',
            'subHeader' => 'Daftar Pemesanan Obat di Apotek Lamtama',
            'dataArr' => $data,
            'suppliers' => Supplier::all(),
            'medicines_list' => Medicine::all(),
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
    public function store(StoreMedicineOrderRequest $request)
    {
        dd($request);
        try {
            DB::beginTransaction();

            // 1. Hitung Total Price dari detail yang dikirim
            $totalPrice = 0;
            foreach ($request->medicines as $item) {
                $totalPrice += $item['quantity'] * $item['unit_price'];
            }

            // 2. Buat Order Header
            $order = MedicineOrder::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)), // Generate Code
                'user_id' => Auth::id() ?? 1, // Fallback ID 1 jika belum login/testing
                'supplier_id' => $request->supplier_id,
                'order_date' => $request->order_date,
                'total_price' => $totalPrice,
                'status' => $request->status,
            ]);

            foreach ($request->medicines as $item) {
                $order->medicines()->attach($item['medicine_id'], [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price'],
                    'order_id' => $order->order_id
                ]);
            }

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Order created successfully'], 201);
            }
            return redirect()->back()->with('success', 'Order created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicineOrderRequest $request, MedicineOrder $medicineOrder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineOrder $medicineOrder)
    {
        //
    }
}
