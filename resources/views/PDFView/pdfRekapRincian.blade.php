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
        <h4>Rekap Rincian</h4>
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


</body>

</html>
