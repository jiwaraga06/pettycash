@extends('Layout.layout')
@section('title', 'Account')
@section('content')
    <div class="page-inner">
        <div class="page-header">
            <div class="page-title">
                Role Permission
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
                    <a href="#">Role Permission</a>
                </li>
            </ul>
        </div>
        @include('components.alert')
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Data Role Permission</h4>
                    <a class="btn btn-primary btn-sm ml-auto" data-toggle="modal" data-target="#modalForm"
                        onclick=" openAddForm() " href="">
                        <i class="fa fa-plus"></i>
                        Buat Role
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableHead" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Role Permission</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $number = 1;
                            @endphp
                            @foreach ($role as $item)
                                <tr>
                                    <td>{{ $number++ }} </td>
                                    <td> {{ $item->nama_role }} </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            onclick="openEditForm({
                                                id: {{ $item->id }},
                                                nama_role: '{{ $item->nama_role }}'
                                            })"
                                            class="btn btn-sm btn-info mb-2">
                                            <i class="fa fa-pen"></i> Ubah
                                        </a>

                                        <form id="formDelete" method="POST"
                                            action="{{ route('deleteRole', ['id' => $item->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" id="deleteId">
                                        </form>

                                        <a href="" id="btnDelete" data-id="{{ $item->id }}"
                                            class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash">
                                                Hapus</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalForm" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">
                        <span class="fw-largebold" id="modalTitle">Tambah Role Permission</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                {{-- form fleksibel --}}
                <form id="detailForm" method="POST">
                    @csrf
                    <input type="hidden" id="formMethod" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Role</label>
                            <input type="text" class="form-control" id="nama_role" name="nama_role" required />
                        </div>
                        <div class="modal-footer">
                            <button data-dismiss="modal" class="btn btn-info"><i class="fas fa-times"></i> Tutup</button>
                            <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fas fa-check"></i>
                                Simpan</button>
                        </div>
                </form>
            </div>
        </div>
    </div>

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

        function openAddForm() {
            $('#modalTitle').text('Tambah Role Permission');
            $('#btnSubmit').html('<i class="fas fa-check"></i> Simpan');
            $('#detailForm').attr('action', "{{ route('addRole') }}");
            $('#formMethod').val('POST');

            $('#detailForm')[0].reset();
            $('#nama_role').val('');
            $('#modalForm').modal('show');
        }

        function openEditForm(data) {
            $('#modalTitle').text('Edit Role Permission');
            $('#btnSubmit').html('<i class="fas fa-check"></i> Update');
            $('#detailForm').attr('action', "/role/editRole/" + data.id);
            $('#formMethod').val('PUT');

            $('#nama_role').val(data.nama_role);
            $('#modalForm').modal('show');
        }
        $('.btn-delete').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title: "Yakin ingin menghapus?",
                text: "Data tidak dapat dikembalikan setelah dihapus!" + id,
                icon: "warning",
                buttons: ["Batal", "Hapus"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {

                    // submit form ke route delete
                    var form = $('#formDelete');
                    form.attr('action', '/role/' + id);
                    form.submit();
                }
            });
        });
    </script>
@endsection
