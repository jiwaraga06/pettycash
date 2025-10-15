<h3>Halo {{ $details['nama'] }}</h3>
<p>Request petty cash berikut telah disetujui oleh Finance:</p>
<ul>
    <li>Pengaju: {{ $details['pengaju'] }}</li>
    <li>Nominal: Rp {{ number_format($details['nominal'], 0, ',', '.') }}</li>
</ul>
<p>Silakan proses pencairan.</p>
