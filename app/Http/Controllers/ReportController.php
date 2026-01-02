<?php

namespace App\Http\Controllers;

use App\Exports\FinancialExport;
use App\Exports\MedicineExport;
use App\Exports\OperationalExport;
use App\Models\Medicine;
use App\Models\MedicineOrder;
use App\Models\SalesTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function medicineReport() {
        // Analisis Stok
        $totalItems = Medicine::count();
        $outOfStock = Medicine::where('stock', '<=', 5)->count();
        $expired = Medicine::whereDate('expired_date', '<=', now())->count();

        $medicines = Medicine::with(['category', 'supplier'])
            ->orderBy('stock', 'asc') // Prioritas stok sedikit
            ->paginate(15);

        return view('admin.report.medicine_report', compact('medicines', 'totalItems', 'outOfStock', 'expired'));
    }

    public function medicineReportExport() {
        return Excel::download(new MedicineExport, 'medicine-report-' . now()->format('Y-m-d') . '.xlsx');
    }

     public function operationalReport() {
        $logs = Activity::with('causer')
            ->latest()
            ->paginate(20);

        return view('admin.report.operational_report', compact('logs'));
    }

    public function operationalReportExport() {
        return Excel::download(new OperationalExport, 'activity-log-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function financialReport(Request $request) {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $revenue = SalesTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->sum('total_amount');

        $expenses = MedicineOrder::whereBetween('order_date', [$startDate, $endDate])
            ->sum('total_price');

        $profit = $revenue - $expenses;

        $transactions = SalesTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->with('user')
            ->latest('transaction_date')
            ->paginate(15);

        return view('admin.report.financial_report', compact('revenue', 'expenses', 'profit', 'transactions', 'startDate', 'endDate'));
    }

    public function financialReportExport(Request $request) {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        return Excel::download(new FinancialExport($startDate, $endDate), 'financial-report.xlsx');
    }
}
