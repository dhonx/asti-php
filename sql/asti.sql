-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.13-MariaDB - Source distribution
-- Server OS:                    Linux
-- HeidiSQL Version:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for asti
DROP DATABASE IF EXISTS `asti`;
CREATE DATABASE IF NOT EXISTS `asti` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `asti`;

-- Dumping structure for table asti.admin
DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id_admin` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `email` text NOT NULL,
  `no_telp` text NOT NULL,
  `sandi` text NOT NULL,
  `aktif` tinyint(4) DEFAULT 1,
  `tipe_admin` enum('super admin','admin') NOT NULL DEFAULT 'admin',
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`(3072)) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.admin: ~11 rows (approximately)
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
REPLACE INTO `admin` (`id_admin`, `nama`, `email`, `no_telp`, `sandi`, `aktif`, `tipe_admin`) VALUES
	(1, 'Don Nisnoni', 'donnisnoni.tid3@gmail.com', '081234567892', '$2b$10$7BIVDCVuJb9KHcqmBtDugexESgrobiYrYHj62oM6vRVesoM8fbZ/K', 1, 'super admin'),
	(2, 'Andi Kore', 'adni.kore@gmail.com', '08123456789322', '$2b$10$ZDhOfJQk/3VXpQZeN2AppOQEfDK6vRgLn/YlYW3tt86lk4KWwzn7K', 1, 'admin'),
	(3, 'Donna Nisnoni', 'donna@gmail.com', '90909090907', '$2b$10$SqN0Mr5pO8ZwZiWRn.T5O.b1jWh.Q672P7V1JC5QO0eVnOOw57.nK', 1, 'admin'),
	(4, 'Emanuel Safirman Bata', 'emanuelbata@yahoo.co.id', '085123456789', '$2b$10$NOy5gWfMU1yY/97GkyBym.20h4ZOanbxrR9H8YnZJm8/Kqh7YZWpi', 1, 'admin'),
	(5, 'Idris Koli', 'idriskoli@gmail.com', '081234778990', '$2b$10$02gH0cuG9VLYwUj6axpCIOPwpralwSzntjP3W5VJddYLTpvbXhCXe', 1, 'admin'),
	(6, 'Chece Balla', 'checeballa@gmail.com', '097812732435', '$2b$10$csAKSegL42M656PIRRkLJet07QEIELo0jUO/6EfXnt0RCbK8yTw8G', 1, 'admin'),
	(7, 'Ana Delfy', 'anna.delfy@gmail.com', '083345337756', '$2b$10$16dTBr/LmJjqkVlHOQ5uouJ0jqQB0gOQNaXsLIH4FVVdbWUZbp3pq', 1, 'admin'),
	(8, 'Chris Nisnoni', 'chris.tid3@gmail.com', '081239272732', '$2b$10$9CpirPnsBEZ9agFhXpDihuGXIPEaNnU.dRAxRi/.8K9foxfNzaFLG', 1, 'admin'),
	(9, 'Ariel Dillak', 'arieldillak@gmail.com', '836427364327', '$2b$10$HmtIWSBtYVc5LJsk4H1Yg.n5UZ9yjsPZcspFYxOpuTPcPFCwdZ/IG', 1, 'admin'),
	(10, 'Luki Farmas', 'lukifarma@gmail.com', '764562356325', '$2b$10$hs1oDgxdaSWM5uYAyFSEuu5FZbiskZKNwLuc9OyYGYjAHl5v9Hbie', 1, 'admin'),
	(20, 'Randy Orton', 'randi-orton@yahoo.com', '084376436646', '$2b$10$R7.MGH2bCCNKBFqAEpi1bu8bXUYZj5zf7NNAb9EU.Wj.SGh7iooHW', 1, 'admin');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Dumping structure for table asti.barang
DROP TABLE IF EXISTS `barang`;
CREATE TABLE IF NOT EXISTS `barang` (
  `id_barang` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_komponen` int(10) unsigned NOT NULL,
  `kode_inventaris` text NOT NULL,
  `id_perolehan` int(10) unsigned NOT NULL,
  `harga_beli` int(10) unsigned NOT NULL,
  `status_barang` enum('AKTIF','TIDAK AKTIF') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `kondisi` enum('Baik','Rusak Ringan','Rusak Berat') NOT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `FK_ADMIN` (`id_admin`),
  KEY `FK_KOMPONEN` (`id_komponen`),
  KEY `FK_ORDER` (`id_perolehan`),
  CONSTRAINT `FK_ADMIN` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  CONSTRAINT `FK_KOMPONEN` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`),
  CONSTRAINT `FK_PEROLEHAN` FOREIGN KEY (`id_perolehan`) REFERENCES `perolehan` (`id_perolehan`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.barang: ~0 rows (approximately)
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;

-- Dumping structure for table asti.detail_mutasi_barang
DROP TABLE IF EXISTS `detail_mutasi_barang`;
CREATE TABLE IF NOT EXISTS `detail_mutasi_barang` (
  `id_mutasi_barang` int(10) unsigned NOT NULL,
  `id_barang` int(10) unsigned NOT NULL,
  `id_ruang` int(10) unsigned NOT NULL,
  KEY `FK_MUTASI_BARANG` (`id_mutasi_barang`),
  KEY `FK_BARANG` (`id_barang`),
  KEY `FK_RUANG` (`id_ruang`),
  CONSTRAINT `FK_BARANG` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `FK_MUTASI_BARANG` FOREIGN KEY (`id_mutasi_barang`) REFERENCES `mutasi_barang` (`id_mutasi_barang`),
  CONSTRAINT `FK_RUANG` FOREIGN KEY (`id_ruang`) REFERENCES `ruang` (`id_ruang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.detail_mutasi_barang: ~0 rows (approximately)
/*!40000 ALTER TABLE `detail_mutasi_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_mutasi_barang` ENABLE KEYS */;

-- Dumping structure for table asti.detail_pembuangan
DROP TABLE IF EXISTS `detail_pembuangan`;
CREATE TABLE IF NOT EXISTS `detail_pembuangan` (
  `id_pembuangan` int(10) unsigned NOT NULL,
  `id_barang` int(10) unsigned NOT NULL,
  `kondisi_barang` enum('Y','N') NOT NULL,
  `keterangan` text DEFAULT NULL,
  KEY `FK_PEMBUANGAN` (`id_pembuangan`),
  KEY `FK_BARANG4` (`id_barang`),
  CONSTRAINT `FK_BARANG4` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `FK_PEMBUANGAN` FOREIGN KEY (`id_pembuangan`) REFERENCES `pembuangan` (`id_pembuangan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.detail_pembuangan: ~0 rows (approximately)
/*!40000 ALTER TABLE `detail_pembuangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_pembuangan` ENABLE KEYS */;

-- Dumping structure for table asti.detail_pemesanan
DROP TABLE IF EXISTS `detail_pemesanan`;
CREATE TABLE IF NOT EXISTS `detail_pemesanan` (
  `id_pemesanan` int(10) unsigned NOT NULL,
  `id_komponen` int(10) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  KEY `FK_PEMESANAN` (`id_pemesanan`),
  KEY `FK_KOMPONEN2` (`id_komponen`),
  CONSTRAINT `FK_KOMPONEN2` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`),
  CONSTRAINT `FK_PEMESANAN` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.detail_pemesanan: ~0 rows (approximately)
/*!40000 ALTER TABLE `detail_pemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_pemesanan` ENABLE KEYS */;

-- Dumping structure for table asti.detail_peminjaman
DROP TABLE IF EXISTS `detail_peminjaman`;
CREATE TABLE IF NOT EXISTS `detail_peminjaman` (
  `id_detail_peminjaman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_barang` int(10) unsigned NOT NULL,
  `kondisi_pinjam` enum('Y','N') NOT NULL DEFAULT 'Y',
  `jumlah_pinjam` varchar(50) NOT NULL,
  `kondisi_kembali` enum('Y','N') NOT NULL DEFAULT 'Y',
  `jumlah_kembali` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_detail_peminjaman`),
  KEY `FK_BARANG3` (`id_barang`),
  CONSTRAINT `FK_BARANG3` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.detail_peminjaman: ~0 rows (approximately)
/*!40000 ALTER TABLE `detail_peminjaman` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_peminjaman` ENABLE KEYS */;

-- Dumping structure for table asti.detail_perolehan
DROP TABLE IF EXISTS `detail_perolehan`;
CREATE TABLE IF NOT EXISTS `detail_perolehan` (
  `id_perolehan` int(10) unsigned NOT NULL,
  `id_komponen` int(10) unsigned NOT NULL,
  `harga_beli` int(10) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  KEY `id_order` (`id_perolehan`),
  KEY `FK_KOMPONEN3` (`id_komponen`),
  CONSTRAINT `FK_KOMPONEN3` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`),
  CONSTRAINT `FK_ORDER2` FOREIGN KEY (`id_perolehan`) REFERENCES `perolehan` (`id_perolehan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.detail_perolehan: ~0 rows (approximately)
/*!40000 ALTER TABLE `detail_perolehan` DISABLE KEYS */;
/*!40000 ALTER TABLE `detail_perolehan` ENABLE KEYS */;

-- Dumping structure for table asti.instansi
DROP TABLE IF EXISTS `instansi`;
CREATE TABLE IF NOT EXISTS `instansi` (
  `id_instansi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `alamat` text NOT NULL,
  `email` text NOT NULL,
  `no_telp` text NOT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.instansi: ~3 rows (approximately)
/*!40000 ALTER TABLE `instansi` DISABLE KEYS */;
REPLACE INTO `instansi` (`id_instansi`, `nama`, `alamat`, `email`, `no_telp`) VALUES
	(1, 'Internal', '-', '-', '-'),
	(2, 'Universitas Kristen Artha Wacana', 'Jl. Adisucipto 147 - Oesapa', 'ukaw_kupang@yahoo.co.id', '+62380881584'),
	(3, 'Universitas Nusa Cendana', 'Jl. Adisucipto - Penfui', 'info@undana.ac.id', '+82380 881580');
/*!40000 ALTER TABLE `instansi` ENABLE KEYS */;

-- Dumping structure for table asti.kategori
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table asti.kategori: ~2 rows (approximately)
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
REPLACE INTO `kategori` (`id_kategori`, `nama`) VALUES
	(1, 'Internal'),
	(2, 'Eksternal');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;

-- Dumping structure for table asti.komponen
DROP TABLE IF EXISTS `komponen`;
CREATE TABLE IF NOT EXISTS `komponen` (
  `id_komponen` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `tipe` text NOT NULL,
  `merek` text NOT NULL,
  `spesifikasi` text NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('AKTIF','TIDAK AKTIF') NOT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_komponen`),
  KEY `admin` (`id_admin`),
  CONSTRAINT `admin` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.komponen: ~0 rows (approximately)
/*!40000 ALTER TABLE `komponen` DISABLE KEYS */;
/*!40000 ALTER TABLE `komponen` ENABLE KEYS */;

-- Dumping structure for table asti.mutasi_barang
DROP TABLE IF EXISTS `mutasi_barang`;
CREATE TABLE IF NOT EXISTS `mutasi_barang` (
  `id_mutasi_barang` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_mutasi` date NOT NULL,
  `setuju_1` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `setuju_2` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `setuju_3` enum('Ya','Tidak') NOT NULL DEFAULT 'Tidak',
  `keterangan` text DEFAULT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_mutasi_barang`),
  KEY `FK_ADMIN2` (`id_admin`),
  CONSTRAINT `FK_ADMIN2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.mutasi_barang: ~0 rows (approximately)
/*!40000 ALTER TABLE `mutasi_barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `mutasi_barang` ENABLE KEYS */;

-- Dumping structure for table asti.mutasi_pegawai
DROP TABLE IF EXISTS `mutasi_pegawai`;
CREATE TABLE IF NOT EXISTS `mutasi_pegawai` (
  `id_mutasi_pegawai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_mutasi` date NOT NULL,
  `nama_pegawai` text NOT NULL,
  `id_unit` int(10) unsigned NOT NULL,
  `id_pegawai` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_mutasi_pegawai`),
  KEY `FK_UNIT` (`id_unit`),
  KEY `FK_PEGAWAI3` (`id_pegawai`),
  CONSTRAINT `FK_PEGAWAI3` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`),
  CONSTRAINT `FK_UNIT` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id_unit`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.mutasi_pegawai: ~0 rows (approximately)
/*!40000 ALTER TABLE `mutasi_pegawai` DISABLE KEYS */;
/*!40000 ALTER TABLE `mutasi_pegawai` ENABLE KEYS */;

-- Dumping structure for table asti.pegawai
DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id_pegawai` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_pegawai` text NOT NULL,
  `nama_pegawai` text NOT NULL,
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `no_pegawai` (`no_pegawai`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.pegawai: ~6 rows (approximately)
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
REPLACE INTO `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`) VALUES
	(1, '6437474375734643', 'Kevin Clark'),
	(2, '3467273464328756', 'John Vice'),
	(3, '4379854937465845', 'Hendrik Ken'),
	(7, '682736456', 'Julio Cesarxss'),
	(8, '37643657', 'Kevin Clays'),
	(9, '7364635245', 'Hendri Asam');
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;

-- Dumping structure for table asti.pemasok
DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE IF NOT EXISTS `pemasok` (
  `id_pemasok` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `no_telp` text NOT NULL,
  `alamat` text NOT NULL,
  `email` text NOT NULL,
  `nama_pemilik` text NOT NULL,
  `status` enum('AKTIF','TIDAK AKTIF') NOT NULL,
  `keterangan` text DEFAULT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_pemasok`),
  KEY `admin_` (`id_admin`),
  CONSTRAINT `admin_` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.pemasok: ~0 rows (approximately)
/*!40000 ALTER TABLE `pemasok` DISABLE KEYS */;
/*!40000 ALTER TABLE `pemasok` ENABLE KEYS */;

-- Dumping structure for table asti.pembuangan
DROP TABLE IF EXISTS `pembuangan`;
CREATE TABLE IF NOT EXISTS `pembuangan` (
  `id_pembuangan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_buang` datetime NOT NULL,
  `id_pegawai` int(10) unsigned NOT NULL,
  `setuju_1` varchar(4) NOT NULL,
  `setuju_2` varchar(4) NOT NULL,
  `setuju_3` varchar(4) NOT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_pembuangan`),
  KEY `FK_PEGAWAI2` (`id_pegawai`),
  CONSTRAINT `FK_PEGAWAI2` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.pembuangan: ~0 rows (approximately)
/*!40000 ALTER TABLE `pembuangan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pembuangan` ENABLE KEYS */;

-- Dumping structure for table asti.pemeliharaan
DROP TABLE IF EXISTS `pemeliharaan`;
CREATE TABLE IF NOT EXISTS `pemeliharaan` (
  `id_pemeliharaan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_pemeliharaan` datetime NOT NULL,
  `id_pegawai` int(10) unsigned NOT NULL,
  `id_barang` int(10) unsigned NOT NULL,
  `kondisi_awal` tinyint(3) unsigned NOT NULL,
  `keterangan_awal` text DEFAULT NULL,
  `tanggal_perbaikan` datetime NOT NULL,
  `kondisi_akhir` tinyint(3) unsigned NOT NULL,
  `keterangan_akhir` text DEFAULT NULL,
  `biaya_perawatan` int(10) unsigned NOT NULL,
  `status_perbaikan` enum('Belum','Dalam Proses Perbaikan','Selesai') NOT NULL,
  PRIMARY KEY (`id_pemeliharaan`),
  KEY `FK_PEGAWAI4` (`id_pegawai`),
  KEY `FK_BARANG5` (`id_barang`),
  CONSTRAINT `FK_BARANG5` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `FK_PEGAWAI4` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.pemeliharaan: ~0 rows (approximately)
/*!40000 ALTER TABLE `pemeliharaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pemeliharaan` ENABLE KEYS */;

-- Dumping structure for table asti.pemesanan
DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id_pemesanan` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_pesan` date NOT NULL,
  `id_pegawai` int(10) unsigned NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Usulan','Diterima','Dalam Proses Pemesanan','Ditunda','DItolak') NOT NULL DEFAULT 'Usulan',
  PRIMARY KEY (`id_pemesanan`),
  KEY `FK_PEGAWAI` (`id_pegawai`),
  CONSTRAINT `FK_PEGAWAI` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.pemesanan: ~0 rows (approximately)
/*!40000 ALTER TABLE `pemesanan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pemesanan` ENABLE KEYS */;

-- Dumping structure for table asti.peminjam
DROP TABLE IF EXISTS `peminjam`;
CREATE TABLE IF NOT EXISTS `peminjam` (
  `id_peminjam` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `no_telp` text NOT NULL,
  `jabatan` text NOT NULL,
  `sandi` text NOT NULL,
  `id_kategori` int(10) unsigned DEFAULT NULL,
  `id_instansi` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_peminjam`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_instansi` (`id_instansi`),
  CONSTRAINT `peminjam_ibfk_185` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `peminjam_ibfk_186` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.peminjam: ~4 rows (approximately)
/*!40000 ALTER TABLE `peminjam` DISABLE KEYS */;
REPLACE INTO `peminjam` (`id_peminjam`, `nama`, `no_telp`, `jabatan`, `sandi`, `id_kategori`, `id_instansi`) VALUES
	(3, 'Dion Ulysa', '546745675663', 'Ketua Mahasiswa Fakultas Hukum', '$2b$10$zj.QuAz2JF4po56fDL1aLOjPh/65X280y6z5q/TZlgW3rJcoVt08C', 1, 1),
	(4, 'Innocent Mang', '93827263642', 'Ketua Mahasiswa STIKOM Uyelindo', '$2b$10$KizurLKadxz4qCEE1n.AKuN8B1NZzjulgsmZRtgW7HcWOKNRVeek6', 2, 2),
	(5, 'Alyssa Anan', '111111111111', 'Mahasiswa', '$2b$10$6pU2qelVN8UHz/1ITgvl6O2XBZxOsgyyoe.TlRQAFN4g3MxcH4GXW', 2, 3),
	(7, 'Leo Saans', '111111111111', 'Mahasiswa', '$2b$10$sa3OPbx7w2RQyw3VwNW3lOt/Uc081uRT46Oz0R8LwGyYIqS5Bk3hK', 2, 2);
/*!40000 ALTER TABLE `peminjam` ENABLE KEYS */;

-- Dumping structure for table asti.peminjaman
DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE IF NOT EXISTS `peminjaman` (
  `id_peminjaman` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_peminjaman` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL,
  `id_peminjam` int(10) unsigned NOT NULL,
  `tujuan_peminjaman` text NOT NULL,
  `setuju_1_pinjam` varchar(4) NOT NULL,
  `setuju_2_pinjam` varchar(4) NOT NULL,
  `setuju_3_pinjam` varchar(4) NOT NULL,
  `status_peminjaman` tinyint(4) unsigned NOT NULL,
  `keterangan_peminjaman` text DEFAULT NULL,
  `id_admin` int(10) unsigned NOT NULL,
  `tanggal_pengembalian` datetime NOT NULL,
  `setuju_1_kembali` varchar(4) NOT NULL,
  `setuju_2_kembali` varchar(4) NOT NULL,
  `setuju_3_kembali` varchar(4) NOT NULL,
  `status_pengembalian` tinyint(4) NOT NULL,
  `keterangan_pengembalian` text DEFAULT NULL,
  PRIMARY KEY (`id_peminjaman`),
  KEY `FK_PEMINJAM` (`id_peminjam`),
  KEY `FK_ADMIN3` (`id_admin`),
  CONSTRAINT `FK_ADMIN3` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`),
  CONSTRAINT `FK_PEMINJAM` FOREIGN KEY (`id_peminjam`) REFERENCES `peminjam` (`id_peminjam`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.peminjaman: ~0 rows (approximately)
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;

-- Dumping structure for table asti.perolehan
DROP TABLE IF EXISTS `perolehan`;
CREATE TABLE IF NOT EXISTS `perolehan` (
  `id_perolehan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL,
  `id_pemasok` int(10) unsigned NOT NULL,
  `status` enum('pembelian','bantuan','penyesuaian stok') NOT NULL DEFAULT 'pembelian',
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_perolehan`),
  KEY `FK_PEMASOK` (`id_pemasok`),
  CONSTRAINT `FK_PEMASOK` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.perolehan: ~0 rows (approximately)
/*!40000 ALTER TABLE `perolehan` DISABLE KEYS */;
/*!40000 ALTER TABLE `perolehan` ENABLE KEYS */;

-- Dumping structure for table asti.perusahaan
DROP TABLE IF EXISTS `perusahaan`;
CREATE TABLE IF NOT EXISTS `perusahaan` (
  `id_perusahaan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `alamat` text NOT NULL,
  `email` text NOT NULL,
  `no_telp` text NOT NULL,
  `logo` text DEFAULT NULL,
  `sejarah_singkat` text DEFAULT NULL,
  `deskripsi` text NOT NULL,
  `banner` text DEFAULT NULL,
  `warna_bg` varchar(10) DEFAULT NULL,
  `warna_huruf` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_perusahaan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table asti.perusahaan: ~0 rows (approximately)
/*!40000 ALTER TABLE `perusahaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `perusahaan` ENABLE KEYS */;

-- Dumping structure for table asti.ruang
DROP TABLE IF EXISTS `ruang`;
CREATE TABLE IF NOT EXISTS `ruang` (
  `id_ruang` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `id_unit` int(10) unsigned NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `keterangan` text DEFAULT '-',
  PRIMARY KEY (`id_ruang`),
  KEY `FK_UNIT2` (`id_unit`),
  CONSTRAINT `FK_UNIT2` FOREIGN KEY (`id_unit`) REFERENCES `unit` (`id_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.ruang: ~0 rows (approximately)
/*!40000 ALTER TABLE `ruang` DISABLE KEYS */;
/*!40000 ALTER TABLE `ruang` ENABLE KEYS */;

-- Dumping structure for table asti.unit
DROP TABLE IF EXISTS `unit`;
CREATE TABLE IF NOT EXISTS `unit` (
  `id_unit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `sandi` text NOT NULL,
  `keterangan` text DEFAULT '-',
  PRIMARY KEY (`id_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table asti.unit: ~0 rows (approximately)
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;
/*!40000 ALTER TABLE `unit` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
