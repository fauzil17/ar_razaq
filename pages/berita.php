<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita - Website Masjid</title>
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

    <!-- Berita Section -->
    <section class="berita-section">
        <div class="container">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../index.php">Beranda</a></li>
                    <li class="breadcrumb-item active">Berita</li>
                </ol>
            </nav>

            <h1 class="section-title">Berita Terbaru</h1>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Kajian Rutin Setiap Jum'at</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 3 Februari 2026</p>
                            <p class="card-text">
                                Kajian ilmu Islam setiap jum'at malam pukul 19:30 WIB bersama ustadz tamu yang berpengalaman 
                                di bidangnya. Materi kajian disesuaikan dengan kebutuhan dan minat jemaah yang hadir.
                            </p>
                            <p class="card-text">
                                Topik bulan ini: "Pentingnya Pendidikan Anak dalam Perspektif Islam". Semua kalangan 
                                masyarakat dipersilahkan menghadiri acara ini.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Program Buka Puasa Bersama</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 3 Februari 2026</p>
                            <p class="card-text">
                                Masjid kami mengadakan program buka puasa bersama setiap hari dalam bulan Ramadan. 
                                Program ini adalah wadah kebersamaan jemaah dalam menjalani ibadah puasa dengan penuh berkah.
                            </p>
                            <p class="card-text">
                                Kami menyiapkan menu makanan bergizi dan sajian istimewa yang disumbangkan dari berbagai 
                                donatur. Acara dimulai pukul 17:45 WIB dengan kegiatan seru untuk seluruh keluarga.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pendaftaran Santri TPA Dibuka</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 3 Februari 2026</p>
                            <p class="card-text">
                                Pendaftaran santri baru untuk program TPA (Taman Pendidikan Al-Qur'an) telah dibuka. 
                                Program ini dirancang khusus untuk anak-anak usia 5-12 tahun dengan metode pembelajaran 
                                yang interaktif dan menyenangkan.
                            </p>
                            <p class="card-text">
                                Biaya pendaftaran terjangkau dengan kurikulum yang mencakup membaca Al-Qur'an, tajwid, 
                                dan pendidikan akhlak. Pendaftaran dibuka dari sekarang hingga tempat penuh.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Renovasi Ruang Ibadah Wanita</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 1 Februari 2026</p>
                            <p class="card-text">
                                Alhamdulillah, renovasi ruang ibadah wanita telah selesai dilakukan dengan hasil yang 
                                sangat memuaskan. Ruang yang lebih luas, nyaman, dan modern ini diharapkan dapat meningkatkan 
                                kenyamanan ibadah para wanita muslimah.
                            </p>
                            <p class="card-text">
                                Kami mengucapkan terima kasih atas kontribusi semua donatur yang telah membantu 
                                mewujudkan proyek perbaikan ini.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Bakti Sosial untuk Anak Yatim</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 31 Januari 2026</p>
                            <p class="card-text">
                                Dalam rangka menjalankan misi sosial, masjid kami mengadakan bakti sosial untuk anak-anak 
                                yatim dan piatu. Program ini meliputi pemberian paket perlengkapan sekolah, makanan, 
                                dan kasih sayang.
                            </p>
                            <p class="card-text">
                                Ratusan anak mendapatkan manfaat dari kegiatan ini. Acara penuh kebahagiaan ini menjadi 
                                bukti kepedulian jemaah terhadap sesama.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Hasil Uji Coba Pengeras Suara Baru</h5>
                            <p class="card-text text-muted"><i class="fas fa-calendar"></i> 28 Januari 2026</p>
                            <p class="card-text">
                                Masjid kami telah memasang sistem pengeras suara baru dengan teknologi terkini. 
                                Hasil uji coba menunjukkan kualitas suara yang jernih dan jangkauan yang lebih luas.
                            </p>
                            <p class="card-text">
                                Dengan upgrade ini, diharapkan suara muezzin dan khutbah dapat terdengar dengan jelas 
                                ke seluruh penjuru masjid dan sekitarnya.
                            </p>
                            <a href="#" class="btn btn-sm btn-primary">Baca Selengkapnya</a>
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
