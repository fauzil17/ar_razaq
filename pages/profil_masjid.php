<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Masjid - Website Masjid</title>
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

    <!-- Profil Masjid Section -->
    <section class="profil-section" style="background-image: url('assets/images/masjid.jpg'); background-size: cover; background-position: center;">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                    <li class="breadcrumb-item active">Profil Masjid</li>
                </ol>
            </nav>

            <h1 class="section-title">Profil Masjid Kami</h1>
            
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h2>Sejarah Singkat</h2>
                    <p>
                        Masjid kami didirikan pada tahun 2005 sebagai tempat ibadah dan pusat pembelajaran Islam bagi masyarakat sekitar. 
                        Dengan komitmen untuk menyebarkan ilmu agama dan membangun komunitas yang kuat, masjid ini telah berkembang menjadi 
                        salah satu pusat kegiatan keagamaan terpercaya di daerah ini.
                    </p>
                    <p>
                        Hingga saat ini, masjid kami telah melayani ribuan jemaah dengan berbagai program kegiatan yang edukatif dan inspiratif.
                    </p>
                </div>
                <div class="col-md-6 mb-4">
                    <h2>Visi & Misi</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Visi</h5>
                            <p class="card-text">
                                Menjadi masjid yang menjadi pusat pembelajaran Islam terpadu dan menjadi teladan dalam kehidupan masyarakat.
                            </p>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Misi</h5>
                            <ul class="card-text">
                                <li>Menyebarkan dan mengamalkan nilai-nilai Islam yang moderasi</li>
                                <li>Menyelenggarakan kegiatan pendidikan agama untuk semua usia</li>
                                <li>Membangun silaturahmi dan kebersamaan dalam komunitas</li>
                                <li>Memberikan pelayanan sosial kepada masyarakat yang membutuhkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <h2>Fasilitas</h2>
                    <ul class="list-group">
                        <li class="list-group-item"><i class="fas fa-mosque text-success"></i> Ruang Ibadah Utama</li>
                        <li class="list-group-item"><i class="fas fa-book text-success"></i> Perpustakaan Islam</li>
                        <li class="list-group-item"><i class="fas fa-child text-success"></i> Ruang Kelas TPA</li>
                        <li class="list-group-item"><i class="fas fa-utensils text-success"></i> Dapur & Ruang Makan</li>
                        <li class="list-group-item"><i class="fas fa-wheelchair text-success"></i> Akses Difabel</li>
                        <li class="list-group-item"><i class="fas fa-car text-success"></i> Tempat Parkir</li>
                    </ul>
                </div>
                <div class="col-md-6 mb-4">
                    <h2>Informasi Kontak</h2>
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Alamat:</strong> Jalan Contoh No. 123, Kota XYZ</p>
                            <p><strong>Telepon:</strong> (021) 1234-5678</p>
                            <p><strong>Email:</strong> info@masjidkami.com</p>
                            <p><strong>Website:</strong> www.masjidkami.com</p>
                            <p><strong>Jam Operasional:</strong></p>
                            <ul>
                                <li>Senin - Jumat: 04:00 - 21:00 WIB</li>
                                <li>Sabtu - Minggu: 04:00 - 22:00 WIB</li>
                            </ul>
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
