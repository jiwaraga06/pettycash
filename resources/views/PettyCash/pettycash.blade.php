@extends('Layout.layout')
@section('title', 'List - Petty Cash')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <div class="page-title">
                Petty Cash
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
                    <a class="btn btn-primary btn-sm ml-auto" href="{{ route('show.showRequestPettyCash') }}">
                        <i class="fa fa-plus"></i>
                        Buat Permohonan
                    </a>
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

                                    <td>
                                        {{-- <a href="#modalDetail/{{ $item->id }}" class="btn btn-sm btn-info "
                                            data-toggle="modal" data-target="#modalDetail/{{ $item->id }}"><i
                                                class="fa fa-eye">
                                                Detail</i></a> --}}
                                        @if ($item->status == 'pending')
                                            <a href="{{ route('show.showEditPettyCash', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-info "><i class="fa fa-pen">
                                                    Ubah</i></a>
                                            <a href="" id="btnDelete" data-id="{{ $item->id }}"
                                                class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash">
                                                    Hapus</i></a>
                                            <form action="" method="POST" id="formDelete">
                                                @csrf
                                            </form>
                                        @elseif ($item->status == 'rejected')
                                            <a href="{{ route('show.showEditPettyCash', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-info "><i class="fa fa-pen">
                                                    Ubah</i></a>
                                            <a href="" id="btnDelete" data-id="{{ $item->id }}"
                                                class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash">
                                                    Hapus</i></a>
                                            <form action="" method="POST" id="formDelete">
                                                @csrf
                                            </form>
                                        @elseif ($item->status == 'paid')
                                            <a href="{{ route('show.showDetailPettyCash', ['id' => $item->id]) }}"
                                                class="btn btn-sm btn-info ">
                                                <i class="fa fa-eye"></i> Detail</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL DETAIL --}}

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

        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: "Yakin ingin menghapus?",
                text: "Data tidak dapat dikembalikan setelah dihapus!",
                icon: "warning",
                buttons: ["Batal", "Hapus"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    // submit form ke route delete
                    var form = $('#formDelete');
                    form.attr('action', '/pettycash/' + id);
                    form.submit();
                }
            });
        });

        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split('.'),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }
    </script>
@endsection
