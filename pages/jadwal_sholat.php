<?php
session_start();
date_default_timezone_set("Asia/Makassar");

include '../config/koneksi.php';

/* ================= API JADWAL SHOLAT ================= */
$hari_ini = date('l');
$tanggal = date("d-m-Y");

$kota = "Ende";
$negara = "Indonesia";

$url = "https://api.aladhan.com/v1/timingsByCity?city=$kota&country=$negara&method=11";
$response = file_get_contents($url);
$data_api = json_decode($response, true);
$jadwal = $data_api['data']['timings'];

/* ================= CRUD INFORMASI ================= */

// TAMBAH
if (isset($_POST['tambah'])) {
    $judul = $_POST['judul'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("INSERT INTO informasi (judul, lokasi, deskripsi) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $judul, $lokasi, $deskripsi);
    $stmt->execute();
    header("Location: jadwal_sholat.php");
    exit;
}

// EDIT
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    $stmt = $conn->prepare("UPDATE informasi SET judul=?, lokasi=?, deskripsi=? WHERE id=?");
    $stmt->bind_param("sssi", $judul, $lokasi, $deskripsi, $id);
    $stmt->execute();
    header("Location: jadwal_sholat.php");
    exit;
}

// HAPUS
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $conn->query("DELETE FROM informasi WHERE id=$id");
    header("Location: jadwal_sholat.php");
    exit;
}

/* ================= PAGINATION ================= */
$batas = 3;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$total_data = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM informasi"));
$total_halaman = ceil($total_data / $batas);

$data = mysqli_query($conn, "SELECT * FROM informasi ORDER BY id DESC LIMIT $halaman_awal, $batas");
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

<!-- ================= JADWAL SHOLAT ================= -->

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 justify-content-center">

<?php
$prayers = [
    "Subuh" => $jadwal['Fajr'],
    "Dzuhur" => $jadwal['Dhuhr'],
    "Ashar" => $jadwal['Asr'],
    "Maghrib" => $jadwal['Maghrib'],
    "Isya" => $jadwal['Isha']
];

foreach ($prayers as $nama => $waktu):
?>

<div class="col mb-3">
<div class="prayer-card small-card">
<h3><?= $nama; ?></h3>
<p class="time"><?= $waktu; ?></p>
<p class="text-muted">Semoga Allah menerima ibadah kita</p>
</div>
</div>

<?php endforeach; ?>

<?php if ($hari_ini == "Friday"): ?>
<div class="col mb-3">
<div class="prayer-card small-card">
<h3>Jumat</h3>
<p class="time"><?= $jadwal['Dhuhr']; ?></p>
<p class="text-muted">Khutbah dimulai sebelum Dzuhur</p>
</div>
</div>
<?php endif; ?>

</div>

<!-- ================= INFORMASI MASJID ================= -->

<div class="row mt-5">
<div class="col-md-6">

<div class="d-flex justify-content-between align-items-center mb-3">
<h2>Informasi Masjid</h2>
<button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
<i class="fa fa-plus"></i> Tambah
</button>
</div>

<?php while ($row = mysqli_fetch_assoc($data)) : ?>

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

<!-- MODAL EDIT -->
<div class="modal fade" id="editModal<?= $row['id']; ?>">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">
<input type="hidden" name="id" value="<?= $row['id']; ?>">
<div class="modal-header">
<h5>Edit Informasi</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input type="text" name="judul" value="<?= $row['judul']; ?>" class="form-control mb-3" required>
<input type="text" name="lokasi" value="<?= $row['lokasi']; ?>" class="form-control mb-3" required>
<textarea name="deskripsi" class="form-control" rows="4" required><?= $row['deskripsi']; ?></textarea>
</div>
<div class="modal-footer">
<button type="submit" name="edit" class="btn btn-warning">Update</button>
</div>
</form>
</div>
</div>
</div>

<?php endwhile; ?>

<!-- PAGINATION -->
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahModal">
<div class="modal-dialog">
<div class="modal-content">
<form method="POST">
<div class="modal-header">
<h5>Tambah Informasi</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
<input type="text" name="judul" class="form-control mb-3" placeholder="Judul Informasi" required>
<input type="text" name="lokasi" class="form-control mb-3" placeholder="Lokasi / Tempat Kegiatan" required>
<textarea name="deskripsi" class="form-control" rows="4" placeholder="Deskripsi Informasi" required></textarea>
</div>
<div class="modal-footer">
<button type="submit" name="tambah" class="btn btn-success">Simpan</button>
</div>
</form>
</div>
</div>
</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>