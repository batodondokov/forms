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


CREATE TABLE admins (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login` VARCHAR(255),
  `password` VARCHAR(255),
  PRIMARY KEY (id)
);

INSERT INTO admins (`login`, `password`) VALUES ('admin', 'f865b53623b121fd34ee5426c792e5c33af8c227');

CREATE TABLE stats (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip` VARCHAR(40),
  `session` VARCHAR(255),
  `hits` int UNSIGNED,
  `created_at` TIMESTAMP NOT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
);