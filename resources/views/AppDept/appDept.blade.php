@extends('Layout.layout')
@section('title', 'App Dept Head - Petty Cash')

@section('content')
    <div class="page-inner">
        <div class="page-header">
            <div class="page-title">
                App Dept Head
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
            </ul>

        </div>
        @include('components.alert')

        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Petty Cash</h4>

                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableHead" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Saldo</th>
                                <th>Saldo Terpakai</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                    @if ($item->status == 'pending')
                                        <td> <span class="badge badge-warning">PENDING</span> </td>
                                    @elseif ($item->status == 'dept_approved')
                                        <td> <span class="badge badge-info">Approve by Head Dept</span> </td>
                                    @elseif ($item->status == 'finance_approved')
                                        <td> <span class="badge badge-info">Approve by Finance</span> </td>
                                    @elseif ($item->status == 'paid')
                                        <td> <span class="badge badge-primary">PAID</span> </td>
                                    @elseif ($item->status == 'rejected')
                                        <td> <span class="badge badge-danger">REJECTED</span> </td>
                                    @endif

                                    <td>
                                        @if ($item->status == 'pending')
                                            <a href="" data-id="{{ $item->id }}"
                                                class="btn btn-sm btn-success btn-approve"><i class="fa fa-check">
                                                    Terima</i></a>
                                            <form action="" method="POST" id="formApprove">
                                                @csrf
                                            </form>
                                            <a href="" data-id="{{ $item->id }}" data-toggle="modal"
                                                data-target="#modalNote/{{ $item->id }}"
                                                class="btn btn-sm btn-danger btn-rejects"><i class="fa fa-times">
                                                    Tolak</i></a>
                                            </br>
                                            {{-- <form action="" method="POST" id="formReject">
                                                @csrf
                                            </form> --}}
                                        @endif
                                        <a href="#modalDetail/{{ $item->id }}" class="btn btn-sm btn-info "
                                            data-toggle="modal" data-target="#modalDetail/{{ $item->id }}"><i
                                                class="fa fa-eye">
                                                Detail</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @foreach ($pettycash as $a)
        <div class="modal fade" id="modalDetail/{{ $a->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">
                            <span class="fw-largebold"> Detail Permohonan</span>
                        </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td> <span class="badge badge-info">{{ $a->status }} </span></td>
                                </tr>
                                <tr>
                                    <td>Kode</td>
                                    <td>:</td>
                                    <td>{{ $a->kode_pettycash }}</td>
                                </tr>
                                <tr>
                                    <td>Saldo</td>
                                    <td>:</td>
                                    <td>Rp. {{ number_format($a->amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Sisa Saldo</td>
                                    <td>:</td>
                                    <td>Rp. {{ number_format($a->used_amount, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td>Tipe Permohonanan</td>
                                    <td>:</td>
                                    <td>{{ $a->tipe }} </td>
                                </tr>
                                <tr>
                                    <td>Keterangan</td>
                                    <td>:</td>
                                    <td>{{ $a->description }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-info"><i class="fas fa-times"></i>Tutup</button>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @foreach ($pettycash as $a)
        <div class="modal fade" id="modalNote/{{ $a->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header no-bd">
                        <h5 class="modal-title">
                            <span class="fw-largebold"> Alasan Menolak Permohonan</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('rejectedDeptHead', ['id' => $a->id]) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="">Catatan</label>
                                <textarea type="text" class="form-control" id="note" name="note" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-info"><i class="fas fa-times"></i>Tutup</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    @if (session()->has('success'))
        <script>
            swal("Good job!", "{{ session('success') }}", {
                icon: "success",
                buttons: {
                    confirm: {
                        className: "btn btn-success",
                    },
                },
            });
        </script>
    @endif
    <script>
        $(function() {
            $("#tableHead").DataTable({});
        })
        $('.btn-approve').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: "Anda yakin ingin menyetujui?",
                text: "Data tidak dapat dikembalikan setelah melakukan perubahan",
                icon: "warning",
                buttons: ["Tutup", "Setuju"],
                dangerMode: true,
            }).then((willApprove) => {
                if (willApprove) {

                    // submit form ke route delete
                    var form = $('#formApprove');
                    form.attr('action', '/pettycash/approvedDeptHead/' + id);
                    form.submit();
                }
            });
        });
        $('.btn-reject').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: "Anda yakin ingin menolak pengajuan ini?",
                text: "Data tidak dapat dikembalikan setelah melakukan perubahan",
                icon: "warning",
                buttons: ["Tutup", "Setuju"],
                dangerMode: true,
            }).then((willReject) => {
                // if (willReject) {

                //     var form = $('#formReject');
                //     form.attr('action', '/pettycash/rejectedDeptHead/' + id);
                //     form.submit();
                // }
            });
        });
    </script>
@endsection
