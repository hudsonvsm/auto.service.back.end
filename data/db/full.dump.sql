SET FOREIGN_KEY_CHECKS=0;

-- Dumping database structure for auto_service
CREATE DATABASE IF NOT EXISTS `auto_service`;
USE `auto_service`;

-- Дъмп структура за таблица auto_service.automobile
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

-- Дъмп данни за таблица auto_service.automobile: ~4 rows (approximately)
DELETE FROM `automobile`;
INSERT INTO `automobile` (`id`, `license_number`, `model_id`, `year_of_production`, `engine_number`, `vin_number`, `color_id`, `engine_capacity`, `description`, `owner_id`) VALUES
	(1, 'РВ 4568 СА', 5, 1990, '789563214', '125478696', 1, 22, NULL, 3),
	(3, 'В 2012 СА', 2, 2007, '123465798', '98765431', 2, 96, 'Алоха', 3),
	(5, 'СА 1155 РТ', 2, 2012, '987654321', '12345698', 5, 78, NULL, 3);

-- Дъмп структура за таблица auto_service.automobile_brand
CREATE TABLE IF NOT EXISTS `automobile_brand` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_brand: ~3 rows (approximately)
DELETE FROM `automobile_brand`;
INSERT INTO `automobile_brand` (`id`, `name`) VALUES
	(1, 'Тойота'),
	(2, 'Нисан'),
	(3, 'Мерцедес');

-- Дъмп структура за таблица auto_service.automobile_brand_model
CREATE TABLE IF NOT EXISTS `automobile_brand_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '0',
  `brand_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `FK_brand_model_car_brand` (`brand_id`),
  CONSTRAINT `FK_brand_model_car_brand` FOREIGN KEY (`brand_id`) REFERENCES `automobile_brand` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_brand_model: ~6 rows (approximately)
DELETE FROM `automobile_brand_model`;
INSERT INTO `automobile_brand_model` (`id`, `name`, `brand_id`) VALUES
	(1, 'Аурис', 1),
	(2, 'Приус', 1),
	(3, 'ГТР', 2),
	(4, 'Сивик', 2),
	(5, '500СЛ', 3),
	(6, '350Г', 3);

-- Дъмп структура за view auto_service.automobile_data
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `automobile_data` (
	`id` INT(11) NOT NULL,
	`license_number` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`brand` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`model` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`year_of_production` INT(4) NOT NULL,
	`engine_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`vin_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`engine_capacity` INT(4) NULL,
	`automobile_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`owner_full_name` VARCHAR(101) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Дъмп структура за таблица auto_service.automobile_part
CREATE TABLE IF NOT EXISTS `automobile_part` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.automobile_part: ~6 rows (approximately)
DELETE FROM `automobile_part`;
INSERT INTO `automobile_part` (`id`, `name`, `price`) VALUES
	(1, 'тампон', 20.00000),
	(2, 'втулка', 100.00000),
	(3, 'човеко_час', 20.00000),
	(4, 'проверка на ходова част', 49.99000),
	(5, 'смяна на гуми', 19.99000),
	(6, 'основен ремонт', 1000.20000);

-- Дъмп структура за таблица auto_service.automobile_part__repair_card
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

-- Дъмп данни за таблица auto_service.automobile_part__repair_card: ~1 rows (approximately)
DELETE FROM `automobile_part__repair_card`;
INSERT INTO `automobile_part__repair_card` (`id`, `automobile_part_id`, `repair_card_id`) VALUES
	(4, 2, 2);

-- Дъмп структура за таблица auto_service.client
CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.client: ~3 rows (approximately)
DELETE FROM `client`;
INSERT INTO `client` (`id`, `first_name`, `last_name`, `phone_number`) VALUES
	(1, 'Пенчо', 'Пенчев', '+3598877995638'),
	(2, 'Пенка', 'Пенчева', '+3598777998562'),
	(3, 'Иван', 'Гочев', '+3598675489232');

-- Дъмп структура за таблица auto_service.color
CREATE TABLE IF NOT EXISTS `color` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.color: ~5 rows (approximately)
DELETE FROM `color`;
INSERT INTO `color` (`id`, `color`) VALUES
	(1, 'бял'),
	(2, 'зелен'),
	(3, 'син'),
	(4, 'червен'),
	(5, 'цветен');

-- Дъмп структура за таблица auto_service.repair_card
CREATE TABLE IF NOT EXISTS `repair_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` bigint(17) NOT NULL DEFAULT '0',
  `acceptance_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `automobile_id` int(11) NOT NULL,
  `worker_id` int(11) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `total_price` decimal(10,5) NOT NULL DEFAULT '0.00000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `number` (`number`),
  KEY `FK_repair_card_worker` (`worker_id`),
  KEY `FK_repair_card_automobile` (`automobile_id`),
  CONSTRAINT `FK_repair_card_automobile` FOREIGN KEY (`automobile_id`) REFERENCES `automobile` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_repair_card_worker` FOREIGN KEY (`worker_id`) REFERENCES `worker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дъмп данни за таблица auto_service.repair_card: ~7 rows (approximately)
DELETE FROM `repair_card`;
INSERT INTO `repair_card` (`id`, `number`, `acceptance_date`, `start_date`, `end_date`, `automobile_id`, `worker_id`, `description`, `total_price`) VALUES
	(1, 97324441003884541, '2017-09-22 00:19:32', NULL, NULL, 5, 3, NULL, 0.00000),
	(2, 97324441003884542, '2017-09-22 00:19:32', NULL, NULL, 3, 1, NULL, 100.00000),
	(3, 97324441003884543, '2017-09-22 00:19:32', NULL, NULL, 5, 3, NULL, 0.00000),
	(4, 97324441003884544, '2017-09-22 00:19:32', NULL, NULL, 5, 3, NULL, 0.00000),
	(5, 97324441003884545, '2017-09-22 00:45:10', NULL, NULL, 5, 3, NULL, 0.00000),
	(6, 97324441003884546, '2017-09-22 00:45:53', NULL, NULL, 5, 3, NULL, 0.00000),
	(7, 97324441003884547, '2017-09-22 00:46:18', NULL, NULL, 3, 2, NULL, 0.00000);

-- Дъмп структура за view auto_service.repair_card_data
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `repair_card_data` (
	`number` BIGINT(17) NOT NULL,
	`acceptance_date` TIMESTAMP NOT NULL,
	`start_date` TIMESTAMP NULL,
	`end_date` TIMESTAMP NULL,
	`card_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`total_price` DECIMAL(10,5) NOT NULL,
	`worker_full_name` VARCHAR(101) NOT NULL COLLATE 'utf8_general_ci',
	`license_number` VARCHAR(15) NOT NULL COLLATE 'utf8_general_ci',
	`brand` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`model` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`year_of_production` INT(4) NOT NULL,
	`engine_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`vin_number` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`engine_capacity` INT(4) NULL,
	`automobile_description` VARCHAR(255) NULL COLLATE 'utf8_general_ci',
	`color` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`owner_full_name` VARCHAR(101) NULL COLLATE 'utf8_general_ci'
) ENGINE=MyISAM;

-- Дъмп структура за таблица auto_service.worker
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
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `add_uuid_repair_card_before_insert` BEFORE INSERT ON `repair_card` FOR EACH ROW BEGIN

SET NEW.number = UUID_SHORT();

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дъмп структура за trigger auto_service.update_total_price_add_part_to_repair_card
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
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `automobile_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `automobile_data` AS SELECT 
	a.id,
	a.license_number,
	ab.name AS brand,
	abm.name AS model,
	a.year_of_production,
	a.engine_number,
	a.vin_number,
	a.engine_capacity,
	a.description AS automobile_description,
	c.color,
	CONCAT(cl.first_name, ' ', cl.last_name) AS owner_full_name
FROM automobile AS a
INNER JOIN color AS c ON c.id=a.color_id
INNER JOIN `client` AS cl ON cl.id=a.owner_id
INNER JOIN automobile_brand_model AS abm ON abm.id=a.model_id
INNER JOIN automobile_brand AS ab ON ab.id=abm.brand_id ;

-- Дъмп структура за view auto_service.repair_card_data
-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `repair_card_data`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `repair_card_data` AS SELECT 
	rc.number,
	rc.acceptance_date,
	rc.start_date,
	rc.end_date,
	rc.description AS card_description,
	rc.total_price,
	CONCAT(w.first_name, ' ', w.last_name) AS worker_full_name,
	ad.license_number,
	ad.brand,
	ad.model,
	ad.year_of_production,
	ad.engine_number,
	ad.vin_number,
	ad.engine_capacity,
	ad.automobile_description,
	ad.color,
	ad.owner_full_name
FROM repair_card AS rc
INNER JOIN automobile_data AS ad ON ad.id=rc.automobile_id
INNER JOIN worker AS w ON w.id=rc.worker_id ;

SET FOREIGN_KEY_CHECKS=1;
