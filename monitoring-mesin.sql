-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Sep 03, 2026 at 09:02 AM
-- Server version: 5.7.24
-- PHP Version: 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring-mesin`
--

-- --------------------------------------------------------

--
-- Table structure for table `am`
--

CREATE TABLE `am` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `kegiatan_uuid` varchar(255) NOT NULL,
  `kegiatan` varchar(500) NOT NULL,
  `target` int(11) NOT NULL,
  `jadwal` int(11) NOT NULL,
  `pelaksana` varchar(50) NOT NULL,
  `catatan` varchar(50) NOT NULL,
  `dokumentasi_acc` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `area`
--

CREATE TABLE `area` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_area` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `area_gmp`
--

CREATE TABLE `area_gmp` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `area` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `badpro`
--

CREATE TABLE `badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) DEFAULT NULL,
  `nama_badpro` varchar(55) NOT NULL,
  `kategori` int(11) NOT NULL,
  `proses_uuid` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL,
  `aktif` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `badpro_input`
--

CREATE TABLE `badpro_input` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `sortasi_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `jumlah` float NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `bahanbaku`
--

CREATE TABLE `bahanbaku` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `no_reservasi` float NOT NULL,
  `item_barang_uuid` varchar(255) NOT NULL,
  `item_barang` varchar(55) NOT NULL,
  `qty_reservasi` float NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `spv_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `sisastok` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cek_mesin`
--

CREATE TABLE `cek_mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `area` varchar(50) NOT NULL,
  `group` varchar(50) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `mesin` varchar(50) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `item_uuid` varchar(255) NOT NULL,
  `item` varchar(50) NOT NULL,
  `checklist` int(11) NOT NULL,
  `checklist2` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `keterangan2` varchar(255) NOT NULL,
  `paraf_prod` varchar(255) DEFAULT NULL,
  `fr_uuid` varchar(255) DEFAULT NULL,
  `paraf_qc` varchar(255) DEFAULT NULL,
  `spv_uuid` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified-at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cheklist_sanitasi`
--

CREATE TABLE `cheklist_sanitasi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `area` varchar(50) NOT NULL,
  `kegiatan_uuid` varchar(255) NOT NULL,
  `nama_item` varchar(50) NOT NULL,
  `waktu_kondisi` time NOT NULL,
  `kondisi_uuid` varchar(255) NOT NULL,
  `tindakan` varchar(255) DEFAULT NULL,
  `petugas` varchar(50) NOT NULL,
  `waktu_tindakan` time NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chemical`
--

CREATE TABLE `chemical` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `chemical_master_uuid` varchar(255) NOT NULL,
  `nama_chemical` varchar(50) NOT NULL,
  `stock` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chemical_master`
--

CREATE TABLE `chemical_master` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `chemical_name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `chemical_stock`
--

CREATE TABLE `chemical_stock` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `chemical_master_uuid` varchar(255) NOT NULL,
  `stock_murni` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ch_rj_cooking`
--

CREATE TABLE `ch_rj_cooking` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `berat` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ch_rj_mesin`
--

CREATE TABLE `ch_rj_mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `operator_uuid` varchar(255) NOT NULL,
  `berat` float NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE `departemen` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `user_uuid` varchar(100) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `modified_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departemen_old`
--

CREATE TABLE `departemen_old` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `drystore`
--

CREATE TABLE `drystore` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `user_uuid` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `drystore_type`
--

CREATE TABLE `drystore_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `std_waste` decimal(10,2) NOT NULL DEFAULT '0.00',
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `satuan` varchar(50) NOT NULL,
  `user_uuid` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `drystore_waste`
--

CREATE TABLE `drystore_waste` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `user_uuid` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `drystore_waste_transaksi`
--

CREATE TABLE `drystore_waste_transaksi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `drystore_uuid` varchar(100) NOT NULL,
  `type_uuid` varchar(100) NOT NULL,
  `waste_uuid` varchar(100) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `berat` decimal(15,3) NOT NULL DEFAULT '0.000',
  `keterangan` text,
  `user_uuid` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `filkar`
--

CREATE TABLE `filkar` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `shift` int(11) NOT NULL,
  `kode_batch` varchar(255) NOT NULL,
  `proses_uuid` varchar(255) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `jml_mp` int(11) NOT NULL,
  `jumlah_box` int(11) DEFAULT NULL,
  `jumlah_kontainer` int(11) NOT NULL,
  `jumlah_kg` decimal(10,3) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `kr_uuid` varchar(255) NOT NULL,
  `qc_id` varchar(255) NOT NULL,
  `spv_uuid` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `foto_pengajuan`
--

CREATE TABLE `foto_pengajuan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `pengajuan_uuid` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `f_btajam`
--

CREATE TABLE `f_btajam` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `shift` int(11) NOT NULL,
  `jenis_btajam_uuid` varchar(255) NOT NULL,
  `kode_btajam_uuid` varchar(255) NOT NULL,
  `kondisi` int(11) NOT NULL,
  `keterangan` varchar(500) NOT NULL,
  `frm_uuid` varchar(255) NOT NULL,
  `spv_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `gmp`
--

CREATE TABLE `gmp` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `kegiatan_uuid` varchar(255) NOT NULL,
  `kegiatan` varchar(500) NOT NULL,
  `jadwal` int(11) NOT NULL,
  `target` int(11) NOT NULL,
  `pelaksana` varchar(50) NOT NULL,
  `dokumentasi_acc` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history_lifetime`
--

CREATE TABLE `history_lifetime` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `part_uuid` varchar(255) NOT NULL,
  `nama_part` varchar(50) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `kondisi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `item_cekmesin`
--

CREATE TABLE `item_cekmesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `kegiatan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_btajam`
--

CREATE TABLE `jenis_btajam` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `jenis_benda` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_pbelah`
--

CREATE TABLE `jenis_pbelah` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `sub_area_uuid` varchar(255) NOT NULL,
  `jenis_barang` varchar(55) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sortasi`
--

CREATE TABLE `jenis_sortasi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `jenis_kategori` varchar(50) NOT NULL,
  `ket` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_am`
--

CREATE TABLE `kegiatan_am` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `kegiatan` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan_gmp`
--

CREATE TABLE `kegiatan_gmp` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `lokasi_uuid` varchar(255) NOT NULL,
  `kegiatan` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kode_btajam`
--

CREATE TABLE `kode_btajam` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `jenis_btajam_uuid` varchar(255) NOT NULL,
  `kode_benda` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kode_chemical`
--

CREATE TABLE `kode_chemical` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `chemical_master_uuid` varchar(255) NOT NULL,
  `kode_chemical` varchar(55) NOT NULL,
  `persentase` float NOT NULL,
  `satuan` int(11) NOT NULL,
  `banding` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kode_pbelah`
--

CREATE TABLE `kode_pbelah` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `jenis_pbelah_uuid` varchar(255) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `kondisi_area`
--

CREATE TABLE `kondisi_area` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `kegiatan_gmp_uuid` varchar(255) NOT NULL,
  `kode_chemical_uuid` varchar(255) NOT NULL,
  `kondisi` varchar(255) NOT NULL,
  `tindakan` varchar(255) NOT NULL,
  `target` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `larutan`
--

CREATE TABLE `larutan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `chemical_stock_uuid` varchar(255) NOT NULL,
  `kode_chemical_uuid` varchar(255) NOT NULL,
  `chemical_used` int(11) NOT NULL,
  `larutan` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `larutan_used`
--

CREATE TABLE `larutan_used` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `cheklist_sanitasi_uuid` varchar(255) NOT NULL,
  `kode_chemical_uuid` varchar(255) NOT NULL,
  `used` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `lokasi_gmp`
--

CREATE TABLE `lokasi_gmp` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `nama_mesin` varchar(50) NOT NULL,
  `nama_operator` varchar(50) NOT NULL,
  `nama_pelaksana` varchar(255) NOT NULL,
  `keluhan` varchar(255) NOT NULL,
  `dokumentasi` varchar(50) NOT NULL,
  `dokumentasi_acc` varchar(50) NOT NULL,
  `tindakan` varchar(100) NOT NULL,
  `tindakan_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `nama_acc` varchar(255) DEFAULT NULL,
  `acc_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `manual_books`
--

CREATE TABLE `manual_books` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `judul` varchar(50) NOT NULL,
  `keterangan` varchar(500) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delete_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `master_speed`
--

CREATE TABLE `master_speed` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `mesin` varchar(50) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `speed` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mesin`
--

CREATE TABLE `mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_area` varchar(50) NOT NULL,
  `nama_mesin` varchar(50) NOT NULL,
  `rh_update` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `version` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `monitor`
--

CREATE TABLE `monitor` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `nama_mesin` varchar(50) NOT NULL,
  `part_uuid` varchar(50) NOT NULL,
  `nama_part` varchar(50) NOT NULL,
  `nama_pelaksana` varchar(50) NOT NULL,
  `nama_foreman` varchar(50) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `jadwal` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `rh_awal` int(11) NOT NULL,
  `installed_at` datetime DEFAULT NULL,
  `removed_at` datetime DEFAULT NULL,
  `final_rh` double DEFAULT NULL,
  `status` int(11) NOT NULL,
  `catatan` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mp_usage`
--

CREATE TABLE `mp_usage` (
  `id` int(10) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(50) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `formula_uuid` varchar(255) DEFAULT NULL,
  `formula_kg` decimal(10,2) DEFAULT '0.00',
  `rework_kg` decimal(10,2) DEFAULT '0.00',
  `total_output` decimal(10,2) DEFAULT '0.00',
  `batch_persen` decimal(10,2) NOT NULL,
  `is_full` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mp_usage_detail`
--

CREATE TABLE `mp_usage_detail` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `mp_usage_uuid` varchar(255) NOT NULL,
  `m_formula_detail_uuid` varchar(255) NOT NULL,
  `bahan_uuid` varchar(255) NOT NULL,
  `nama_bahan` varchar(50) NOT NULL,
  `qty` decimal(12,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_bahan`
--

CREATE TABLE `m_bahan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `kode_bahan` varchar(50) NOT NULL,
  `nama_bahan` varchar(255) NOT NULL,
  `keterangan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_formula`
--

CREATE TABLE `m_formula` (
  `id` int(11) NOT NULL,
  `uuid` varchar(250) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `nama_formula` varchar(100) NOT NULL,
  `total` int(11) NOT NULL,
  `keterangan` text,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_formula_detail`
--

CREATE TABLE `m_formula_detail` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `formula_uuid` varchar(50) DEFAULT NULL,
  `bahan_uuid` varchar(50) DEFAULT NULL,
  `nama_bahan` varchar(100) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_kondisi`
--

CREATE TABLE `m_kondisi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `kondisi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_proses`
--

CREATE TABLE `m_proses` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nama_proses` varchar(50) NOT NULL,
  `urutan` int(11) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` varchar(255) NOT NULL,
  `update_at` datetime DEFAULT NULL,
  `deleted_by` varchar(255) NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `m_tindakan`
--

CREATE TABLE `m_tindakan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `tindakan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(50) NOT NULL,
  `nama_mesin` varchar(50) NOT NULL,
  `nama_part` varchar(50) NOT NULL,
  `lifetime` int(11) NOT NULL,
  `harga` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `part` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jenis` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pengecekan_pbelah`
--

CREATE TABLE `pengecekan_pbelah` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `jenis_pbelah_uuid` varchar(255) NOT NULL,
  `kode_pbelah_uuid` varchar(255) NOT NULL,
  `kondisi` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `frm_uuid` int(11) DEFAULT NULL,
  `spv_uuid` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pengecekan_tools`
--

CREATE TABLE `pengecekan_tools` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `tools_mesin_uuid` varchar(255) NOT NULL,
  `kondisi` int(11) NOT NULL,
  `kelengkapan` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `fr_uuid` varchar(255) NOT NULL,
  `spv_uuid` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `performa`
--

CREATE TABLE `performa` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `performa` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pg_varian`
--

CREATE TABLE `pg_varian` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `shift` int(11) NOT NULL,
  `varian_1_uuid` varchar(255) NOT NULL,
  `batch_1` varchar(255) NOT NULL,
  `varian_2_uuid` varchar(255) NOT NULL,
  `batch_2` varchar(255) NOT NULL,
  `kondisi` int(11) NOT NULL,
  `area` varchar(255) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `spv_uuid` varchar(225) NOT NULL,
  `kr_uuid` int(11) NOT NULL,
  `qc_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pg_varian_rt`
--

CREATE TABLE `pg_varian_rt` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `uuid_varian_1` varchar(255) NOT NULL,
  `varian_name_1` varchar(255) NOT NULL,
  `uuid_kode_prod_1` varchar(255) NOT NULL,
  `uuid_varian_2` varchar(255) NOT NULL,
  `varian_name_2` varchar(255) NOT NULL,
  `uuid_kode_prod_2` varchar(255) NOT NULL,
  `kondisi` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `kr_uuid` varchar(255) DEFAULT NULL,
  `spv_uuid` varchar(255) DEFAULT NULL,
  `qc_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `pn_badpro`
--

CREATE TABLE `pn_badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `shift` varchar(255) DEFAULT NULL,
  `kode_produksi` varchar(255) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `varian` varchar(255) NOT NULL,
  `qty_kg` float NOT NULL,
  `kr_uuid` varchar(255) DEFAULT NULL,
  `qc_id` varchar(255) DEFAULT NULL,
  `spv_uuid` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `printing`
--

CREATE TABLE `printing` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `zanasi_uuid` varchar(255) NOT NULL,
  `print` int(11) NOT NULL,
  `catatan` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `release`
--

CREATE TABLE `release` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `berat` decimal(10,0) NOT NULL,
  `hold_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rj_filler`
--

CREATE TABLE `rj_filler` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(50) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `operator_uuid` varchar(255) NOT NULL,
  `berat` float NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rt_mesin`
--

CREATE TABLE `rt_mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `nama_mesin` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rt_rjmesin`
--

CREATE TABLE `rt_rjmesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `rt_mesin_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(100) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `reject` float NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `kr_uuid` varchar(255) NOT NULL,
  `spv_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rwk_kupas`
--

CREATE TABLE `rwk_kupas` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `berat` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rwk_pakai`
--

CREATE TABLE `rwk_pakai` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) DEFAULT NULL,
  `rwk_kupas_uuid` varchar(255) NOT NULL,
  `dipakai` float NOT NULL,
  `mp_usage_uuid` varchar(255) NOT NULL,
  `plastik` int(11) NOT NULL DEFAULT '0',
  `metal` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `acc_qc` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_realtime`
--

CREATE TABLE `sensor_realtime` (
  `id` bigint(20) NOT NULL,
  `plc_code` varchar(50) NOT NULL,
  `sensor_code` varchar(50) NOT NULL,
  `value` decimal(10,3) DEFAULT NULL,
  `sensor_timestamp` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sortasi`
--

CREATE TABLE `sortasi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `shift` int(11) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `proses_uuid` varchar(255) NOT NULL,
  `jumlah_wip` int(11) NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `jml_mp` int(11) NOT NULL,
  `jml_release` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status_am`
--

CREATE TABLE `status_am` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `am_uuid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `catatan` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status_gmp`
--

CREATE TABLE `status_gmp` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `gmp_uuid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `catatan` varchar(200) NOT NULL,
  `dokumentasi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status_maintenance`
--

CREATE TABLE `status_maintenance` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `maintenance_uuid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `catatan` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status_part`
--

CREATE TABLE `status_part` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `monitor_uuid` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `status_pengajuan`
--

CREATE TABLE `status_pengajuan` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `pengajuan_uuid` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_area`
--

CREATE TABLE `sub_area` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_badpro`
--

CREATE TABLE `sub_badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `sub_badpro` varchar(55) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_filkar`
--

CREATE TABLE `sub_filkar` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `filkar_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `jumlah` float NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sub_role`
--

CREATE TABLE `sub_role` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `users_uuid` varchar(255) NOT NULL,
  `subrole` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tbatch`
--

CREATE TABLE `tbatch` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `batch_ke` int(11) NOT NULL,
  `kode_batch` varchar(50) DEFAULT NULL,
  `tanggal_produksi` date DEFAULT NULL,
  `total` int(11) NOT NULL,
  `adonan` decimal(10,3) DEFAULT NULL,
  `rework_used` decimal(10,3) DEFAULT NULL,
  `filkar_kg` decimal(10,3) DEFAULT NULL,
  `filkar_box` int(11) DEFAULT NULL,
  `sortasi_box` int(11) DEFAULT NULL,
  `release_box` int(11) DEFAULT NULL,
  `bad_filkar_rework_kg` decimal(10,3) NOT NULL,
  `bad_filkar_reject_kg` decimal(10,3) NOT NULL,
  `bad_sortasi_rework_kg` decimal(10,3) DEFAULT NULL,
  `bad_sortasi_reject_kg` decimal(10,3) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tcounter`
--

CREATE TABLE `tcounter` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) DEFAULT NULL,
  `device_id` varchar(50) NOT NULL,
  `speed` int(11) NOT NULL,
  `counter` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tools_mesin`
--

CREATE TABLE `tools_mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `area_uuid` varchar(255) NOT NULL,
  `nama_tools` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_after`
--

CREATE TABLE `t_after` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_issue_uuid` varchar(255) NOT NULL,
  `cap` varchar(50) NOT NULL,
  `deadline` date NOT NULL,
  `dok_after` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_badpro`
--

CREATE TABLE `t_badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(50) NOT NULL,
  `proses_uuid` varchar(255) NOT NULL,
  `ref_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) DEFAULT NULL,
  `berat` decimal(10,3) NOT NULL,
  `kategori` int(11) NOT NULL,
  `keterangan` varchar(500) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_badpro_mesin`
--

CREATE TABLE `t_badpro_mesin` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(225) NOT NULL,
  `t_badpro_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_before`
--

CREATE TABLE `t_before` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_issue_uuid` varchar(255) NOT NULL,
  `gap` varchar(50) NOT NULL,
  `dok_before` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_cuci`
--

CREATE TABLE `t_cuci` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `varian_uuid` varchar(225) NOT NULL,
  `kode_batch_hasil` varchar(55) NOT NULL,
  `tbatch_uuid_hasil` varchar(255) DEFAULT NULL,
  `jumlah_box_hasil` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_cuci_detail`
--

CREATE TABLE `t_cuci_detail` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_cuci_uuid` varchar(255) DEFAULT NULL,
  `sortasi_uuid` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `jumlah_box` int(11) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `modified_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_detail`
--

CREATE TABLE `t_detail` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_issue_uuid` varchar(255) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `dokumentasi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_downtime`
--

CREATE TABLE `t_downtime` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_speed_uuid` varchar(255) NOT NULL,
  `downtime` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_form`
--

CREATE TABLE `t_form` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `varian` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_hasil`
--

CREATE TABLE `t_hasil` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `t_after_uuid` varchar(255) NOT NULL,
  `evaluasi` varchar(255) NOT NULL,
  `dok_hasil` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_hold`
--

CREATE TABLE `t_hold` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `tbatch_uuid` varchar(255) NOT NULL,
  `kode_batch` varchar(50) NOT NULL,
  `berat` decimal(10,0) NOT NULL,
  `status` int(11) NOT NULL,
  `keterangan` varchar(500) NOT NULL,
  `hold_by` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_issue`
--

CREATE TABLE `t_issue` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pic` varchar(50) NOT NULL,
  `issue` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_planning`
--

CREATE TABLE `t_planning` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `plan` int(11) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `varian` varchar(255) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `clean` int(11) NOT NULL,
  `target_qty` int(11) NOT NULL,
  `target_kg` decimal(10,0) NOT NULL,
  `formula` float NOT NULL,
  `filkar` float DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `fr_rt_rjmesin` varchar(255) DEFAULT NULL,
  `spv_rt_rjmesin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `t_speed`
--

CREATE TABLE `t_speed` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `master_speed_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) DEFAULT NULL,
  `t_sensor_device_id` varchar(50) NOT NULL,
  `varian_uuid` varchar(255) NOT NULL,
  `speed` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `quality` int(11) NOT NULL,
  `keterangan` varchar(50) NOT NULL,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `user_uuid` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(225) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `nik` varchar(100) NOT NULL,
  `join_date` date NOT NULL,
  `resign_date` date DEFAULT NULL,
  `birth_date` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `pendidikan` int(11) NOT NULL COMMENT '1=SMP, 2=SMA/SMK,3=D3,4=D4/S1,5=S2,6=S3',
  `status` int(11) NOT NULL,
  `type` int(11) NOT NULL COMMENT '1= Head, 2 = Supervisor, 3 = Staff',
  `asal` varchar(100) NOT NULL,
  `jenis_kelamin` int(11) NOT NULL COMMENT '1=Laki-Laki, 2=Perempuan',
  `departemen` varchar(100) NOT NULL,
  `hak_akses` text,
  `created_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `modified_at` datetime(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6),
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users_old`
--

CREATE TABLE `users_old` (
  `id` int(11) NOT NULL,
  `uuid` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(150) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `nik` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `dept` varchar(100) NOT NULL,
  `type` int(11) NOT NULL,
  `join_date` varchar(50) NOT NULL,
  `birth_date` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resign_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `varian`
--

CREATE TABLE `varian` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `varian` varchar(255) NOT NULL,
  `panjang` decimal(10,2) DEFAULT NULL,
  `berat` decimal(10,2) DEFAULT NULL,
  `kontainer_kg` decimal(10,3) DEFAULT NULL,
  `box_kg` decimal(10,3) DEFAULT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_badpro`
--

CREATE TABLE `v_badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `badpro` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_smfg`
--

CREATE TABLE `v_smfg` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `jumlah` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_smfgchart`
--

CREATE TABLE `v_smfgchart` (
  `id` int(11) NOT NULL,
  `chart_id` varchar(20) DEFAULT NULL,
  `chart_title` varchar(100) DEFAULT NULL,
  `badpro_uuid` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_smfgmsn`
--

CREATE TABLE `v_smfgmsn` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `mesin_uuid` varchar(255) NOT NULL,
  `berat` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_sortasi`
--

CREATE TABLE `v_sortasi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `persen` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `v_sr_badpro`
--

CREATE TABLE `v_sr_badpro` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `t_planning_uuid` varchar(255) NOT NULL,
  `badpro_uuid` varchar(255) NOT NULL,
  `jumlah` float NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wd`
--

CREATE TABLE `wd` (
  `WD_ID` int(11) NOT NULL,
  `WD_DATE` date NOT NULL,
  `WD_UUIDNMPRODUK` varchar(255) DEFAULT NULL,
  `WD_NMPRODUK` varchar(20) NOT NULL,
  `WD_KDPRODUK` varchar(17) NOT NULL,
  `WD_WAKTU` varchar(10) NOT NULL,
  `WD_PJNG` float NOT NULL,
  `WD_DT` float NOT NULL,
  `WD_SISA` varchar(16) NOT NULL,
  `WD_VACUUM` varchar(16) NOT NULL,
  `WD_SEAL` varchar(16) NOT NULL,
  `WD_PRINTKD` varchar(16) NOT NULL,
  `WD_SHPCC` float NOT NULL,
  `WD_PHPCC` varchar(16) NOT NULL,
  `WD_KNDPCC` varchar(16) NOT NULL,
  `WD_SHPC` float NOT NULL,
  `WD_PHPC` varchar(16) NOT NULL,
  `WD_KNDPC` varchar(16) NOT NULL,
  `WD_SHDRYER` varchar(16) NOT NULL,
  `WD_SPEEDCONV` double NOT NULL,
  `WD_SPEEDCONV2` double NOT NULL,
  `WD_SPEEDCONV3` double NOT NULL,
  `WD_SPEEDCONV4` double NOT NULL,
  `WD_QC` int(11) NOT NULL,
  `WD_PROD` int(11) NOT NULL,
  `WD_CTT` varchar(100) NOT NULL,
  `WD_QCSPV` int(11) NOT NULL,
  `WD_IS_DELETE` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `zanasi`
--

CREATE TABLE `zanasi` (
  `id` int(11) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `user_uuid` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `rutin` int(11) NOT NULL,
  `varian` varchar(255) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `exp` varchar(50) NOT NULL,
  `permintaan` int(11) NOT NULL,
  `total_print` int(11) NOT NULL DEFAULT '0',
  `catatan` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `am`
--
ALTER TABLE `am`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_gmp`
--
ALTER TABLE `area_gmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `badpro`
--
ALTER TABLE `badpro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cheklist_sanitasi`
--
ALTER TABLE `cheklist_sanitasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chemical`
--
ALTER TABLE `chemical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chemical_master`
--
ALTER TABLE `chemical_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chemical_stock`
--
ALTER TABLE `chemical_stock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_rj_cooking`
--
ALTER TABLE `ch_rj_cooking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_rj_mesin`
--
ALTER TABLE `ch_rj_mesin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departemen`
--
ALTER TABLE `departemen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departemen_old`
--
ALTER TABLE `departemen_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `drystore`
--
ALTER TABLE `drystore`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_drystore_uuid` (`uuid`),
  ADD UNIQUE KEY `uk_drystore_tanggal` (`tanggal`),
  ADD KEY `idx_drystore_user_uuid` (`user_uuid`),
  ADD KEY `idx_drystore_created_at` (`created_at`);

--
-- Indexes for table `drystore_type`
--
ALTER TABLE `drystore_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_drystore_type_uuid` (`uuid`),
  ADD KEY `idx_drystore_type_aktif` (`aktif`),
  ADD KEY `idx_drystore_type_user_uuid` (`user_uuid`);

--
-- Indexes for table `drystore_waste`
--
ALTER TABLE `drystore_waste`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_drystore_waste_uuid` (`uuid`),
  ADD KEY `idx_drystore_waste_aktif` (`aktif`),
  ADD KEY `idx_drystore_waste_user_uuid` (`user_uuid`);

--
-- Indexes for table `drystore_waste_transaksi`
--
ALTER TABLE `drystore_waste_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_dwt_uuid` (`uuid`),
  ADD KEY `idx_dwt_drystore_uuid` (`drystore_uuid`),
  ADD KEY `idx_dwt_type_uuid` (`type_uuid`),
  ADD KEY `idx_dwt_waste_uuid` (`waste_uuid`),
  ADD KEY `idx_dwt_user_uuid` (`user_uuid`),
  ADD KEY `idx_dwt_created_at` (`created_at`);

--
-- Indexes for table `filkar`
--
ALTER TABLE `filkar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `foto_pengajuan`
--
ALTER TABLE `foto_pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gmp`
--
ALTER TABLE `gmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_lifetime`
--
ALTER TABLE `history_lifetime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_cekmesin`
--
ALTER TABLE `item_cekmesin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_pbelah`
--
ALTER TABLE `jenis_pbelah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jenis_sortasi`
--
ALTER TABLE `jenis_sortasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan_am`
--
ALTER TABLE `kegiatan_am`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan_gmp`
--
ALTER TABLE `kegiatan_gmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kode_chemical`
--
ALTER TABLE `kode_chemical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kode_pbelah`
--
ALTER TABLE `kode_pbelah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kondisi_area`
--
ALTER TABLE `kondisi_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `larutan`
--
ALTER TABLE `larutan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `larutan_used`
--
ALTER TABLE `larutan_used`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lokasi_gmp`
--
ALTER TABLE `lokasi_gmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mt_uuid` (`uuid`),
  ADD KEY `idx_mt_created` (`created_at`),
  ADD KEY `idx_mt_mesin` (`mesin_uuid`);

--
-- Indexes for table `manual_books`
--
ALTER TABLE `manual_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_speed`
--
ALTER TABLE `master_speed`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_mesin_varian` (`mesin_uuid`,`varian_uuid`),
  ADD KEY `idx_ms_uuid` (`uuid`),
  ADD KEY `idx_ms_varian` (`varian_uuid`),
  ADD KEY `idx_ms_mesin` (`mesin_uuid`),
  ADD KEY `idx_ms_varian_mesin` (`varian_uuid`,`mesin_uuid`);

--
-- Indexes for table `mesin`
--
ALTER TABLE `mesin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mesin_uuid` (`uuid`),
  ADD KEY `idx_mesin_nama` (`nama_mesin`),
  ADD KEY `idx_mesin_area_nama` (`nama_area`,`nama_mesin`);

--
-- Indexes for table `monitor`
--
ALTER TABLE `monitor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_monitor_status` (`status`),
  ADD KEY `idx_monitor_installed_at` (`installed_at`),
  ADD KEY `idx_monitor_mesin_uuid` (`mesin_uuid`);

--
-- Indexes for table `mp_usage`
--
ALTER TABLE `mp_usage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD KEY `idx_t_planning_uuid` (`t_planning_uuid`),
  ADD KEY `idx_varian_uuid` (`varian_uuid`),
  ADD KEY `idx_formula_uuid` (`formula_uuid`),
  ADD KEY `idx_kode_batch` (`kode_batch`);

--
-- Indexes for table `mp_usage_detail`
--
ALTER TABLE `mp_usage_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mp_usage_uuid` (`mp_usage_uuid`),
  ADD KEY `m_formula_detail_uuid` (`m_formula_detail_uuid`),
  ADD KEY `bahan_uuid` (`bahan_uuid`);

--
-- Indexes for table `m_bahan`
--
ALTER TABLE `m_bahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_formula`
--
ALTER TABLE `m_formula`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_formula_detail`
--
ALTER TABLE `m_formula_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_kondisi`
--
ALTER TABLE `m_kondisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_proses`
--
ALTER TABLE `m_proses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_tindakan`
--
ALTER TABLE `m_tindakan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengecekan_pbelah`
--
ALTER TABLE `pengecekan_pbelah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengecekan_tools`
--
ALTER TABLE `pengecekan_tools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `performa`
--
ALTER TABLE `performa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pg_varian`
--
ALTER TABLE `pg_varian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `printing`
--
ALTER TABLE `printing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_zanasi_uuid` (`zanasi_uuid`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_zanasi_created` (`zanasi_uuid`,`created_at`),
  ADD KEY `idx_printing_uuid` (`uuid`),
  ADD KEY `idx_printing_zanasi` (`zanasi_uuid`),
  ADD KEY `idx_printing_created` (`created_at`),
  ADD KEY `idx_printing_user` (`user_uuid`),
  ADD KEY `idx_printing_zanasi_created` (`zanasi_uuid`,`created_at`);

--
-- Indexes for table `rj_filler`
--
ALTER TABLE `rj_filler`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rwk_kupas`
--
ALTER TABLE `rwk_kupas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_rwk_kupas_uuid` (`uuid`),
  ADD KEY `idx_rwk_kupas_tbatch` (`tbatch_uuid`),
  ADD KEY `idx_rwk_kupas_stock` (`tbatch_uuid`);

--
-- Indexes for table `rwk_pakai`
--
ALTER TABLE `rwk_pakai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_rwk_pakai_uuid` (`uuid`),
  ADD KEY `idx_rwk_pakai_kupas` (`rwk_kupas_uuid`),
  ADD KEY `idx_rwk_pakai_produksi` (`mp_usage_uuid`);

--
-- Indexes for table `sensor_realtime`
--
ALTER TABLE `sensor_realtime`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_sensor_time` (`plc_code`,`sensor_code`,`sensor_timestamp`),
  ADD KEY `idx_sensor_code` (`sensor_code`),
  ADD KEY `idx_timestamp` (`sensor_timestamp`);

--
-- Indexes for table `sortasi`
--
ALTER TABLE `sortasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_am`
--
ALTER TABLE `status_am`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_gmp`
--
ALTER TABLE `status_gmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_maintenance`
--
ALTER TABLE `status_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sm_uuid` (`uuid`),
  ADD KEY `idx_sm_maintenance` (`maintenance_uuid`),
  ADD KEY `idx_sm_maintenance_created` (`maintenance_uuid`,`created_at`),
  ADD KEY `idx_sm_status` (`status`);

--
-- Indexes for table `status_part`
--
ALTER TABLE `status_part`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status_pengajuan`
--
ALTER TABLE `status_pengajuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pengajuan_created` (`pengajuan_uuid`,`created_at`),
  ADD KEY `idx_pengajuan_status` (`pengajuan_uuid`,`status`);

--
-- Indexes for table `sub_area`
--
ALTER TABLE `sub_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_role`
--
ALTER TABLE `sub_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbatch`
--
ALTER TABLE `tbatch`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tbatch_deleted_at` (`deleted_at`),
  ADD KEY `idx_tb_uuid` (`uuid`),
  ADD KEY `idx_tb_plan` (`t_planning_uuid`),
  ADD KEY `idx_tb_batchke` (`batch_ke`),
  ADD KEY `idx_tb_created` (`created_at`),
  ADD KEY `idx_tb_plan_created` (`t_planning_uuid`,`created_at`),
  ADD KEY `idx_tb_plan_batch` (`t_planning_uuid`,`batch_ke`);

--
-- Indexes for table `tcounter`
--
ALTER TABLE `tcounter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tcounter_mesin_created` (`mesin_uuid`,`created_at`),
  ADD KEY `idx_tcounter_tbatch_uuid` (`tbatch_uuid`),
  ADD KEY `idx_tc_uuid` (`uuid`),
  ADD KEY `idx_tc_batch` (`tbatch_uuid`),
  ADD KEY `idx_tc_mesin` (`mesin_uuid`),
  ADD KEY `idx_tc_batch_mesin` (`tbatch_uuid`,`mesin_uuid`);

--
-- Indexes for table `tools_mesin`
--
ALTER TABLE `tools_mesin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_after`
--
ALTER TABLE `t_after`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_badpro`
--
ALTER TABLE `t_badpro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_badpro_uuid` (`uuid`),
  ADD KEY `idx_badpro_ref` (`ref_uuid`),
  ADD KEY `idx_badpro_ref_badpro` (`ref_uuid`,`badpro_uuid`);

--
-- Indexes for table `t_badpro_mesin`
--
ALTER TABLE `t_badpro_mesin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_before`
--
ALTER TABLE `t_before`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_cuci`
--
ALTER TABLE `t_cuci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_cuci_detail`
--
ALTER TABLE `t_cuci_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_detail`
--
ALTER TABLE `t_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_downtime`
--
ALTER TABLE `t_downtime`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_td_uuid` (`uuid`),
  ADD KEY `idx_td_speed` (`t_speed_uuid`),
  ADD KEY `idx_td_speed_created` (`t_speed_uuid`,`created_at`);

--
-- Indexes for table `t_form`
--
ALTER TABLE `t_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_hasil`
--
ALTER TABLE `t_hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_hold`
--
ALTER TABLE `t_hold`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_issue`
--
ALTER TABLE `t_issue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_planning`
--
ALTER TABLE `t_planning`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_planning_start` (`start`),
  ADD KEY `idx_planning_end` (`end`),
  ADD KEY `idx_planning_deleted_at` (`deleted_at`),
  ADD KEY `idx_tp_uuid` (`uuid`),
  ADD KEY `idx_tp_tanggal` (`tanggal`),
  ADD KEY `idx_tp_created` (`created_at`),
  ADD KEY `idx_tp_deleted_created` (`deleted_at`,`created_at`),
  ADD KEY `idx_tp_varian` (`varian`);

--
-- Indexes for table `t_speed`
--
ALTER TABLE `t_speed`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ts_uuid` (`uuid`),
  ADD KEY `idx_ts_plan` (`t_planning_uuid`),
  ADD KEY `idx_ts_mesin` (`mesin_uuid`),
  ADD KEY `idx_ts_plan_mesin` (`t_planning_uuid`,`mesin_uuid`),
  ADD KEY `idx_ts_plan_speed` (`t_planning_uuid`,`speed`),
  ADD KEY `idx_ts_mesin_speed` (`mesin_uuid`,`speed`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_old`
--
ALTER TABLE `users_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `varian`
--
ALTER TABLE `varian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_varian_uuid` (`uuid`),
  ADD KEY `idx_varian_varian` (`varian`);

--
-- Indexes for table `v_badpro`
--
ALTER TABLE `v_badpro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_smfg`
--
ALTER TABLE `v_smfg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_smfgchart`
--
ALTER TABLE `v_smfgchart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_smfgmsn`
--
ALTER TABLE `v_smfgmsn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_sortasi`
--
ALTER TABLE `v_sortasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `v_sr_badpro`
--
ALTER TABLE `v_sr_badpro`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zanasi`
--
ALTER TABLE `zanasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_uuid` (`uuid`),
  ADD KEY `idx_varian` (`varian`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_kode` (`kode`),
  ADD KEY `idx_zanasi_varian` (`varian`),
  ADD KEY `idx_zanasi_created_at` (`created_at`),
  ADD KEY `idx_zanasi_kode` (`kode`),
  ADD KEY `idx_zanasi_rutin` (`rutin`),
  ADD KEY `idx_zanasi_uuid` (`uuid`),
  ADD KEY `idx_zanasi_created` (`created_at`),
  ADD KEY `idx_zanasi_user` (`user_uuid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `am`
--
ALTER TABLE `am`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area`
--
ALTER TABLE `area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area_gmp`
--
ALTER TABLE `area_gmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `badpro`
--
ALTER TABLE `badpro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cheklist_sanitasi`
--
ALTER TABLE `cheklist_sanitasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chemical`
--
ALTER TABLE `chemical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chemical_master`
--
ALTER TABLE `chemical_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chemical_stock`
--
ALTER TABLE `chemical_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ch_rj_cooking`
--
ALTER TABLE `ch_rj_cooking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ch_rj_mesin`
--
ALTER TABLE `ch_rj_mesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departemen`
--
ALTER TABLE `departemen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departemen_old`
--
ALTER TABLE `departemen_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drystore`
--
ALTER TABLE `drystore`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drystore_type`
--
ALTER TABLE `drystore_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drystore_waste`
--
ALTER TABLE `drystore_waste`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `drystore_waste_transaksi`
--
ALTER TABLE `drystore_waste_transaksi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `filkar`
--
ALTER TABLE `filkar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `foto_pengajuan`
--
ALTER TABLE `foto_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gmp`
--
ALTER TABLE `gmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_lifetime`
--
ALTER TABLE `history_lifetime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_cekmesin`
--
ALTER TABLE `item_cekmesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_pbelah`
--
ALTER TABLE `jenis_pbelah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenis_sortasi`
--
ALTER TABLE `jenis_sortasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kegiatan_am`
--
ALTER TABLE `kegiatan_am`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kegiatan_gmp`
--
ALTER TABLE `kegiatan_gmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kode_chemical`
--
ALTER TABLE `kode_chemical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kode_pbelah`
--
ALTER TABLE `kode_pbelah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kondisi_area`
--
ALTER TABLE `kondisi_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `larutan`
--
ALTER TABLE `larutan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `larutan_used`
--
ALTER TABLE `larutan_used`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lokasi_gmp`
--
ALTER TABLE `lokasi_gmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manual_books`
--
ALTER TABLE `manual_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_speed`
--
ALTER TABLE `master_speed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mesin`
--
ALTER TABLE `mesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monitor`
--
ALTER TABLE `monitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_usage`
--
ALTER TABLE `mp_usage`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mp_usage_detail`
--
ALTER TABLE `mp_usage_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_bahan`
--
ALTER TABLE `m_bahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_formula`
--
ALTER TABLE `m_formula`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_formula_detail`
--
ALTER TABLE `m_formula_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_kondisi`
--
ALTER TABLE `m_kondisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_proses`
--
ALTER TABLE `m_proses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_tindakan`
--
ALTER TABLE `m_tindakan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengecekan_pbelah`
--
ALTER TABLE `pengecekan_pbelah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengecekan_tools`
--
ALTER TABLE `pengecekan_tools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `performa`
--
ALTER TABLE `performa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pg_varian`
--
ALTER TABLE `pg_varian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `printing`
--
ALTER TABLE `printing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rj_filler`
--
ALTER TABLE `rj_filler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rwk_kupas`
--
ALTER TABLE `rwk_kupas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rwk_pakai`
--
ALTER TABLE `rwk_pakai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sensor_realtime`
--
ALTER TABLE `sensor_realtime`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sortasi`
--
ALTER TABLE `sortasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_am`
--
ALTER TABLE `status_am`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_gmp`
--
ALTER TABLE `status_gmp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_maintenance`
--
ALTER TABLE `status_maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_part`
--
ALTER TABLE `status_part`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `status_pengajuan`
--
ALTER TABLE `status_pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_area`
--
ALTER TABLE `sub_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_role`
--
ALTER TABLE `sub_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbatch`
--
ALTER TABLE `tbatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tcounter`
--
ALTER TABLE `tcounter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tools_mesin`
--
ALTER TABLE `tools_mesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_after`
--
ALTER TABLE `t_after`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_badpro`
--
ALTER TABLE `t_badpro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_badpro_mesin`
--
ALTER TABLE `t_badpro_mesin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_before`
--
ALTER TABLE `t_before`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_cuci`
--
ALTER TABLE `t_cuci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_cuci_detail`
--
ALTER TABLE `t_cuci_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_detail`
--
ALTER TABLE `t_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_downtime`
--
ALTER TABLE `t_downtime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_form`
--
ALTER TABLE `t_form`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_hasil`
--
ALTER TABLE `t_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_hold`
--
ALTER TABLE `t_hold`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_issue`
--
ALTER TABLE `t_issue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_planning`
--
ALTER TABLE `t_planning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_speed`
--
ALTER TABLE `t_speed`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users_old`
--
ALTER TABLE `users_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `varian`
--
ALTER TABLE `varian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_badpro`
--
ALTER TABLE `v_badpro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_smfg`
--
ALTER TABLE `v_smfg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_smfgchart`
--
ALTER TABLE `v_smfgchart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_smfgmsn`
--
ALTER TABLE `v_smfgmsn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_sortasi`
--
ALTER TABLE `v_sortasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `v_sr_badpro`
--
ALTER TABLE `v_sr_badpro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zanasi`
--
ALTER TABLE `zanasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
