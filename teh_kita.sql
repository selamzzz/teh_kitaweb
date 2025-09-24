-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 24, 2025 at 12:17 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teh kita`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail`
--

CREATE TABLE `tb_detail` (
  `id_detail` int NOT NULL,
  `id_transaksi` int NOT NULL,
  `id_produk` int NOT NULL,
  `jumlah` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_detail`
--

INSERT INTO `tb_detail` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`) VALUES
(1, 1, 86, 2),
(2, 2, 77, 2),
(3, 2, 80, 2),
(4, 3, 85, 1),
(5, 3, 87, 2),
(6, 4, 89, 4),
(7, 5, 79, 1),
(8, 6, 123, 2),
(9, 7, 83, 1);

--
-- Triggers `tb_detail`
--
DELIMITER $$
CREATE TRIGGER `kurangi_stok` AFTER INSERT ON `tb_detail` FOR EACH ROW BEGIN
    UPDATE tb_produk
    SET stok = stok - NEW.jumlah
    WHERE id = NEW.id_produk;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Original Tea-Varian Tea'),
(2, 'Milky Series Varian Rasa');

-- --------------------------------------------------------

--
-- Table structure for table `tb_produk`
--

CREATE TABLE `tb_produk` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `harga` int NOT NULL,
  `stok` int NOT NULL,
  `foto` text,
  `deskripsi` text,
  `id_kategori` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_produk`
--

INSERT INTO `tb_produk` (`id`, `nama`, `harga`, `stok`, `foto`, `deskripsi`, `id_kategori`) VALUES
(77, 'JASMINE TEA', 2500, 968, '1756652438_Teh Ori.png', 'Rasa Original Teh Yang Khas Dan Menyegarkan ', 1),
(79, 'LEMON TEA', 2500, 997, '1756693974_lemontea.png', 'Perpaduan Antara Teh dan Lemon Yang Menyegarkan', 1),
(80, 'STRAWBERY TEA', 2500, 998, '1756694073_strawberytea.png', 'Perpaduan Rasa Teh Deengan Strawbery Yang Meneyegarkan', 1),
(81, 'LECY TEA', 2500, 100, '1756694198_lecytea.png', 'Perpaduan Rasa Teh  Original Dan Caampuran Rasa Lecy Yang Belance', 1),
(82, 'APLE TEA', 2500, 100, '1756694277_apletea.png', 'Perpaduan Antara Teh Ori Dan Aple Yang Menyegarkan', 1),
(83, 'THAITEA ORIGINAL', 10000, 99, '1756816755_thaiteaori.jpg', 'Perpaduan antara teh dengan creeamy nya susu', 2),
(84, 'THAITEA CHEESE', 10000, 1000, '1756816809_thaitechese.jpg', 'Perpaduan antara teh dan susu dibalut dengan toping cream chese ', 2),
(85, 'THAITEA OREO', 10000, 99, '1756816874_thaiteaoreo.jpg', 'Perpaduan antara teh dan susu dengan di balut taburan oreo', 2),
(86, 'THAITEA  CHOCO', 10000, 98, '1756816935_thaiteachoco.jpg', 'Perpaduan antara teh dan susu dengan di balut taburan bubuk choco', 2),
(87, 'GRENTEA ORIGINAL', 10000, 98, '1756817005_grenteaori.jpg', 'Perpaduan antara teh hiaju dan susu yang enak', 2),
(88, 'GRENTEA CHEESE', 10000, 100, '1756817075_grenteachese.jpg', 'Perpaduan antara teh hijau dan susu di tambah dengan cheese cream', 2),
(89, 'GRENTEA OREO', 10000, 96, '1756817133_grenteaoreo.png', 'Perpaduan antara teh hijau dan susu di tambah dengan taburan oreo   ', 2),
(92, 'GRENTEA CHOCO', 10000, 1000, '1757404531_grenteachoco.jpg', 'Perpaduan Antara Teh Dan Bubuk Choco Yang Manis Nagih ', 2),
(93, 'MATCHA', 10000, 1000, '1757421043_matchaori.jpg', 'Rasa matcha yang menyegarkan enk bgt sumpah ya', 2),
(116, 'RED VELVET', 10000, 1000, '1757692244_redvelvet.jpg', 'enaak nyoo enakkkk', 2),
(123, 'SIGNATURE COFE', 10000, 8, '1757703030_kopisusu.jpg', 'yareuuu enakkkk', 2),
(126, 'VANILA BLUE', 10000, 10, '1758503883_vanilablue.jpg', 'enak bangedd aseli', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int NOT NULL,
  `id_pelanggan` int NOT NULL,
  `tanggal` datetime NOT NULL,
  `total_harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id_transaksi`, `id_pelanggan`, `tanggal`, `total_harga`) VALUES
(1, 2, '2025-09-12 19:56:54', 20000),
(2, 2, '2025-09-13 02:05:22', 10000),
(3, 2, '2025-09-13 11:47:25', 30000),
(4, 11, '2025-09-17 08:19:34', 40000),
(5, 11, '2025-09-17 08:37:46', 2500),
(6, 11, '2025-09-17 10:08:16', 20000),
(7, 12, '2025-09-17 11:03:16', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  `role` enum('admin','pelanggan') NOT NULL DEFAULT 'pelanggan',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id`, `nama`, `email`, `foto_profil`, `username`, `password`, `hp`, `alamat`, `role`, `created`) VALUES
(1, 'adminteh', 'adminteh@gmail.com', 'uploads/profil/admin_1.jpg', 'adminteh', 'admin123', '085921903893', 'Kantor Pusat', 'admin', '2025-08-13 03:15:16'),
(2, 'moch sultan', 'sultan@gmail.com', 'profile_2.jpg', 'sultan22', 'sultan22', '083843126564', 'Katiasa,Harjamukti Cirebon\r\n', 'pelanggan', '2025-08-13 03:24:23'),
(11, 'sela mutiara', 'sela@gmail.com', '', 'sela22', 'sela22', '085921903893', 'Attarbiyyah 1', 'pelanggan', '2025-09-17 01:19:12'),
(12, 'dwi cantik', 'dwi@gmail.com', '', 'dwi', '123', '08886789809', 'smk 1', 'pelanggan', '2025-09-17 04:02:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_detail`
--
ALTER TABLE `tb_detail`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produk_kategori` (`id_kategori`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_detail`
--
ALTER TABLE `tb_detail`
  MODIFY `id_detail` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tb_produk`
--
ALTER TABLE `tb_produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_produk`
--
ALTER TABLE `tb_produk`
  ADD CONSTRAINT `fk_produk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `tb_kategori` (`id_kategori`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
