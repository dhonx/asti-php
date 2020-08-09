/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: admin
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: barang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: detail_mutasi_barang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `detail_mutasi_barang`;
CREATE TABLE `detail_mutasi_barang` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: detail_pembuangan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `detail_pembuangan`;
CREATE TABLE `detail_pembuangan` (
  `id_pembuangan` int(10) unsigned NOT NULL,
  `id_barang` int(10) unsigned NOT NULL,
  `kondisi_barang` enum('Y','N') NOT NULL,
  `keterangan` text DEFAULT NULL,
  KEY `FK_PEMBUANGAN` (`id_pembuangan`),
  KEY `FK_BARANG4` (`id_barang`),
  CONSTRAINT `FK_BARANG4` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  CONSTRAINT `FK_PEMBUANGAN` FOREIGN KEY (`id_pembuangan`) REFERENCES `pembuangan` (`id_pembuangan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: detail_pemesanan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `detail_pemesanan`;
CREATE TABLE `detail_pemesanan` (
  `id_pemesanan` int(10) unsigned NOT NULL,
  `id_komponen` int(10) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  KEY `FK_PEMESANAN` (`id_pemesanan`),
  KEY `FK_KOMPONEN2` (`id_komponen`),
  CONSTRAINT `FK_KOMPONEN2` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`),
  CONSTRAINT `FK_PEMESANAN` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: detail_peminjaman
# ------------------------------------------------------------

DROP TABLE IF EXISTS `detail_peminjaman`;
CREATE TABLE `detail_peminjaman` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: detail_perolehan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `detail_perolehan`;
CREATE TABLE `detail_perolehan` (
  `id_perolehan` int(10) unsigned NOT NULL,
  `id_komponen` int(10) unsigned NOT NULL,
  `harga_beli` int(10) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  KEY `id_order` (`id_perolehan`),
  KEY `FK_KOMPONEN3` (`id_komponen`),
  CONSTRAINT `FK_KOMPONEN3` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`),
  CONSTRAINT `FK_ORDER2` FOREIGN KEY (`id_perolehan`) REFERENCES `perolehan` (`id_perolehan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: instansi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `instansi`;
CREATE TABLE `instansi` (
  `id_instansi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `alamat` text NOT NULL,
  `email` text NOT NULL,
  `no_telp` text NOT NULL,
  PRIMARY KEY (`id_instansi`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: kategori
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id_kategori` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  PRIMARY KEY (`id_kategori`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: komponen
# ------------------------------------------------------------

DROP TABLE IF EXISTS `komponen`;
CREATE TABLE `komponen` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: mutasi_barang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mutasi_barang`;
CREATE TABLE `mutasi_barang` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: mutasi_pegawai
# ------------------------------------------------------------

DROP TABLE IF EXISTS `mutasi_pegawai`;
CREATE TABLE `mutasi_pegawai` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pegawai
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id_pegawai` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `no_pegawai` text NOT NULL,
  `nama_pegawai` text NOT NULL,
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `no_pegawai` (`no_pegawai`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pemasok
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE `pemasok` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pembuangan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pembuangan`;
CREATE TABLE `pembuangan` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pemeliharaan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pemeliharaan`;
CREATE TABLE `pemeliharaan` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pemesanan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal_pesan` date NOT NULL,
  `id_pegawai` int(10) unsigned NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('Usulan','Diterima','Dalam Proses Pemesanan','Ditunda','DItolak') NOT NULL DEFAULT 'Usulan',
  PRIMARY KEY (`id_pemesanan`),
  KEY `FK_PEGAWAI` (`id_pegawai`),
  CONSTRAINT `FK_PEGAWAI` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: peminjam
# ------------------------------------------------------------

DROP TABLE IF EXISTS `peminjam`;
CREATE TABLE `peminjam` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: peminjaman
# ------------------------------------------------------------

DROP TABLE IF EXISTS `peminjaman`;
CREATE TABLE `peminjaman` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: perolehan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `perolehan`;
CREATE TABLE `perolehan` (
  `id_perolehan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL,
  `id_pemasok` int(10) unsigned NOT NULL,
  `status` enum('Pembelian','Bantuan','Penyesuaian Stok') NOT NULL DEFAULT 'Pembelian',
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id_perolehan`),
  KEY `FK_PEMASOK` (`id_pemasok`),
  CONSTRAINT `FK_PEMASOK` FOREIGN KEY (`id_pemasok`) REFERENCES `pemasok` (`id_pemasok`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: perusahaan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `perusahaan`;
CREATE TABLE `perusahaan` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: ruang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ruang`;
CREATE TABLE `ruang` (
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

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: unit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unit`;
CREATE TABLE `unit` (
  `id_unit` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `sandi` text NOT NULL,
  `keterangan` text DEFAULT '-',
  PRIMARY KEY (`id_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: admin
# ------------------------------------------------------------

INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    1,
    'Don Nisnoni',
    'donnisnoni.tid3@gmail.com',
    '081234567892',
    '$2b$10$7BIVDCVuJb9KHcqmBtDugexESgrobiYrYHj62oM6vRVesoM8fbZ/K',
    1,
    'super admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    2,
    'Andi Kore',
    'adni.kore@gmail.com',
    '08123456789322',
    '$2b$10$ZDhOfJQk/3VXpQZeN2AppOQEfDK6vRgLn/YlYW3tt86lk4KWwzn7K',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    3,
    'Donna Nisnoni',
    'donna@gmail.com',
    '90909090907',
    '$2b$10$SqN0Mr5pO8ZwZiWRn.T5O.b1jWh.Q672P7V1JC5QO0eVnOOw57.nK',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    4,
    'Emanuel Safirman Bata',
    'emanuelbata@yahoo.co.id',
    '085123456789',
    '$2b$10$NOy5gWfMU1yY/97GkyBym.20h4ZOanbxrR9H8YnZJm8/Kqh7YZWpi',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    5,
    'Idris Koli',
    'idriskoli@gmail.com',
    '081234778990',
    '$2b$10$02gH0cuG9VLYwUj6axpCIOPwpralwSzntjP3W5VJddYLTpvbXhCXe',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    6,
    'Chece Balla',
    'checeballa@gmail.com',
    '097812732435',
    '$2b$10$csAKSegL42M656PIRRkLJet07QEIELo0jUO/6EfXnt0RCbK8yTw8G',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    7,
    'Ana Delfy',
    'anna.delfy@gmail.com',
    '083345337756',
    '$2b$10$16dTBr/LmJjqkVlHOQ5uouJ0jqQB0gOQNaXsLIH4FVVdbWUZbp3pq',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    8,
    'Chris Nisnoni',
    'chris.tid3@gmail.com',
    '081239272732',
    '$2b$10$9CpirPnsBEZ9agFhXpDihuGXIPEaNnU.dRAxRi/.8K9foxfNzaFLG',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    9,
    'Ariel Dillak',
    'arieldillak@gmail.com',
    '836427364327',
    '$2b$10$HmtIWSBtYVc5LJsk4H1Yg.n5UZ9yjsPZcspFYxOpuTPcPFCwdZ/IG',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    10,
    'Luki Farmas',
    'lukifarma@gmail.com',
    '764562356325',
    '$2b$10$hs1oDgxdaSWM5uYAyFSEuu5FZbiskZKNwLuc9OyYGYjAHl5v9Hbie',
    1,
    'admin'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`
  )
VALUES
  (
    20,
    'Randy Orton',
    'randi-orton@yahoo.com',
    '084376436646',
    '$2b$10$R7.MGH2bCCNKBFqAEpi1bu8bXUYZj5zf7NNAb9EU.Wj.SGh7iooHW',
    1,
    'admin'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: barang
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: detail_mutasi_barang
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: detail_pembuangan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: detail_pemesanan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: detail_peminjaman
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: detail_perolehan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: instansi
# ------------------------------------------------------------

INSERT INTO
  `instansi` (`id_instansi`, `nama`, `alamat`, `email`, `no_telp`)
VALUES
  (1, 'Internal', '-', '-', '-');
INSERT INTO
  `instansi` (`id_instansi`, `nama`, `alamat`, `email`, `no_telp`)
VALUES
  (
    2,
    'Universitas Kristen Artha Wacana',
    'Jl. Adisucipto 147 - Oesapa',
    'ukaw_kupang@yahoo.co.id',
    '+62380881584'
  );
INSERT INTO
  `instansi` (`id_instansi`, `nama`, `alamat`, `email`, `no_telp`)
VALUES
  (
    3,
    'Universitas Nusa Cendana',
    'Jl. Adisucipto - Penfui',
    'info@undana.ac.id',
    '+82380 881580'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: kategori
# ------------------------------------------------------------

INSERT INTO
  `kategori` (`id_kategori`, `nama`)
VALUES
  (1, 'Internal');
INSERT INTO
  `kategori` (`id_kategori`, `nama`)
VALUES
  (2, 'Eksternal');

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: komponen
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: mutasi_barang
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: mutasi_pegawai
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pegawai
# ------------------------------------------------------------

INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (1, '6437474375734643', 'Kevin Clark');
INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (2, '3467273464328756', 'John Vice');
INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (3, '4379854937465845', 'Hendrik Ken');
INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (7, '682736456', 'Julio Cesarxss');
INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (8, '37643657', 'Kevin Clays');
INSERT INTO
  `pegawai` (`id_pegawai`, `no_pegawai`, `nama_pegawai`)
VALUES
  (9, '7364635245', 'Hendri Asam');

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pemasok
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pembuangan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pemeliharaan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pemesanan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: peminjam
# ------------------------------------------------------------

INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `no_telp`,
    `jabatan`,
    `sandi`,
    `id_kategori`,
    `id_instansi`
  )
VALUES
  (
    3,
    'Dion Ulysa',
    '546745675663',
    'Ketua Mahasiswa Fakultas Hukum',
    '$2b$10$zj.QuAz2JF4po56fDL1aLOjPh/65X280y6z5q/TZlgW3rJcoVt08C',
    1,
    1
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `no_telp`,
    `jabatan`,
    `sandi`,
    `id_kategori`,
    `id_instansi`
  )
VALUES
  (
    4,
    'Innocent Mang',
    '93827263642',
    'Ketua Mahasiswa STIKOM Uyelindo',
    '$2b$10$KizurLKadxz4qCEE1n.AKuN8B1NZzjulgsmZRtgW7HcWOKNRVeek6',
    2,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `no_telp`,
    `jabatan`,
    `sandi`,
    `id_kategori`,
    `id_instansi`
  )
VALUES
  (
    5,
    'Alyssa Anan',
    '111111111111',
    'Mahasiswa',
    '$2b$10$6pU2qelVN8UHz/1ITgvl6O2XBZxOsgyyoe.TlRQAFN4g3MxcH4GXW',
    2,
    3
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `no_telp`,
    `jabatan`,
    `sandi`,
    `id_kategori`,
    `id_instansi`
  )
VALUES
  (
    7,
    'Leo Saans',
    '111111111111',
    'Mahasiswa',
    '$2b$10$sa3OPbx7w2RQyw3VwNW3lOt/Uc081uRT46Oz0R8LwGyYIqS5Bk3hK',
    2,
    2
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: peminjaman
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: perolehan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: perusahaan
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: ruang
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: unit
# ------------------------------------------------------------


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
