<!DOCTYPE html>
<html lang="en">

<head>

    {{-- <title>Cetak Doc Barang</title> --}}
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<style type="text/css">
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table tr td,
    table tr th {
        font-size: 9pt;
        border: 1px solid black;
        padding: 5px;
    }

    table thead {
        background: #f2f2f2;
    }
</style>

</head>

<body>
    <div class="container">
        <h4>Rekap Permohonan</h4>
        <div class="table-responsive">
            <table id="tableHead" class="table">
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
                    @php $number = 1; @endphp
                    @foreach ($pettycash as $item)
                        <tr>
                            <td>{{ $number++ }} </td>
                            <td>{{ $item->kode_pettycash }}</td>
                            <td>Rp. {{ number_format($item->amount, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($item->used_amount, 0, ',', '.') }}</td>
                            <td>{{ $item->tipe }}</td>
                            <td>{{ $item->description }}</td>
                            <td>
                                @if ($item->status == 'pending')
                                    <span class="badge badge-info">PENDING</span>
                                @elseif ($item->status == 'dept_approved')
                                    <span class="badge badge-info">Approve by Head Dept</span>
                                @elseif ($item->status == 'finance_approved')
                                    <span class="badge badge-info">Approve by Finance</span>
                                @elseif ($item->status == 'paid')
                                    <span class="badge badge-primary">PAID</span>
                                @elseif ($item->status == 'rejected')
                                    <span class="badge badge-danger">REJECTED</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


</body>

</html>
