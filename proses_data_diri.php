<?php
// Proses data yang dikirim dari form_data_diri.html menggunakan metode POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: form_data_diri.html');
    exit;
}

// Ambil dan rapikan input nama.
$nama = trim($_POST['nama_lengkap'] ?? '');
$tanggalLahir = trim($_POST['tanggal_lahir'] ?? '');

// Daftar bulan bahasa Indonesia untuk mengubah format tanggal.
$namaBulan = [
    1 => 'Januari',
    2 => 'Februari',
    3 => 'Maret',
    4 => 'April',
    5 => 'Mei',
    6 => 'Juni',
    7 => 'Juli',
    8 => 'Agustus',
    9 => 'September',
    10 => 'Oktober',
    11 => 'November',
    12 => 'Desember'
];

// Validasi format tanggal YYYY-MM-DD.
$tanggalObj = DateTime::createFromFormat('!Y-m-d', $tanggalLahir);
$tanggalValid = $tanggalObj && $tanggalObj->format('Y-m-d') === $tanggalLahir;

// Hanya izinkan pilihan hobi yang tersedia pada formulir.
$daftarHobi = ['Membaca', 'Olahraga', 'Musik', 'Lainnya'];
$hobiMasuk = $_POST['hobi'] ?? [];
if (!is_array($hobiMasuk)) {
    $hobiMasuk = [];
}
$hobiDipilih = array_values(array_intersect($daftarHobi, $hobiMasuk));

// Validasi input wajib sebelum menampilkan hasil.
$pesanError = [];
if ($nama === '') {
    $pesanError[] = 'Nama lengkap wajib diisi.';
}
if (!$tanggalValid) {
    $pesanError[] = 'Tanggal lahir tidak valid. Silakan kembali dan pilih tanggal yang benar.';
}

// Ubah tanggal menjadi contoh format: 7 Januari 2006.
if ($tanggalValid) {
    $tanggalIndonesia = (int) $tanggalObj->format('j') . ' '
        . $namaBulan[(int) $tanggalObj->format('n')] . ' '
        . $tanggalObj->format('Y');
} else {
    $tanggalIndonesia = '';
}

// Gabungkan hobi menggunakan koma dan spasi.
$hobiIndonesia = count($hobiDipilih) > 0
    ? implode(', ', $hobiDipilih)
    : 'Tidak ada hobi yang dipilih.';

// Escape seluruh data pengguna sebelum ditampilkan ke halaman.
$namaAman = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
$hobiAman = htmlspecialchars($hobiIndonesia, ENT_QUOTES, 'UTF-8');
$tanggalAman = htmlspecialchars($tanggalIndonesia, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Data Diri</title>
    <style>
        :root {
            --blue: #1677e8;
            --blue-dark: #0f5fbe;
            --text: #202124;
            --muted: #5f6368;
            --border: #e4e8ef;
            --background: #f5f7fb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 36px 18px;
            background: var(--background);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.55;
        }
        .page { width: 100%; max-width: 650px; margin: 0 auto; }
        .brand {
            margin-bottom: 18px;
            color: #566274;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-align: center;
            text-transform: uppercase;
        }
        .brand-line { height: 4px; margin-top: 12px; border-radius: 4px; background: var(--blue); }
        .card {
            padding: 30px 34px 32px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 8px 24px rgba(32, 55, 90, 0.07);
        }
        h1 { margin: 0 0 23px; padding-bottom: 13px; border-bottom: 1px solid var(--border); font-size: 25px; }
        .result-row {
            display: grid;
            grid-template-columns: 155px minmax(0, 1fr);
            gap: 12px;
            padding: 13px 0;
            border-bottom: 1px solid #f0f2f5;
            font-size: 15px;
        }
        .result-label { font-weight: 700; }
        .result-value { overflow-wrap: anywhere; }
        .back-link { display: inline-block; margin-top: 24px; color: var(--blue); font-size: 14px; text-decoration: none; }
        .back-link:hover { color: var(--blue-dark); text-decoration: underline; }
        .error-box { margin-bottom: 20px; padding: 13px 15px; border: 1px solid #f0b7b7; border-radius: 6px; background: #fff5f5; color: #a12626; }
        .error-box p { margin: 4px 0; font-size: 14px; }
        @media (max-width: 520px) {
            body { padding: 22px 12px; }
            .card { padding: 23px 19px; }
            h1 { font-size: 22px; }
            .result-row { grid-template-columns: 1fr; gap: 3px; }
        }
    </style>
</head>
<body>
    <main class="page">
        <div class="brand">
            Laboratorium Praktikum Informatika
            <div class="brand-line"></div>
        </div>

        <section class="card" aria-labelledby="page-title">
            <h1 id="page-title">Tampilan Data Anda</h1>

            <?php if (count($pesanError) > 0): ?>
                <div class="error-box" role="alert">
                    <?php foreach ($pesanError as $pesan): ?>
                        <p><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="result-row">
                    <div class="result-label">Nama Lengkap:</div>
                    <div class="result-value"><?= $namaAman; ?></div>
                </div>
                <div class="result-row">
                    <div class="result-label">Tanggal Lahir:</div>
                    <div class="result-value"><?= $tanggalAman; ?></div>
                </div>
                <div class="result-row">
                    <div class="result-label">Hobi:</div>
                    <div class="result-value"><?= $hobiAman; ?></div>
                </div>
            <?php endif; ?>

            <a class="back-link" href="form_data_diri.html">&laquo; Kembali ke Form</a>
        </section>
    </main>
</body>
</html>
