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
  `aktif` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `tipe_admin` enum('admin','super_admin') NOT NULL DEFAULT 'admin',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_admin`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: barang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `barang`;
CREATE TABLE `barang` (
  `id_barang` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_inventaris` text NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `aktif` tinyint(3) unsigned DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `kondisi` enum('baik','rusak_ringan','rusak_berat') NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_komponen` int(10) unsigned DEFAULT NULL,
  `id_admin` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_barang`),
  KEY `id_komponen` (`id_komponen`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`id_komponen`) REFERENCES `komponen` (`id_komponen`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `barang_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: instansi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `instansi`;
CREATE TABLE `instansi` (
  `id_instansi` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `email` text NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_instansi`),
  UNIQUE KEY `email` (`email`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: kategori
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id_kategori` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

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
  `aktif` tinyint(3) unsigned DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_komponen`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `komponen_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pegawai
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pegawai`;
CREATE TABLE `pegawai` (
  `id_pegawai` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `no_pegawai` tinytext NOT NULL,
  `nama` tinytext NOT NULL,
  `email` tinytext NOT NULL,
  `sandi` tinytext NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_pegawai`),
  UNIQUE KEY `no_pegawai` (`no_pegawai`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: pemasok
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pemasok`;
CREATE TABLE `pemasok` (
  `id_pemasok` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `no_telp` text NOT NULL,
  `alamat` text NOT NULL,
  `email` text NOT NULL,
  `nama_pemilik` text NOT NULL,
  `aktif` tinyint(3) unsigned DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_admin` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_pemasok`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `pemasok_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# ------------------------------------------------------------
# SCHEMA DUMP FOR TABLE: peminjam
# ------------------------------------------------------------

DROP TABLE IF EXISTS `peminjam`;
CREATE TABLE `peminjam` (
  `id_peminjam` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nama` text NOT NULL,
  `jabatan` text NOT NULL DEFAULT '',
  `no_telp` text NOT NULL,
  `sandi` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_instansi` int(10) unsigned DEFAULT NULL,
  `id_kategori` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id_peminjam`),
  KEY `id_kategori` (`id_kategori`),
  KEY `id_instansi` (`id_instansi`),
  CONSTRAINT `peminjam_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `peminjam_ibfk_2` FOREIGN KEY (`id_instansi`) REFERENCES `instansi` (`id_instansi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

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
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    'Don Nisnoni',
    'donnisnoni.tid3@gmail.com',
    '081234567892',
    '$2y$10$UxxptXtGbiP1sEluVpua8uTA.Y6x91taXvqU3rgvLGvDZVIkLN63G',
    1,
    'super_admin',
    '2020-07-22 22:20:01',
    '2020-07-22 22:20:01'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    3,
    'Donna Nisnoni',
    'donna@gmail.com',
    '90909090907',
    '$2b$10$SqN0Mr5pO8ZwZiWRn.T5O.b1jWh.Q672P7V1JC5QO0eVnOOw57.nK',
    1,
    'admin',
    '2020-07-21 00:26:02',
    '2020-07-21 00:26:02'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    4,
    'Emanuel Safirman Bata',
    'emanuelbata@yahoo.co.id',
    '85123456789',
    '$2b$10$NOy5gWfMU1yY/97GkyBym.20h4ZOanbxrR9H8YnZJm8/Kqh7YZWpi',
    0,
    'admin',
    '2020-07-20 00:16:34',
    '2020-07-20 00:16:34'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    5,
    'Idris Koli',
    'idriskoli@gmail.com',
    '081234778990',
    '$2b$10$02gH0cuG9VLYwUj6axpCIOPwpralwSzntjP3W5VJddYLTpvbXhCXe',
    0,
    'admin',
    '2020-07-20 23:20:56',
    '2020-07-25 19:36:33'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    6,
    'Chece Balla',
    'checeballa@gmail.com',
    '97812732435',
    '$2b$10$csAKSegL42M656PIRRkLJet07QEIELo0jUO/6EfXnt0RCbK8yTw8G',
    1,
    'admin',
    '2020-07-20 01:23:59',
    '2020-07-20 01:23:59'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    7,
    'Ana Delfy',
    'anna.delfy@gmail.com',
    '83345337756',
    '$2b$10$16dTBr/LmJjqkVlHOQ5uouJ0jqQB0gOQNaXsLIH4FVVdbWUZbp3pq',
    1,
    'admin',
    '2020-07-19 23:32:58',
    '2020-07-19 23:32:58'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    8,
    'Chris Nisnoni',
    'chris.tid3@gmail.com',
    '81239272732',
    '$2b$10$9CpirPnsBEZ9agFhXpDihuGXIPEaNnU.dRAxRi/.8K9foxfNzaFLG',
    1,
    'admin',
    '2020-07-21 00:21:14',
    '2020-07-21 00:21:14'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    9,
    'Ariel Dillak',
    'arieldillak@gmail.com',
    '836427364327',
    '$2b$10$HmtIWSBtYVc5LJsk4H1Yg.n5UZ9yjsPZcspFYxOpuTPcPFCwdZ/IG',
    1,
    'admin',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    10,
    'Luki Farma',
    'lukifarma@gmail.com',
    '764562356325',
    '$2b$10$hs1oDgxdaSWM5uYAyFSEuu5FZbiskZKNwLuc9OyYGYjAHl5v9Hbie',
    1,
    'admin',
    '2020-07-20 01:53:05',
    '2020-07-20 01:53:05'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    11,
    'Randy Orton',
    'randi-orton@yahoo.com',
    '84376436646',
    '$2b$10$R7.MGH2bCCNKBFqAEpi1bu8bXUYZj5zf7NNAb9EU.Wj.SGh7iooHW',
    1,
    'admin',
    '2020-07-19 22:36:28',
    '2020-07-19 22:36:28'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    12,
    'Rian Malaikosa',
    'rian.malaikosa@data.co.id',
    '444444444444',
    '$2b$10$COc5qIyTaCxk069QtbQ08eBcpULzBd5NQeZlmkArVbSgVWZPWl.M6',
    1,
    'admin',
    '2020-07-20 23:20:35',
    '2020-07-20 23:20:35'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    13,
    'Diana Luan',
    'dianaluan@yahoo.co.id',
    '22222222',
    '$2y$10$0XY7sf1io3wM4F96p9ZdLeXSngCcpiLg8siIBt/QLRrzIt529G1ga',
    1,
    'admin',
    '2020-07-20 01:31:54',
    '2020-07-20 01:31:54'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    15,
    'Rio De Janeiro',
    'donnisnoni@uyelindo.ac.id',
    '99999999',
    '$2y$10$4CnH8aOpZl8ITsoOaV6ex.Rd3oxMjY3xHK0kfpPrQoeNkFiO3La8C',
    1,
    'admin',
    '2020-07-21 00:16:13',
    '2020-07-21 00:16:13'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    21,
    'Dion Rasi',
    'dionrasi@gmail.com',
    '085239272732',
    '$2y$10$pwvrvoW8BCHq0JhI1visWeJ8E/diaZxs7EtTkp6J4lHwfUSBbwhRe',
    1,
    'admin',
    '2020-07-21 00:17:18',
    '2020-07-21 00:17:18'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    22,
    'Aldi Vulus',
    'donnisnoni.tid3x@gmail.com',
    '11111111111',
    '$2y$10$f7BTHI.csNGR5M.0GQwOVezXpl/FRk2DvQI5.6pMiiBMr5/j2H9hS',
    1,
    'admin',
    '2020-07-22 20:55:46',
    '2020-07-22 20:55:46'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    23,
    'Lorensius Lama',
    'lorensius@gmail.com',
    '22222222',
    '$2y$10$1Fwr5BlQxmGtA.ql34D00.y904WdZpE8c90ioKbQPG3arkSv9dxp2',
    0,
    'admin',
    '2020-07-23 19:05:09',
    '2020-07-23 19:05:22'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    24,
    'Ignasius Uly',
    'ignasius@gmail.com',
    '111111111111',
    '$2y$10$s1eGF.0tf7dYELiZjrP2MemSuRSuFH6HESGeIK9InYnCul7N3dCxO',
    1,
    'admin',
    '2020-07-23 19:18:37',
    '2020-07-23 19:18:37'
  );
INSERT INTO
  `admin` (
    `id_admin`,
    `nama`,
    `email`,
    `no_telp`,
    `sandi`,
    `aktif`,
    `tipe_admin`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    26,
    'Chicko Jeriko',
    'chicko.jeriko@gmail.com',
    '111111111111',
    '$2y$10$LYOrqrFJCvAsy9YBHkJDgewTshq0nbmqlAdAoQDZkw3IDsorrgRAq',
    1,
    'admin',
    '2020-07-25 23:46:44',
    '2020-07-28 23:36:28'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: barang
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: instansi
# ------------------------------------------------------------

INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    1,
    'Internal',
    '-',
    '-',
    '-',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    2,
    'Universitas Nusa Cendana',
    'info@undana.ac.id',
    'Jl. Adisucipto - Penfui',
    '38088158088',
    '2020-07-28 21:34:39',
    '2020-07-28 21:34:39'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    3,
    'Universitas Kristen Artha Wacana',
    'ukaw_kupang@yahoo.co.id',
    'Jl.  Adisucipto 147 - Oesapa',
    '623808810509',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    4,
    'Unika Widya Mandira',
    'info@unwira.ac.id',
    'Jl. Jend. Achmad Yani No.50-52 - Oeba',
    '380833395',
    '2020-07-28 23:35:35',
    '2020-07-28 23:35:35'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    5,
    'STIKOM Artha Buana',
    'stikom.artha.buana@gmail.com',
    'Jl. Samratulangi 3 No.1 - Oesapa',
    '3808431084',
    '2020-07-28 21:34:46',
    '2020-07-28 21:34:46'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    6,
    'STIKES Nusantara Kupang',
    'stikesnskupang@gmail.com',
    'Jl. Nibaki 99 -  Liliba',
    '380828233',
    '2020-07-29 00:43:12',
    '2020-07-29 00:43:12'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    7,
    'Politeknik Negeri Kupang',
    'surat@pnk.ac.id',
    'Jl. Adi Sucipto - Penfui',
    '0380881245',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );
INSERT INTO
  `instansi` (
    `id_instansi`,
    `nama`,
    `email`,
    `alamat`,
    `no_telp`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    8,
    'Politeknik Pertanian Negeri Kupang',
    'politanikoe@yahoo.com',
    'Jl. Adisucipto - Penfui ',
    '380881600',
    '2020-07-28 22:49:14',
    '2020-07-28 22:49:14'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: kategori
# ------------------------------------------------------------

INSERT INTO
  `kategori` (`id_kategori`, `nama`, `created_at`, `updated_at`)
VALUES
  (
    1,
    'Internal',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );
INSERT INTO
  `kategori` (`id_kategori`, `nama`, `created_at`, `updated_at`)
VALUES
  (
    2,
    'Eksternal',
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: komponen
# ------------------------------------------------------------

INSERT INTO
  `komponen` (
    `id_komponen`,
    `nama`,
    `tipe`,
    `merek`,
    `spesifikasi`,
    `keterangan`,
    `aktif`,
    `created_at`,
    `updated_at`,
    `id_admin`
  )
VALUES
  (
    1,
    'Laptop Acer E5-471',
    'E5-471',
    'ACER',
    'Operating System:  Linpus™ Linux®\nProcessor Manufacturer: Intel®\nProcessor Type: Core™ i3\nProcessor Model: i3-4030U\nProcessor Speed: 1.80 GHz\nGraphics Controller Manufacturer: Intel®\nGraphics Memory Technology: DDR3 SDRAM\nGraphics Memory Accessibility: Shared\nScreen Size: 35.6 cm (14\")\nDisplay Screen Type: LCD\nDisplay Screen Technology: CineCrystal\nScreen Mode: HD\nBacklight Technology: LED\nScreen Resolution: 1366 x 768\nStandard Memory: 4 GB\nMemory Technology: DDR3L SDRAM\nNumber of Total Memory Slots: 2\nMemory Card Reader: Yes\nMemory Card Supported: Secure Digital (SD) Card\nTotal Hard Drive Capacity: 500 GB\nOptical Drive Type: DVD-Writer\nWireless LAN Standard: IEEE 802.11b/g/n\nEthernet Technology: Gigabit Ethernet\nMicrophone: Yes\nFinger Print Reader: No\nHDMI: Yes\nVGA: Yes\nTotal Number of USB Ports: 3\nNetwork (RJ-45): Yes\nOperating System: Linpus™ Linux®\nPointing Device Type: TouchPad\nKeyboard: Yes\nNumber of Cells: 6-cell\nBattery Chemistry: Lithium Ion (Li-Ion)\nBattery Capacity: 2500 mAh\nMaximum Power Supply Wattage: 40 W\nHeight: 30.30 mm\nHeight (Front): 25.30 mm\nHeight (Rear): 30.30 mm\nWidth: 346 mm\nDepth: 248 mm\nWeight (Approximate): 2.30 kg\nColour: Red\nWireless LAN: Acer Nplify 802.11b/g/nxxxx',
    '-',
    1,
    '2020-06-25 15:30:36',
    '2020-06-25 15:30:36',
    1
  );
INSERT INTO
  `komponen` (
    `id_komponen`,
    `nama`,
    `tipe`,
    `merek`,
    `spesifikasi`,
    `keterangan`,
    `aktif`,
    `created_at`,
    `updated_at`,
    `id_admin`
  )
VALUES
  (
    3,
    'wqdsadqw',
    'sfsafe',
    'sdcsfreds',
    'sefeaswfasw',
    'e',
    1,
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00',
    1
  );
INSERT INTO
  `komponen` (
    `id_komponen`,
    `nama`,
    `tipe`,
    `merek`,
    `spesifikasi`,
    `keterangan`,
    `aktif`,
    `created_at`,
    `updated_at`,
    `id_admin`
  )
VALUES
  (
    4,
    'makexxx',
    'hxgxgxg',
    'sksjshsg',
    'gxgxg',
    'hxgxgx',
    1,
    '0000-00-00 00:00:00',
    '0000-00-00 00:00:00',
    1
  );
INSERT INTO
  `komponen` (
    `id_komponen`,
    `nama`,
    `tipe`,
    `merek`,
    `spesifikasi`,
    `keterangan`,
    `aktif`,
    `created_at`,
    `updated_at`,
    `id_admin`
  )
VALUES
  (
    5,
    'Windows 10 Pro',
    'Pro',
    'Windows',
    '-----',
    '----',
    1,
    NULL,
    '2020-06-25 15:41:54',
    1
  );
INSERT INTO
  `komponen` (
    `id_komponen`,
    `nama`,
    `tipe`,
    `merek`,
    `spesifikasi`,
    `keterangan`,
    `aktif`,
    `created_at`,
    `updated_at`,
    `id_admin`
  )
VALUES
  (
    6,
    'Macbook Air',
    'Air',
    'Apple Inc',
    'RAM: 2GB',
    'hcukcuawk',
    1,
    NULL,
    '2020-06-25 15:40:21',
    1
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pegawai
# ------------------------------------------------------------

INSERT INTO
  `pegawai` (
    `id_pegawai`,
    `no_pegawai`,
    `nama`,
    `email`,
    `sandi`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    12,
    '1513001600',
    'Bryan Adams',
    'bryanadams@gmail.com',
    '$2y$10$7WTRuelkRVRZK4IiVQmsmej7EDd4u6.l84xcblChs7u0Bdg.U.Jeu',
    '2020-07-30 11:03:35',
    '2020-07-30 11:03:35'
  );
INSERT INTO
  `pegawai` (
    `id_pegawai`,
    `no_pegawai`,
    `nama`,
    `email`,
    `sandi`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    13,
    '1513001700',
    'Billie Elishx',
    'billie.elish@gmail.com',
    '$2y$10$saCEAUvfimXviox4lycrLOywtAckXP8Bw7xxq2uKCSPsYuquxOJyy',
    '2020-07-30 11:15:47',
    '2020-07-30 11:58:02'
  );
INSERT INTO
  `pegawai` (
    `id_pegawai`,
    `no_pegawai`,
    `nama`,
    `email`,
    `sandi`,
    `created_at`,
    `updated_at`
  )
VALUES
  (
    14,
    '1513001900',
    'Scott Adkins',
    'scottadkins@gmail.com',
    '$2y$10$Bx89ShwYaCvVPzKmPS1J2er2y6vYfeD.RNqgBvPosrrl7thHqSUg6',
    '2020-07-30 12:08:51',
    '2020-07-30 12:08:51'
  );

# ------------------------------------------------------------
# DATA DUMP FOR TABLE: pemasok
# ------------------------------------------------------------


# ------------------------------------------------------------
# DATA DUMP FOR TABLE: peminjam
# ------------------------------------------------------------

INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    14,
    'Gregorius Nahak',
    'Mahasiswa',
    '08136353421',
    '$2b$10$igQPRch5PfQwlOKHwxB49O3sEDANOzp1XX2.FGlBXabpT8Q59L65q',
    '2020-07-30 22:36:50',
    '2020-07-30 22:36:57',
    6,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    15,
    'Linda Jordan',
    'Ketua Mahasiswa',
    '82753543421',
    '$2b$10$P.W1E7qvtvWF.aebm6tr1.FOYCA1TX3Nqf2nzzirLLY2yF1zQj0Ay',
    '2020-07-30 22:36:44',
    '2020-07-30 22:37:02',
    5,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    16,
    'Hendrik Rasyam',
    'Mahasiswa',
    '716253434323',
    '$2b$10$HWQE/YsFwkQmH6mXWOjQEunKMLx4t54Zun3wH4HsPMYt.Kz7do8re',
    '2020-07-30 13:25:53',
    '2020-07-30 13:25:54',
    6,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    18,
    'Kevin Lassagna',
    'Mahasiswa',
    '012345678912',
    '$2y$10$eMn3F9TJa08xOu0ftxbC1.MFyCLCM6Cri1FTdDX0tgJ3MW88Mz.7K',
    '2020-07-30 22:20:52',
    '2020-07-30 22:20:52',
    2,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    19,
    'Lissa Right',
    'Mahasiswa',
    '123456789012',
    '$2y$10$6phinaLNETGbvnATDbJeD.hRaM/z2vRdL1zmyahOCOFfXIb68mMrO',
    '2020-07-30 22:35:38',
    '2020-07-30 22:35:38',
    3,
    2
  );
INSERT INTO
  `peminjam` (
    `id_peminjam`,
    `nama`,
    `jabatan`,
    `no_telp`,
    `sandi`,
    `created_at`,
    `updated_at`,
    `id_instansi`,
    `id_kategori`
  )
VALUES
  (
    20,
    'Zayn Malik',
    'Mahasiswa',
    '999999999999',
    '$2y$10$tpva6aklYtWom53WDNUcJOIB29.9WjmCI91YqUZpY5mVp7Kiilq3y',
    '2020-07-31 11:02:51',
    '2020-07-31 11:02:51',
    8,
    2
  );

/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
