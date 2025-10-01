@extends('Layout.layout')
@section('title', 'Rekap Rincian - Petty Cash')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <div class="page-title">
                Rekap Rincian
            </div>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="#">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Petty Cash</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Rekap Rincian</a>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Rekap Rincian</h4>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('show.showRekapRincian') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Tanggal Awal</label>
                                <input type="date" class="form-control" id="tgl_awal" name="tgl_awal"
                                    value="{{ $valueTglAwal }}" required />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir"
                                    value="{{ $valueTglAkhir }}" required />
                            </div>
                        </div>
                          <div class="">
                            <p style="color: white">a</p>
                            <button type="submit" class="btn btn-primary btn-sm" ><i class="fas fa-search"></i>Cari
                                Data</button>
                        </div>
                        <div class="ml-2">
                            <p style="color: white">a</p>
                            <a href="{{ route('exportRekapRincian', ['tgl_awal' => request('tgl_awal'),'tgl_akhir' => request('tgl_akhir') ]) }}" class="btn btn-danger btn-sm" ><i class="fas fa-file"></i> Export PDF</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="tableHead" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Satuan</th>
                                <th>Harga Satuan</th>
                                <th>Total </th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $number = 1;
                            @endphp
                            @foreach ($pettycash as $item)
                                <tr>
                                    <td>{{ $number++ }} </td>
                                    <td> {{ $item->kode_pettycash_details }} </td>
                                    <td> {{ $item->item_name }} </td>
                                    <td> {{ $item->quantity }} </td>
                                    <td> {{ $item->unit }} </td>
                                    <td>Rp. {{ number_format($item->unit_price) }} </td>
                                    <td>Rp. {{ number_format($item->total_price) }} </td>
                                    <td> {{ $item->note }} </td>


                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $("#tableHead").DataTable({});
        })
    </script>
@endsection
