<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PettyCashEmail;
use App\Models\PettyCash;
use App\Models\PettyCashDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PettyCashController extends Controller
{
    // USER
    public function showPettyCashUser()
    {
        $pettycash = PettyCash::with(['user'])->get();
        $data = ([
            "pettycash" => $pettycash
        ]);
        return view('PettyCash.pettycash', $data);
    }

    public function showRequestPettyCash()
    {
        return view('PettyCash.requestPettyCash');
    }
    public function showEditPettyCash($id)
    {
        $pettycash = PettyCash::findOrFail($id);
        $data = ([
            "pettycash" => $pettycash
        ]);
        return view('PettyCash.editPettyCash', $data);
    }

    // REKAP

    public function showRekapPermohonan(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $pettycash = collect();
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $pettycash =  PettyCash::with(['user'])->whereBetween('tanggal_pengajuan', [$tgl_awal, $tgl_akhir])->get();
        }
        $data = ([
            "pettycash" => $pettycash,
            "valueTglAwal" => $tgl_awal,
            "valueTglAkhir" => $tgl_akhir
        ]);
        return view('Rekap.RekapPermohonan', $data);
    }
    public function showRekapRincian(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $pettycash = collect();
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $pettycash =  PettyCashDetail::with(['pettycash'])->whereBetween('created_at', [$tgl_awal, $tgl_akhir])->get();
        }
        $data = ([
            "pettycash" => $pettycash,
            "valueTglAwal" => $tgl_awal,
            "valueTglAkhir" => $tgl_akhir
        ]);
        return view('Rekap.RekapRincian', $data);
    }
    public function exportRekapPermohonan(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $pettycash = collect();
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $pettycash =  PettyCash::with(['user'])->whereBetween('tanggal_pengajuan', [$tgl_awal, $tgl_akhir])->get();
        }
        $data = ([
            "pettycash" => $pettycash,
        ]);
        $pdf = Pdf::loadView('/PDFView/pdfRekapPermohonan', $data);
        return $pdf->stream();
        // return $pdf->download('Rekap Permohonan.pdf');
    }
    public function exportRekapRincian(Request $request)
    {
        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $pettycash = collect();
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $pettycash =  PettyCashDetail::with(['pettycash'])->whereBetween('created_at', [$tgl_awal, $tgl_akhir])->get();
        }
        $data = ([
            "pettycash" => $pettycash,
            "valueTglAwal" => $tgl_awal,
            "valueTglAkhir" => $tgl_akhir
        ]);
        $pdf = Pdf::loadView('/PDFView/pdfRekapRincian', $data);
        return $pdf->stream();
        // return $pdf->download('Rekap Permohonan.pdf');
    }

    //

    public function sendEmail()
    {
        $details = [
            'title' => 'Halo dari Laravel 11',
            'body' => 'Ini adalah email percobaan menggunakan Mailtrap'
        ];
        Mail::to("testing@malasngoding.com")->send(new PettyCashEmail($details));

        return "Email telah dikirim";
    }

    public function addRequest(Request $request)
    {
        $credential = $request->validate([
            "kode_pettycash" => "required",
            "amount" => "required",
            "tipe" => "required",
            "description" => "required",
        ]);
        $amount = preg_replace('/[^0-9]/', '', $request->amount);
        PettyCash::create([
            "kode_pettycash" => $request->kode_pettycash,
            "amount" => (int) $amount,
            "used_amount" => 0,
            "tipe" => $request->tipe,
            "status" => 'pending',
            "created_by" => Auth::user()->id,
            "description" => $request->description,
        ]);
        return redirect()->route('show.showPettyCashUser')
            ->with('success', 'Petty Cash berhasil dibuat');
    }
    public function editPettyCash(Request $request, $id)
    {
        $credential = $request->validate([
            "kode_pettycash" => "required",
            "amount" => "required",
            "tipe" => "required",
            "description" => "required",
        ]);
        $amount = preg_replace('/[^0-9]/', '', $request->amount);
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "kode_pettycash" => $request->kode_pettycash,
            "amount" => (int) $amount,
            "used_amount" => 0,
            "tipe" => $request->tipe,
            "status" => 'pending',
            "created_by" => Auth::user()->id,
            "description" => $request->description,
        ]);
        return redirect()->route('show.showPettyCashUser')
            ->with('success', 'Petty Cash berhasil diperbarui!');
    }
    public function deletePettyCash($id)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->delete();

        return redirect()->route('show.showPettyCashUser')
            ->with('success', 'Petty Cash berhasil dihapus!');
    }
    // DEPTHEAD
    public function showPettyCashDeptHead()
    {
        $pettycash = PettyCash::with(['user'])->where('status', 'dept_approved')->orWhere('status', 'pending')->orWhere('status', 'rejected')->get();
        $data = ([
            "pettycash" => $pettycash
        ]);
        return view('AppDept.appDept', $data);
    }
    public function approvedDeptHead($id)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "status" => "dept_approved",
            "dept_approved_by" => Auth::user()->id,
        ]);
        return redirect()->route('show.showPettyCashDeptHead')
            ->with('success', 'Petty Cash berhasil disetujui');
    }

    public function rejectedDeptHead($id, Request $request)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "status" => "rejected",
            "note" => $request->note,
        ]);
        return redirect()->route('show.showPettyCashDeptHead')
            ->with('success', 'Petty Cash berhasil ditolak');
    }
    // FINANCE
    public function showPettyCashFinHead()
    {
        $pettycash = PettyCash::with(['user'])->where('status', 'dept_approved')->orWhere('status', 'pending')->orWhere('status', 'rejected')->orWhere('status', 'finance_approved')->get();
        $data = ([
            "pettycash" => $pettycash,
            "finance_approved_by" => Auth::user()->id,
        ]);
        return view('AppFinance.appFin', $data);
    }
    public function approvedFinance($id)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "status" => "finance_approved",
            "finance_approved_by" => Auth::user()->id,
        ]);
        return redirect()->route('show.showPettyCashFinHead')
            ->with('success', 'Petty Cash berhasil disetujui');
    }

    public function rejectedFinance($id, Request $request)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "status" => "rejected",
            "note" => $request->note,

        ]);
        return redirect()->route('show.showPettyCashFinHead')
            ->with('success', 'Petty Cash berhasil ditolak');
    }
    public function paid(Request $request, $id)
    {
        $credential = $request->validate([
            "tanggal_pencairan" => "required"
        ]);
        $pettycash = PettyCash::findOrFail($id);
        $pettycash->update([
            "tanggal_pencairan" => $request->tanggal_pencairan,
            "status" => "paid"
        ]);
        return redirect()->route('show.showPettyCashFinHead')
            ->with('success', 'Pencairan berhasil');
    }
}
