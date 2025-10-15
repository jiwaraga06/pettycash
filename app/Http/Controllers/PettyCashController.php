<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\PettyCashEmail;
use App\Models\PettyCash;
use App\Models\PettyCashDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

    public function sendEmail($mailTo)
    {
        $details = [
            'title' => 'Halo dari Laravel 11',
            'body' => 'Ini adalah email percobaan menggunakan Mailtrap'
        ];

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
        $pettyCash = PettyCash::create([
            "kode_pettycash" => $request->kode_pettycash,
            "amount" => (int) $amount,
            "used_amount" => 0,
            "tipe" => $request->tipe,
            "status" => 'pending',
            "user_id" => Auth::user()->id,
            "description" => $request->description,
        ]);
        $details = [
            'body'         => 'Ada pengajuan petty cash baru yang perlu ditinjau.',
            'kode_pettycash' => $pettyCash->kode_pettycash,
            'amount'       => $pettyCash->amount,
            'tipe'         => $pettyCash->tipe,
            'user_id'   => Auth::user()->name,
        ];

        Mail::to('atasan.dept@email.com')->send(
            new PettyCashEmail(
                $details,
                'Request Petty Cash - Approval Needed',
                'emails.emailPettyCash',
                Auth::user()->email,
                Auth::user()->name
            )
        );
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
            "user_id" => Auth::user()->id,
            "description" => $request->description,
        ]);
        $details = [
            'body'         => 'Ada pengajuan petty cash baru yang perlu ditinjau.',
            'kode_pettycash' => $pettycash->kode_pettycash,
            'amount'       => $pettycash->amount,
            'tipe'         => $pettycash->tipe,
            'user_id'   => Auth::user()->name,
        ];

        Mail::to('atasan.dept@email.com')->send(
            new PettyCashEmail(
                $details,
                'Request Petty Cash - Approval Needed',
                'emails.emailPettyCash',
                Auth::user()->email,
                Auth::user()->name
            )
        );
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
    // finance_approved
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
        $pettycashUser =  PettyCash::with(['user'])->where('user_id', $pettycash->user_id)->first();
        $pettycash->update([
            "status" => "dept_approved",
            "dept_approved_by" => Auth::user()->id,
        ]);
        $details = [
            'nama'         => 'Finance',
            'pengaju' => $pettycashUser->user->name,
            'nominal'       => $pettycash->amount
        ];

        Mail::to('atasan.fin@email.com')->send(
            new PettyCashEmail(
                $details,
                'Petty Cash Approved',
                'emails.emailAtasan',
                Auth::user()->email,
                Auth::user()->name
            )
        );
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
        $pettycashUser =  PettyCash::with(['user'])->where('user_id', $pettycash->user_id)->first();
        $pettycash->update([
            "status" => "finance_approved",
            "finance_approved_by" => Auth::user()->id,
        ]);
        $details = [
            'nama'         => 'User',
            'pengaju' => $pettycashUser->user->name,
            'nominal'       => $pettycash->amount
        ];

        Mail::to('user@email.com')->send(
            new PettyCashEmail(
                $details,
                'Petty Cash Approved',
                'emails.emailFinance',
                Auth::user()->email,
                Auth::user()->name
            )
        );
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
        $pettycashUser =  PettyCash::with(['user'])->where('user_id', $pettycash->user_id)->first();

        $tanggalPencairan = Carbon::parse($request->tanggal_pencairan);
        $tanggalHariIni = Carbon::today();
        $status = 'paid';
        if ($tanggalPencairan->equalTo($tanggalHariIni)) {
            $status = 'paid';
        } elseif ($tanggalPencairan->lessThan($tanggalHariIni)) {
            $status = 'waiting paid';
        } else {
        // Kalau tanggal pencairan di masa depan, opsional bisa atur status lain
        $status = 'waiting paid';
    }

        $pettycash->update([
            'tanggal_pencairan' => $tanggalPencairan,
            'status' => $status
        ]);

        $details = [
            'nama'         => 'User',
            'pengaju' => $pettycashUser->user->name,
            'nominal'       => $pettycash->amount,
            'tanggal_pencairan'       => $request->tanggal_pencairan,
        ];

        Mail::to('user@email.com')->send(
            new PettyCashEmail(
                $details,
                'Petty Cash Paid',
                'emails.emailPencairan',
                Auth::user()->email,
                Auth::user()->name
            )
        );
        return redirect()->route('show.showPettyCashFinHead')
            ->with('success', 'Pencairan berhasil');
    }
}
