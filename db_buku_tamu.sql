-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 01:48 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_buku_tamu`
--

-- --------------------------------------------------------

--
-- Table structure for table `apel_pagi`
--

CREATE TABLE `apel_pagi` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time NOT NULL,
  `status` enum('tepat_waktu','telat') COLLATE utf8mb4_unicode_ci NOT NULL,
  `telat_menit` int DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `apel_pagi`
--

INSERT INTO `apel_pagi` (`id`, `user_id`, `tanggal`, `jam_masuk`, `status`, `telat_menit`, `latitude`, `longitude`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`) VALUES
(1, 13, '2025-11-28', '15:07:19', 'telat', 457, -6.7263903, 108.5389041, NULL, NULL, NULL, '2025-11-28 08:07:19', '2025-11-28 08:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `bidang`
--

CREATE TABLE `bidang` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_bidang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bidang`
--

INSERT INTO `bidang` (`id`, `nama_bidang`, `deskripsi`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Sekretariat', 'Membantu Kepala Dinas dalam pembinaan dan pemberian layanan administrasi penyusunan perencanaan, penatausahaan, keuangan, sumber daya manusia Aparatur, kerumahtanggaan, arsip dan perpustakaan, organisasi dan tatalaksana, kerjasama, hubungan masyarakat, protokol, pengelolaan barang milik daerah/negara dan dokumentasi Dinas serta melaksanakan pengoordinasian penyusunan peraturan perundang-undangan dan bantuan hukum dalam penyelenggaraan tugas Dinas.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(2, 'Bidang Infrastruktur Teknologi Informasi dan Komunikasi', 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan domain, penatalaksanaan dan pengawasan serta sistem jaringan informatika.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(3, 'Bidang Layanan E-Government', 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan tata kelola e-government, pengembangan ekosistem e-government serta pengembangan aplikasi.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(4, 'Bidang Pengelolaan Informasi dan Komunikasi Publik', 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan dan layanan informasi, pengelolaan komunikasi serta hubungan masyarakat.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(5, 'Bidang Persandian dan Keamanan Informasi', 'Membantu Kepala Dinas dalam memimpin dan menyelenggarakan tugas urusan pemerintahan bidang persandian dan keamanan informasi meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan persandian, keamanan informasi serta layanan keamanan informasi.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(6, 'Bidang Statistik Sektoral', 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengumpulan dan analisis data, sumber daya statistik sektor serta layanan dan penyebarluasan data.', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:4:{s:1:\"a\";s:2:\"id\";s:1:\"b\";s:4:\"name\";s:1:\"c\";s:10:\"guard_name\";s:1:\"r\";s:5:\"roles\";}s:11:\"permissions\";a:51:{i:0;a:4:{s:1:\"a\";i:1;s:1:\"b\";s:10:\"users.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:1;a:4:{s:1:\"a\";i:2;s:1:\"b\";s:12:\"users.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:2;a:4:{s:1:\"a\";i:3;s:1:\"b\";s:12:\"users.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:3;a:4:{s:1:\"a\";i:4;s:1:\"b\";s:12:\"users.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:4;a:4:{s:1:\"a\";i:5;s:1:\"b\";s:10:\"roles.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:5;a:4:{s:1:\"a\";i:6;s:1:\"b\";s:12:\"roles.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:6;a:4:{s:1:\"a\";i:7;s:1:\"b\";s:12:\"roles.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:7;a:4:{s:1:\"a\";i:8;s:1:\"b\";s:12:\"roles.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:8;a:4:{s:1:\"a\";i:9;s:1:\"b\";s:16:\"permissions.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:9;a:4:{s:1:\"a\";i:10;s:1:\"b\";s:18:\"permissions.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:10;a:4:{s:1:\"a\";i:11;s:1:\"b\";s:18:\"permissions.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:11;a:4:{s:1:\"a\";i:12;s:1:\"b\";s:18:\"permissions.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:12;a:4:{s:1:\"a\";i:13;s:1:\"b\";s:12:\"pegawai.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:13;a:4:{s:1:\"a\";i:14;s:1:\"b\";s:14:\"pegawai.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:14;a:4:{s:1:\"a\";i:15;s:1:\"b\";s:14:\"pegawai.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:15;a:4:{s:1:\"a\";i:16;s:1:\"b\";s:14:\"pegawai.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:16;a:4:{s:1:\"a\";i:17;s:1:\"b\";s:18:\"pegawai.rapat.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:17;a:4:{s:1:\"a\";i:18;s:1:\"b\";s:11:\"bidang.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:18;a:4:{s:1:\"a\";i:19;s:1:\"b\";s:13:\"bidang.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:19;a:4:{s:1:\"a\";i:20;s:1:\"b\";s:13:\"bidang.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:20;a:4:{s:1:\"a\";i:21;s:1:\"b\";s:13:\"bidang.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:21;a:4:{s:1:\"a\";i:22;s:1:\"b\";s:12:\"jabatan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:22;a:4:{s:1:\"a\";i:23;s:1:\"b\";s:14:\"jabatan.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:23;a:4:{s:1:\"a\";i:24;s:1:\"b\";s:14:\"jabatan.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:24;a:4:{s:1:\"a\";i:25;s:1:\"b\";s:14:\"jabatan.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:25;a:4:{s:1:\"a\";i:26;s:1:\"b\";s:11:\"visits.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:26;a:4:{s:1:\"a\";i:27;s:1:\"b\";s:13:\"visits.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:27;a:4:{s:1:\"a\";i:28;s:1:\"b\";s:13:\"visits.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:28;a:4:{s:1:\"a\";i:29;s:1:\"b\";s:13:\"visits.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:29;a:4:{s:1:\"a\";i:30;s:1:\"b\";s:14:\"visits.approve\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:30;a:4:{s:1:\"a\";i:31;s:1:\"b\";s:13:\"visits.reject\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:31;a:4:{s:1:\"a\";i:32;s:1:\"b\";s:15:\"visits.checkout\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:2;}}i:32;a:4:{s:1:\"a\";i:33;s:1:\"b\";s:19:\"pegawai.visits.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:33;a:4:{s:1:\"a\";i:34;s:1:\"b\";s:22:\"pegawai.visits.details\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:34;a:4:{s:1:\"a\";i:35;s:1:\"b\";s:16:\"tamu.visits.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:35;a:4:{s:1:\"a\";i:36;s:1:\"b\";s:18:\"tamu.visits.create\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:36;a:4:{s:1:\"a\";i:37;s:1:\"b\";s:20:\"tamu.visits.checkout\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:37;a:4:{s:1:\"a\";i:38;s:1:\"b\";s:19:\"tamu.profile.update\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:4;}}i:38;a:4:{s:1:\"a\";i:39;s:1:\"b\";s:12:\"reports.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:39;a:4:{s:1:\"a\";i:40;s:1:\"b\";s:14:\"reports.export\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:40;a:4:{s:1:\"a\";i:41;s:1:\"b\";s:9:\"logs.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:41;a:4:{s:1:\"a\";i:42;s:1:\"b\";s:11:\"logs.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:42;a:4:{s:1:\"a\";i:43;s:1:\"b\";s:12:\"surveys.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:43;a:4:{s:1:\"a\";i:44;s:1:\"b\";s:14:\"surveys.delete\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:44;a:4:{s:1:\"a\";i:45;s:1:\"b\";s:10:\"rapat.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:45;a:4:{s:1:\"a\";i:46;s:1:\"b\";s:12:\"rapat.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:46;a:4:{s:1:\"a\";i:47;s:1:\"b\";s:12:\"rapat.invite\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:47;a:4:{s:1:\"a\";i:48;s:1:\"b\";s:11:\"rapat.rekap\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:48;a:4:{s:1:\"a\";i:49;s:1:\"b\";s:15:\"instansi.manage\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:2:{i:0;i:1;i:1;i:3;}}i:49;a:4:{s:1:\"a\";i:50;s:1:\"b\";s:9:\"tamu.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}i:50;a:4:{s:1:\"a\";i:51;s:1:\"b\";s:14:\"kunjungan.view\";s:1:\"c\";s:3:\"web\";s:1:\"r\";a:1:{i:0;i:1;}}}s:5:\"roles\";a:4:{i:0;a:3:{s:1:\"a\";i:1;s:1:\"b\";s:5:\"admin\";s:1:\"c\";s:3:\"web\";}i:1;a:3:{s:1:\"a\";i:3;s:1:\"b\";s:7:\"pegawai\";s:1:\"c\";s:3:\"web\";}i:2;a:3:{s:1:\"a\";i:2;s:1:\"b\";s:10:\"frontliner\";s:1:\"c\";s:3:\"web\";}i:3;a:3:{s:1:\"a\";i:4;s:1:\"b\";s:4:\"tamu\";s:1:\"c\";s:3:\"web\";}}}', 1764403087);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daftar_survey`
--

CREATE TABLE `daftar_survey` (
  `id` bigint UNSIGNED NOT NULL,
  `link_survey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` int NOT NULL DEFAULT '1',
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_logs`
--

CREATE TABLE `history_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `record_id` bigint UNSIGNED NOT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_logs`
--

INSERT INTO `history_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `reason`, `deleted_at`, `created_id`, `updated_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'created', 'bidang', 1, NULL, '{\"id\": 1, \"deskripsi\": \"Membantu Kepala Dinas dalam pembinaan dan pemberian layanan administrasi penyusunan perencanaan, penatausahaan, keuangan, sumber daya manusia Aparatur, kerumahtanggaan, arsip dan perpustakaan, organisasi dan tatalaksana, kerjasama, hubungan masyarakat, protokol, pengelolaan barang milik daerah/negara dan dokumentasi Dinas serta melaksanakan pengoordinasian penyusunan peraturan perundang-undangan dan bantuan hukum dalam penyelenggaraan tugas Dinas.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Sekretariat\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(2, NULL, 'created', 'bidang', 2, NULL, '{\"id\": 2, \"deskripsi\": \"Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan domain, penatalaksanaan dan pengawasan serta sistem jaringan informatika.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Bidang Infrastruktur Teknologi Informasi dan Komunikasi\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(3, NULL, 'created', 'bidang', 3, NULL, '{\"id\": 3, \"deskripsi\": \"Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan tata kelola e-government, pengembangan ekosistem e-government serta pengembangan aplikasi.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Bidang Layanan E-Government\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(4, NULL, 'created', 'bidang', 4, NULL, '{\"id\": 4, \"deskripsi\": \"Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan dan layanan informasi, pengelolaan komunikasi serta hubungan masyarakat.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Bidang Pengelolaan Informasi dan Komunikasi Publik\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(5, NULL, 'created', 'bidang', 5, NULL, '{\"id\": 5, \"deskripsi\": \"Membantu Kepala Dinas dalam memimpin dan menyelenggarakan tugas urusan pemerintahan bidang persandian dan keamanan informasi meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan persandian, keamanan informasi serta layanan keamanan informasi.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Bidang Persandian dan Keamanan Informasi\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(6, NULL, 'created', 'bidang', 6, NULL, '{\"id\": 6, \"deskripsi\": \"Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengumpulan dan analisis data, sumber daya statistik sektor serta layanan dan penyebarluasan data.\", \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_bidang\": \"Bidang Statistik Sektoral\"}', 'Aksi created pada tabel bidang', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(7, NULL, 'created', 'jabatan', 1, NULL, '{\"id\": 1, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Dinas Komunikasi, Informatika dan Statistik\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(8, NULL, 'created', 'jabatan', 2, NULL, '{\"id\": 2, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Sekretaris\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(9, NULL, 'created', 'jabatan', 3, NULL, '{\"id\": 3, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Bidang Persandian dan Keamanan Informasi\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(10, NULL, 'created', 'jabatan', 4, NULL, '{\"id\": 4, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Bidang Layanan E Government\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(11, NULL, 'created', 'jabatan', 5, NULL, '{\"id\": 5, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Bidang Statistik Sektoral\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(12, NULL, 'created', 'jabatan', 6, NULL, '{\"id\": 6, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Bidang Infrastruktur Teknologi Informasi dan Komunikasi\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(13, NULL, 'created', 'jabatan', 7, NULL, '{\"id\": 7, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Sub Bagian Umum dan Kepegawaian\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(14, NULL, 'created', 'jabatan', 8, NULL, '{\"id\": 8, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Kepala Sub Bagian Program dan Pelaporan\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(15, NULL, 'created', 'jabatan', 9, NULL, '{\"id\": 9, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pranata Komputer Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(16, NULL, 'created', 'jabatan', 10, NULL, '{\"id\": 10, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Sandiman Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(17, NULL, 'created', 'jabatan', 11, NULL, '{\"id\": 11, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Statistisi Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(18, NULL, 'created', 'jabatan', 12, NULL, '{\"id\": 12, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pranata Hubungan Masyarakat Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(19, NULL, 'created', 'jabatan', 13, NULL, '{\"id\": 13, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Analis Keuangan Pusat dan Daerah Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(20, NULL, 'created', 'jabatan', 14, NULL, '{\"id\": 14, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Analis Kebijakan Ahli Muda\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(21, NULL, 'created', 'jabatan', 15, NULL, '{\"id\": 15, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Penelaah Teknis Kebijakan\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(22, NULL, 'created', 'jabatan', 16, NULL, '{\"id\": 16, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Penata Layanan Operasional\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(23, NULL, 'created', 'jabatan', 17, NULL, '{\"id\": 17, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pengolah Data dan Informasi\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(24, NULL, 'created', 'jabatan', 18, NULL, '{\"id\": 18, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pranata Komputer Ahli Pertama\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(25, NULL, 'created', 'jabatan', 19, NULL, '{\"id\": 19, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Fasilitator Pemerintahan\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(26, NULL, 'created', 'jabatan', 20, NULL, '{\"id\": 20, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Surveyor Pemetaan Terampil\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(27, NULL, 'created', 'jabatan', 21, NULL, '{\"id\": 21, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pengadministrasi Perkantoran\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(28, NULL, 'created', 'jabatan', 22, NULL, '{\"id\": 22, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Pengelola Layanan Operasional\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(29, NULL, 'created', 'jabatan', 23, NULL, '{\"id\": 23, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Penata Hubungan Masyarakat Ahli Pertama\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(30, NULL, 'created', 'jabatan', 24, NULL, '{\"id\": 24, \"created_at\": \"2025-11-28 14:57:37\", \"updated_at\": \"2025-11-28 14:57:37\", \"nama_jabatan\": \"Operator Layanan Operasional\"}', 'Aksi created pada tabel jabatan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(31, NULL, 'created', 'instansi', 1, NULL, '{\"id\": 1, \"alias\": \"PEMKOT\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:37\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:37\", \"updated_id\": 1, \"nama_instansi\": \"KOTA CIREBON\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37'),
(32, NULL, 'created', 'instansi', 2, NULL, '{\"id\": 2, \"alias\": \"SETDA\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"SEKRETARIAT DAERAH\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(33, NULL, 'created', 'instansi', 3, NULL, '{\"id\": 3, \"alias\": \"SETWAN\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"SEKRETARIAT DPRD\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(34, NULL, 'created', 'instansi', 4, NULL, '{\"id\": 4, \"alias\": \"INSPEKTORAT\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"INSPEKTORAT DAERAH\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(35, NULL, 'created', 'instansi', 5, NULL, '{\"id\": 5, \"alias\": \"DISDIK\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PENDIDIKAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(36, NULL, 'created', 'instansi', 6, NULL, '{\"id\": 6, \"alias\": \"DINKES\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KESEHATAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(37, NULL, 'created', 'instansi', 7, NULL, '{\"id\": 7, \"alias\": \"DPUTR\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PEKERJAAN UMUM DAN TATA RUANG\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(38, NULL, 'created', 'instansi', 8, NULL, '{\"id\": 8, \"alias\": \"DPRKP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PERUMAHAN RAKYAT DAN KAWASAN PERMUKIMAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(39, NULL, 'created', 'instansi', 9, NULL, '{\"id\": 9, \"alias\": \"SATPOL-PP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"SATUAN POLISI PAMONG PRAJA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(40, NULL, 'created', 'instansi', 10, NULL, '{\"id\": 10, \"alias\": \"DPKP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(41, NULL, 'created', 'instansi', 11, NULL, '{\"id\": 11, \"alias\": \"DINSOS\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS SOSIAL\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(42, NULL, 'created', 'instansi', 12, NULL, '{\"id\": 12, \"alias\": \"DISNAKER\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS TENAGA KERJA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(43, NULL, 'created', 'instansi', 13, NULL, '{\"id\": 13, \"alias\": \"DKP3\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(44, NULL, 'created', 'instansi', 14, NULL, '{\"id\": 14, \"alias\": \"DLH\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS LINGKUNGAN HIDUP\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(45, NULL, 'created', 'instansi', 15, NULL, '{\"id\": 15, \"alias\": \"DISDUKCAPIL\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(46, NULL, 'created', 'instansi', 16, NULL, '{\"id\": 16, \"alias\": \"DP3APPKB\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(47, NULL, 'created', 'instansi', 17, NULL, '{\"id\": 17, \"alias\": \"DISHUB\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PERHUBUNGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(48, NULL, 'created', 'instansi', 18, NULL, '{\"id\": 18, \"alias\": \"DKIS\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(49, NULL, 'created', 'instansi', 19, NULL, '{\"id\": 19, \"alias\": \"DKUKMPP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KOPERASI, USAHA KECIL, MENENGAH, PERDAGANGAN DAN PERINDUSTRIAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(50, NULL, 'created', 'instansi', 20, NULL, '{\"id\": 20, \"alias\": \"DPMPTSP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(51, NULL, 'created', 'instansi', 21, NULL, '{\"id\": 21, \"alias\": \"DISBUDPAR\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS KEBUDAYAAN DAN PARIWISATA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(52, NULL, 'created', 'instansi', 22, NULL, '{\"id\": 22, \"alias\": \"DISPORA\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PEMUDA DAN OLAHRAGA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(53, NULL, 'created', 'instansi', 23, NULL, '{\"id\": 23, \"alias\": \"DISPUSIP\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"DINAS PERPUSTAKAAN DAN KEARSIPAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(54, NULL, 'created', 'instansi', 24, NULL, '{\"id\": 24, \"alias\": \"BAPPELITBANGDA\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"BADAN PERENCANAAN PEMBANGUNAN, PENELITIAN DAN PENGEMBANGAN DAERAH\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(55, NULL, 'created', 'instansi', 25, NULL, '{\"id\": 25, \"alias\": \"BPKPD\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"BADAN PENGELOLA KEUANGAN DAN PENDAPATAN DAERAH\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(56, NULL, 'created', 'instansi', 26, NULL, '{\"id\": 26, \"alias\": \"BKPSDM\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(57, NULL, 'created', 'instansi', 27, NULL, '{\"id\": 27, \"alias\": \"BPBD\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"BADAN PENANGGULANGAN BENCANA DAERAH\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(58, NULL, 'created', 'instansi', 28, NULL, '{\"id\": 28, \"alias\": \"BAKESBANGPOL\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"BADAN KESATUAN BANGSA DAN POLITIK\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(59, NULL, 'created', 'instansi', 29, NULL, '{\"id\": 29, \"alias\": \"KECHARJAMUKTI\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KECAMATAN HARJAMUKTI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(60, NULL, 'created', 'instansi', 30, NULL, '{\"id\": 30, \"alias\": \"KECLMHWUNGKUK\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KECAMATAN LEMAHWUNGKUK\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(61, NULL, 'created', 'instansi', 31, NULL, '{\"id\": 31, \"alias\": \"KECKEJAKSAN\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KECAMATAN KEJAKSAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(62, NULL, 'created', 'instansi', 32, NULL, '{\"id\": 32, \"alias\": \"KECKESAMBI\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KECAMATAN KESAMBI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(63, NULL, 'created', 'instansi', 33, NULL, '{\"id\": 33, \"alias\": \"KECPEKALIPAN\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KECAMATAN PEKALIPAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(64, NULL, 'created', 'instansi', 34, NULL, '{\"id\": 34, \"alias\": \"RSDGJ\", \"jenis\": \"instansi\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"RSD GUNUNG JATI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(65, NULL, 'created', 'instansi', 35, NULL, '{\"id\": 35, \"alias\": \"KELARGASUNYA\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN ARGASUNYA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(66, NULL, 'created', 'instansi', 36, NULL, '{\"id\": 36, \"alias\": \"KELDRAJAT\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN DRAJAT\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(67, NULL, 'created', 'instansi', 37, NULL, '{\"id\": 37, \"alias\": \"KELHARJAMUKTI\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN HARJAMUKTI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(68, NULL, 'created', 'instansi', 38, NULL, '{\"id\": 38, \"alias\": \"KELJAGASATRU\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN JAGASATRU\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(69, NULL, 'created', 'instansi', 39, NULL, '{\"id\": 39, \"alias\": \"KELKALIJAGA\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:38\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:38\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KALIJAGA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38'),
(70, NULL, 'created', 'instansi', 40, NULL, '{\"id\": 40, \"alias\": \"KELKARYAMULYA\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KARYAMULYA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(71, NULL, 'created', 'instansi', 41, NULL, '{\"id\": 41, \"alias\": \"KELKEBONBARU\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KEBON BARU\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(72, NULL, 'created', 'instansi', 42, NULL, '{\"id\": 42, \"alias\": \"KELKECAPI\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KECAPI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(73, NULL, 'created', 'instansi', 43, NULL, '{\"id\": 43, \"alias\": \"KELKEJAKSAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KEJAKSAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(74, NULL, 'created', 'instansi', 44, NULL, '{\"id\": 44, \"alias\": \"KELKESAMBI\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KESAMBI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(75, NULL, 'created', 'instansi', 45, NULL, '{\"id\": 45, \"alias\": \"KELKESENDEN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KESENDEN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(76, NULL, 'created', 'instansi', 46, NULL, '{\"id\": 46, \"alias\": \"KELKESEPUHAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN KESEPUHAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(77, NULL, 'created', 'instansi', 47, NULL, '{\"id\": 47, \"alias\": \"KELLARANGAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN LARANGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(78, NULL, 'created', 'instansi', 48, NULL, '{\"id\": 48, \"alias\": \"KELLMHWUNGKUK\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN LEMAHWUNGKUK\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(79, NULL, 'created', 'instansi', 49, NULL, '{\"id\": 49, \"alias\": \"KELPANJUNAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PANJUNAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(80, NULL, 'created', 'instansi', 50, NULL, '{\"id\": 50, \"alias\": \"KELPEGAMBIRAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PEGAMBIRAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(81, NULL, 'created', 'instansi', 51, NULL, '{\"id\": 51, \"alias\": \"KELPEKALANGAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PEKALANGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(82, NULL, 'created', 'instansi', 52, NULL, '{\"id\": 52, \"alias\": \"KELPEKALIPAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PEKALIPAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(83, NULL, 'created', 'instansi', 53, NULL, '{\"id\": 53, \"alias\": \"KELPEKIRINGAN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PEKIRINGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(84, NULL, 'created', 'instansi', 54, NULL, '{\"id\": 54, \"alias\": \"KELPULASAREN\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN PULASAREN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(85, NULL, 'created', 'instansi', 55, NULL, '{\"id\": 55, \"alias\": \"KELSUKAPURA\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN SUKAPURA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(86, NULL, 'created', 'instansi', 56, NULL, '{\"id\": 56, \"alias\": \"KELSUNYARAGI\", \"jenis\": \"kelurahan\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"KELURAHAN SUNYARAGI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(87, NULL, 'created', 'instansi', 57, NULL, '{\"id\": 57, \"alias\": \"PKMASTANAGARIB\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS ASTANAGARIB\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(88, NULL, 'created', 'instansi', 58, NULL, '{\"id\": 58, \"alias\": \"PKMCANGKOL\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS CANGKOL\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(89, NULL, 'created', 'instansi', 59, NULL, '{\"id\": 59, \"alias\": \"PKMDRAJAT\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS DRAJAT\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(90, NULL, 'created', 'instansi', 60, NULL, '{\"id\": 60, \"alias\": \"PKMGUNUNGSARI\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS GUNUNGSARI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(91, NULL, 'created', 'instansi', 61, NULL, '{\"id\": 61, \"alias\": \"PKMJAGASATRU\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS JAGASATRU\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(92, NULL, 'created', 'instansi', 62, NULL, '{\"id\": 62, \"alias\": \"PKMJALANKEMBANG\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS JALANKEMBANG\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(93, NULL, 'created', 'instansi', 63, NULL, '{\"id\": 63, \"alias\": \"PKMKALIJAGAPERMAI\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS KALIJAGAPERMAI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(94, NULL, 'created', 'instansi', 64, NULL, '{\"id\": 64, \"alias\": \"PKMKALITANJUNG\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS KALITANJUNG\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(95, NULL, 'created', 'instansi', 65, NULL, '{\"id\": 65, \"alias\": \"PKMKEJAKSAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS KEJAKSAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(96, NULL, 'created', 'instansi', 66, NULL, '{\"id\": 66, \"alias\": \"PKMKESAMBI\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS KESAMBI\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(97, NULL, 'created', 'instansi', 67, NULL, '{\"id\": 67, \"alias\": \"PKMKESUNEAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS KESUNEAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(98, NULL, 'created', 'instansi', 68, NULL, '{\"id\": 68, \"alias\": \"PKMLARANGAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS LARANGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(99, NULL, 'created', 'instansi', 69, NULL, '{\"id\": 69, \"alias\": \"PKMMAJASEM\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS MAJASEM\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(100, NULL, 'created', 'instansi', 70, NULL, '{\"id\": 70, \"alias\": \"PKMNELAYAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS NELAYAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(101, NULL, 'created', 'instansi', 71, NULL, '{\"id\": 71, \"alias\": \"PKMPAMITRAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PAMITRAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(102, NULL, 'created', 'instansi', 72, NULL, '{\"id\": 72, \"alias\": \"PKMPEGAMBIRAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PEGAMBIRAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(103, NULL, 'created', 'instansi', 73, NULL, '{\"id\": 73, \"alias\": \"PKMPEKALANGAN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PEKALANGAN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(104, NULL, 'created', 'instansi', 74, NULL, '{\"id\": 74, \"alias\": \"PKMPERUMNASUTARA\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PERUMNASUTARA\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(105, NULL, 'created', 'instansi', 75, NULL, '{\"id\": 75, \"alias\": \"PKMPESISIR\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PESISIR\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(106, NULL, 'created', 'instansi', 76, NULL, '{\"id\": 76, \"alias\": \"PKMPULASAREN\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS PULASAREN\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(107, NULL, 'created', 'instansi', 77, NULL, '{\"id\": 77, \"alias\": \"PKMSITOPENG\", \"jenis\": \"puskesmas\", \"is_active\": true, \"created_at\": \"2025-11-28 14:57:39\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:39\", \"updated_id\": 1, \"nama_instansi\": \"PUSKESMAS SITOPENG\"}', 'Aksi created pada tabel instansi', NULL, NULL, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39'),
(108, NULL, 'created', 'ruangan', 1, NULL, '{\"id\": 1, \"dipakai\": false, \"id_kantor\": 2, \"created_at\": \"2025-11-28 14:57:42\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:42\", \"updated_id\": 1, \"nama_ruangan\": \"Mini Command Center (MCC)\", \"kapasitas_maksimal\": 30}', 'Aksi created pada tabel ruangan', NULL, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42'),
(109, NULL, 'created', 'ruangan', 2, NULL, '{\"id\": 2, \"dipakai\": false, \"id_kantor\": 2, \"created_at\": \"2025-11-28 14:57:42\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:42\", \"updated_id\": 1, \"nama_ruangan\": \"Co-Working Space (CWS)\", \"kapasitas_maksimal\": 50}', 'Aksi created pada tabel ruangan', NULL, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43'),
(110, NULL, 'created', 'ruangan', 3, NULL, '{\"id\": 3, \"dipakai\": false, \"id_kantor\": 1, \"created_at\": \"2025-11-28 14:57:42\", \"created_id\": 1, \"updated_at\": \"2025-11-28 14:57:42\", \"updated_id\": 1, \"nama_ruangan\": \"Laboratorium Komputer (LABKOM)\", \"kapasitas_maksimal\": 40}', 'Aksi created pada tabel ruangan', NULL, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43'),
(111, NULL, 'created', 'apel_pagi', 1, NULL, '{\"id\": 1, \"status\": \"telat\", \"tanggal\": \"2025-11-27T17:00:00.000000Z\", \"user_id\": 13, \"latitude\": \"-6.7263903380833\", \"jam_masuk\": \"2025-11-28T08:07:19.896696Z\", \"longitude\": \"108.53890409569159\", \"created_at\": \"2025-11-28 15:07:19\", \"updated_at\": \"2025-11-28 15:07:19\", \"telat_menit\": 457.3316116}', 'Aksi created pada tabel apel_pagi', NULL, NULL, NULL, '2025-11-28 08:07:19', '2025-11-28 08:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `instansi`
--

CREATE TABLE `instansi` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_instansi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `instansi`
--

INSERT INTO `instansi` (`id`, `nama_instansi`, `lokasi`, `alias`, `jenis`, `is_active`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'KOTA CIREBON', NULL, 'PEMKOT', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(2, 'SEKRETARIAT DAERAH', NULL, 'SETDA', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(3, 'SEKRETARIAT DPRD', NULL, 'SETWAN', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(4, 'INSPEKTORAT DAERAH', NULL, 'INSPEKTORAT', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(5, 'DINAS PENDIDIKAN', NULL, 'DISDIK', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(6, 'DINAS KESEHATAN', NULL, 'DINKES', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(7, 'DINAS PEKERJAAN UMUM DAN TATA RUANG', NULL, 'DPUTR', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(8, 'DINAS PERUMAHAN RAKYAT DAN KAWASAN PERMUKIMAN', NULL, 'DPRKP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(9, 'SATUAN POLISI PAMONG PRAJA', NULL, 'SATPOL-PP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(10, 'DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN', NULL, 'DPKP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(11, 'DINAS SOSIAL', NULL, 'DINSOS', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(12, 'DINAS TENAGA KERJA', NULL, 'DISNAKER', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(13, 'DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN', NULL, 'DKP3', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(14, 'DINAS LINGKUNGAN HIDUP', NULL, 'DLH', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(15, 'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL', NULL, 'DISDUKCAPIL', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(16, 'DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA', NULL, 'DP3APPKB', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(17, 'DINAS PERHUBUNGAN', NULL, 'DISHUB', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(18, 'DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK', NULL, 'DKIS', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(19, 'DINAS KOPERASI, USAHA KECIL, MENENGAH, PERDAGANGAN DAN PERINDUSTRIAN', NULL, 'DKUKMPP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(20, 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU', NULL, 'DPMPTSP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(21, 'DINAS KEBUDAYAAN DAN PARIWISATA', NULL, 'DISBUDPAR', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(22, 'DINAS PEMUDA DAN OLAHRAGA', NULL, 'DISPORA', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(23, 'DINAS PERPUSTAKAAN DAN KEARSIPAN', NULL, 'DISPUSIP', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(24, 'BADAN PERENCANAAN PEMBANGUNAN, PENELITIAN DAN PENGEMBANGAN DAERAH', NULL, 'BAPPELITBANGDA', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(25, 'BADAN PENGELOLA KEUANGAN DAN PENDAPATAN DAERAH', NULL, 'BPKPD', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(26, 'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA', NULL, 'BKPSDM', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(27, 'BADAN PENANGGULANGAN BENCANA DAERAH', NULL, 'BPBD', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(28, 'BADAN KESATUAN BANGSA DAN POLITIK', NULL, 'BAKESBANGPOL', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(29, 'KECAMATAN HARJAMUKTI', NULL, 'KECHARJAMUKTI', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(30, 'KECAMATAN LEMAHWUNGKUK', NULL, 'KECLMHWUNGKUK', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(31, 'KECAMATAN KEJAKSAN', NULL, 'KECKEJAKSAN', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(32, 'KECAMATAN KESAMBI', NULL, 'KECKESAMBI', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(33, 'KECAMATAN PEKALIPAN', NULL, 'KECPEKALIPAN', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(34, 'RSD GUNUNG JATI', NULL, 'RSDGJ', 'instansi', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(35, 'KELURAHAN ARGASUNYA', NULL, 'KELARGASUNYA', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(36, 'KELURAHAN DRAJAT', NULL, 'KELDRAJAT', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(37, 'KELURAHAN HARJAMUKTI', NULL, 'KELHARJAMUKTI', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(38, 'KELURAHAN JAGASATRU', NULL, 'KELJAGASATRU', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(39, 'KELURAHAN KALIJAGA', NULL, 'KELKALIJAGA', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:38', '2025-11-28 07:57:38', NULL),
(40, 'KELURAHAN KARYAMULYA', NULL, 'KELKARYAMULYA', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(41, 'KELURAHAN KEBON BARU', NULL, 'KELKEBONBARU', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(42, 'KELURAHAN KECAPI', NULL, 'KELKECAPI', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(43, 'KELURAHAN KEJAKSAN', NULL, 'KELKEJAKSAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(44, 'KELURAHAN KESAMBI', NULL, 'KELKESAMBI', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(45, 'KELURAHAN KESENDEN', NULL, 'KELKESENDEN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(46, 'KELURAHAN KESEPUHAN', NULL, 'KELKESEPUHAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(47, 'KELURAHAN LARANGAN', NULL, 'KELLARANGAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(48, 'KELURAHAN LEMAHWUNGKUK', NULL, 'KELLMHWUNGKUK', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(49, 'KELURAHAN PANJUNAN', NULL, 'KELPANJUNAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(50, 'KELURAHAN PEGAMBIRAN', NULL, 'KELPEGAMBIRAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(51, 'KELURAHAN PEKALANGAN', NULL, 'KELPEKALANGAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(52, 'KELURAHAN PEKALIPAN', NULL, 'KELPEKALIPAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(53, 'KELURAHAN PEKIRINGAN', NULL, 'KELPEKIRINGAN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(54, 'KELURAHAN PULASAREN', NULL, 'KELPULASAREN', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(55, 'KELURAHAN SUKAPURA', NULL, 'KELSUKAPURA', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(56, 'KELURAHAN SUNYARAGI', NULL, 'KELSUNYARAGI', 'kelurahan', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(57, 'PUSKESMAS ASTANAGARIB', NULL, 'PKMASTANAGARIB', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(58, 'PUSKESMAS CANGKOL', NULL, 'PKMCANGKOL', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(59, 'PUSKESMAS DRAJAT', NULL, 'PKMDRAJAT', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(60, 'PUSKESMAS GUNUNGSARI', NULL, 'PKMGUNUNGSARI', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(61, 'PUSKESMAS JAGASATRU', NULL, 'PKMJAGASATRU', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(62, 'PUSKESMAS JALANKEMBANG', NULL, 'PKMJALANKEMBANG', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(63, 'PUSKESMAS KALIJAGAPERMAI', NULL, 'PKMKALIJAGAPERMAI', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(64, 'PUSKESMAS KALITANJUNG', NULL, 'PKMKALITANJUNG', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(65, 'PUSKESMAS KEJAKSAN', NULL, 'PKMKEJAKSAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(66, 'PUSKESMAS KESAMBI', NULL, 'PKMKESAMBI', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(67, 'PUSKESMAS KESUNEAN', NULL, 'PKMKESUNEAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(68, 'PUSKESMAS LARANGAN', NULL, 'PKMLARANGAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(69, 'PUSKESMAS MAJASEM', NULL, 'PKMMAJASEM', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(70, 'PUSKESMAS NELAYAN', NULL, 'PKMNELAYAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(71, 'PUSKESMAS PAMITRAN', NULL, 'PKMPAMITRAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(72, 'PUSKESMAS PEGAMBIRAN', NULL, 'PKMPEGAMBIRAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(73, 'PUSKESMAS PEKALANGAN', NULL, 'PKMPEKALANGAN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(74, 'PUSKESMAS PERUMNASUTARA', NULL, 'PKMPERUMNASUTARA', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(75, 'PUSKESMAS PESISIR', NULL, 'PKMPESISIR', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(76, 'PUSKESMAS PULASAREN', NULL, 'PKMPULASAREN', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL),
(77, 'PUSKESMAS SITOPENG', NULL, 'PKMSITOPENG', 'puskesmas', 1, 1, 1, NULL, '2025-11-28 07:57:39', '2025-11-28 07:57:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama_jabatan`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Kepala Dinas Komunikasi, Informatika dan Statistik', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(2, 'Sekretaris', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(3, 'Kepala Bidang Persandian dan Keamanan Informasi', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(4, 'Kepala Bidang Layanan E Government', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(5, 'Kepala Bidang Statistik Sektoral', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(6, 'Kepala Bidang Infrastruktur Teknologi Informasi dan Komunikasi', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(7, 'Kepala Sub Bagian Umum dan Kepegawaian', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(8, 'Kepala Sub Bagian Program dan Pelaporan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(9, 'Pranata Komputer Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(10, 'Sandiman Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(11, 'Statistisi Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(12, 'Pranata Hubungan Masyarakat Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(13, 'Analis Keuangan Pusat dan Daerah Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(14, 'Analis Kebijakan Ahli Muda', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(15, 'Penelaah Teknis Kebijakan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(16, 'Penata Layanan Operasional', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(17, 'Pengolah Data dan Informasi', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(18, 'Pranata Komputer Ahli Pertama', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(19, 'Fasilitator Pemerintahan', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(20, 'Surveyor Pemetaan Terampil', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(21, 'Pengadministrasi Perkantoran', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(22, 'Pengelola Layanan Operasional', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(23, 'Penata Hubungan Masyarakat Ahli Pertama', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL),
(24, 'Operator Layanan Operasional', NULL, NULL, NULL, '2025-11-28 07:57:37', '2025-11-28 07:57:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kantor`
--

CREATE TABLE `kantor` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kantor` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kantor`
--

INSERT INTO `kantor` (`id`, `nama_kantor`, `alamat`, `latitude`, `longitude`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'DKIS Bypass', 'Jl. Brigjend Dharsono No.1, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45135', -6.7254818, 108.5389768, NULL, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(2, 'DKIS Kesambi', 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134', -6.7281094, 108.5527149, NULL, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` bigint UNSIGNED NOT NULL,
  `tamu_id` bigint UNSIGNED NOT NULL,
  `pegawai_id` bigint UNSIGNED NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('menunggu','diterima','ditolak','sedang_bertamu','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'menunggu',
  `alasan_penolakan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `waktu_masuk` timestamp NULL DEFAULT NULL,
  `waktu_keluar` timestamp NULL DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_22_062643_create_permission_tables', 1),
(5, '2025_09_26_034212_create_bidangs_table', 1),
(6, '2025_09_26_036911_create_jabatans_table', 1),
(7, '2025_09_26_040125_create_pegawais_table', 1),
(8, '2025_09_26_041144_create_tamus_table', 1),
(9, '2025_09_26_042238_create_kunjungans_table', 1),
(10, '2025_09_26_071049_create_history_log_table', 1),
(11, '2025_10_03_033506_add_email_verification_code_to_users_table', 1),
(12, '2025_10_06_075416_add_alasan_penolakan_to_kunjungan_table', 1),
(13, '2025_10_07_024340_alter_pegawai_nullable_columns', 1),
(14, '2025_10_09_042125_create_notifications_table', 1),
(15, '2025_10_10_034640_add_softdeletes_to_users_table', 1),
(16, '2025_10_10_042558_add_softdeletes_to_multiple_tables', 1),
(17, '2025_10_10_071358_add_audit_columns_to_users_table', 1),
(18, '2025_10_17_031623_create_surveys_table', 1),
(19, '2025_10_18_091613_create_kantor_table', 1),
(20, '2025_10_18_091659_create_ruangan_table', 1),
(21, '2025_10_19_073321_create_instansi_table', 1),
(22, '2025_10_19_114955_create_rapats_table', 1),
(23, '2025_10_19_115622_create_rapat_undangan_table', 1),
(24, '2025_10_19_115855_create_survey_rapat_table', 1),
(25, '2025_10_20_041213_create_daftar_surveys_table', 1),
(26, '2025_10_23_001659_add_instansi_id_to_users_table', 1),
(27, '2025_11_03_101110_add_jenis_rapat_to_rapat_table', 1),
(28, '2025_11_04_132313_create_rapat_undangan_instansi_table', 1),
(29, '2025_11_04_134816_add_rapat_undangan_instansi_id_to_rapat_undangan_table', 1),
(30, '2025_11_17_150058_create_survey_rapat_respon_table', 1),
(31, '2025_11_20_091428_add_survey_id_to_rapat', 1),
(32, '2025_11_21_134649_add_status_survey_to_rapat_undangan_table', 1),
(33, '2025_11_25_095822_add_method_checkin_to_rapat_undangan_table', 1),
(34, '2025_11_26_212826_create_apel_pagi_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(3, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(3, 'App\\Models\\User', 6),
(3, 'App\\Models\\User', 7),
(3, 'App\\Models\\User', 8),
(3, 'App\\Models\\User', 9),
(3, 'App\\Models\\User', 10),
(1, 'App\\Models\\User', 11),
(2, 'App\\Models\\User', 12),
(3, 'App\\Models\\User', 13),
(4, 'App\\Models\\User', 14);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `bidang_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan_id` bigint UNSIGNED DEFAULT NULL,
  `nip` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apel_token` varchar(64) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `user_id`, `bidang_id`, `jabatan_id`, `nip`, `apel_token`, `telepon`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 1, '198001010001', 'PYDBg9HkIXJFuUrr36nhyBaPXECAhZoC', '0811111111', 1, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(2, 2, 1, 2, '198001010002', 'RGlcuabYBiJmhwrHKJT2XExwy0VNVv8j', '0811111112', 1, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(3, 3, 2, 3, '198001010003', 'ZeOWsbREgELnJ14Bq2WePPen3cK8CHUS', '0811111113', 1, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(4, 4, 2, 4, '198001010004', 'skyeIolRwMFMMAUvkIKFl36XuhftzPsr', '0811111114', 1, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(5, 5, 3, 5, '198001010005', 'R2hkrBmkt3MdAoZenTZO7ijtFN8okQmd', '0811111115', 1, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(6, 6, 3, 6, '198001010006', 'KNI2xjxyoApJfLDiUDvbX8AppOijhlov', '0811111116', 1, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(7, 7, 4, 7, '198001010007', 'tIkzYaa4vk2bWR3bgAqg2r1gWMAxragO', '0811111117', 1, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(8, 8, 4, 8, '198001010008', 'nn0LjEa38Lg6J8C15AqLzFCRE8bklql7', '0811111118', 1, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(9, 9, 5, 9, '198001010009', '5eZQ3rX1vTmRm1jtSlo5Ypz751P9A7br', '0811111119', 1, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(10, 10, 5, 10, '198001010010', 'INgtmdR7g1hc45tyhYGdDQTeynOOxeuT', '0811111120', 1, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(11, 13, 3, 4, '02111929344', 'SGX7ykl8mupiOnNvAMyd8Xjt6VrecuQB', '082123456789', 1, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'users.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(2, 'users.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(3, 'users.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(4, 'users.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(5, 'roles.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(6, 'roles.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(7, 'roles.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(8, 'roles.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(9, 'permissions.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(10, 'permissions.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(11, 'permissions.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(12, 'permissions.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(13, 'pegawai.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(14, 'pegawai.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(15, 'pegawai.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(16, 'pegawai.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(17, 'pegawai.rapat.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(18, 'bidang.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(19, 'bidang.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(20, 'bidang.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(21, 'bidang.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(22, 'jabatan.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(23, 'jabatan.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(24, 'jabatan.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(25, 'jabatan.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(26, 'visits.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(27, 'visits.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(28, 'visits.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(29, 'visits.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(30, 'visits.approve', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(31, 'visits.reject', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(32, 'visits.checkout', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(33, 'pegawai.visits.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(34, 'pegawai.visits.details', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(35, 'tamu.visits.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(36, 'tamu.visits.create', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(37, 'tamu.visits.checkout', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(38, 'tamu.profile.update', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(39, 'reports.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(40, 'reports.export', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(41, 'logs.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(42, 'logs.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(43, 'surveys.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(44, 'surveys.delete', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(45, 'rapat.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(46, 'rapat.manage', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(47, 'rapat.invite', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(48, 'rapat.rekap', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(49, 'instansi.manage', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(50, 'tamu.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36'),
(51, 'kunjungan.view', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `rapat`
--

CREATE TABLE `rapat` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_rapat` enum('Internal','Eksternal') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Internal',
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruangan_id` bigint UNSIGNED DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `radius` int NOT NULL DEFAULT '100',
  `jumlah_tamu` int DEFAULT NULL,
  `jumlah_instansi` int DEFAULT NULL,
  `qr_token` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_dimulai',
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `survey_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rapat_undangan`
--

CREATE TABLE `rapat_undangan` (
  `id` bigint UNSIGNED NOT NULL,
  `rapat_id` bigint UNSIGNED NOT NULL,
  `rapat_undangan_instansi_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `instansi_id` bigint UNSIGNED DEFAULT NULL,
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kehadiran` enum('pending','pending_verification','hadir','tidak_hadir','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `method_checkin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_survey` enum('belum_isi','sudah_isi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_isi',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_out_at` timestamp NULL DEFAULT NULL,
  `checked_in_by` bigint UNSIGNED DEFAULT NULL,
  `checkin_latitude` decimal(10,7) DEFAULT NULL,
  `checkin_longitude` decimal(10,7) DEFAULT NULL,
  `checkin_distance` int DEFAULT NULL,
  `keterlambatan_menit` int DEFAULT NULL,
  `checkin_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkin_token_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkin_verified_at` timestamp NULL DEFAULT NULL,
  `qr_scanned_at` timestamp NULL DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rapat_undangan_instansi`
--

CREATE TABLE `rapat_undangan_instansi` (
  `id` bigint UNSIGNED NOT NULL,
  `rapat_id` bigint UNSIGNED NOT NULL,
  `instansi_id` bigint UNSIGNED NOT NULL,
  `kuota` int UNSIGNED NOT NULL DEFAULT '1',
  `jumlah_hadir` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-11-28 07:57:35', '2025-11-28 07:57:35'),
(2, 'frontliner', 'web', '2025-11-28 07:57:35', '2025-11-28 07:57:35'),
(3, 'pegawai', 'web', '2025-11-28 07:57:35', '2025-11-28 07:57:35'),
(4, 'tamu', 'web', '2025-11-28 07:57:36', '2025-11-28 07:57:36');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(26, 2),
(30, 2),
(31, 2),
(32, 2),
(17, 3),
(33, 3),
(34, 3),
(45, 3),
(46, 3),
(47, 3),
(48, 3),
(49, 3),
(35, 4),
(36, 4),
(37, 4),
(38, 4);

-- --------------------------------------------------------

--
-- Table structure for table `ruangan`
--

CREATE TABLE `ruangan` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kantor` bigint UNSIGNED NOT NULL,
  `kapasitas_maksimal` int UNSIGNED NOT NULL DEFAULT '0',
  `dipakai` int NOT NULL DEFAULT '0',
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ruangan`
--

INSERT INTO `ruangan` (`id`, `nama_ruangan`, `id_kantor`, `kapasitas_maksimal`, `dipakai`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Mini Command Center (MCC)', 2, 30, 0, 1, 1, NULL, '2025-11-28 07:57:42', '2025-11-28 09:06:02', NULL),
(2, 'Co-Working Space (CWS)', 2, 50, 0, 1, 1, NULL, '2025-11-28 07:57:42', '2025-11-28 09:06:02', NULL),
(3, 'Laboratorium Komputer (LABKOM)', 1, 40, 0, 1, 1, NULL, '2025-11-28 07:57:42', '2025-11-28 09:06:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4KhvckpoF51vnqp7wW8XyCbLB2NnIq3b0hZeMFOV', 11, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGdyaGtEcFVjOExBcGFtUlVJaFpGdjJDb25QNVFYeG9RWWlCc1NNMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL25vdGlmaWthc2kiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL25vdGlmaWthc2kiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMTt9', 1764320757),
('ct5rwQ35UNSk1Ym9DRWEiruQQ8PYEpVUKcRv49z5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidUY3cmpsQmJDbGRLS0hUUWQyYnJ5c2hLbnBZSzZDYTlxS1k5MlNacyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9idWt1LXRhbXUtZGlnaXRhbC50ZXN0L2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1764316678),
('fSW8AazoVlTDwArxIASzpF9n4VjxxKvWtcrIkZx9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia0RudnlNZVNuaEFtbVNKMmNlY2Rwa1lCQ01rRlRNVGFlaThUUDd6YSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764316673),
('HzIZRv8J4lH2T5WWBoNNDXPh7VlxaHzP9c0XJnlS', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFJFOUwwODdTTUtOTUFOWVRvRmxXd0xmNXlldnBRc1p3QU5mVlBMNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764316674),
('U0o3npUsboy1xsy4MlZkjYWWEb3cmxVoJUH4xknp', 12, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiVDhTMzlJTmVGMFpFN25zUXI3bU50NHBFNUlVZnB1RjdSWjA2N0FiMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0MDoiaHR0cHM6Ly9idWt1LXRhbXUtZGlnaXRhbC50ZXN0L2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQxOiJodHRwczovL2J1a3UtdGFtdS1kaWdpdGFsLnRlc3Qvbm90aWZpa2FzaSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjEyO30=', 1764320817),
('ZTcbCCPFMWpk9A5sQaR7LqOs7UXzh2zzlg9sYNcE', NULL, '192.168.200.98', 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.2 Mobile/15E148 Safari/604.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSGxyMUpFTnRaMFd1U2VsZ3RhMVdZMjB4c3RhSjNhT1RpNllxYmFUMyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNDoiaHR0cHM6Ly8xOTIuMTY4LjIwMC4xMDgvbm90aWZpa2FzaSI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjY1OiJodHRwczovLzE5Mi4xNjguMjAwLjEwOC9hcGVscGFnaS9aZU9Xc2JSRWdFTG5KMTRCcTJXZVBQZW4zY0s4Q0hVUyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1764320553);

-- --------------------------------------------------------

--
-- Table structure for table `surveys`
--

CREATE TABLE `surveys` (
  `id` bigint UNSIGNED NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kunjungan_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint DEFAULT NULL,
  `feedback` text COLLATE utf8mb4_unicode_ci,
  `kemudahan_registrasi` tinyint UNSIGNED DEFAULT NULL,
  `keramahan_pelayanan` tinyint UNSIGNED DEFAULT NULL,
  `waktu_tunggu` tinyint UNSIGNED DEFAULT NULL,
  `saran` text COLLATE utf8mb4_unicode_ci,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_rapat`
--

CREATE TABLE `survey_rapat` (
  `id` bigint UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('Internal','Eksternal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `survey_rapat_respon`
--

CREATE TABLE `survey_rapat_respon` (
  `id` bigint UNSIGNED NOT NULL,
  `survey_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jawaban` json NOT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tamu`
--

CREATE TABLE `tamu` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instansi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tamu`
--

INSERT INTO `tamu` (`id`, `user_id`, `nama`, `instansi`, `no_hp`, `email`, `alamat`, `deleted_at`, `created_id`, `updated_id`, `created_at`, `updated_at`) VALUES
(1, 14, 'Tamu', NULL, NULL, 'tamu@example.com', NULL, NULL, NULL, NULL, '2025-11-28 07:57:44', '2025-11-28 07:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `instansi_id` bigint UNSIGNED DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_expires_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_id` bigint UNSIGNED DEFAULT NULL,
  `updated_id` bigint UNSIGNED DEFAULT NULL,
  `deleted_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `profile_photo`, `email_verified_at`, `instansi_id`, `password`, `verification_code`, `verification_expires_at`, `status`, `remember_token`, `created_id`, `updated_id`, `deleted_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Ma\'ruf Nuryasa', 'maruf@dkis.go.id', NULL, '2025-11-28 07:57:40', 18, '$2y$12$eEL49TZbdc9qU9TLDFaRI.9m5LG8wJ/dfVmBOV65NxwPv6VslAOde', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(2, 'Asep Komara', 'asep@dkis.go.id', NULL, '2025-11-28 07:57:40', 18, '$2y$12$MNvi85vaLPjEVYKpTmiMmugfPZ5S2AJjmjahPocSdMJG7WwNaG.Na', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(3, 'Aria Dipahandi', 'aria@dkis.go.id', NULL, '2025-11-28 07:57:40', 18, '$2y$12$tffw/0ByWTPleItXLKc2c.uHgYznIDJ3cxf10gw41II24XFRGNxk.', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:40', '2025-11-28 07:57:40', NULL),
(4, 'Eka Purnomo Sidik', 'eka@dkis.go.id', NULL, '2025-11-28 07:57:41', 18, '$2y$12$8ft9r1ir5zcoWAx0CUnHIuQM6PK78SEw8zbOxN/3gzCLxrHb.L48O', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(5, 'Herro Yudhistira', 'herro@dkis.go.id', NULL, '2025-11-28 07:57:41', 18, '$2y$12$494GXDbnDsaOVn.BbimqAeeslFRwy8ugA9kz6Gj.rV4Ez/g8Iclki', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(6, 'Monang M.T. Situmorang', 'monang@dkis.go.id', NULL, '2025-11-28 07:57:41', 18, '$2y$12$sP2t18zdU0vPAP7qIj6Yxe7LTh5QrdIvyp/H1bkn/mfGL7qyzQQ0O', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:41', '2025-11-28 07:57:41', NULL),
(7, 'Linda Suminar', 'linda@dkis.go.id', NULL, '2025-11-28 07:57:42', 18, '$2y$12$U1uh62tNMF1VclLVUJOeNemulEODcqC/koz9kNd9V/lQrBR4Arpgm', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(8, 'Hendy Hermawan', 'hendy@dkis.go.id', NULL, '2025-11-28 07:57:42', 18, '$2y$12$7RNUqP1W9IWXjQdAiCzDCODsmujYkrlxNhG87h8jXqsManM9fap/W', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(9, 'Dodi Solihudin', 'dodi@dkis.go.id', NULL, '2025-11-28 07:57:42', 18, '$2y$12$5LcHcO7zKOL54L4iM5yypefEy8pHFAbdwbKBbNSU1ei9Id4SP77GO', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(10, 'Indra Gunawan', 'indra@dkis.go.id', NULL, '2025-11-28 07:57:42', 18, '$2y$12$9PZDaOmbYWKOR3jiUmdbsuZnXWs/MmbWAuP0WsaO1qfddWhFn.1Km', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:42', '2025-11-28 07:57:42', NULL),
(11, 'Admin', 'admin@dkis.go.id', NULL, '2025-11-28 07:57:43', 18, '$2y$12$1E6BkCwuf2kGHUSY31BBlOPTEES7QQEgjNe0jQ.hBULJqavW1uitW', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43', NULL),
(12, 'Frontliner', 'frontliner@dkis.go.id', NULL, '2025-11-28 07:57:43', 18, '$2y$12$TmYXee4ztwOghOanBojakuJdJ00r6YJvNgdgErF/nbjjElCySbG2q', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43', NULL),
(13, 'Pegawai', 'pegawai@dkis.go.id', NULL, '2025-11-28 07:57:43', 18, '$2y$12$mIY71V7zzgCUIDcZBi6tGO8PHrYJZgu2TyW4y5/nP0S/VGQwkeavu', NULL, NULL, 1, NULL, NULL, NULL, NULL, '2025-11-28 07:57:43', '2025-11-28 07:57:43', NULL),
(14, 'Tamu', 'tamu@example.com', NULL, '2025-11-28 07:57:44', NULL, '$2y$12$YrYVooKnqTK7Jp3KQrWUn.YvBRvEJLqlSvPWZAY99uUJ1TQQa6ZjK', NULL, NULL, 0, NULL, NULL, NULL, NULL, '2025-11-28 07:57:44', '2025-11-28 07:57:44', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apel_pagi`
--
ALTER TABLE `apel_pagi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `apel_pagi_user_id_foreign` (`user_id`);

--
-- Indexes for table `bidang`
--
ALTER TABLE `bidang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `daftar_survey`
--
ALTER TABLE `daftar_survey`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `history_logs`
--
ALTER TABLE `history_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `instansi`
--
ALTER TABLE `instansi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kantor`
--
ALTER TABLE `kantor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kunjungan_tamu_id_foreign` (`tamu_id`),
  ADD KEY `kunjungan_pegawai_id_foreign` (`pegawai_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pegawai_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `pegawai_apel_token_unique` (`apel_token`),
  ADD KEY `pegawai_bidang_id_foreign` (`bidang_id`),
  ADD KEY `pegawai_jabatan_id_foreign` (`jabatan_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `rapat`
--
ALTER TABLE `rapat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rapat_ruangan_id_foreign` (`ruangan_id`),
  ADD KEY `rapat_survey_id_foreign` (`survey_id`);

--
-- Indexes for table `rapat_undangan`
--
ALTER TABLE `rapat_undangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rapat_undangan_checkin_token_unique` (`checkin_token`),
  ADD KEY `rapat_undangan_rapat_id_foreign` (`rapat_id`),
  ADD KEY `rapat_undangan_user_id_foreign` (`user_id`),
  ADD KEY `rapat_undangan_instansi_id_foreign` (`instansi_id`),
  ADD KEY `rapat_undangan_rapat_undangan_instansi_id_foreign` (`rapat_undangan_instansi_id`);

--
-- Indexes for table `rapat_undangan_instansi`
--
ALTER TABLE `rapat_undangan_instansi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rapat_undangan_instansi_rapat_id_instansi_id_unique` (`rapat_id`,`instansi_id`),
  ADD KEY `rapat_undangan_instansi_instansi_id_foreign` (`instansi_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `ruangan`
--
ALTER TABLE `ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ruangan_id_kantor_index` (`id_kantor`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `surveys`
--
ALTER TABLE `surveys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `surveys_link_unique` (`link`),
  ADD KEY `surveys_kunjungan_id_foreign` (`kunjungan_id`),
  ADD KEY `surveys_user_id_foreign` (`user_id`);

--
-- Indexes for table `survey_rapat`
--
ALTER TABLE `survey_rapat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `survey_rapat_slug_unique` (`slug`);

--
-- Indexes for table `survey_rapat_respon`
--
ALTER TABLE `survey_rapat_respon`
  ADD PRIMARY KEY (`id`),
  ADD KEY `survey_rapat_respon_survey_id_foreign` (`survey_id`);

--
-- Indexes for table `tamu`
--
ALTER TABLE `tamu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tamu_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_instansi_id_foreign` (`instansi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apel_pagi`
--
ALTER TABLE `apel_pagi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bidang`
--
ALTER TABLE `bidang`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `daftar_survey`
--
ALTER TABLE `daftar_survey`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_logs`
--
ALTER TABLE `history_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `instansi`
--
ALTER TABLE `instansi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kantor`
--
ALTER TABLE `kantor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `rapat`
--
ALTER TABLE `rapat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rapat_undangan`
--
ALTER TABLE `rapat_undangan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rapat_undangan_instansi`
--
ALTER TABLE `rapat_undangan_instansi`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ruangan`
--
ALTER TABLE `ruangan`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `surveys`
--
ALTER TABLE `surveys`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey_rapat`
--
ALTER TABLE `survey_rapat`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `survey_rapat_respon`
--
ALTER TABLE `survey_rapat_respon`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tamu`
--
ALTER TABLE `tamu`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apel_pagi`
--
ALTER TABLE `apel_pagi`
  ADD CONSTRAINT `apel_pagi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_logs`
--
ALTER TABLE `history_logs`
  ADD CONSTRAINT `history_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD CONSTRAINT `kunjungan_pegawai_id_foreign` FOREIGN KEY (`pegawai_id`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kunjungan_tamu_id_foreign` FOREIGN KEY (`tamu_id`) REFERENCES `tamu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_bidang_id_foreign` FOREIGN KEY (`bidang_id`) REFERENCES `bidang` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pegawai_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pegawai_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rapat`
--
ALTER TABLE `rapat`
  ADD CONSTRAINT `rapat_ruangan_id_foreign` FOREIGN KEY (`ruangan_id`) REFERENCES `ruangan` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `rapat_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `survey_rapat` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rapat_undangan`
--
ALTER TABLE `rapat_undangan`
  ADD CONSTRAINT `rapat_undangan_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapat_undangan_rapat_id_foreign` FOREIGN KEY (`rapat_id`) REFERENCES `rapat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapat_undangan_rapat_undangan_instansi_id_foreign` FOREIGN KEY (`rapat_undangan_instansi_id`) REFERENCES `rapat_undangan_instansi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapat_undangan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rapat_undangan_instansi`
--
ALTER TABLE `rapat_undangan_instansi`
  ADD CONSTRAINT `rapat_undangan_instansi_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapat_undangan_instansi_rapat_id_foreign` FOREIGN KEY (`rapat_id`) REFERENCES `rapat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `surveys`
--
ALTER TABLE `surveys`
  ADD CONSTRAINT `surveys_kunjungan_id_foreign` FOREIGN KEY (`kunjungan_id`) REFERENCES `kunjungan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `surveys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `survey_rapat_respon`
--
ALTER TABLE `survey_rapat_respon`
  ADD CONSTRAINT `survey_rapat_respon_survey_id_foreign` FOREIGN KEY (`survey_id`) REFERENCES `survey_rapat` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tamu`
--
ALTER TABLE `tamu`
  ADD CONSTRAINT `tamu_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_instansi_id_foreign` FOREIGN KEY (`instansi_id`) REFERENCES `instansi` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
