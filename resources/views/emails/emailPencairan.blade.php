<h3>Halo {{ $details['nama'] }}</h3>
<p>Request petty cash kamu <strong>dicairkan</strong> oleh Finance.</p>
<ul>
    <li>Pengaju: {{ $details['pengaju'] }}</li>
    <li>Nominal: Rp {{ number_format($details['nominal'], 0, ',', '.') }}</li>
    <li>Tanggal Pencairan: {{ $details['tanggal_pencairan'] }}</li>
</ul>
<p>Terima kasih 🙌</p>
