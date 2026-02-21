<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kegiatan - Website Masjid</title>
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

    <!-- Kegiatan Section -->
    <section class="kegiatan-section">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                    <li class="breadcrumb-item active">Kegiatan</li>
                </ol>
            </nav>

            <h1 class="section-title">Kegiatan Masjid Kami</h1>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Pengajian Rutin</h5>
                            <p class="card-text">
                                Kajian ilmu Islam setiap hari dengan berbagai topik yang relevan dengan kehidupan modern. 
                                Dipandu oleh ustadz berpengalaman yang siap menjawab setiap pertanyaan.
                            </p>
                            <p><strong>Jadwal:</strong> Setiap hari Pukul 19:30 WIB</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-child fa-3x text-success mb-3"></i>
                            <h5 class="card-title">TPA & Madrasah</h5>
                            <p class="card-text">
                                Program Taman Pendidikan Al-Qur'an untuk anak-anak dengan metode pembelajaran yang menyenangkan 
                                dan efektif. Kami juga menyediakan kelas Madrasah untuk pendidikan agama yang komprehensif.
                            </p>
                            <p><strong>Jadwal:</strong> Senin-Jumat Pukul 15:30 - 17:30 WIB</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-handshake fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Zakat & Infaq</h5>
                            <p class="card-text">
                                Program pengumpulan zakat dan infaq untuk membantu masyarakat yang membutuhkan. 
                                Setiap dana yang terkumpul akan disalurkan tepat sasaran dan transparan.
                            </p>
                            <p><strong>Penerimaan:</strong> Setiap hari di posko utama</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-people-group fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Acara Sosial</h5>
                            <p class="card-text">
                                Berbagai kegiatan sosial dan pemberdayaan masyarakat seperti bansos, bakti sosial, 
                                dan pelatihan keterampilan untuk meningkatkan ekonomi masyarakat.
                            </p>
                            <p><strong>Jadwal:</strong> Sesuai kebutuhan & musim</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-dumbbell fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Olahraga & Kesehatan</h5>
                            <p class="card-text">
                                Program kesehatan masyarakat meliputi pemeriksaan kesehatan gratis dan program olahraga 
                                bersama untuk menjaga kesehatan fisik jemaah.
                            </p>
                            <p><strong>Jadwal:</strong> Minggu Pagi Pukul 06:00 WIB</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Silaturahmi & Acara</h5>
                            <p class="card-text">
                                Acara kebersamaan seperti buka puasa bersama, perayaan hari besar Islam, 
                                dan gathering jemaah untuk mempererat silaturahmi.
                            </p>
                            <p><strong>Jadwal:</strong> Sesuai musim & hari besar Islam</p>
                        </div>
                    </div>
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
