<?php
// Tentukan base path
$currentPath = dirname($_SERVER['PHP_SELF']);
if ($currentPath == '/' || $currentPath == '\\') {
    $base_path = '';
} else {
    $base_path = str_replace('\\', '/', $currentPath);
    // Jika berada di folder pages/, naik 1 level
    if (strpos($_SERVER['PHP_SELF'], '/pages/') !== false) {
        $base_path = dirname($base_path);
    }
}

// Normalisasi: pastikan tidak menghasilkan '//' ( dirname bisa mengembalikan '/')
$base_path = rtrim($base_path, '/\\');
?>
<!-- Navbar Bootstrap -->
<nav class="navbar navbar-expand-lg" style="background-color: #2563eb;">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex ms-5" href="<?php echo $base_path; ?>/index.php">
            <i class="fas fa-mosque fa-2x me-2 text-white"></i>
            <span class="masjid-name">ar razaq</span>
        </a>

        <!-- Hamburger Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Menu -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>/index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>/pages/profil_masjid.php">Profil Masjid</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>/pages/jadwal_sholat.php">Jadwal Sholat</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>/pages/kegiatan.php">Kegiatan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>/pages/berita.php">Berita</a>
                </li>
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button"
       data-bs-toggle="dropdown" aria-expanded="false">
        Kas
    </a>

    <ul class="dropdown-menu">

        <!-- KAS MASJID -->
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">
                Kas Masjid
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/kas-harian.php">
                        Input Kas Harian
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/kas-jumat.php">
                        Input Kas Jum'at
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/kas-laporan.php">
                        Laporan
                    </a>
                </li>
            </ul>
        </li>

        <li><hr class="dropdown-divider"></li>

        <!-- ZAKAT & INFAK -->
        <li class="dropdown-submenu">
            <a class="dropdown-item dropdown-toggle" href="#">
                Kas Zakat & Infak
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/zakat.php">
                         Zakat
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/infak.php">
                        Infak
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/penyaluran.php">
                        Penyaluran
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="<?php echo $base_path; ?>/pages/laporan.php">
                        Laporan
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="galeriDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Galeri
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="galeriDropdown">
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/galeri.php">Semua Galeri</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-header">Berdasarkan Kegiatan</li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/galeri.php?kategori=pengajian">Pengajian</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/galeri.php?kategori=ramadhan">Ramadhan</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/galeri.php?kategori=sosial">Sosial</a></li>
                        <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/galeri.php?kategori=remaja-masjid">Remaja Masjid</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user"></i> User
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/profil.php">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>/logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/login.php">Login</a></li>
                            <li><a class="dropdown-item" href="<?php echo $base_path; ?>/pages/register.php">Daftar</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
