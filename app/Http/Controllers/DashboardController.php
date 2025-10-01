<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PettyCash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $totalSaldo = PettyCash::sum('amount');
        $jumlahPengajuan = PettyCash::count();
        $jumlahApproved = PettyCash::whereIn('status', ['dept_approved', 'finance_approved', 'paid'])->count();
        $jumlahRejected = PettyCash::where('status', 'rejected')->count();

        $data = [
            'totalSaldo' => $totalSaldo,
            'jumlahPengajuan' => $jumlahPengajuan,
            'jumlahApproved' => $jumlahApproved,
            'jumlahRejected' => $jumlahRejected,
            'jumlahPending' => PettyCash::where('status', 'pending')->count(),
            'jumlahDeptApproved' => PettyCash::where('status', 'dept_approved')->count(),
            'jumlahFinanceApproved' => PettyCash::where('status', 'finance_approved')->count(),
            'jumlahPaid' => PettyCash::where('status', 'paid')->count(),
        ];
        return view('Dashboaard.dashboard', $data);
    }

    public function chartSummaryAmount()
    {
        $summaryAmount = DB::select(<<<SQL
WITH months AS (
    SELECT generate_series(
        date_trunc('year', CURRENT_DATE),
        date_trunc('year', CURRENT_DATE) + interval '11 months',
        interval '1 month'
    )::date AS month_start
),
summary AS (
    SELECT
        date_trunc('month', tanggal_pengajuan)::date AS month_start,
        COALESCE(SUM(amount), 0) AS total_amount
    FROM petty_cashes
    WHERE tanggal_pengajuan >= date_trunc('year', CURRENT_DATE)
      AND tanggal_pengajuan < date_trunc('year', CURRENT_DATE) + interval '1 year'
    GROUP BY date_trunc('month', tanggal_pengajuan)
)
SELECT
    TO_CHAR(m.month_start, 'Month') AS bulan,
    COALESCE(s.total_amount, 0) AS total_amount
FROM months m
LEFT JOIN summary s ON m.month_start = s.month_start
ORDER BY m.month_start;

SQL);

        return response()->json(["data" => $summaryAmount]);
    }
}
