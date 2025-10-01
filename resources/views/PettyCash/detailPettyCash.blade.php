@extends('Layout.layout')
@section('title', 'Request - Petty Cash')
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
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Detail Petty Cash</a>
                </li>
            </ul>

        </div>
        @include('components.alert')


        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Permohonan</h4>
            </div>
            <div class="card-body">
                <div class="col-md-6 mt-0">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <td style="width: 200px">Kode</td>
                                <td style="width: 50px">:</td>
                                <td><span class="badge badge-info">{{ $pettycash->kode_pettycash }} </span> </td>
                            </tr>
                            <tr>
                                <td>Saldo</td>
                                <td>:</td>
                                <td>Rp. {{ number_format($pettycash->amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Saldo Terpakai</td>
                                <td>:</td>
                                <td>Rp. {{ number_format($pettycash->used_amount, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Tipe Permohonanan</td>
                                <td>:</td>
                                <td>{{ $pettycash->tipe }} </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td>{{ $pettycash->description }} </td>
                            </tr>
                        </tbody>
                    </table>

                    <a href="" data-toggle="modal" data-target="#modalForm" class="btn btn-sm btn-primary"
                        onclick="generateCode()"><i class="fa fa-plus"></i> Buat Rincian</a>
                </div>
                <table class="table table-bordered mt-4" id="headDetail">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Unit</th>
                            <th>Unit per Price</th>
                            <th>Total Price</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $number = 1;
                        @endphp
                        @foreach ($pettycashDetail as $a)
                            <tr>
                                <td>{{ $number++ }} </td>
                                <td>{{ $a->item_name }} </td>
                                <td>{{ $a->quantity }} </td>
                                <td>{{ $a->unit }} </td>
                                <td>Rp. {{ number_format($a->unit_price, 0, ',', '.') }} </td>
                                <td>Rp. {{ number_format($a->total_price, 0, ',', '.') }} </td>
                                <td>{{ $a->note }} </td>
                                <td><a href="javascript:void(0)"
                                        onclick="openEditForm({
                                                id: {{ $a->id }},
                                                kode_pettycash_details: '{{ $a->kode_pettycash_details }}',
                                                item_name: '{{ $a->item_name }}',
                                                quantity: {{ $a->quantity }},
                                                unit: '{{ $a->unit }}',
                                                unit_price: {{ $a->unit_price }},
                                                total_price: {{ $a->total_price }},
                                                note: '{{ $a->note }}'
                                            })"
                                        class="btn btn-sm btn-info mb-2">
                                        <i class="fa fa-pen"></i> Ubah
                                    </a>

                                    <form id="formDelete" method="POST"
                                        action="{{ route('deleteDetail', ['id' => $pettycash->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="id" id="deleteId">
                                    </form>

                                    <a href="" id="btnDelete" data-id="{{ $a->id }}"
                                        class="btn btn-sm btn-danger btn-delete"><i class="fa fa-trash">
                                            Hapus</i></a>
                                </td>
                            </tr>
                        @endforeach
                        <tr class="bg-info table-bordered-bd-info">
                            <td colspan="5" class="text-right text-white"><strong>Total Keseluruhan</strong></td>
                            <td colspan="3" class="text-white"><strong>Rp
                                    {{ number_format($summaryTotal, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalForm" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header no-bd">
                    <h5 class="modal-title">
                        <span class="fw-largebold" id="modalTitle">Tambah Rincian</span>
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
                            <label for="">Detail Kode</label>
                            <input type="text" class="form-control" id="kode_pettycash_details"
                                name="kode_pettycash_details" required readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Barang</label>
                            <input type="text" class="form-control" id="item_name" name="item_name" required />
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required />
                        </div>
                        <div class="form-group">
                            <label for="">Unit</label>
                            <input type="text" class="form-control" id="unit" name="unit" required
                                placeholder="pcs, kg, trip dll" />
                        </div>
                        <div class="form-group">
                            <label for="">Harga per-Unit</label>
                            <input type="text" class="form-control" id="unit_price" name="unit_price"
                                oninput="updateRupiahUnit()" required />
                        </div>
                        <div class="form-group">
                            <label for="">Total Harga</label>
                            <input type="text" class="form-control" id="total_price" name="total_price" required
                                readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea type="text" class="form-control" id="note" name="note" rows="3" required></textarea>
                        </div>
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
    <script>
        $(function() {
            $('#quantity, #unit_price').on('input', function() {
                hitungTotal();
            });
        })

        function openAddForm() {
            $('#modalTitle').text('Tambah Rincian');
            $('#btnSubmit').html('<i class="fas fa-check"></i> Simpan');
            $('#detailForm').attr('action', "{{ route('addDetail', ['id' => $pettycash->id]) }}");
            $('#formMethod').val('POST');

            $('#detailForm')[0].reset();
            $('#kode_pettycash_details').val(genPettyCashCode());
            $('#modalForm').modal('show');
        }

        function openEditForm(data) {
            $('#modalTitle').text('Edit Rincian');
            $('#btnSubmit').html('<i class="fas fa-check"></i> Update');
            $('#detailForm').attr('action', "/detailPettyCash/editDetail/" + data.id);
            $('#formMethod').val('PUT');

            $('#kode_pettycash_details').val(data.kode_pettycash_details);
            $('#item_name').val(data.item_name);
            $('#quantity').val(data.quantity);
            $('#unit').val(data.unit);
            $('#unit_price').val(formatRupiah(data.unit_price.toString(), 'Rp. '));
            $('#total_price').val(formatRupiah(data.total_price.toString(), 'Rp. '));
            $('#note').val(data.note);
            $('#modalForm').modal('show');
        }

        function generateCode() {
            $("#kode_pettycash_details").val(genPettyCashCode())
            openAddForm()
        }
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
                    $('#deleteId').val(id);
                    form.submit();
                }
            });
        });

        function genPettyCashCode({
            prefix = 'PCD',
            length = 6
        } = {}) {
            const alphabet = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';
            const now = new Date();
            const yy = String(now.getFullYear()).slice(-2);
            const m = (now.getMonth() + 1).toString(36).toUpperCase();

            const bytes = (globalThis.crypto && crypto.getRandomValues) ?
                crypto.getRandomValues(new Uint8Array(length)) :
                Array.from({
                    length
                }, () => Math.floor(Math.random() * 256));

            let rand = '';
            for (const b of bytes) rand += alphabet[b % alphabet.length];

            return `${prefix}-${yy}${m}-${rand}`;
        }

        function hitungTotal() {
            const qty = parseInt(document.getElementById('quantity').value) || 0;
            let harga = document.getElementById('unit_price').value.replace(/[^0-9]/g, '') || 0;

            harga = parseInt(harga);
            const total = qty * harga;

            document.getElementById('total_price').value = formatRupiah(total.toString(), 'Rp. ');
        }

        function updateRupiahUnit() {
            const inputField = document.getElementById('unit_price');
            const inputValue = inputField.value;
            inputField.value = formatRupiah(inputValue, 'Rp. ');
        } // updateRupiahppn
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
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
