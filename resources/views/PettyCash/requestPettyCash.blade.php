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
                    <form action="{{ route('addRequest') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Kode</label>
                            <input type="text" class="form-control" id="kode_pettycash" name="kode_pettycash" readonly />
                        </div>
                        <div class="form-group">
                            <label for="">Saldo</label>
                            <input type="text" class="form-control" id="amount" name="amount"
                                placeholder="Masukan Jumlah Saldo" oninput="updateRupiahAmount()" required />
                        </div>
                        <div class="form-group ">
                            <label>Tipe Permohonan</label>
                            <select class="form-control" id="tipe" name="tipe" required>
                                <option value="">-- Pilih Tipe Permohonan --</option>
                                <option value="Operasional">Operasional</option>
                                <option value="Project">Project</option>
                                <option value="Perjalanan">Perjalanan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Catatan</label>
                            <textarea type="text" class="form-control" id="description" name="description" rows="5" required> </textarea>
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
            $("#kode_pettycash").val(genPettyCashCode())
        })
        // rupiah.value.replace(/[^0-9]/g, '')
        function genPettyCashCode({
            prefix = 'PC',
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

        function updateRupiahAmount() {
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
