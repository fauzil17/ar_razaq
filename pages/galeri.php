<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri - Website Masjid</title>
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

    <!-- Galeri Section -->
    <section class="galeri-section">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="../pages/galeri.php">Galeri</a></li>
                    <li class="breadcrumb-item active">
                        <?php
                        $kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
                        $kategori_map = [
                            'pengajian' => 'Pengajian',
                            'ramadhan' => 'Ramadhan',
                            'sosial' => 'Sosial',
                            'remaja-masjid' => 'Remaja Masjid'
                        ];
                        if ($kategori && isset($kategori_map[$kategori])) {
                            echo htmlspecialchars($kategori_map[$kategori]);
                        } else {
                            echo 'Semua Galeri';
                        }
                        ?>
                    </li>
                </ol>
            </nav>

            <h1 class="section-title">Galeri Foto Masjid</h1>

            <?php
            // Daftar item galeri (sumber data sederhana). Untuk produksi, ambil dari DB.
            $items = [
                ['img' => '../assets/images/kegiatan1.jpg', 'title' => 'Kegiatan 1', 'desc' => 'Dokumentasi kegiatan masjid kami', 'kategori' => 'sosial'],
                ['img' => '../assets/images/kegiatan 2.jpg', 'title' => 'Kegiatan 2', 'desc' => 'Dokumentasi kegiatan masjid kami', 'kategori' => 'ramadhan'],
                ['img' => '../assets/images/kegiatan 3.jpg', 'title' => 'Kegiatan 3', 'desc' => 'Dokumentasi kegiatan masjid kami', 'kategori' => 'pengajian'],
                ['img' => '../assets/images/kegiatan4.jpg', 'title' => 'Kegiatan 4', 'desc' => 'Dokumentasi kegiatan masjid kami', 'kategori' => 'remaja-masjid'],
                ['img' => '../assets/images/kegiatan5.jpg', 'title' => 'Kegiatan 5', 'desc' => 'Dokumentasi kegiatan masjid kami', 'kategori' => 'remaja-masjid'],
            ];

            // Filter jika kategori diberikan dan valid
            $filtered = $items;
            if ($kategori && isset($kategori_map[$kategori])) {
                $filtered = array_values(array_filter($items, function ($it) use ($kategori) {
                    return isset($it['kategori']) && $it['kategori'] === $kategori;
                }));
            }
            ?>

            <div class="row">
                <?php if (count($filtered) === 0): ?>
                    <div class="col-12">
                        <p>Tidak ada foto untuk kategori ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($filtered as $it): ?>
                        <div class="col-md-4 mb-4">
                            <div class="galeri-item">
                                <img src="<?php echo htmlspecialchars($it['img']); ?>" alt="<?php echo htmlspecialchars($it['title']); ?>" class="img-fluid rounded">
                                <div class="galeri-caption">
                                    <h5><?php echo htmlspecialchars($it['title']); ?></h5>
                                    <p><?php echo htmlspecialchars($it['desc']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="text-center mt-5">
                <h3>Dokumentasi Lebih Lanjut</h3>
                <p>Untuk melihat dokumentasi kegiatan masjid yang lebih lengkap, Anda bisa mengunjungi media sosial kami.</p>
                <div class="social-links">
                    <a href="#" class="btn btn-info btn-sm me-2"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#" class="btn btn-info btn-sm me-2"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#" class="btn btn-info btn-sm"><i class="fab fa-youtube"></i> YouTube</a>
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
