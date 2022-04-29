CREATE DATABASE formdb;

CREATE TABLE `topics` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255),
  PRIMARY KEY(`id`)
);

INSERT INTO `topics` (`name`) VALUES
('Бизнес'), 
('Технологии'), 
('Реклама и Маркетинг');

CREATE TABLE `payments` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255),
  PRIMARY KEY(`id`)
);

INSERT INTO `payments` (`name`) VALUES
('WebMoney'),
('Яндекс.Деньги'),
('PayPal'),
('Кредитная карта');


CREATE TABLE forms (
    `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `ip` VARCHAR(40),
    `name` VARCHAR(255),
    `lastname` VARCHAR(255),
    `email` VARCHAR(255),
    `phone` VARCHAR(255),
    `topic_id` INT(10),
    `payment_id` INT(10),
    `is_confirmed` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL,
    PRIMARY KEY(`id`),
    INDEX `topic_id` (`topic_id`),
    INDEX `payment_id` (`payment_id`),
    INDEX `deleted_at` (`deleted_at`)
);


ALTER TABLE forms DROP COLUMN updated_at;


CREATE TABLE `test` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `number` INT(10),
  PRIMARY KEY(`id`)
);

INSERT INTO test (number) VALUES ('Реклама и Маркетинг');

Румянцев Николай +79149327740 rumyancev@mail.com
Зверева Ульяна +79143293070 zvereva@mail.com
Егорова Ксения +79144512601 egorova@mail.com
Сергеева Арина +79142736939 sergeeva@mail.com
Демидова Диана +79146164674 demidova@mail.com