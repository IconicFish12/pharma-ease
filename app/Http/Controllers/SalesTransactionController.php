<?php

namespace App\Http\Controllers;

use App\Events\LowStockMedicine;
use App\Models\SalesTransaction;
use App\Http\Requests\StoreSalesTransactionRequest;
use App\Http\Requests\UpdateSalesTransactionRequest;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.transaction.cashier_menu');
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
    public function store(StoreSalesTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = SalesTransaction::create([
                'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id() ?? $request->user_id,
                'transaction_date' => now(),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);

                if ($medicine->stock < $item['quantity']) {
                    throw new \Exception("Stok {$medicine->medicine_name} tidak cukup.");
                }

                $medicine->decrement('stock', $item['quantity']);

                $subtotal = $medicine->price * $item['quantity'];
                $totalAmount += $subtotal;

                $transaction->medicines()->attach($medicine->medicine_id, [
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price,
                    'subtotal' => $subtotal
                ]);

                if ($medicine->stock <= 10) {
                    LowStockMedicine::dispatch($medicine);
                }
            }

            $transaction->update(['total_amount' => $totalAmount]);

            DB::commit();

            if (request()->wantsJson()) {
                return response()->json(['status' => 'success', 'message' => 'Transaction successful']);
            }
            return redirect()->back()->with('success', 'Transaction successful');

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SalesTransaction $salesTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SalesTransaction $salesTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalesTransactionRequest $request, SalesTransaction $salesTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalesTransaction $salesTransaction)
    {
        //
    }
}
