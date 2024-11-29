-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 29, 2024 at 05:48 AM
-- Server version: 8.0.39
-- PHP Version: 8.2.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absentot`
--

-- --------------------------------------------------------

--
-- Table structure for table `perizinan`
--

CREATE TABLE `perizinan` (
  `kode_asisten` varchar(10) NOT NULL,
  `izin_awal` tinyint(1) DEFAULT '0',
  `izin_akhir` tinyint(1) DEFAULT '0',
  `izin_telat` tinyint(1) DEFAULT '0',
  `izin_tidak_menghadiri` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `perizinan`
--

INSERT INTO `perizinan` (`kode_asisten`, `izin_awal`, `izin_akhir`, `izin_telat`, `izin_tidak_menghadiri`) VALUES
('ACC', 0, 0, 1, 0),
('ALL', 0, 1, 0, 0),
('ALY', 0, 0, 0, 1),
('ARC', 0, 1, 0, 0),
('ARG', 0, 1, 0, 0),
('AUL', 0, 0, 1, 0),
('BAY', 0, 1, 0, 0),
('CHZ', 0, 0, 1, 0),
('CLA', 0, 1, 0, 0),
('DAN', 0, 0, 1, 0),
('DAR', 0, 0, 1, 0),
('DYS', 0, 0, 1, 0),
('EKA', 0, 1, 0, 0),
('FAV', 0, 0, 1, 0),
('FLO', 0, 1, 1, 0),
('FYN', 0, 0, 1, 0),
('GAN', 0, 1, 0, 0),
('GND', 0, 1, 0, 0),
('JFT', 0, 0, 0, 1),
('LEX', 0, 1, 0, 0),
('MHZ', 0, 1, 0, 0),
('MIT', 0, 1, 0, 0),
('NAI', 0, 1, 0, 0),
('NFB', 0, 0, 1, 0),
('NUE', 0, 0, 1, 0),
('OIL', 0, 1, 0, 0),
('ONE', 0, 0, 1, 0),
('RAF', 0, 1, 0, 0),
('RAP', 0, 0, 1, 0),
('RAR', 0, 0, 1, 0),
('RDJ', 0, 0, 1, 0),
('REL', 0, 1, 0, 0),
('RIZ', 0, 0, 0, 1),
('RYN', 0, 0, 1, 0),
('RZE', 0, 0, 1, 0),
('SHA', 0, 1, 0, 0),
('SNI', 0, 0, 1, 0),
('SYW', 0, 0, 1, 0),
('TGH', 0, 1, 0, 0),
('TIP', 0, 0, 0, 1),
('TNT', 0, 1, 0, 0),
('TRA', 0, 1, 0, 0),
('UKI', 0, 1, 0, 0),
('UZY', 0, 0, 0, 1),
('WGG', 0, 1, 0, 0),
('WLN', 0, 0, 1, 0),
('ZAI', 0, 1, 1, 0),
('ZEN', 0, 1, 1, 0),
('ZIN', 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `kode_asisten` varchar(10) NOT NULL,
  `absen_awal` tinyint(1) DEFAULT '0',
  `jam_absen_awal` time DEFAULT NULL,
  `keterangan_awal` varchar(20) DEFAULT NULL,
  `absen_akhir` tinyint(1) DEFAULT '0',
  `jam_absen_akhir` time DEFAULT NULL,
  `keterangan_akhir` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`kode_asisten`, `absen_awal`, `jam_absen_awal`, `keterangan_awal`, `absen_akhir`, `jam_absen_akhir`, `keterangan_akhir`) VALUES
('AAA', 1, '16:47:00', 'Tepat waktu', 0, NULL, NULL),
('ACC', 1, '16:43:00', 'Tepat waktu', 1, '20:34:00', NULL),
('AKA', 0, NULL, NULL, 0, NULL, NULL),
('AKI', 1, '15:36:00', 'Tepat waktu', 1, '20:33:00', NULL),
('AKK', 1, '15:43:00', 'Tepat waktu', 1, '20:33:00', NULL),
('ALD', 1, '16:38:00', 'Tepat waktu', 1, '20:41:00', NULL),
('ALL', 1, '15:42:00', 'Tepat waktu', 0, NULL, NULL),
('ALY', 1, '16:36:00', 'Tepat waktu', 0, NULL, NULL),
('AMG', 1, '16:37:00', 'Tepat waktu', 0, NULL, NULL),
('ARC', 1, '16:10:00', 'Tepat waktu', 0, NULL, NULL),
('ARG', 1, '17:15:00', 'Lambat', 0, NULL, NULL),
('ARZ', 1, '15:41:00', 'Tepat waktu', 0, NULL, NULL),
('AUL', 1, '18:19:00', 'Lambat', 1, '20:33:00', NULL),
('BAY', 1, '16:20:00', 'Tepat waktu', 0, NULL, NULL),
('BIL', 1, '16:31:00', 'Tepat waktu', 1, '20:40:00', NULL),
('BRI', 0, NULL, NULL, 0, NULL, NULL),
('BUS', 1, '15:40:00', 'Tepat waktu', 1, '20:32:00', NULL),
('CHZ', 1, '15:47:00', 'Tepat waktu', 1, '20:34:00', NULL),
('CLA', 0, NULL, NULL, 0, NULL, NULL),
('CYN', 1, '16:31:00', 'Tepat waktu', 1, '20:32:00', NULL),
('DAN', 1, '16:30:00', 'Tepat waktu', 1, '20:35:00', NULL),
('DAR', 1, '16:21:00', 'Tepat waktu', 1, '20:33:00', NULL),
('DAZ', 0, NULL, NULL, 0, NULL, NULL),
('DEY', 1, '20:39:00', 'Lambat', 1, '20:37:00', NULL),
('DHY', 1, '17:15:00', 'Lambat', 1, '20:38:00', NULL),
('DNR', 0, NULL, NULL, 0, NULL, NULL),
('DPR', 1, '17:09:00', 'Lambat', 0, NULL, NULL),
('DUN', 1, '15:49:00', 'Tepat waktu', 1, '20:36:00', NULL),
('DYS', 1, '16:51:00', 'Tepat waktu', 1, '20:34:00', NULL),
('EKA', 1, '16:29:00', 'Tepat waktu', 1, '20:32:00', NULL),
('EZL', 1, '18:12:00', 'Lambat', 1, '20:37:00', NULL),
('FAV', 1, '18:26:00', 'Lambat', 1, '20:33:00', NULL),
('FAZ', 1, '16:47:00', 'Tepat waktu', 0, NULL, NULL),
('FLO', 1, '16:51:00', 'Tepat waktu', 1, '20:34:00', NULL),
('FYN', 1, '16:33:00', 'Tepat waktu', 1, '20:32:00', NULL),
('GAN', 1, '16:36:00', 'Tepat waktu', 0, NULL, NULL),
('GND', 1, '17:09:00', 'Lambat', 0, NULL, NULL),
('GUS', 1, '15:44:00', 'Tepat waktu', 1, '20:32:00', NULL),
('IAN', 1, '16:10:00', 'Tepat waktu', 1, '20:32:00', NULL),
('ION', 1, '16:36:00', 'Tepat waktu', 1, '20:35:00', NULL),
('JFT', 1, '17:03:00', 'Lambat', 1, '20:32:00', NULL),
('JIN', 1, '17:03:00', 'Lambat', 0, NULL, NULL),
('LEX', 1, '16:20:00', 'Tepat waktu', 0, NULL, NULL),
('LLY', 1, '16:59:00', 'Tepat waktu', 0, NULL, NULL),
('MAS', 1, '16:47:00', 'Tepat waktu', 1, '20:33:00', NULL),
('MHZ', 1, '15:40:00', 'Tepat waktu', 0, NULL, NULL),
('MIT', 1, '16:34:00', 'Tepat waktu', 0, NULL, NULL),
('NAI', 1, '16:46:00', 'Tepat waktu', 1, '20:33:00', NULL),
('NFB', 0, NULL, NULL, 1, '20:35:00', NULL),
('NOE', 1, '16:07:00', 'Tepat waktu', 1, '20:37:00', NULL),
('NST', 0, NULL, NULL, 0, NULL, NULL),
('NTR', 0, NULL, NULL, 0, NULL, NULL),
('NUE', 1, '18:12:00', 'Lambat', 1, '20:34:00', NULL),
('OIL', 0, NULL, NULL, 0, NULL, NULL),
('ONE', 1, '17:09:00', 'Lambat', 1, '20:33:00', NULL),
('PER', 0, NULL, NULL, 0, NULL, NULL),
('RAD', 0, NULL, NULL, 0, NULL, NULL),
('RAF', 1, '17:17:00', 'Lambat', 1, '20:33:00', NULL),
('RAP', 1, '16:54:00', 'Tepat waktu', 0, NULL, NULL),
('RAR', 1, '18:12:00', 'Lambat', 1, '20:34:00', NULL),
('RDJ', 1, '16:51:00', 'Tepat waktu', 1, '20:34:00', NULL),
('REL', 1, '16:44:00', 'Tepat waktu', 1, '20:34:00', NULL),
('RIZ', 1, '16:41:00', 'Tepat waktu', 1, '20:32:00', NULL),
('RYN', 1, '18:25:00', 'Lambat', 1, '20:33:00', NULL),
('RYU', 1, '15:37:00', 'Tepat waktu', 1, '20:33:00', NULL),
('RZE', 1, '16:36:00', 'Tepat waktu', 1, '20:33:00', NULL),
('SAM', 1, '16:35:00', 'Tepat waktu', 0, NULL, NULL),
('SHA', 1, '16:10:00', 'Tepat waktu', 0, NULL, NULL),
('SNI', 1, '18:19:00', 'Lambat', 1, '20:32:00', NULL),
('SOH', 1, '16:56:00', 'Tepat waktu', 1, '20:35:00', NULL),
('SSS', 1, '16:52:00', 'Tepat waktu', 1, '20:33:00', NULL),
('SYW', 1, '16:19:00', 'Tepat waktu', 1, '20:38:00', NULL),
('TAN', 1, '16:47:00', 'Tepat waktu', 1, '20:37:00', NULL),
('TGH', 1, '16:13:00', 'Tepat waktu', 1, '20:35:00', NULL),
('THI', 0, NULL, NULL, 0, NULL, NULL),
('TIN', 0, NULL, NULL, 0, NULL, NULL),
('TIP', 1, '16:41:00', 'Tepat waktu', 1, '20:32:00', NULL),
('TNT', 1, '16:29:00', 'Tepat waktu', 0, NULL, NULL),
('TRA', 1, '16:21:00', 'Tepat waktu', 1, '20:36:00', NULL),
('UKI', 1, '17:03:00', 'Lambat', 1, '20:34:00', NULL),
('UZY', 1, '18:11:00', 'Lambat', 1, '20:33:00', NULL),
('VAL', 1, '16:21:00', 'Tepat waktu', 1, '20:35:00', NULL),
('VIS', 1, '16:37:00', 'Tepat waktu', 1, '20:35:00', NULL),
('WGG', 1, '17:15:00', 'Lambat', 1, '20:33:00', NULL),
('WLN', 1, '16:54:00', 'Tepat waktu', 1, '20:38:00', NULL),
('ZAI', 1, '15:45:00', 'Tepat waktu', 0, NULL, NULL),
('ZEN', 1, '16:25:00', 'Tepat waktu', 1, '20:33:00', NULL),
('ZIN', 1, '16:20:00', 'Tepat waktu', 1, '20:33:00', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `perizinan`
--
ALTER TABLE `perizinan`
  ADD PRIMARY KEY (`kode_asisten`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`kode_asisten`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
