  <p>{{ $details['body'] ?? 'Ada pengajuan kas kecil baru.' }}</p>

  <br>

  <p>
      <strong>Kode Petty Cash:</strong> {{ $details['kode_pettycash'] ?? '-' }} <br>
      <strong>Nominal:</strong> Rp {{ isset($details['amount']) ? number_format($details['amount'], 0, ',', '.') : '-' }}
      <br>
      <strong>Tipe:</strong> {{ $details['tipe'] ?? '-' }} <br>
      <strong>Dibuat oleh:</strong> {{ $details['created_by'] ?? '-' }}
  </p>

  <br>
  <p>Terima kasih,<br>
