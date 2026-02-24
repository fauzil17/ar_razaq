-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 24, 2026 at 12:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `masjid_fauzil`
--

-- --------------------------------------------------------

--
-- Table structure for table `infak`
--

CREATE TABLE `infak` (
  `id_infak` int NOT NULL,
  `tanggal` date NOT NULL,
  `nama_pemberi` varchar(100) DEFAULT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_masjid`
--

CREATE TABLE `kas_masjid` (
  `id_kas` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_kas` enum('Harian','Jumat') NOT NULL DEFAULT 'Harian',
  `kategori_kas` enum('Pemasukan','Pengeluaran') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `keterangan` text,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kepala_keluarga`
--

CREATE TABLE `kepala_keluarga` (
  `id_kepala` int NOT NULL,
  `nama_kepala` varchar(100) NOT NULL,
  `alamat` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyaluran_dana`
--

CREATE TABLE `penyaluran_dana` (
  `id_penyaluran` int NOT NULL,
  `tanggal` date NOT NULL,
  `jenis_dana` enum('Zakat Fitrah','Zakat Mal','Infak','Kas Masjid') NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `penerima` varchar(255) NOT NULL,
  `keterangan` text,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','bendahara','pengguna') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zakat_fitrah`
--

CREATE TABLE `zakat_fitrah` (
  `id_zakat_fitrah` int NOT NULL,
  `tanggal` date NOT NULL,
  `id_kepala` int DEFAULT NULL,
  `alamat` text,
  `jumlah_jiwa` int NOT NULL,
  `harga_beras` decimal(10,2) NOT NULL,
  `total_zakat` decimal(15,2) NOT NULL,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jenis_zakat` varchar(50) DEFAULT NULL,
  `keterangan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zakat_fitrah`
--

INSERT INTO `zakat_fitrah` (`id_zakat_fitrah`, `tanggal`, `id_kepala`, `alamat`, `jumlah_jiwa`, `harga_beras`, `total_zakat`, `id_user`, `created_at`, `jenis_zakat`, `keterangan`) VALUES
(2, '2026-02-23', NULL, NULL, 6, '200.00', '1200.00', NULL, '2026-02-23 01:31:09', 'Zakat Fitrah', 'contoh'),
(4, '2026-02-23', NULL, NULL, 5, '200.00', '1000.00', NULL, '2026-02-23 02:53:26', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `zakat_fitrah_anggota`
--

CREATE TABLE `zakat_fitrah_anggota` (
  `id_anggota` int NOT NULL,
  `id_zakat_fitrah` int NOT NULL,
  `nama_anggota` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zakat_fitrah_anggota`
--

INSERT INTO `zakat_fitrah_anggota` (`id_anggota`, `id_zakat_fitrah`, `nama_anggota`, `created_at`) VALUES
(14, 2, 'fauzil', '2026-02-23 02:20:05'),
(15, 2, 'qw', '2026-02-23 02:20:05'),
(16, 2, 'fg', '2026-02-23 02:20:05'),
(17, 2, 'dr', '2026-02-23 02:20:05'),
(18, 2, 'yiy', '2026-02-23 02:20:05'),
(19, 2, 'tuty', '2026-02-23 02:20:05'),
(27, 4, 'aldi', '2026-02-23 02:53:41'),
(28, 4, 'vg', '2026-02-23 02:53:41'),
(29, 4, 'gdd', '2026-02-23 02:53:41'),
(30, 4, 'hdh', '2026-02-23 02:53:41'),
(31, 4, 'itr', '2026-02-23 02:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `zakat_mal`
--

CREATE TABLE `zakat_mal` (
  `id_zakat_mal` int NOT NULL,
  `tanggal` date NOT NULL,
  `nama_pembayar` varchar(100) NOT NULL,
  `jenis_harta` varchar(100) NOT NULL,
  `nilai_harta` decimal(15,2) NOT NULL,
  `nisab` decimal(15,2) NOT NULL,
  `total_zakat` decimal(15,2) NOT NULL,
  `id_user` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `infak`
--
ALTER TABLE `infak`
  ADD PRIMARY KEY (`id_infak`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `kas_masjid`
--
ALTER TABLE `kas_masjid`
  ADD PRIMARY KEY (`id_kas`);

--
-- Indexes for table `kepala_keluarga`
--
ALTER TABLE `kepala_keluarga`
  ADD PRIMARY KEY (`id_kepala`);

--
-- Indexes for table `penyaluran_dana`
--
ALTER TABLE `penyaluran_dana`
  ADD PRIMARY KEY (`id_penyaluran`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `zakat_fitrah`
--
ALTER TABLE `zakat_fitrah`
  ADD PRIMARY KEY (`id_zakat_fitrah`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `fk_zakat_kepala` (`id_kepala`);

--
-- Indexes for table `zakat_fitrah_anggota`
--
ALTER TABLE `zakat_fitrah_anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD KEY `id_zakat_fitrah` (`id_zakat_fitrah`);

--
-- Indexes for table `zakat_mal`
--
ALTER TABLE `zakat_mal`
  ADD PRIMARY KEY (`id_zakat_mal`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `infak`
--
ALTER TABLE `infak`
  MODIFY `id_infak` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_masjid`
--
ALTER TABLE `kas_masjid`
  MODIFY `id_kas` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kepala_keluarga`
--
ALTER TABLE `kepala_keluarga`
  MODIFY `id_kepala` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `penyaluran_dana`
--
ALTER TABLE `penyaluran_dana`
  MODIFY `id_penyaluran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zakat_fitrah`
--
ALTER TABLE `zakat_fitrah`
  MODIFY `id_zakat_fitrah` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `zakat_fitrah_anggota`
--
ALTER TABLE `zakat_fitrah_anggota`
  MODIFY `id_anggota` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `zakat_mal`
--
ALTER TABLE `zakat_mal`
  MODIFY `id_zakat_mal` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `infak`
--
ALTER TABLE `infak`
  ADD CONSTRAINT `infak_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `zakat_fitrah`
--
ALTER TABLE `zakat_fitrah`
  ADD CONSTRAINT `fk_zakat_kepala` FOREIGN KEY (`id_kepala`) REFERENCES `kepala_keluarga` (`id_kepala`) ON DELETE CASCADE,
  ADD CONSTRAINT `zakat_fitrah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `zakat_fitrah_anggota`
--
ALTER TABLE `zakat_fitrah_anggota`
  ADD CONSTRAINT `zakat_fitrah_anggota_ibfk_1` FOREIGN KEY (`id_zakat_fitrah`) REFERENCES `zakat_fitrah` (`id_zakat_fitrah`) ON DELETE CASCADE;

--
-- Constraints for table `zakat_mal`
--
ALTER TABLE `zakat_mal`
  ADD CONSTRAINT `zakat_mal_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
