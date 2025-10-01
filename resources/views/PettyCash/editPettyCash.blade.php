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
                    <a href="#">Permohonan</a>
                </li>
            </ul>

        </div>
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Permohonan</h4>

            </div>
            <div class="card-body">
                <div class="col-6">
                    <form action="{{ route('editPettyCash', ['id' => $pettycash->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="">Kode</label>
                            <input type="text" class="form-control" id="kode_pettycash" name="kode_pettycash"
                                value="{{ old('kode_pettycash', $pettycash->kode_pettycash) }}" readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Saldo</label>
                            <input type="text" class="form-control" id="amount" name="amount"
                                value="{{ old('amount', $pettycash->amount) }}" placeholder="Masukan Jumlah Saldo"
                                oninput="updateRupiahAmount()" required />
                        </div>
                        <div class="form-group ">
                            <label>Tipe Permohonan</label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option value="">-- Pilih Tipe Permohonan --</option>
                                <option value="Operasional"
                                    {{ old('tipe', $pettycash->tipe ?? '') == 'Operasional' ? 'selected' : '' }}>Operasional
                                </option>
                                <option value="Project"
                                    {{ old('tipe', $pettycash->tipe ?? '') == 'Project' ? 'selected' : '' }}>Project
                                </option>
                                <option value="Perjalanan"
                                    {{ old('tipe', $pettycash->tipe ?? '') == 'Perjalanan' ? 'selected' : '' }}>Perjalanan
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea type="text" class="form-control" id="description" name="description" rows="5" required>{{ old('description', $pettycash->description) }} </textarea>
                        </div>
                        <div class="ml-2 mt-6">
                            <a class="btn btn-danger btn-sm" href="{{ route('show.showPettyCashUser') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/core/jquery.3.2.1.min.js') }}"></script>
    <script>
        $(function() {
            let amountField = document.getElementById('amount');
            if (amountField.value) {
                amountField.value = formatRupiah(amountField.value, 'Rp. ');
            }
            $("#amount").on("input", function() {
                this.value = formatRupiah(this.value, 'Rp. ');
            });
        })


        function updateRupiahAmount() {
            var nilai_ppn = $("#amount").val();
            const inputField = document.getElementById('amount');
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
