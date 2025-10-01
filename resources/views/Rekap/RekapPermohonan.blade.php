@extends('Layout.layout')
@section('title', 'Rekap Permohonan - Petty Cash')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <div class="page-title">
                Rekap Permohonan
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
                    <a href="#">Rekap Permohonan</a>
                </li>
            </ul>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Rekap Permohonan</h4>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('show.showRekapPermohonan') }}">
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
                            <a href="{{ route('exportRekapPermohonan', ['tgl_awal' => request('tgl_awal'),'tgl_akhir' => request('tgl_akhir') ]) }}" class="btn btn-danger btn-sm" ><i class="fas fa-file"></i> Export PDF</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="tableHead" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Saldo</th>
                                <th>Saldo Terpakai</th>
                                <th>Tipe</th>
                                <th>Catatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $number = 1;
                            @endphp
                            @foreach ($pettycash as $item)
                                <tr>
                                    <td>{{ $number++ }} </td>
                                    <td> {{ $item->kode_pettycash }} </td>
                                    <td> Rp. {{ number_format($item->amount, 0, ',', '.') }}</td>
                                    <td> Rp. {{ number_format($item->used_amount, 0, ',', '.') }}</td>
                                    <td> {{ $item->tipe }} </td>
                                    <td> {{ $item->description }} </td>
                                    @if ($item->status == 'pending')
                                        <td> <span class="badge badge-info">PENDING</span> </td>
                                    @elseif ($item->status == 'dept_approved')
                                        <td> <span class="badge badge-info">Approve by Head Dept</span> </td>
                                    @elseif ($item->status == 'finance_approved')
                                        <td> <span class="badge badge-info">Approve by Finance</span> </td>
                                    @elseif ($item->status == 'paid')
                                        <td> <span class="badge badge-primary">PAID</span> </td>
                                    @elseif ($item->status == 'rejected')
                                        <td> <span class="badge badge-danger">REJECTED</span> </td>
                                    @endif


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
