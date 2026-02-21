<?php
session_start();
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
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Subuh</h3>
                        <p class="time">04:30</p>
                        <p class="text-muted">Jangan lewatkan sholat Subuh</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Dzuhur</h3>
                        <p class="time">12:15</p>
                        <p class="text-muted">Waktu istirahat siang</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Ashar</h3>
                        <p class="time">15:45</p>
                        <p class="text-muted">Sore hari</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Maghrib</h3>
                        <p class="time">18:05</p>
                        <p class="text-muted">Setelah matahari terbenam</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Isya</h3>
                        <p class="time">19:30</p>
                        <p class="text-muted">Malam hari</p>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="prayer-card">
                        <h3>Jumat</h3>
                        <p class="time">12:00</p>
                        <p class="text-muted">Khutbah dimulai lebih awal</p>
                    </div>
                </div>
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
