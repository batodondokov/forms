--Создание таблицы пользователей
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `login` VARCHAR(26),
    `password` VARCHAR(41),
    `email` VARCHAR(255),
    `registered_at` TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT NULL,
    PRIMARY KEY (`id`)
);

--Создание таблицы типов задач
DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(12),
    PRIMARY KEY (`id`)
);
INSERT INTO `types`(`name`)
VALUES ('Встреча'), ('Звонок'), ('Совещание'), ('Дело');

--Создание таблицы длительности задач
DROP TABLE IF EXISTS `durations`;
CREATE TABLE IF NOT EXISTS `durations`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(10),
    PRIMARY KEY (`id`)
);
INSERT INTO `durations`(`name`)
VALUES ('1 час'), ('2 часа'), ('3 часа'), ('4 часа'), ('5 часов'),
('6 часов'), ('7 часов'), ('8 часов'), ('9 часов'), ('10 часов'),
('11 часов'), ('12 часов'), ('13 часов'), ('14 часов'), ('15 часов'),
('16 часов'), ('17 часов'), ('18 часов'), ('19 часов'), ('20 часов'),
('21 час'), ('22 часа'), ('23 часа'), ('24 часа');

--Создание таблицы задач
DROP TABLE IF EXISTS `tasks`;
CREATE TABLE IF NOT EXISTS `tasks`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED,
    `topic` VARCHAR(255),
    `type_id` INT UNSIGNED,
    `place` VARCHAR(255),
    `date` DATE,
    `time` TIME,
    `duration_id` INT UNSIGNED,
    `comment` VARCHAR(255),
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT NULL,
    `status` ENUM('Текущая','Просроченная','Выполненная') DEFAULT 'Текущая',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)  REFERENCES `users` (`id`),
    FOREIGN KEY (`type_id`)  REFERENCES `types` (`id`),
    FOREIGN KEY (`duration_id`)  REFERENCES `durations` (`id`)
);

--Создание таблицы сессий
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session` VARCHAR(50),
    `user_id` INT UNSIGNED,
    `ip` VARCHAR(50),
    `hits` INT UNSIGNED DEFAULT 1,
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`)  REFERENCES `users` (`id`)
);

