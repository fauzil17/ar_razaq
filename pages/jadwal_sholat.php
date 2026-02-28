<?php
session_start();
date_default_timezone_set("Asia/Makassar");
include '../config/koneksi.php';

/* ================= AUTO GENERATE JADWAL 1 TAHUN ================= */

$tahun_sekarang = date("Y");
$tanggal_awal_tahun = $tahun_sekarang . "-01-01";

/* Cek apakah tanggal 1 Januari sudah ada */
$cek_tanggal_awal = $conn->prepare("SELECT id FROM jadwal_sholat WHERE tanggal=?");
$cek_tanggal_awal->bind_param("s", $tanggal_awal_tahun);
$cek_tanggal_awal->execute();
$cek_awal = $cek_tanggal_awal->get_result();

if ($cek_awal->num_rows == 0) {

    $kota = "Ende";
    $negara = "Indonesia";

    for ($bulan = 1; $bulan <= 12; $bulan++) {

        $url = "https://api.aladhan.com/v1/calendarByCity?city=$kota&country=$negara&method=11&month=$bulan&year=$tahun_sekarang";
        $response = @file_get_contents($url);

        if ($response === false) continue;

        $data_api = json_decode($response, true);
        if (!isset($data_api['data'])) continue;

        foreach ($data_api['data'] as $hari) {

            $tanggal_format = date("Y-m-d", strtotime($hari['date']['gregorian']['date']));

            $subuh   = substr($hari['timings']['Fajr'], 0, 5);
$dzuhur  = substr($hari['timings']['Dhuhr'], 0, 5);
$ashar   = substr($hari['timings']['Asr'], 0, 5);
$maghrib = substr($hari['timings']['Maghrib'], 0, 5);
$isya    = substr($hari['timings']['Isha'], 0, 5);

            $stmt = $conn->prepare("INSERT IGNORE INTO jadwal_sholat 
                (tanggal, subuh, dzuhur, ashar, maghrib, isya) 
                VALUES (?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("ssssss",
                $tanggal_format,
                $subuh,
                $dzuhur,
                $ashar,
                $maghrib,
                $isya
            );

            $stmt->execute();
        }
    }
}

/* ================= AMBIL JADWAL HARI INI ================= */

$tanggal_hari_ini = date("Y-m-d");
$hari_ini = date("l");

$stmt_today = $conn->prepare("SELECT * FROM jadwal_sholat WHERE tanggal=?");
$stmt_today->bind_param("s", $tanggal_hari_ini);
$stmt_today->execute();
$jadwal_db = $stmt_today->get_result()->fetch_assoc();

/* Pengaman jika kosong */
if (!$jadwal_db) {
    $jadwal_db = [
        'subuh' => '--:--',
        'dzuhur' => '--:--',
        'ashar' => '--:--',
        'maghrib' => '--:--',
        'isya' => '--:--'
    ];
}

$jadwal = [
    "Fajr" => substr($jadwal_db['subuh'], 0, 5),
    "Dhuhr" => substr($jadwal_db['dzuhur'], 0, 5),
    "Asr" => substr($jadwal_db['ashar'], 0, 5),
    "Maghrib" => substr($jadwal_db['maghrib'], 0, 5),
    "Isha" => substr($jadwal_db['isya'], 0, 5)
];

/* ================= CRUD INFORMASI ================= */

// TAMBAH
if (isset($_POST['tambah'])) {
    $stmt = $conn->prepare("INSERT INTO informasi (judul, lokasi, deskripsi) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_POST['judul'], $_POST['lokasi'], $_POST['deskripsi']);
    $stmt->execute();
    header("Location: jadwal_sholat.php");
    exit;
}

// EDIT
if (isset($_POST['edit'])) {
    $stmt = $conn->prepare("UPDATE informasi SET judul=?, lokasi=?, deskripsi=? WHERE id=?");
    $stmt->bind_param("sssi", $_POST['judul'], $_POST['lokasi'], $_POST['deskripsi'], $_POST['id']);
    $stmt->execute();
    header("Location: jadwal_sholat.php");
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM informasi WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: jadwal_sholat.php");
    exit;
}

/* ================= PAGINATION ================= */

$batas = 3;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$total_data = $conn->query("SELECT COUNT(*) as total FROM informasi")
                    ->fetch_assoc()['total'];

$total_halaman = ceil($total_data / $batas);

$data = $conn->query("SELECT * FROM informasi ORDER BY id DESC LIMIT $halaman_awal, $batas");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jadwal Sholat - Website Masjid</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<section class="jadwal-section">
<div class="container">

<nav aria-label="breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
<li class="breadcrumb-item active">Jadwal Sholat</li>
</ol>
</nav>

<h1 class="section-title">Jadwal Sholat Hari Ini</h1>

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 justify-content-center">

<?php
$prayers = [
    "Subuh" => ["waktu" => $jadwal['Fajr']],
    "Dzuhur" => ["waktu" => $jadwal['Dhuhr']],
    "Ashar" => ["waktu" => $jadwal['Asr']],
    "Maghrib" => ["waktu" => $jadwal['Maghrib']],
    "Isya" => ["waktu" => $jadwal['Isha']]
];

if ($hari_ini == "Friday") {
    $prayers["Jumat"] = ["waktu" => $jadwal['Dhuhr']];
}

foreach ($prayers as $nama => $data_sholat):
?>

<div class="col mb-3">
<div class="prayer-card small-card">
<h3><?= $nama; ?></h3>
<p class="time"><?= $data_sholat['waktu']; ?></p>
</div>
</div>

<?php endforeach; ?>
</div>

<div class="row mt-5">
<div class="col-md-6">

<div class="d-flex justify-content-between align-items-center mb-3">
<h2>Informasi Masjid</h2>
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
<i class="fa fa-plus"></i> Tambah
</button>
</div>

<?php while ($row = $data->fetch_assoc()) : ?>
<div class="card shadow-sm border-0 mb-3">
<div class="card-body d-flex justify-content-between">
<div>
<h5 class="fw-bold"><?= $row['judul']; ?></h5>
<small class="text-primary">
<i class="fa fa-map-marker-alt"></i> <?= $row['lokasi']; ?>
</small>
<p class="text-muted mb-0 mt-2"><?= $row['deskripsi']; ?></p>
</div>
<div>
<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id']; ?>">
<i class="fa fa-edit"></i>
</button>
<a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
<i class="fa fa-trash"></i>
</a>
</div>
</div>
</div>
<?php endwhile; ?>

<nav>
<ul class="pagination justify-content-center mt-3">
<?php for ($x = 1; $x <= $total_halaman; $x++) : ?>
<li class="page-item <?= ($x == $halaman) ? 'active' : ''; ?>">
<a class="page-link" href="?halaman=<?= $x; ?>"><?= $x; ?></a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>

<div class="col-md-6">
<h2>Catatan Penting</h2>
<ul class="list-group">
<li class="list-group-item">✓ Sholat berjamaah sangat dianjurkan</li>
<li class="list-group-item">✓ Datang 10-15 menit sebelum adzan</li>
<li class="list-group-item">✓ Pakaian yang rapi dan sopan</li>
<li class="list-group-item">✓ Berwudhu sebelum memasuki masjid</li>
<li class="list-group-item">✓ Matikan suara ponsel selama sholat</li>
</ul>
</div>

</div>
</div>
</section>

<?php include '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>