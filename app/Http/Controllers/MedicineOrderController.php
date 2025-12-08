<?php

namespace App\Http\Controllers;

use App\Models\MedicineOrder;
use App\Http\Requests\StoreMedicineOrderRequest;
use App\Http\Requests\UpdateMedicineOrderRequest;
use App\Http\Resources\MedicineOrderResource;
use App\Models\Medicine;
use App\Models\Supplier;
use App\Models\User;
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
        $data = MedicineOrder::with(['user', 'supplier', 'medicines'])->latest('created_at');

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Medicine Order Data fetched successfully',
                'data' => MedicineOrderResource::collection($data->get())
            ]);
        }

        return view('admin.medicine.medicine_order', [
            'title' => 'Medicine Order Data',
            'mainHeader' => 'Medicine Order List',
            'subHeader' => 'Daftar Pemesanan Obat di Apotek Lamtama',
            'dataArr' => $data->paginate(request()->get('paginate', 15)),
            'suppliers' => Supplier::all(),
            'users' => User::all(),
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
        // dd($request->all());
        try {
            DB::beginTransaction();

            $totalPrice = 0;
            foreach ($request->medicines as $item) {
                $totalPrice += $item['quantity'] * $item['unit_price'];
            }

            $order = MedicineOrder::create([
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'user_id' => $request->user_id,
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
        // dd($request);
        try {
            DB::beginTransaction();

            $order = MedicineOrder::findOrFail($medicineOrder->order_id);

            $totalPrice = 0;
            foreach ($request->medicines as $item) {
                $totalPrice += $item['quantity'] * $item['unit_price'];
            }

            $order->update([
                'supplier_id' => $request->supplier_id,
                'user_id' => $request->user_id ?? $order->user_id, // Update user jika ada di request
                'order_date' => $request->order_date,
                'status' => $request->status,
                'total_price' => $totalPrice,
            ]);

            $syncData = [];
            foreach ($request->medicines as $item) {
                $syncData[$item['medicine_id']] = [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['quantity'] * $item['unit_price']
                ];
            }
            $order->medicines()->sync($syncData);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Order updated successfully']);
            }
            return redirect()->back()->with('success', 'Order updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicineOrder $medicineOrder)
    {
         try {
            $order = MedicineOrder::findOrFail($medicineOrder->order_id);
            $order->delete();

            if (request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Order deleted successfully']);
            }
            return redirect()->back()->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
}
