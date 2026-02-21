<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Masjid</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Header & Navigation -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero" style="background-image: url('assets/images/masjid.jpg'); background-size: cover; background-position: center;">
        <div class="hero-content">
            <h1>Selamat Datang di Masjid Kami</h1>
            <p>Tempat berbagi ilmu, doa, dan kebersamaan dalam iman</p>
            <a href="#tentang" class="btn btn-primary">Pelajari Lebih Lanjut</a>
        </div>
    </section>

    <!-- Jadwal Sholat -->
    <section class="jadwal-sholat" id="jadwal">
        <div class="container">
            <h2>Jadwal Sholat Hari Ini</h2>
            <div class="prayer-times">
                <div class="prayer-card">
                    <h3>Subuh</h3>
                    <p class="time">04:30</p>
                </div>
                <div class="prayer-card">
                    <h3>Dzuhur</h3>
                    <p class="time">12:15</p>
                </div>
                <div class="prayer-card">
                    <h3>Ashar</h3>
                    <p class="time">15:45</p>
                </div>
                <div class="prayer-card">
                    <h3>Maghrib</h3>
                    <p class="time">18:05</p>
                </div>
                <div class="prayer-card">
                    <h3>Isya</h3>
                    <p class="time">19:30</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Terbaru -->
    <section class="berita" id="berita">
        <div class="container">
            <h2>Berita Terbaru</h2>
            <div class="news-grid">
                <article class="news-card">
                    <h3>Kajian Rutin Setiap Jum'at</h3>
                    <p class="date">3 Februari 2026</p>
                    <p>Kajian ilmu Islam setiap jum'at malam pukul 19:30 WIB bersama ustadz tamu...</p>
                    <a href="#" class="btn-read">Baca Selengkapnya</a>
                </article>
                <article class="news-card">
                    <h3>Program Buka Puasa Bersama</h3>
                    <p class="date">3 Februari 2026</p>
                    <p>Masjid kami mengadakan program buka puasa bersama setiap hari dalam bulan Ramadan...</p>
                    <a href="#" class="btn-read">Baca Selengkapnya</a>
                </article>
                <article class="news-card">
                    <h3>Pendaftaran Santri TPA Dibuka</h3>
                    <p class="date">3 Februari 2026</p>
                    <p>Pendaftaran santri baru untuk program TPA (Taman Pendidikan Al-Qur'an) telah dibuka...</p>
                    <a href="#" class="btn-read">Baca Selengkapnya</a>
                </article>
            </div>
        </div>
    </section>

    <!-- Kegiatan -->
    <section class="kegiatan" id="kegiatan">
        <div class="container">
            <h2>Kegiatan Kami</h2>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h3>Pengajian Rutin</h3>
                    <p>Kajian ilmu Islam setiap hari dengan berbagai topik yang relevan</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-child"></i>
                    </div>
                    <h3>TPA & Madrasah</h3>
                    <p>Pendidikan Al-Qur'an dan ilmu Islam untuk anak-anak kami</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Zakat & Infaq</h3>
                    <p>Program pengumpulan zakat dan infaq untuk membantu yang membutuhkan</p>
                </div>
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-people-group"></i>
                    </div>
                    <h3>Acara Sosial</h3>
                    <p>Berbagai kegiatan sosial dan pemberdayaan masyarakat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Donasi -->
    <section class="cta-donasi">
        <div class="container">
            <h2>Dukung Masjid Kami</h2>
            <p>Berkontribusi untuk pengembangan dan keberlanjutan masjid kami</p>
            <a href="pages/donasi.php" class="btn btn-primary">Berikan Donasi</a>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/script.js"></script>
</body>
</html>