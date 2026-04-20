-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 08:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','kadis','bidang','pekerja') NOT NULL,
  `divisi` enum('Umum','Bina Marga','Cipta Karya') DEFAULT 'Umum',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `role`, `divisi`, `created_at`) VALUES
(1, 'admin', 'admin123', 'Administrator DPUTR', 'admin', 'Umum', '2026-01-04 03:33:59'),
(2, 'kadis', 'kadis123', 'Bpk. Kepala Dinas', 'kadis', 'Umum', '2026-01-04 03:33:59'),
(3, 'kabid_bm', 'bm123', 'Kabid Bina Marga', 'bidang', 'Bina Marga', '2026-01-04 03:33:59'),
(4, 'kabid_ck', 'ck123', 'Kabid Cipta Karya', 'bidang', 'Cipta Karya', '2026-01-04 03:33:59'),
(5, 'pekerja_jalan', 'kerja123', 'Mang Ujang Aspal', 'pekerja', 'Bina Marga', '2026-01-04 03:33:59'),
(6, 'pekerja_gedung', 'kerja123', 'Mas Joko Tukang', 'pekerja', 'Cipta Karya', '2026-01-04 03:33:59'),
(8, 'gibs', 'gib123', 'gibran', 'pekerja', 'Cipta Karya', '2026-01-04 08:12:07'),
(9, 'Salman', 'Salmanbm', 'Muhammad Salman', 'pekerja', 'Bina Marga', '2026-01-05 03:28:09'),
(11, 'Syauqi', 'SyauqiAdmin', 'Ahmad Syauqi Mubarok', 'admin', 'Umum', '2026-01-05 04:08:47'),
(12, 'Supriyanto', 'SupriyantoCK', 'Supriyanto rahadi', 'pekerja', 'Cipta Karya', '2026-01-06 03:53:48');

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
  `status` enum('Pending','Proses','Menunggu Validasi','Selesai','Ditolak') DEFAULT 'Pending',
  `id_pekerja` int(11) DEFAULT NULL,
  `tgl_selesai` datetime DEFAULT NULL,
  `foto_perbaikan` text DEFAULT NULL,
  `catatan_petugas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaduan`
--

INSERT INTO `pengaduan` (`id`, `nomor_tiket`, `nama_pelapor`, `jenis_aset`, `lokasi_aset`, `keterangan`, `bukti_foto`, `tanggal_lapor`, `status`, `id_pekerja`, `tgl_selesai`, `foto_perbaikan`, `catatan_petugas`) VALUES
(2, 'TKT-99102', 'Siti Aminah', 'Gedung', 'Aula Kecamatan', 'Atap bocor saat hujan deras', 'atap_bocor.jpg', '2025-12-02 02:30:00', 'Proses', NULL, NULL, NULL, NULL),
(8, 'TKT-20260106-9062', 'Asti Purnama', 'Gedung', 'Syasadjsasjd', 'dfaea', '20260106_695cfb1a40ada_0.png', '2026-01-06 12:07:54', 'Selesai', 12, '2026-01-06 19:41:16', 'selesai_1767703235_8.jpeg', 'apalah\r\n'),
(9, 'TKT-20260106-9770', 'Muhammed Avdol', 'Gedung', 'sdawdas', 'sdsdaw', '20260106_695cff1b21cce_0.jpeg', '2026-01-06 12:24:59', 'Selesai', NULL, NULL, NULL, NULL),
(10, 'TKT-20260111-6800', 'Muhammed Avdol', 'Drainase', 'fsdf', 'sesf', '20260111_696315de40a43_0.jpeg', '2026-01-11 03:15:42', 'Proses', NULL, NULL, NULL, NULL),
(11, 'TKT-20260111-7984', 'jamal', 'Drainase', 'Jl.Prambanan no.2 ', 'Selokan area kuningan Jebol Perlu diperbaiki', '20260111_69633b4c0d3af_0.png,20260111_69633b4c0ddb3_1.jpg,20260111_69633b4c0e10a_2.jpg', '2026-01-11 05:55:26', 'Pending', NULL, NULL, NULL, NULL),
(12, 'TKT-20260111-3584', 'xcv', 'Jalan', 'cvx', 'xcv', '20260111_69633cf53e062_0.jpg,20260111_69633cf53e919_1.jpg', '2026-01-11 06:02:29', 'Pending', NULL, NULL, NULL, NULL),
(13, 'TKT-20260111-3420', 'Asti Purnama', 'Jembatan', 'ghftgh', 'fgfg', '20260111_69635bf6ec45d_0.png,20260111_69635bf971d59_1.png', '2026-01-11 08:14:49', 'Pending', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sistem`
--

CREATE TABLE `pengaturan_sistem` (
  `id` int(11) NOT NULL,
  `app_name` varchar(100) DEFAULT 'SIJAKA',
  `maintenance_mode` tinyint(1) DEFAULT 0,
  `theme` varchar(20) DEFAULT 'light',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_sistem`
--

INSERT INTO `pengaturan_sistem` (`id`, `app_name`, `maintenance_mode`, `theme`, `updated_at`) VALUES
(1, 'SIJAKA', 1, 'light', '2026-01-12 07:48:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pengaduan`
--
ALTER TABLE `pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_tiket` (`nomor_tiket`),
  ADD KEY `status` (`status`),
  ADD KEY `id_pekerja` (`id_pekerja`);

--
-- Indexes for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pengaduan`
--
ALTER TABLE `pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
