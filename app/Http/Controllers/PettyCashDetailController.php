<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PettyCash;
use App\Models\PettyCashDetail;
use Illuminate\Http\Request;

class PettyCashDetailController extends Controller
{
    public function showDetailPettyCash($id)
    {
        $pettycash = PettyCash::findOrFail($id);
        $pettycashDetail = PettyCashDetail::where('pettycash_id', $id)->get();
        $summaryTotal = PettyCashDetail::where('pettycash_id', $id)->sum('total_price');
        if ($pettycash->used_amount != $summaryTotal) {
            $pettycash->update([
                "used_amount" => $summaryTotal
            ]);
        }
        $data = ([
            "pettycash" => $pettycash,
            "pettycashDetail" => $pettycashDetail,
            "summaryTotal" => $summaryTotal,
        ]);
        return view('PettyCash.detailPettyCash', $data);
    }

    public function addDetail(Request $request, $id)
    {
        $credential = $request->validate([
            "kode_pettycash_details" => "required|string",
            "item_name" => "required|string",
            "quantity" => "required|numeric",
            "unit" => "required|string",
            "unit_price" => "required",
            "total_price" => "required",
            "note" => "required",
        ]);

        $unit_price = preg_replace('/[^0-9]/', '', $request->unit_price);
        $total_price = preg_replace('/[^0-9]/', '', $request->total_price);

        $pettycash = PettyCash::findOrFail($id);
        $summaryTotalDetail = PettyCashDetail::where('pettycash_id', $id)->sum('total_price');
        if (($summaryTotalDetail + $total_price) > $pettycash->amount) {
            return redirect()->back()->with('error', 'Sub Total Rincian melebihi saldo awal Petty Cash !');
        }
        PettyCashDetail::create([
            "pettycash_id" => $id,
            "kode_pettycash_details" => $request->kode_pettycash_details,
            "item_name" => $request->item_name,
            "quantity" => $request->quantity,
            "unit" => $request->unit,
            "unit_price" => $unit_price,
            "total_price" => $total_price,
            "note" => $request->note,
        ]);

        return redirect()->back()->with('success', 'Rincian berhasil dibuat');
    }

    public function editDetail(Request $request, $id)
    {
        $credential = $request->validate([
            "kode_pettycash_details" => "required|string",
            "item_name" => "required|string",
            "quantity" => "required|numeric",
            "unit" => "required|string",
            "unit_price" => "required",
            "total_price" => "required",
            "note" => "required",
        ]);

        $unit_price = preg_replace('/[^0-9]/', '', $request->unit_price);
        $total_price = preg_replace('/[^0-9]/', '', $request->total_price);

        $pettycashDetail = PettyCashDetail::findOrFail($id);
        $pettycash = PettyCash::findOrFail($pettycashDetail->pettycash_id);
        $summaryTotalDetail = PettyCashDetail::where('pettycash_id', $pettycash->id)->where('id', '!=', $id)->sum('total_price');

        if (($summaryTotalDetail + $total_price) > $pettycash->amount) {
            return redirect()->back()->with('error', 'Sub Total Rincian melebihi saldo awal Petty Cash !');
        }

        $pettycashDetail->update([
            "kode_pettycash_details" => $request->kode_pettycash_details,
            "item_name" => $request->item_name,
            "quantity" => $request->quantity,
            "unit" => $request->unit,
            "unit_price" => $unit_price,
            "total_price" => $total_price,
            "note" => $request->note,
        ]);

        return redirect()->back()->with('success', 'Rincian berhasil diperbarui!');
    }

    public function deleteDetail(Request $request, $id)
    {
        $pettycashDetail = PettyCashDetail::findOrFail($request->id);
        $pettycashDetail->delete();

        return redirect()->route('show.showDetailPettyCash', $id)
            ->with('success', 'Rincian berhasil dihapus');
    }
}
