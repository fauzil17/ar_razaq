<?php
$hari_ini = date('l'); // Mendapatkan nama hari (Friday, Monday, dll)
session_start();

// Set timezone Indonesia
date_default_timezone_set("Asia/Makassar"); // Sesuaikan (Makassar untuk NTT)

// Ambil tanggal hari ini
$tanggal = date("d-m-Y");

// Ambil data dari API (ganti kota sesuai lokasi masjid Anda)
$kota = "Ende";
$negara = "Indonesia";

$url = "https://api.aladhan.com/v1/timingsByCity?city=$kota&country=$negara&method=11";

$response = file_get_contents($url);
$data = json_decode($response, true);

$jadwal = $data['data']['timings'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Sholat - Website Masjid</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header & Navigation -->
    <?php include '../includes/navbar.php'; ?>

    <!-- Jadwal Sholat Section -->
    <section class="jadwal-section">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                    <li class="breadcrumb-item active">Jadwal Sholat</li>
                </ol>
            </nav>

            <h1 class="section-title">Jadwal Sholat Hari Ini</h1>
            
            <div class="row row-cols-2 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6 justify-content-center">

                       <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Subuh</h3>
                               <p class="time"><?php echo $jadwal['Fajr']; ?></p>
                               <p class="text-muted">Jangan lewatkan sholat Subuh</p>
                           </div>
                       </div>

                       <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Dzuhur</h3>
                               <p class="time"><?php echo $jadwal['Dhuhr']; ?></p>
                               <p class="text-muted">Jangan lewatkan sholat Dzuhur</p>
                           </div>
                       </div>

                       <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Ashar</h3>
                               <p class="time"><?php echo $jadwal['Asr']; ?></p>
                               <p class="text-muted">Jangan lewatkan sholat Ashar</p>
                           </div>
                       </div>

                       <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Maghrib</h3>
                               <p class="time"><?php echo $jadwal['Maghrib']; ?></p>
                               <p class="text-muted">Jangan lewatkan sholat Maghrib</p>
                           </div>
                       </div>

                      <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Isya</h3>
                               <p class="time"><?php echo $jadwal['Isha']; ?></p>
                               <p class="text-muted">Jangan lewatkan sholat Isya</p>
                           </div>
                       </div>

                       <?php if ($hari_ini == "Friday"): ?>
                       <div class="col mb-3">
                           <div class="prayer-card small-card">
                               <h3>Jumat</h3>
                               <p class="time"><?php echo $jadwal['Dhuhr']; ?></p>
                               <p class="text-muted">Khutbah dimulai sebelum Dzuhur</p>
                           </div>
                       </div>
                       <?php endif; ?>

            </div>

            <div class="row mt-5">
                <div class="col-md-6">
                    <h2>Informasi Tambahan</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Sholat Tarawih (Ramadan)</h5>
                            <p class="card-text">Setiap malam Ramadan dimulai pukul 20:00 WIB</p>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Sholat Ied</h5>
                            <p class="card-text">Idul Fitri & Idul Adha: Pukul 07:30 WIB</p>
                        </div>
                    </div>
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

    <!-- Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="../assets/js/script.js"></script>
</body>
</html>
