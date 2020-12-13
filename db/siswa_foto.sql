-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `siswa_foto`;
CREATE TABLE `siswa_foto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nis` varchar(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `telp` varchar(15) NOT NULL,
  `alamat` text NOT NULL,
  `foto` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `siswa_foto` (`id`, `nis`, `nama`, `jenis_kelamin`, `telp`, `alamat`, `foto`) VALUES
(32,	'5555',	'Anita Mui',	'Perempuan',	'5555',	'New York',	'13122020161027img3.jpg'),
(33,	'444',	'John Lark',	'Laki-laki',	'888',	'Nevada',	'13122020161107img1.jpg');

-- 2020-12-13 16:11:44
