-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 04, 2026 at 03:51 AM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sijaka`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaduan`
--

CREATE TABLE `pengaduan` (
  `id` int(11) NOT NULL,
  `nomor_tiket` varchar(20) NOT NULL,
  `nama_pelapor` varchar(100) NOT NULL,
  `jenis_aset` enum('Jalan','Jembatan','Drainase','Gedung','Lainnya') NOT NULL,
  `lokasi_aset` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `bukti_foto` text NOT NULL,
  `tanggal_lapor` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Proses','Selesai','Ditolak') DEFAULT 'Pending',
  `id_pekerja` int(11) DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `foto_perbaikan` text DEFAULT NULL,
  `catatan_petugas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `nomor_tiket`, `nama_pelapor`, `jenis_aset`, `lokasi_aset`, `keterangan`, `bukti_foto`, `tanggal_lapor`, `status`, `id_pekerja`, `tgl_selesai`, `foto_perbaikan`, `catatan_petugas`) VALUES
(1, 'TKT-88219', 'Budi Santoso', 'Jalan', 'Jl. Raya Ciawigebang No. 4', 'Lubang besar sedalam 10cm bahaya buat motor', 'jalan_rusak.jpg', '2025-12-01 01:00:00', 'Pending', NULL, NULL, NULL, NULL),
(2, 'TKT-99102', 'Siti Aminah', 'Gedung', 'Aula Kecamatan', 'Atap bocor saat hujan deras', 'atap_bocor.jpg', '2025-12-02 02:30:00', 'Proses', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','kadis','bidang','pekerja') NOT NULL,
  `divisi` enum('Umum','Bina Marga','Cipta Karya') DEFAULT 'Umum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `divisi`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Administrator DPUTR', 'admin', 'Umum', '2026-01-04 03:33:59'),
(2, 'kadis', 'kadis123', 'Bpk. Kepala Dinas', 'kadis', 'Umum', '2026-01-04 03:33:59'),
(3, 'kabid_bm', 'bm123', 'Kabid Bina Marga', 'bidang', 'Bina Marga', '2026-01-04 03:33:59'),
(4, 'kabid_ck', 'ck123', 'Kabid Cipta Karya', 'bidang', 'Cipta Karya', '2026-01-04 03:33:59'),
(5, 'pekerja_jalan', 'kerja123', 'Mang Ujang Aspal', 'pekerja', 'Bina Marga', '2026-01-04 03:33:59'),
(6, 'pekerja_gedung', 'kerja123', 'Mas Joko Tukang', 'pekerja', 'Cipta Karya', '2026-01-04 03:33:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_tiket` (`nomor_tiket`),
  ADD KEY `status` (`status`),
  ADD KEY `id_pekerja` (`id_pekerja`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
