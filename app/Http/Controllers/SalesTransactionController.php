<?php

namespace App\Http\Controllers;

use App\Events\LowStockMedicine;
use App\Models\SalesTransaction;
use App\Models\Medicine;
use App\Http\Requests\StoreSalesTransactionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SalesTransactionController extends Controller
{
    public function index()
    {
        $medicines = Medicine::with('category')->latest()->get();

        return view('admin.transaction.cashier_menu', [
            'medicines' => $medicines
        ]);
    }

    public function store(StoreSalesTransactionRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = SalesTransaction::create([
                'kode_penjualan' => 'TRX-' . strtoupper(Str::random(8)),
                'user_id' => Auth::id() ?? $request->user_id,
                'transaction_date' => now(),
                'total_price' => 0,
            ]);

             $grandTotal = 0;

            foreach ($request->items as $item) {
                $medicine = Medicine::findOrFail($item['medicine_id']);

                if ($medicine->stock < $item['quantity']) {
                    throw new \Exception("Stok {$medicine->medicine_name} tidak cukup.");
                }

                $medicine->decrement('stock', $item['quantity']);

                $medicine->refresh();

                $subtotal = $medicine->price * $item['quantity'];
                $grandTotal += $subtotal;

                $transaction->medicines()->attach($medicine->medicine_id, [
                    'quantity' => $item['quantity'],
                    'unit_price' => $medicine->price,
                    'subtotal' => $subtotal
                ]);

                if ($medicine->stock <= 15) {
                    LowStockMedicine::dispatch($medicine);
                }
            }

            $cashReceived = $request->cash_received;
            if ($cashReceived < $grandTotal) {
                throw new \Exception("Uang pembayaran kurang! Total: " . number_format($grandTotal));
            }

            $change = $cashReceived - $grandTotal;

            $transaction->update(['total_price' => $grandTotal]);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaction successful',
                    'change' => $change,
                    'transaction_code' => $transaction->kode_penjualan
                ]);
            }

            return redirect()->back()->with('success', 'Transaction successful! Change: ' . number_format($change));

        } catch (\Exception $e) {
            DB::rollBack();
            if (request()->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
