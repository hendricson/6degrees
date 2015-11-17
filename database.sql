/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table 6degrees.connections
CREATE TABLE IF NOT EXISTS `connections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `connect_from` int(11) NOT NULL,
  `connect_to` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `connect_from` (`connect_from`,`connect_to`,`status`),
  KEY `idx_connect_to` (`connect_to`),
  KEY `idx_connect_from` (`connect_from`),
  KEY `idx_connect_tofrom` (`connect_to`,`connect_from`)
) ENGINE=MyISAM AUTO_INCREMENT=136 DEFAULT CHARSET=utf8;

-- Dumping data for table 6degrees.connections: 53 rows
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
INSERT INTO `connections` (`id`, `connect_from`, `connect_to`, `status`) VALUES
	(4, 80, 66, 1),
	(5, 66, 80, 1),
	(13, 65, 64, 1),
	(14, 64, 65, 1),
	(16, 66, 78, 1),
	(17, 78, 66, 1),
	(18, 65, 67, 1),
	(19, 67, 65, 1),
	(21, 66, 62, 1),
	(22, 62, 66, 1),
	(23, 79, 74, 1),
	(24, 74, 79, 1),
	(25, 65, 82, 1),
	(26, 82, 65, 1),
	(27, 67, 71, 1),
	(28, 71, 67, 1),
	(29, 80, 75, 1),
	(30, 75, 80, 1),
	(31, 65, 72, 1),
	(32, 72, 65, 1),
	(33, 65, 68, 1),
	(34, 68, 65, 1),
	(37, 68, 69, 1),
	(38, 69, 68, 1),
	(40, 69, 79, 1),
	(41, 79, 69, 1),
	(49, 62, 102, 0),
	(55, 62, 65, 1),
	(57, 75, 74, 1),
	(58, 74, 92, 1),
	(59, 92, 74, 1),
	(60, 79, 75, 1),
	(61, 65, 62, 1),
	(62, 94, 93, 1),
	(63, 93, 94, 1),
	(65, 119, 93, 1),
	(66, 93, 119, 1),
	(67, 94, 93, 1),
	(68, 93, 94, 1),
	(71, 119, 62, 1),
	(72, 62, 119, 1),
	(73, 62, 120, 0),
	(80, 117, 119, 1),
	(81, 119, 117, 1),
	(109, 62, 153, 0),
	(124, 62, 94, 1),
	(125, 94, 62, 1),
	(130, 68, 76, 1),
	(131, 76, 68, 1),
	(132, 64, 81, 1),
	(133, 81, 64, 1),
	(134, 62, 104, 1),
	(135, 104, 62, 1);
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;


-- Dumping structure for table 6degrees.friends
CREATE TABLE IF NOT EXISTS `friends` (
  `id_user` int(11) unsigned NOT NULL DEFAULT '0',
  `friends1` text COMMENT '1st circle: friends',
  `friends2` text COMMENT '2nd circle: friends of friends',
  `friends3` text COMMENT '3d circle: friends of friends of friends',
  `friends4` text COMMENT '4th circle: friends of friends of friends of friends',
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- Dumping structure for table 6degrees.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=164 DEFAULT CHARSET=utf8;

-- Dumping data for table 6degrees.users: 44 rows
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`) VALUES
	(62, 'Sergey Brin'),
	(64, 'Joel Maxwell'),
	(65, 'Andrew Bleu'),
	(66, 'Maria Maxwell'),
	(67, 'Covert Innoff'),
	(68, 'Justin Hoffman'),
	(69, 'Diego Gonzalez'),
	(70, 'Rosalie Grace'),
	(71, 'Paula Kristina'),
	(72, 'Joss Rebel'),
	(73, 'Manuela Brasgo'),
	(74, 'John Luanda'),
	(75, 'Philip Roland'),
	(76, 'Brad Gridge'),
	(77, 'Rosanne Deborah'),
	(78, 'Savannah Grace'),
	(79, 'Jennifer Land'),
	(80, 'Michael Ray'),
	(81, 'Ricky West'),
	(82, 'George Harrison'),
	(92, 'Vanus Luxus'),
	(93, 'Purple Leggs'),
	(94, 'Shawn Stussy'),
	(102, 'Test Atatari'),
	(104, 'Test Support'),
	(106, 'Jeena Lolo'),
	(107, 'Alicia Cool'),
	(109, 'International Freight Company'),
	(110, 'Another Cool Company'),
	(114, 'Great Logistics'),
	(115, 'Icecream Factory'),
	(116, 'Apple Company'),
	(117, 'Billie Jean'),
	(119, 'Annie Lennox'),
	(120, 'Acie Basie'),
	(121, 'Maria Mirabella'),
	(122, 'Wow Corporation'),
	(131, 'Victor Hugo'),
	(153, 'Name Lastname'),
	(159, 'Mahmood Inthemood'),
	(160, 'Lolita Ferrari'),
	(161, 'Lolita Mercedes'),
	(162, 'Big Muzzy'),
	(163, 'Princess Sylvia');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
