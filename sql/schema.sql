DROP TABLE IF EXISTS `characters`;
CREATE TABLE `characters` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`language_id` INT DEFAULT NULL,
	`name` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	INDEX `IDX_3A29410E82F1BAF4` (`language_id`),
	UNIQUE INDEX `UNIQ_3A29410E989D9B6282F1BAF4` (`slug`, `language_id`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

DROP TABLE IF EXISTS `episodes`;
CREATE TABLE `episodes` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`season_id` INT NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`number` INT NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	INDEX `IDX_7DD55EDD4EC001D1` (`season_id`),
	INDEX `IDX_7DD55EDD989D9B62` (`slug`),
	INDEX `IDX_7DD55EDD5E237E06` (`name`),
	UNIQUE INDEX `UNIQ_7DD55EDD4EC001D1989D9B62` (`season_id`, `slug`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`short_code` VARCHAR(3) NOT NULL,
	UNIQUE INDEX `UNIQ_A0D1537917D2FE0D` (`short_code`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

DROP TABLE IF EXISTS `scenarios`;
CREATE TABLE `scenarios` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`episode_id` INT NOT NULL,
	`character_id` INT DEFAULT NULL,
	`text` LONGTEXT NOT NULL,
	`line` INT NOT NULL,
	`intro` TINYINT(1) DEFAULT '0' NOT NULL,
	INDEX `IDX_9338D025362B62A0` (`episode_id`),
	INDEX `IDX_9338D0251136BE75` (`character_id`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

DROP TABLE IF EXISTS `searches`;
CREATE TABLE `searches` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`input` VARCHAR(255) NOT NULL,
	`slug` VARCHAR(255) NOT NULL,
	`search_time` DATETIME NOT NULL,
	INDEX `IDX_60183819989D9B62` (`slug`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

DROP TABLE IF EXISTS `seasons`;
CREATE TABLE `seasons` (
	`id` INT AUTO_INCREMENT NOT NULL,
	`language_id` INT NOT NULL,
	`number` SMALLINT NOT NULL,
	`year` INT NOT NULL,
	INDEX `IDX_B4F4301C82F1BAF4` (`language_id`),
	UNIQUE INDEX `UNIQ_B4F4301C96901F5482F1BAF4` (`number`, `language_id`),
	PRIMARY KEY (`id`)
) DEFAULT CHARACTER SET `utf8` COLLATE `utf8_unicode_ci` ENGINE = InnoDB;

ALTER TABLE `characters` ADD CONSTRAINT `FK_3A29410E82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);
ALTER TABLE `episodes` ADD CONSTRAINT `FK_7DD55EDD4EC001D1` FOREIGN KEY (`season_id`) REFERENCES `seasons` (`id`);
ALTER TABLE `scenarios` ADD CONSTRAINT `FK_9338D025362B62A0` FOREIGN KEY (`episode_id`) REFERENCES `episodes` (`id`);
ALTER TABLE `scenarios` ADD CONSTRAINT `FK_9338D0251136BE75` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`);
ALTER TABLE `seasons` ADD CONSTRAINT `FK_B4F4301C82F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

INSERT INTO `languages` (`id`, `name`, `short_code`) VALUES
	(1, 'Česky', 'cs');

INSERT INTO `seasons` (`id`, `number`, `year`, `language_id`) VALUES
	(10, 1, 1988, 1),
	(11, 2, 1988, 1),
	(12, 3, 1989, 1),
	(13, 4, 1991, 1),
	(14, 5, 1992, 1),
	(15, 6, 1993, 1),
	(16, 7, 1997, 1),
	(17, 8, 1999, 1),
	(18, 9, 2009, 1),
	(19, 10, 2012, 1);

INSERT INTO `episodes` (`id`, `season_id`, `name`, `number`, `slug`) VALUES
	(53, 10, 'Konec', 1, 'konec'),
	(54, 10, 'Ozvěny budoucnosti', 2, 'ozveny-budoucnosti'),
	(55, 10, 'Rovnováha sil', 3, 'rovnovaha-sil'),
	(56, 10, 'Čekání na Boha', 4, 'cekani-na-boha'),
	(57, 10, 'Sebevědomí a Mindrák', 5, 'sebevedomi-a-mindrak'),
	(58, 10, 'Já na druhou', 6, 'ja-na-druhou'),
	(59, 11, 'Kryton', 1, 'kryton'),
	(60, 11, 'Lepší než život', 2, 'lepsi-nez-zivot'),
	(61, 11, 'Díky za tu vzpomínku', 3, 'diky-za-tu-vzpominku'),
	(62, 11, 'Škvíra ve stázi', 4, 'skvira-ve-stazi'),
	(63, 11, 'Queeg', 5, 'queeg'),
	(64, 11, 'Paralelní vesmír', 6, 'paralelni-vesmir'),
	(65, 12, 'Pozpátku', 1, 'pozpatku'),
	(66, 12, 'Trosečníci', 2, 'trosecnici'),
	(67, 12, 'Polymorf', 3, 'polymorf'),
	(69, 12, 'Výměna těl', 4, 'vymena-tel'),
	(70, 12, 'Fotostroj času', 5, 'fotostroj-casu'),
	(71, 12, 'Poslední den', 6, 'posledni-den'),
	(72, 13, 'Kamila', 1, 'kamila'),
	(73, 13, 'D.N.A.', 2, 'd-n-a'),
	(74, 13, 'Spravedlnost', 3, 'spravedlnost'),
	(75, 13, 'Bílá díra', 4, 'bila-dira'),
	(76, 13, 'Jiná dimenze', 5, 'jina-dimenze'),
	(77, 13, 'Roztavení', 6, 'roztaveni'),
	(78, 14, 'Hololoď', 1, 'hololod'),
	(79, 14, 'Inkvizitor', 2, 'inkvizitor'),
	(80, 14, 'Psychoteror', 3, 'psychoteror'),
	(81, 14, 'Karanténa', 4, 'karantena'),
	(82, 14, 'Démoni a andělé', 5, 'demoni-a-andele'),
	(83, 14, 'Návrat do reality', 6, 'navrat-do-reality'),
	(84, 15, 'Psirény', 1, 'psireny'),
	(85, 15, 'Legie', 2, 'legie'),
	(86, 15, 'Pistolníci z Apokalypsy', 3, 'pistolnici-z-apokalypsy'),
	(87, 15, 'Polymorf II - Emocuc', 4, 'polymorf-2-emocuc'),
	(88, 15, 'Rimmerosvět', 5, 'rimmerosvet'),
	(89, 15, 'Mimo realitu', 6, 'mimo-realitu'),
	(90, 16, 'Pekelně ostrý výlet', 1, 'pekelne-ostry-vylet'),
	(91, 16, 'Nachystejte květináče', 2, 'nachystejte-kvetinace'),
	(92, 16, 'Uroboros', 3, 'uroboros'),
	(93, 16, 'Zkouška kanálem', 4, 'zkouska-kanalem'),
	(94, 16, 'Stesk', 5, 'stesk'),
	(95, 16, 'Žádná legrace', 6, 'zadna-legrace'),
	(96, 16, 'Epidém', 7, 'epidem'),
	(97, 16, 'Nanarchie', 7, 'nanarchie'),
	(98, 17, 'Zpátky v Červeném 1', 1, 'zpatky-v-cervenem-1'),
	(99, 17, 'Zpátky v Červeném 2', 2, 'zpatky-v-cervenem-2'),
	(100, 17, 'Zpátky v Červeném 3', 3, 'zpatky-v-cervenem-3'),
	(101, 17, 'Kassandra', 4, 'kassandra'),
	(102, 17, 'Televize Kryton', 5, 'televize-kryton'),
	(103, 17, 'Pete 1', 6, 'pete-1'),
	(104, 17, 'Pete 2', 7, 'pete-2'),
	(105, 17, 'Jenom sympaťáci...', 8, 'jenom-sympataci');
