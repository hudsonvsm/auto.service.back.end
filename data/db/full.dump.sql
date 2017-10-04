SET FOREIGN_KEY_CHECKS=0;

-- Dumping database structure for auto_service
DROP DATABASE IF EXISTS `auto_service`;
CREATE DATABASE IF NOT EXISTS `auto_service`;
USE `auto_service`;

-- Дъмп структура за таблица auto_service.automobile
DROP TABLE IF EXISTS `automobile`;
CREATE TABLE IF NOT EXISTS `automobile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `license_number` varchar(15) NOT NULL,
  `model_id` int(11) NOT NULL,
  `year_of_production` int(4) NOT NULL,
  `engine_number` varchar(25) NOT NULL,
  `vin_number` varchar(25) NOT NULL,
  `color_id` int(11) NOT NULL,
  `engine_capacity` int(4) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `engine_number` (`engine_number`),
  UNIQUE KEY `vin_number` (`vin_number`),
  UNIQUE KEY `license_number` (`license_number`),
  KEY `FK_automobile_colors` (`color_id`),
  KEY `FK_automobile_client` (`owner_id`),
  KEY `FK_automobile_automobile_brand_model` (`model_id`),
  CONSTRAINT `FK_automobile_automobile_brand_model` FOREIGN KEY (`model_id`) REFERENCES `automobile_brand_model` (`id`),
  CONSTRAINT `FK_automobile_client` FOREIGN KEY (`owner_id`) REFERENCES `client` (`id`),
  CONSTRAINT `FK_automobile_colors` FOREIGN KEY (`color_id`) REFERENCES `color` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile: ~5 rows (approximately)
DELETE FROM `automobile`;
INSERT INTO `automobile` (`id`, `license_number`, `model_id`, `year_of_production`, `engine_number`, `vin_number`, `color_id`, `engine_capacity`, `description`, `owner_id`) VALUES
	(1, 'РВ 4568 СА', 5, 1990, '789563214', '125478696', 1, 22, 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и тafgdfnb  rd jtdy jkutk .', 3),
	(2, 'СА 1156 РТ', 2, 2012, '9876543211', '123456981', 4, 78, 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и тafgdfnb  rd jtdy jkutk .', 3),
	(3, 'В 2012 СА', 2, 2007, '123465798', '98765431', 2, 96, 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и тafgdfnb  rd jtdy jkutk .', 3),
	(4, 'В 2013 СА', 2, 2007, '1234657981', '98765-431', 3, 96, 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и тafgdfnb  rd jtdy jkutk .', 3),
	(6, 'СА 4135 РТ', 4, 2012, '9876-543217', '1232456987', 5, 78, 'Lorem Ipsum е елементарен примерен текст, използван в печатарската и тafgdfnb  rd jtdy jkutk .', 3);

-- Дъмп структура за таблица auto_service.automobile_brand
DROP TABLE IF EXISTS `automobile_brand`;
CREATE TABLE IF NOT EXISTS `automobile_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_brand: ~9 rows (approximately)
DELETE FROM `automobile_brand`;
INSERT INTO `automobile_brand` (`id`, `name`) VALUES
	(6, 'ada'),
	(4, 'ala'),
	(5, 'alas'),
	(10, 'Aston Martin'),
	(7, 'ddsddsd'),
	(8, 'sdsafdsgfsdfgs'),
	(3, 'Мерцедес'),
	(2, 'Нисан'),
	(1, 'Тойота');

-- Дъмп структура за таблица auto_service.automobile_brand_model
DROP TABLE IF EXISTS `automobile_brand_model`;
CREATE TABLE IF NOT EXISTS `automobile_brand_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_brand_id` (`name`,`brand_id`),
  KEY `FK_brand_model_car_brand` (`brand_id`),
  CONSTRAINT `FK_brand_model_car_brand` FOREIGN KEY (`brand_id`) REFERENCES `automobile_brand` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_brand_model: ~18 rows (approximately)
DELETE FROM `automobile_brand_model`;
INSERT INTO `automobile_brand_model` (`id`, `name`, `brand_id`) VALUES
	(6, '350Г', 3),
	(5, '500СЛ', 3),
	(24, 'ADO', 1),
	(18, 'asdad', 3),
	(25, 'DB9', 10),
	(17, 'ddd', 3),
	(12, 'dsd', 3),
	(20, 'fff', 3),
	(7, 'G500', 3),
	(15, 'sd', 3),
	(16, 'sdd', 3),
	(13, 'Smart', 3),
	(14, 'ss', 3),
	(23, 'Айго', 1),
	(1, 'Аурис', 1),
	(3, 'ГТР', 2),
	(2, 'Приус', 1),
	(4, 'Сивик', 2);

-- Дъмп структура за view auto_service.automobile_data
DROP VIEW IF EXISTS `automobile_data`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `automobile_data` (
	`id` INT(11) NOT NULL,
	`license_number` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`model` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`brand` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`year_of_production` INT(4) NOT NULL,
	`engine_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`vin_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`engine_capacity` INT(4) NULL,
	`automobile_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`owner_full_name` VARCHAR(101) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Дъмп структура за таблица auto_service.automobile_part
DROP TABLE IF EXISTS `automobile_part`;
CREATE TABLE IF NOT EXISTS `automobile_part` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_part: ~7 rows (approximately)
DELETE FROM `automobile_part`;
INSERT INTO `automobile_part` (`id`, `name`, `price`) VALUES
	(1, 'тампон', 20.00),
	(2, 'втулка', 100.00),
	(3, 'човеко час', 20.00),
	(4, 'проверка на ходова част', 49.99),
	(5, 'смяна на гуми', 19.99),
	(6, 'основен ремонт', 1001.20),
	(12, 'dasdas1', 13456.00);

-- Дъмп структура за таблица auto_service.automobile_part__repair_card
DROP TABLE IF EXISTS `automobile_part__repair_card`;
CREATE TABLE IF NOT EXISTS `automobile_part__repair_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `automobile_part_id` int(11) NOT NULL,
  `repair_card_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_automobile_part__repair_card_automobile_part` (`automobile_part_id`),
  KEY `FK_automobile_part__repair_card_repair_card` (`repair_card_id`),
  CONSTRAINT `FK_automobile_part__repair_card_automobile_part` FOREIGN KEY (`automobile_part_id`) REFERENCES `automobile_part` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_automobile_part__repair_card_repair_card` FOREIGN KEY (`repair_card_id`) REFERENCES `repair_card` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_part__repair_card: ~9 rows (approximately)
DELETE FROM `automobile_part__repair_card`;
INSERT INTO `automobile_part__repair_card` (`id`, `automobile_part_id`, `repair_card_id`) VALUES
	(4, 2, 2),
	(5, 3, 2),
	(6, 3, 2),
	(7, 2, 2),
	(8, 2, 2),
	(9, 2, 2),
	(33, 3, 7),
	(39, 2, 7),
	(40, 1, 13),
	(41, 2, 16),
	(42, 12, 16),
	(43, 2, 12);

-- Дъмп структура за таблица auto_service.client
DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.client: ~9 rows (approximately)
DELETE FROM `client`;
INSERT INTO `client` (`id`, `first_name`, `last_name`, `phone_number`) VALUES
	(1, 'Пенчо', 'Пенчев', '+3598877995638'),
	(2, 'Пенка', 'Пенчева', '+3598777998562'),
	(3, 'Иван', 'Гочев', '+3598675489232'),
	(5, 'Valentin', 'Mladenov', '877338909'),
	(6, 'ABC', 'Chicago', '884208600'),
	(7, 'ABC', 'Chicago', '877338909'),
	(8, 'Valentin', 'Chicago', '877338909'),
	(9, 'Valentin', 'San Mateo', '87733890922'),
	(10, 'Valentin', 'San Mateo', '87733890911');

-- Дъмп структура за таблица auto_service.color
DROP TABLE IF EXISTS `color`;
CREATE TABLE IF NOT EXISTS `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.color: ~8 rows (approximately)
DELETE FROM `color`;
INSERT INTO `color` (`id`, `name`) VALUES
	(1, 'бял'),
	(2, 'зелен'),
	(3, 'син'),
	(4, 'червен'),
	(5, 'цветен'),
	(6, 'Оранжев'),
	(7, 'Лилав'),
	(8, 'sa');

-- Дъмп структура за таблица auto_service.i18n
DROP TABLE IF EXISTS `i18n`;
CREATE TABLE IF NOT EXISTS `i18n` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_en` varchar(50) NOT NULL DEFAULT '0',
  `name_bg` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name_en_name_bg` (`name_en`,`name_bg`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.i18n: ~2 rows (approximately)
DELETE FROM `i18n`;
INSERT INTO `i18n` (`id`, `name_en`, `name_bg`) VALUES
	(1, 'id', 'ид'),
	(2, 'license_number', 'регистрационнен номер');

-- Дъмп структура за таблица auto_service.repair_card
DROP TABLE IF EXISTS `repair_card`;
CREATE TABLE IF NOT EXISTS `repair_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` bigint(17) NOT NULL DEFAULT '0',
  `acceptance_date` date NOT NULL,
  `start_date` date NOT NULL DEFAULT '0000-00-00',
  `end_date` date NOT NULL DEFAULT '0000-00-00',
  `automobile_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`),
  KEY `FK_repair_card_worker` (`worker_id`),
  KEY `FK_repair_card_automobile` (`automobile_id`),
  CONSTRAINT `FK_repair_card_automobile` FOREIGN KEY (`automobile_id`) REFERENCES `automobile` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_repair_card_worker` FOREIGN KEY (`worker_id`) REFERENCES `worker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.repair_card: ~8 rows (approximately)
DELETE FROM `repair_card`;
INSERT INTO `repair_card` (`id`, `number`, `acceptance_date`, `start_date`, `end_date`, `automobile_id`, `worker_id`, `description`, `total_price`) VALUES
	(2, 97324441003884542, '2017-09-22', '0000-00-00', '0000-00-00', 3, 1, 'fgsghdtyjf', 440.00),
	(7, 97324441003884547, '2017-09-22', '0000-00-00', '0000-00-00', 3, 2, 'etrjhrdfjdfh', 120.00),
	(8, 97324441003884548, '2017-10-01', '2017-10-01', '0000-00-00', 4, 1, 'fsdfsdf', 0.00),
	(10, 97324441003884550, '2017-10-06', '2017-10-08', '2017-10-12', 2, 1, 'dfd', 0.00),
	(12, 97340288778895361, '2017-10-02', '2017-10-03', '0000-00-00', 1, 3, 'ALO', 100.00),
	(13, 97340288778895362, '2017-10-02', '2017-10-03', '0000-00-00', 2, 3, '', 20.00),
	(14, 97340288778895363, '2017-10-03', '2017-10-03', '0000-00-00', 2, 1, '', 0.00),
	(15, 97340288778895364, '2017-10-02', '0000-00-00', '0000-00-00', 1, 3, 'a', 0.00),
	(16, 97340288778895365, '2017-10-04', '0000-00-00', '0000-00-00', 6, 2, '', 13556.00);

-- Дъмп структура за view auto_service.repair_card_data
DROP VIEW IF EXISTS `repair_card_data`;
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `repair_card_data` (
	`id` INT(11) NOT NULL,
	`number` BIGINT(17) NOT NULL,
	`acceptance_date` DATE NOT NULL,
	`start_date` DATE NOT NULL,
	`end_date` DATE NOT NULL,
	`card_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`total_price` DECIMAL(10,2) NOT NULL,
	`worker_full_name` VARCHAR(101) NOT NULL COLLATE 'utf8_general_ci',
	`license_number` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`model` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`brand` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`year_of_production` INT(4) NOT NULL,
	`engine_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`vin_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`engine_capacity` INT(4) NULL,
	`automobile_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`owner_full_name` VARCHAR(101) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Дъмп структура за таблица auto_service.worker
DROP TABLE IF EXISTS `worker`;
CREATE TABLE IF NOT EXISTS `worker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.worker: ~3 rows (approximately)
DELETE FROM `worker`;
INSERT INTO `worker` (`id`, `first_name`, `last_name`) VALUES
	(1, 'Иво', 'Гинчев'),
	(2, 'Пламен', 'Разни'),
	(3, 'Петър', 'Петров');

-- Дъмп структура за trigger auto_service.add_uuid_repair_card_before_insert
DROP TRIGGER IF EXISTS `add_uuid_repair_card_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `add_uuid_repair_card_before_insert` BEFORE INSERT ON `repair_card` FOR EACH ROW BEGIN

SET NEW.number = UUID_SHORT();

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дъмп структура за trigger auto_service.update_total_price_add_part_to_repair_card
DROP TRIGGER IF EXISTS `update_total_price_add_part_to_repair_card`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_total_price_add_part_to_repair_card` BEFORE INSERT ON `automobile_part__repair_card` FOR EACH ROW BEGIN

SET @price =(SELECT price
					FROM automobile_part
					WHERE id=NEW.automobile_part_id);

UPDATE repair_card 
SET total_price = total_price + @price
WHERE id=NEW.repair_card_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дъмп структура за trigger auto_service.update_total_price_remove_part_from_repair_card
DROP TRIGGER IF EXISTS `update_total_price_remove_part_from_repair_card`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `update_total_price_remove_part_from_repair_card` BEFORE DELETE ON `automobile_part__repair_card` FOR EACH ROW BEGIN

SET @price =(SELECT price
					FROM automobile_part
					WHERE id=OLD.automobile_part_id);

UPDATE repair_card 
SET total_price = total_price - @price
WHERE id=OLD.repair_card_id;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дъмп структура за view auto_service.automobile_data
DROP VIEW IF EXISTS `automobile_data`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `automobile_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `automobile_data` AS SELECT 
	a.id,
	a.license_number,
	abm.name AS model,
	ab.name AS brand,
	a.year_of_production,
	a.engine_number,
	a.vin_number,
	a.engine_capacity,
	a.description AS automobile_description,
	c.name AS color,
	CONCAT(cl.first_name, ' ', cl.last_name) AS owner_full_name
FROM automobile AS a
INNER JOIN color AS c ON c.id=a.color_id
INNER JOIN `client` AS cl ON cl.id=a.owner_id
INNER JOIN automobile_brand_model AS abm ON abm.id=a.model_id
INNER JOIN automobile_brand AS ab ON ab.id=abm.brand_id ;

-- Дъмп структура за view auto_service.repair_card_data
DROP VIEW IF EXISTS `repair_card_data`;
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `repair_card_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `repair_card_data` AS SELECT 
	rc.id,
	rc.number,
	rc.acceptance_date,
	rc.start_date,
	rc.end_date,
	rc.description AS card_description,
	rc.total_price,
	CONCAT(w.first_name, ' ', w.last_name) AS worker_full_name,
	ad.license_number,
	ad.model,
	ad.brand,
	ad.year_of_production,
	ad.engine_number,
	ad.vin_number,
	ad.engine_capacity,
	ad.automobile_description,
	ad.color,
	ad.owner_full_name
FROM repair_card AS rc
INNER JOIN automobile_data AS ad ON ad.id=rc.automobile_id
INNER JOIN worker AS w ON w.id=rc.worker_id;

SET FOREIGN_KEY_CHECKS=1;