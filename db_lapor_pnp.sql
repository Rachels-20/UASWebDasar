-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 03:17 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_lapor_pnp`
--

-- --------------------------------------------------------

--
-- Table structure for table `rachel_kategori`
--

CREATE TABLE `rachel_kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rachel_kategori`
--

INSERT INTO `rachel_kategori` (`id`, `nama_kategori`, `deskripsi`, `created_at`) VALUES
(1, 'Fasilitas', NULL, '2025-06-26 17:48:45'),
(2, 'Layanan Kampus', NULL, '2025-06-26 17:48:54'),
(3, 'Alat Belajar', NULL, '2025-06-26 17:49:08'),
(4, 'Perilaku Mahasiswa', NULL, '2025-06-26 17:49:25');

-- --------------------------------------------------------

--
-- Table structure for table `rachel_log_status`
--

CREATE TABLE `rachel_log_status` (
  `id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `status` enum('Selesai','Ditolak','Diproses') NOT NULL,
  `catatan` text DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rachel_pengaduan`
--

CREATE TABLE `rachel_pengaduan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Diproses','Selesai','Ditolak') DEFAULT 'Menunggu',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rachel_pengaduan`
--

INSERT INTO `rachel_pengaduan` (`id`, `user_id`, `kategori_id`, `judul`, `isi`, `lampiran`, `status`, `created_at`, `updated_at`) VALUES
(2, 2, 1, 'WC Gedung E', 'Kran air wcnya tidak mau mati', NULL, 'Selesai', '2025-06-27 01:03:39', '2025-06-27 17:33:56'),
(3, 2, 3, 'Labor 308 Gedung E', 'Salah satu PC di Labor 308 di meja belakang isi ram hanya 2gb', NULL, 'Selesai', '2025-06-27 01:14:55', '2025-06-30 13:31:33'),
(4, 2, 2, 'Satpam', 'Satpamnya minus 1 Perparkiran. parkir depan masih kosong malah dilarang parkir', NULL, 'Selesai', '2025-06-27 01:28:43', '2025-06-27 17:33:44'),
(5, 2, 1, 'TestFasilitas', 'sa', NULL, 'Selesai', '2025-06-27 01:31:48', '2025-07-01 08:30:48'),
(6, 2, 3, 'TestFoto', 'foto', '685d9259ae401_imagelaptop.png', 'Selesai', '2025-06-27 01:32:57', '2025-06-27 01:34:03'),
(7, 2, 3, 'TestBesarUploadFoto', 'test', '685d949641ad7_image (9).jpg', 'Selesai', '2025-06-27 01:42:30', '2025-06-27 14:54:02'),
(8, 2, 3, 'test fitur diproses', 'test', NULL, 'Selesai', '2025-06-30 13:00:20', '2025-06-30 13:25:12'),
(9, 2, 3, 'test umpan balik diproses', 'dks', NULL, 'Ditolak', '2025-06-30 13:38:45', '2025-06-30 13:54:40'),
(10, 2, 3, 'test penyelesaian', 'saisas', NULL, 'Selesai', '2025-07-01 08:33:45', '2025-07-05 17:53:40'),
(11, 2, 3, 'test penyelesaian 2', 'ijijij', NULL, 'Selesai', '2025-07-01 08:33:59', '2025-07-01 08:38:48'),
(12, 2, 3, 'test lagi', 'xsaaxsc', NULL, 'Ditolak', '2025-07-01 11:07:46', '2025-07-05 17:53:16'),
(13, 2, 1, 'test pembaruan detail keterangan', 'ya', NULL, 'Selesai', '2025-07-05 16:23:57', '2025-07-05 16:27:37'),
(14, 2, 3, 'test final', 'final', '6868f2085f028_home.jpeg', 'Selesai', '2025-07-05 16:36:08', '2025-07-05 17:51:55'),
(15, 2, 2, 'test menunggu', 'menunggu', NULL, 'Menunggu', '2025-07-05 17:54:40', '2025-07-05 17:54:40'),
(16, 2, 3, 'test diproses', 'test diproses', NULL, 'Diproses', '2025-07-05 17:54:59', '2025-07-05 17:55:22'),
(17, 2, 3, 'final menunggu', 'menunggu', NULL, 'Menunggu', '2025-07-07 19:58:59', '2025-07-07 19:58:59'),
(18, 2, 3, 'final diproses', 'proses', NULL, 'Diproses', '2025-07-07 19:59:28', '2025-07-07 20:03:27'),
(19, 2, 3, 'final selesai dan lampiran', 'selesai lampiran', '686bc4e580e98_home.jpeg', 'Selesai', '2025-07-07 20:00:21', '2025-07-07 20:02:24');

-- --------------------------------------------------------

--
-- Table structure for table `rachel_tanggapan`
--

CREATE TABLE `rachel_tanggapan` (
  `id` int(11) NOT NULL,
  `pengaduan_id` int(11) NOT NULL,
  `responder_id` int(11) NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `catatan_admin_proses` text DEFAULT NULL,
  `catatan_admin_selesai` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rachel_tanggapan`
--

INSERT INTO `rachel_tanggapan` (`id`, `pengaduan_id`, `responder_id`, `tanggal`, `catatan_admin_proses`, `catatan_admin_selesai`) VALUES
(2, 3, 1, '2025-06-30 13:31:33', NULL, 'sudah ditambah ramnya'),
(3, 6, 1, '2025-06-27 01:34:03', NULL, NULL),
(4, 5, 1, '2025-07-01 08:30:48', NULL, 'terima kasih sudah kami perbaiki'),
(5, 7, 1, '2025-06-27 14:54:02', NULL, NULL),
(6, 4, 1, '2025-06-27 17:33:44', NULL, NULL),
(7, 2, 1, '2025-06-27 17:33:56', NULL, NULL),
(8, 8, 1, '2025-06-30 13:25:12', 'detailkan keterangan', 'sudah diperbaiki'),
(9, 9, 1, '2025-06-30 13:54:40', 'berikan keterangan', 'kurang lengkap, tambahkan keterangan dan foto sebagai bukti'),
(10, 11, 1, '2025-07-01 08:38:48', 'ffsfs', 'asacscsc'),
(11, 10, 1, '2025-07-05 17:53:40', 'akan lansung kami proses', 'test diproses-selesai'),
(12, 13, 1, '2025-07-05 16:27:37', 'ddsdks', 'makmxkaxmkamx'),
(13, 14, 1, '2025-07-05 17:51:55', NULL, 'test selesai'),
(14, 12, 1, '2025-07-05 17:53:16', NULL, 'test ditolak'),
(15, 16, 1, '2025-07-05 17:55:22', 'proses', NULL),
(16, 19, 1, '2025-07-07 20:02:24', 'proses', 'selesai'),
(17, 18, 1, '2025-07-07 20:03:27', 'proses', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rachel_users`
--

CREATE TABLE `rachel_users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','mahasiswa') DEFAULT 'mahasiswa',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rachel_users`
--

INSERT INTO `rachel_users` (`id`, `nama`, `email`, `password`, `level`, `created_at`) VALUES
(1, 'rachel', 'rachel@gmail.com', '$2y$10$PNVk8ZrKYCj/iHFyU4sN7.ElMNIo2KXgk/YkWYiPPi/FniVXncpl2', 'admin', '2025-06-25 19:14:21'),
(2, 'user', 'user@gmail.com', '$2y$10$pbs/qv/IG8Q92KYAn4G.DOFnn78tOMLrAapO8Z.HfoiPfSuxvNOy.', 'mahasiswa', '2025-06-26 02:26:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rachel_kategori`
--
ALTER TABLE `rachel_kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rachel_log_status`
--
ALTER TABLE `rachel_log_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`);

--
-- Indexes for table `rachel_pengaduan`
--
ALTER TABLE `rachel_pengaduan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `rachel_tanggapan`
--
ALTER TABLE `rachel_tanggapan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengaduan_id` (`pengaduan_id`),
  ADD KEY `responder_id` (`responder_id`);

--
-- Indexes for table `rachel_users`
--
ALTER TABLE `rachel_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rachel_kategori`
--
ALTER TABLE `rachel_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rachel_log_status`
--
ALTER TABLE `rachel_log_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rachel_pengaduan`
--
ALTER TABLE `rachel_pengaduan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `rachel_tanggapan`
--
ALTER TABLE `rachel_tanggapan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `rachel_users`
--
ALTER TABLE `rachel_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rachel_log_status`
--
ALTER TABLE `rachel_log_status`
  ADD CONSTRAINT `rachel_log_status_ibfk_1` FOREIGN KEY (`pengaduan_id`) REFERENCES `rachel_pengaduan` (`id`);

--
-- Constraints for table `rachel_pengaduan`
--
ALTER TABLE `rachel_pengaduan`
  ADD CONSTRAINT `rachel_pengaduan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `rachel_users` (`id`),
  ADD CONSTRAINT `rachel_pengaduan_ibfk_2` FOREIGN KEY (`kategori_id`) REFERENCES `rachel_kategori` (`id`);

--
-- Constraints for table `rachel_tanggapan`
--
ALTER TABLE `rachel_tanggapan`
  ADD CONSTRAINT `rachel_tanggapan_ibfk_1` FOREIGN KEY (`pengaduan_id`) REFERENCES `rachel_pengaduan` (`id`),
  ADD CONSTRAINT `rachel_tanggapan_ibfk_2` FOREIGN KEY (`responder_id`) REFERENCES `rachel_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
