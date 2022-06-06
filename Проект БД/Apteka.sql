--Создание базы данных аптека
CREATE DATABASE IF NOT EXISTS `apteka`;

--Создание таблицы с клиентами
CREATE TABLE IF NOT EXISTS `clients`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `father_name` VARCHAR(255),
    `birth_date` DATE,
    `phone_number` CHAR(12),
    `gender` ENUM('M','F'),
    PRIMARY KEY (`id`)
);
--Набор типовых данных для таблицы с клиентами
INSERT INTO `clients` (`last_name`, `name`, `father_name`, `birth_date`, `phone_number`, `gender`)
VALUES ('Шестунова', 'Мария', 'Дмитриевна', '1962-07-05', '+78737725538','F'),
('Берестовская', 'Оксана', 'Андреевна', '1966-03-23', '+71593698901','F'),
('Нестеренко', 'Елена', 'Вячеславовна', '1977-09-03', '+79121604588','F'),
('Гуриев', 'Константин', 'Сергеевич', '2002-01-03', '+75682294341','M'),
('Жиганова', 'Елизавета', 'Алексеевна', '1962-06-17', '+78868217442','F'),
('Родовский', 'Марк', 'Константинович', '1975-10-13', '+75005091663','M'),
('Новиков', 'Александр', 'Иванович', '1990-03-03', '+77872514804','M'),
('Носов', 'Павел', 'Михайлович', '1964-06-02', '+71739983145','M'),
('Авдеев', 'Леонид', 'Андреевич', '1960-10-06', '+74494110188','M'),
('Гусева', 'Екатерина', 'Сергеевна', '1962-02-27', '+75105878940','F');


--Создание таблицы с препаратами
CREATE TABLE IF NOT EXISTS `medicaments`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `type_id` INT UNSIGNED,
    `fabricator` VARCHAR(255),
    `country` VARCHAR(255),
    `cost` DECIMAL(10,2),
    `wholesale_cost` DECIMAL(10,2),
    `available_qty` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`type_id`)  REFERENCES `medicament_types` (`id`)
);
--Набор типовых данных для таблицы с препаратами
INSERT INTO `medicaments` (`name`, `type_id`, `fabricator`, `country`, `cost`, `wholesale_cost`, `available_qty`, `created_at`)
VALUES ('Супрадин', 1, 'Делфарм Гайярд', 'Россия', 555.00, 499.00, 53, NOW()),
('Кардиомагнил', 2, 'Такеда Фармасьютикалс ООО', 'Россия', 208.00, 189.00, 14, NOW()),
('Терафлекс', 3, 'Контракт Фармакал Корпорейшн', 'США', 1969.00, 1599.00,  8,NOW()),
('Афлубин', 4, 'Рихард Биттнер АГ', 'Австрия', 335.00, 279.00, 41, NOW()),
('Ксарелто', 2, 'Байер АГ', 'Германия', 3117.00, 2799.00, 18, NOW()),
('Компливит', 2, 'Фармстандарт', 'Россия', 220.00, 189.00, 64, NOW()),
('Назонекс', 5, 'Акрихин АО', 'Бельгия', 674.00, 610.00, 8, NOW()),
('Мексидол', 6, 'АРМАВИРСКАЯ БИОФАБРИКА ФКП', 'Россия', 468.00, 399.00, 35, NOW()),
('Пульмикорт', 7, 'Астра Зенека АБ', 'Швеция', 739.00, 680.00, 18, NOW()),
('Голдлайн Плюс', 3, 'Изварино Фарма ООО', 'Россия', 731.00, 649.00, 11, NOW());


--Создание таблицы с видами препаратов
CREATE TABLE IF NOT EXISTS `medicament_types`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
);
--Набор типовых данных для таблицы с видами препаратов
INSERT INTO `medicament_types` (`name`)
VALUES ('Таблетки шипучие'),
('Таблетки в оболочке'),
('Капсулы'),
('Таблетки подъязычные'),
('Спрей назальный'),
('Ампула'),
('Суспензия');


--Создание таблицы с продажами
CREATE TABLE IF NOT EXISTS `sales`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `date` VARCHAR(255),    
    `medicament_id` INT UNSIGNED,
    `cost` DECIMAL(10,2),
    `quantity` INT UNSIGNED,
    `income` DECIMAL(20,2),
    `staffer_id` INT UNSIGNED,
    `client_id` INT UNSIGNED,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`medicament_id`)  REFERENCES `medicaments` (`id`),
    FOREIGN KEY (`staffer_id`)  REFERENCES `stuff` (`id`),
    FOREIGN KEY (`client_id`)  REFERENCES `clients` (`id`)
);
--Набор типовых данных для таблицы с продажами
INSERT INTO `sales` (`date`, `medicament_id`, `cost`, `quantity`, `income`, `staffer_id`, `client_id`)
VALUES ('2022-05-06', 4, 335.00, 2, 670.00, 3, 10),
('2022-05-12', 7, 674.00, 1, 674.00, 5, 4),
('2022-05-11', 2, 208.00, 2, 416.00, 3, 2),
('2022-05-20', 8, 468.00, 2, 936.00, 6, 8),
('2022-05-28', 6, 220.00, 1, 220.00, 3, 1),
('2022-05-21', 1, 555.00, 3, 1665.00, 4, 3),
('2022-05-20', 4, 335.00, 2, 670.00, 4, 6),
('2022-05-07', 5, 3117.00, 4, 12468.00, 2, 5),
('2022-05-15', 3, 1969.00, 1, 1969.00, 1, 7),
('2022-05-02', 9, 739.00, 2, 1478.00, 4, 9),
('2022-05-28', 3, 1969.00, 3, 5907.00, 3, 2),
('2022-05-10', 10, 731.00, 1, 731.00, 5, 7);


--Создание таблицы с сотрудниками
CREATE TABLE IF NOT EXISTS `staff`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `last_name` VARCHAR(255),
    `father_name` VARCHAR(255),
    `position_id` INT UNSIGNED,
    `birth_date` DATE,
    `phone_number` CHAR(12),
    `gender` ENUM('M','F'),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`position_id`)  REFERENCES `positions` (`id`) 
);
--Набор типовых данных для таблицы с сотрудниками
INSERT INTO `staff` (`name`, `last_name`, `father_name`, `position_id`, `birth_date`, `phone_number`, `gender`)
VALUES ('Кира', 'Ларионова', 'Михайловна', 1, '1970-10-13', '+73691480521', 'F'),
('Арина', 'Карпова', 'Владимировна', 2, '1985-01-07', '+79563323566', 'F'),
('Павел', 'Горшков', 'Григорьевич', 3, '1978-04-09', '+73389958772', 'M'),
('Марк', 'Федотов', 'Ярославович', 2, '1988-10-02', '+74626641031', 'M'),
('Александра', 'Захарова', 'Макаровна', 3, '1976-06-20', '+76013343949', 'F'),
('Артур', 'Степанов', 'Степанович', 4, '1972-06-24', '+78147484748', 'M');


--Создание таблицы с должностями
CREATE TABLE IF NOT EXISTS `positions`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `salary` DECIMAL(10,2),
    PRIMARY KEY (`id`) 
);
--Набор типовых данных для таблицы с должностями
INSERT INTO `positions` (`name`, `salary`)
VALUES ('Заведующий', 45000.00),
('Фармацевт', 38000.00),
('Провизор', 32000.00),
('Фасовщик', 27000.00);


--Создание таблицы с поставками
CREATE TABLE IF NOT EXISTS `deliveries`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_date` DATE,
    `receipt_date` DATE,
    `medicament_id` INT UNSIGNED,
    `wholesale_cost` DECIMAL(10,2),
    `quantity` INT UNSIGNED,  
    `expense` DECIMAL(10,2),
    `status` VARCHAR(20) DEFAULT 'not delivered',
    PRIMARY KEY (`id`),
    FOREIGN KEY (`medicament_id`)  REFERENCES `medicaments` (`id`)
);
--Набор типовых данных для таблицы с поставками
INSERT INTO `deliveries` (`order_date`, `receipt_date`, `medicament_id`, `wholesale_cost`, `quantity`, `expense`, `status`)
VALUES ('2022-05-21', '2022-05-27', 6, 189.00, 10, 1890.00, 'delivered'),
('2022-05-23', '2022-05-29', 2, 189.00, 6, 1134.00, 'delivered'),
('2022-05-10', '2022-05-16', 7, 610.00, 3, 1830.00, 'delivered'),
('2022-05-24', '2022-05-30', 3, 1599.00, 5, 7995.00, 'delivered'),
('2022-05-02', '2022-05-08', 4, 279.00, 10, 2790.00, 'delivered'),
('2022-05-04', '2022-05-10', 1, 499.00, 8, 3992.00, 'delivered'),
('2022-05-18', '2022-05-24', 5, 2799.00, 3, 8397.00, 'delivered'),
('2022-05-12', '2022-05-18', 10, 649.00, 4, 2596.00, 'delivered'),
('2022-05-14', '2022-05-20', 9, 680.00, 5, 3400.00, 'delivered'),
('2022-05-09', '2022-05-15', 1, 499.00, 10, 4990.00, 'delivered'),
('2022-05-13', '2022-05-19', 8, 399.00, 7, 2793.00, 'delivered'),
('2022-05-17', '2022-05-23', 4, 279.00, 3, 837.00, 'delivered');


------------------------------------------------------------------------------------------------------------------------------------------

--Типовые операции

--1)Добавление:
    --клиента
    INSERT INTO `clients` (`last_name`, `name`, `father_name`, `birth_date`, `phone_number`, `gender`)
    VALUES ('Шестунова', 'Мария', 'Дмитриевна', '1962-07-05', '+78737725538','F');

    --препарата
    INSERT INTO `medicaments` (`name`, `type_id`, `fabricator`, `country`, `cost`, `wholesale_cost`, `created_at`)
    VALUES ('Супрадин', 1, 'Делфарм Гайярд', 'Россия', 555.00, 499.00, NOW());

    --вида препарата
    INSERT INTO `medicament_types` (`name`)
    VALUES ('Таблетки шипучие');

    --сотрудника
    INSERT INTO `staff` (`name`, `last_name`, `father_name`, `position_id`, `birth_date`, `phone_number`, `gender`)
    VALUES ('Кира', 'Ларионова', 'Михайловна', 1, '1970-10-13', '+73691480521', 'F');

    --должности
    INSERT INTO `positions` (`name`, `salary`)
    VALUES ('Заведующий', 45000.00);

--2)Правка:
    --клиента
    UPDATE `clients` 
    SET `phone_number` = '+71739983145'
    WHERE `id` = 1;

    --препарата
    UPDATE `medicaments` 
    SET `cost` = 679,  `wholesale_cost` = 599, `updated_at` = NOW()
    WHERE `id` = 1;

    --сотрудника
    UPDATE `staff` 
    SET `position_id` = 2;
    WHERE `id` = 6;

    --должности
    UPDATE `positions` 
    SET `salary` = 40000.00
    WHERE `id` = 2;

--3)Удаление:
    --клиента
    DROP TRIGGER IF EXISTS `trg_delete_сlinet`; -- Триггер на удаление (удаляет связи с другмим таблицами)
    DELIMITER //
    CREATE TRIGGER `trg_delete_сlinet`
    AFTER DELETE ON `clients` FOR EACH ROW
    BEGIN
        UPDATE `sales`
        SET `client_id` = NULL
        WHERE `client_id` = OLD.id;
    END//    
    DELIMITER ;

    DELETE FROM `clients` -- удаление
    WHERE `id` = 1;

    --препарата
    DROP TRIGGER IF EXISTS `trg_delete_medicament`; -- Триггер на удаление (удаляет связи с другмим таблицами)
    DELIMITER //
    CREATE TRIGGER `trg_delete_medicament`
    AFTER DELETE ON `medicaments` FOR EACH ROW
    BEGIN
        UPDATE `sales`
        SET `medicament_id` = NULL
        WHERE `medicament_id` = OLD.id;
        UPDATE `deliveries`
        SET `medicament_id` = NULL
        WHERE `medicament_id` = OLD.id;
    END//    
    DELIMITER ;

    DELETE FROM `medicaments` -- удаление
    WHERE `id` = 1;

    --сотрудника
    DROP TRIGGER IF EXISTS `trg_delete_staffer`; -- Триггер на удаление (удаляет связи с другмим таблицами)
    DELIMITER //
    CREATE TRIGGER `trg_delete_staffer`
    AFTER DELETE ON `staff` FOR EACH ROW
    BEGIN
        UPDATE `sales`
        SET `staffer_id` = NULL
        WHERE `staffer_id` = OLD.id;
    END//    
    DELIMITER ;

    DELETE FROM `staff` -- удаление
    WHERE `id` = 1;

    --должности
    DROP TRIGGER IF EXISTS `trg_delete_position`; -- Триггер на удаление (удаляет связи с другмим таблицами)
    DELIMITER //
    CREATE TRIGGER `trg_delete_position`
    AFTER DELETE ON `positions` FOR EACH ROW
    BEGIN
        UPDATE `staff`
        SET `position_id` = NULL
        WHERE `position_id` = OLD.id;
    END//    
    DELIMITER ;

    DELETE FROM `positions` -- удаление
    WHERE `id` = 1
    
    --вида препаратa
    DROP TRIGGER IF EXISTS `trg_delete_medicament_type`; -- Триггер на удаление (удаляет связи с другмим таблицами)
    DELIMITER //
    CREATE TRIGGER `trg_delete_medicament_type`
    AFTER DELETE ON `medicament_types` FOR EACH ROW
    BEGIN
        UPDATE `medicaments`
        SET `type_id` = NULL
        WHERE `type_id` = OLD.id;
    END//    
    DELIMITER ;

    DELETE FROM `medicament_types` -- удаление
    WHERE `id` = 1

--3)Покупка
    --Тригер для вставки цены препарата по его id, и общей стоимости по его id и количеству
    --Также он обновляет таблицу с препаратами 
    --(вычитает из доступного количества купленное количество)
    DROP TRIGGER IF EXISTS `trg_insert_sale`;
    DELIMITER //
    CREATE TRIGGER `trg_insert_sale`
    BEFORE INSERT ON `sales` FOR EACH ROW
    BEGIN
        SET NEW.cost = (SELECT `cost` FROM `medicaments` WHERE `id` = NEW.medicament_id);
        SET NEW.income = NEW.cost * NEW.quantity;
        UPDATE `medicaments`
        SET `available_qty` = (`available_qty` - NEW.quantity)
        WHERE `id` = NEW.medicament_id;
    END//    
    DELIMITER ;

    INSERT INTO `sales` (`date`, `medicament_id`, `quantity`, `staffer_id`, `client_id`)
    VALUES ('2022-05-31', 4, 2, 3, 10);
    
    --Отмена покупки
    --Триггер обновляет таблицу с препаратами 
    --(прибавляет к доступному количеству отмененное количество с покупки)
    DROP TRIGGER IF EXISTS `trg_delete_sale`;
    DELIMITER //
    CREATE TRIGGER `trg_delete_sale`
    AFTER DELETE ON `sales` FOR EACH ROW
    BEGIN
        UPDATE `medicaments`
        SET `available_qty` = (`available_qty` + OLD.quantity)
        WHERE `id` = OLD.medicament_id;
    END//    
    DELIMITER ;

    DELETE FROM `sales`
    WHERE `id` = 15;

    
--4)Создание поставки
    --Триггер для вставки оптовой цены препарата по его id, и общей стоимости по его id и количеству
    DROP TRIGGER IF EXISTS `trg_insert_deliverie`;
    DELIMITER //
    CREATE TRIGGER `trg_insert_deliverie`
    BEFORE INSERT ON `deliveries` FOR EACH ROW
    BEGIN
        SET NEW.wholesale_cost = (SELECT `wholesale_cost` FROM `medicaments` WHERE `id` = NEW.medicament_id);
        SET NEW.expense = NEW.wholesale_cost * NEW.quantity;
    END//    
    DELIMITER ;

    INSERT INTO `deliveries` (`order_date`, `medicament_id`, `quantity`)
    VALUES ('2022-05-21', 6, 10);

    --Прием поставки
    --Триггер для обновления доступного количества препарата
    DROP TRIGGER IF EXISTS `trg_update_deliverie`;
    DELIMITER //
    CREATE TRIGGER `trg_update_deliverie`
    BEFORE UPDATE ON `deliveries` FOR EACH ROW
    BEGIN
        SET NEW.status = 'delivered';
        UPDATE `medicaments`
        SET `available_qty` = (`available_qty` + OLD.quantity)
        WHERE `id` = OLD.medicament_id;
    END//    
    DELIMITER ;

    UPDATE `deliveries`
    SET `receipt_date` = '2022-05-27'
    WHERE `id` = 13;

    --Отмена поставки
    --Триггер обновляет таблицу с препаратами 
    --(вычитает из доступного количества доставленное количество)
    DROP TRIGGER IF EXISTS `trg_delete_delivery`;
    DELIMITER //
    CREATE TRIGGER `trg_delete_delivery`
    AFTER DELETE ON `deliveries` FOR EACH ROW
    BEGIN
        IF OLD.status = 'delivered' THEN
            UPDATE `medicaments`
            SET `available_qty` = (`available_qty` - OLD.quantity)
            WHERE `id` = OLD.medicament_id;
        END IF;        
    END//    
    DELIMITER ;

    DELETE FROM `deliveries`
    WHERE `id` = 13;

------------------------------------------------------------------------------------------------------------------------------------------

--Представления
--1) Информация о покупках по клиентам, сортировано по клинетам
DROP VIEW IF EXISTS `view_clients_and_seles`;
CREATE VIEW `view_clients_and_seles`
AS
SELECT  get_fio(c.name, c.last_name, c.father_name) as `client`,
        GROUP_CONCAT(s.date SEPARATOR ', ') AS `dates`,
        GROUP_CONCAT(m.name SEPARATOR ', ') AS `medicament_name`,
        GROUP_CONCAT(s.cost SEPARATOR ', ') AS `costs`,
        GROUP_CONCAT(s.quantity SEPARATOR ', ') AS `quantity`,
        SUM(s.income) AS `income`
FROM `sales` AS s
JOIN `clients` AS c ON s.client_id = c.id
JOIN `medicaments` AS m ON s.medicament_id = m.id
GROUP BY `client`
ORDER BY `client`;
SELECT * FROM `view_clients_and_seles`;
+--------------------+------------------------+--------------------------+-----------------+----------+----------+
| client             | dates                  | medicament_name          | costs           | quantity | income   |
+--------------------+------------------------+--------------------------+-----------------+----------+----------+
| Авдеев Л. А.       | 2022-05-02             | Пульмикорт               | 739.00          | 2        |  1478.00 |
| Берестовская О. А. | 2022-05-11, 2022-05-28 | Кардиомагнил, Терафлекс  | 208.00, 1969.00 | 2, 3     |  6323.00 |
| Гуриев К. С.       | 2022-05-12             | Назонекс                 | 674.00          | 1        |   674.00 |
| Гусева Е. С.       | 2022-05-06             | Афлубин                  | 335.00          | 2        |   670.00 |
| Жиганова Е. А.     | 2022-05-07             | Ксарелто                 | 3117.00         | 4        | 12468.00 |
| Нестеренко Е. В.   | 2022-05-21             | Супрадин                 | 555.00          | 3        |  1665.00 |
| Новиков А. И.      | 2022-05-15, 2022-05-10 | Терафлекс, Голдлайн Плюс | 1969.00, 731.00 | 1, 1     |  2700.00 |
| Носов П. М.        | 2022-05-20             | Мексидол                 | 468.00          | 2        |   936.00 |
| Родовский М. К.    | 2022-05-20             | Афлубин                  | 335.00          | 2        |   670.00 |
| Шестунова М. Д.    | 2022-05-28             | Компливит                | 220.00          | 1        |   220.00 |
+--------------------+------------------------+--------------------------+-----------------+----------+----------+


--2)Информация о сотрудниках с должностью и зарплатой, сортировано по зарплате(от высокой к низкой)
DROP VIEW IF EXISTS `view_stuff_info`;
CREATE VIEW `view_stuff_info`
AS
SELECT  get_fio(s.name, s.last_name, s.father_name) as `staffer`,
        p.name AS `position`,
        p.salary,
        s.birth_date,
        s.phone_number,
        s.gender
FROM `staff` AS s
JOIN `positions` AS p ON s.position_id = p.id
ORDER BY p.salary DESC;
SELECT * FROM `view_stuff_info`;
+-----------------+------------+----------+------------+--------------+--------+
| staffer         | position   | salary   | birth_date | phone_number | gender |
+-----------------+------------+----------+------------+--------------+--------+
| Ларионова К. М. | Заведующий | 45000.00 | 1970-10-13 | +73691480521 | F      |
| Карпова А. В.   | Фармацевт  | 38000.00 | 1985-01-07 | +79563323566 | F      |
| Федотов М. Я.   | Фармацевт  | 38000.00 | 1988-10-02 | +74626641031 | M      |
| Горшков П. Г.   | Провизор   | 32000.00 | 1978-04-09 | +73389958772 | M      |
| Захарова А. М.  | Провизор   | 32000.00 | 1976-06-20 | +76013343949 | F      |
| Степанов А. С.  | Фасовщик   | 27000.00 | 1972-06-24 | +78147484748 | M      |
+-----------------+------------+----------+------------+--------------+--------+


--3)Сотрудники сгруппированные по должностям, сортировано по должностям
DROP VIEW IF EXISTS `view_position_group`;
CREATE VIEW `view_position_group`
AS
SELECT  `position`,
        GROUP_CONCAT(staffer SEPARATOR ', ') AS `staff`
FROM `view_stuff_info`
GROUP BY `position`
ORDER BY `position`;
SELECT * FROM `view_position_group`;
+------------+-------------------------------+
| position   | staff                         |
+------------+-------------------------------+
| Заведующий | Ларионова К. М.               |
| Провизор   | Горшков П. Г., Захарова А. М. |
| Фармацевт  | Карпова А. В., Федотов М. Я.  |
| Фасовщик   | Степанов А. С.                |
+------------+-------------------------------+


--4)Информация о препаратах с типом препарата, сортировано по названию товара
DROP VIEW IF EXISTS `view_medicament_info`;
CREATE VIEW `view_medicament_info`
AS
SELECT  m.name AS `medicament`,
        t.name AS `medicament_type`,
        m.fabricator,
        m.country,
        m.cost,
        m.wholesale_cost
FROM `medicaments` AS m
JOIN `medicament_types` AS t ON m.type_id = t.id
ORDER BY `medicament`;
SELECT * FROM `view_medicament_info`;
+---------------+----------------------+------------------------------+----------+---------+----------------+
| medicament    | medicament_type      | fabricator                   | country  | cost    | wholesale_cost |
+---------------+----------------------+------------------------------+----------+---------+----------------+
| Афлубин       | Таблетки подъязычные | Рихард Биттнер АГ            | Австрия  |  335.00 |         279.00 |
| Голдлайн Плюс | Капсулы              | Изварино Фарма ООО           | Россия   |  731.00 |         649.00 |
| Кардиомагнил  | Таблетки в оболочке  | Такеда Фармасьютикалс ООО    | Россия   |  208.00 |         189.00 |
| Компливит     | Таблетки в оболочке  | Фармстандарт                 | Россия   |  220.00 |         189.00 |
| Ксарелто      | Таблетки в оболочке  | Байер АГ                     | Германия | 3117.00 |        2799.00 |
| Мексидол      | Ампула               | АРМАВИРСКАЯ БИОФАБРИКА ФКП   | Россия   |  468.00 |         399.00 |
| Назонекс      | Спрей назальный      | Акрихин АО                   | Бельгия  |  674.00 |         610.00 |
| Пульмикорт    | Суспензия            | Астра Зенека АБ              | Швеция   |  739.00 |         680.00 |
| Супрадин      | Таблетки шипучие     | Делфарм Гайярд               | Россия   |  555.00 |         499.00 |
| Терафлекс     | Капсулы              | Контракт Фармакал Корпорейшн | США      | 1969.00 |        1599.00 |
+---------------+----------------------+------------------------------+----------+---------+----------------+


--5)Препараты сгруппированные по видам, сортировано по видам
DROP VIEW IF EXISTS `view_medicament_types_group`;
CREATE VIEW `view_medicament_types_group`
AS
SELECT  `medicament_type`,
        GROUP_CONCAT(medicament SEPARATOR ', ') AS `medicaments`      
FROM `view_medicament_info`
GROUP BY `medicament_type`
ORDER BY `medicament_type`;
SELECT * FROM `view_medicament_types_group`;
+----------------------+-----------------------------------+
| medicament_type      | medicaments                       |
+----------------------+-----------------------------------+
| Ампула               | Мексидол                          |
| Капсулы              | Терафлекс, Голдлайн Плюс          |
| Спрей назальный      | Назонекс                          |
| Суспензия            | Пульмикорт                        |
| Таблетки в оболочке  | Кардиомагнил, Ксарелто, Компливит |
| Таблетки подъязычные | Афлубин                           |
| Таблетки шипучие     | Супрадин                          |
+----------------------+-----------------------------------+


--6) Имя клиента и его возраст, сортировано по возрасту
DROP VIEW IF EXISTS `view_clients_age`;
CREATE VIEW `view_clients_age`
AS
SELECT  get_fio(`name`, `last_name`, `father_name`) AS `client`,
        get_age(`birth_date`, NOW()) AS `age`      
FROM `clients`
ORDER BY `age` DESC;
SELECT * FROM `view_clients_age`;
+--------------------+------+
| client             | age  |
+--------------------+------+
| Авдеев Л. А.       |   61 |
| Гусева Е. С.       |   60 |
| Шестунова М. Д.    |   59 |
| Жиганова Е. А.     |   59 |
| Носов П. М.        |   58 |
| Берестовская О. А. |   56 |
| Родовский М. К.    |   46 |
| Нестеренко Е. В.   |   44 |
| Новиков А. И.      |   32 |
| Гуриев К. С.       |   20 |
+--------------------+------+

--7)Препараты которых в наличии меньше 10 штук, сортировано по доступному количеству
DROP VIEW IF EXISTS `view_available_qty_sub_10`;
CREATE VIEW `view_available_qty_sub_10`
AS
SELECT  `name`,
        `available_qty`   
FROM `medicaments`
WHERE `available_qty` < 20
ORDER BY `available_qty` DESC;
SELECT * FROM `view_available_qty_sub_10`;
+---------------+---------------+
| name          | available_qty |
+---------------+---------------+
| Ксарелто      |            18 |
| Пульмикорт    |            18 |
| Кардиомагнил  |            14 |
| Терафлекс     |             8 |
| Назонекс      |             8 |
| Голдлайн Плюс |             5 |
+---------------+---------------+


--8)Продажи за последние 2 недели, сортировано по дате
DROP VIEW IF EXISTS `view_last_2_month_sales`;
CREATE VIEW `view_last_2_month_sales`
AS
SELECT * 
FROM `sales`
WHERE `date` >= CURDATE() - INTERVAL 14 DAY
ORDER BY `date` DESC;
SELECT * FROM `view_last_2_month_sales`;
+----+------------+---------------+---------+----------+---------+------------+-----------+
| id | date       | medicament_id | cost    | quantity | income  | staffer_id | client_id |
+----+------------+---------------+---------+----------+---------+------------+-----------+
|  5 | 2022-05-28 |             6 |  220.00 |        1 |  220.00 |          3 |         1 |
| 11 | 2022-05-28 |             3 | 1969.00 |        3 | 5907.00 |          3 |         2 |
+----+------------+---------------+---------+----------+---------+------------+-----------+


--9)Поставки которые еще не пришли, сортировано по дате
DROP VIEW IF EXISTS `view_not_delivered`;
CREATE VIEW `view_not_delivered`
AS
SELECT * 
FROM `deliveries`
WHERE `receipt_date` is NULL AND `status` = 'not_delivered'
ORDER BY `order_date` DESC;
SELECT * FROM `view_not_delivered`;
+----+------------+--------------+---------------+----------------+----------+---------+---------------+
| id | order_date | receipt_date | medicament_id | wholesale_cost | quantity | expense | status        |
+----+------------+--------------+---------------+----------------+----------+---------+---------------+
| 16 | 2022-06-02 | NULL         |             7 |         610.00 |        1 |  610.00 | not_delivered |
| 15 | 2022-06-01 | NULL         |             2 |         189.00 |        4 |  756.00 | not_delivered |
| 17 | 2022-05-31 | NULL         |             9 |         680.00 |        2 | 1360.00 | not_delivered |
+----+------------+--------------+---------------+----------------+----------+---------+---------------+


--10) Принятые поставки со сроками доставки, сортировано по сроками доставки
DROP VIEW IF EXISTS `view_not_delivered`;
CREATE VIEW `view_not_delivered`
AS
SELECT  `order_date`,
        `receipt_date`,
        get_delivery_days(`order_date`, `receipt_date`) AS `delivery_days`,
        `medicament_id`,
        `wholesale_cost`,
        `quantity`,
        `expense`
FROM `deliveries`
WHERE `status` = 'delivered'
ORDER BY `delivery_days` DESC;
SELECT * FROM `view_not_delivered`;
+------------+--------------+---------------+---------------+----------------+----------+---------+
| order_date | receipt_date | delivery_days | medicament_id | wholesale_cost | quantity | expense |
+------------+--------------+---------------+---------------+----------------+----------+---------+
| 2022-05-19 | 2022-05-29   |            10 |            10 |         649.00 |        3 | 1947.00 |
| 2022-05-21 | 2022-05-27   |             6 |             6 |         189.00 |       10 | 1890.00 |
| 2022-05-23 | 2022-05-29   |             6 |             2 |         189.00 |        6 | 1134.00 |
| 2022-05-10 | 2022-05-16   |             6 |             7 |         610.00 |        3 | 1830.00 |
| 2022-05-24 | 2022-05-30   |             6 |             3 |        1599.00 |        5 | 7995.00 |
| 2022-05-02 | 2022-05-08   |             6 |             4 |         279.00 |       10 | 2790.00 |
| 2022-05-04 | 2022-05-10   |             6 |             1 |         499.00 |        8 | 3992.00 |
| 2022-05-18 | 2022-05-24   |             6 |             5 |        2799.00 |        3 | 8397.00 |
| 2022-05-12 | 2022-05-18   |             6 |            10 |         649.00 |        4 | 2596.00 |
| 2022-05-14 | 2022-05-20   |             6 |             9 |         680.00 |        5 | 3400.00 |
| 2022-05-09 | 2022-05-15   |             6 |             1 |         499.00 |       10 | 4990.00 |
| 2022-05-13 | 2022-05-19   |             6 |             8 |         399.00 |        7 | 2793.00 |
| 2022-05-17 | 2022-05-23   |             6 |             4 |         279.00 |        3 |  837.00 |
+------------+--------------+---------------+---------------+----------------+----------+---------+

------------------------------------------------------------------------------------------------------------------------------------------

--Функции
--Принимает имя, фамилию, отчество. Возвращает фамилию и инициалы 
DROP FUNCTION IF EXISTS get_fio;
DELIMITER //
CREATE FUNCTION get_fio(name VARCHAR(255), last_name VARCHAR(255), father_name VARCHAR(255))
RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
    DECLARE fio VARCHAR(255);
    IF name is NULL || name = '' || last_name is NULL || last_name = '' || father_name is NULL || father_name = '' THEN
        RETURN '#####';
    END IF;
    SET fio = CONCAT(last_name, ' ', LEFT(name,1), '. ', LEFT(father_name,1), '.');
    RETURN fio;
END//
DELIMITER ;

--Принимает дату рождения и текущую дату. Возварщает возраст
DROP FUNCTION IF EXISTS get_age;
DELIMITER //
CREATE FUNCTION get_age(birth_date DATE, current_year DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE age INT;
    SET age = TIMESTAMPDIFF(YEAR, birth_date, current_year);
    RETURN age;
END//
DELIMITER ;

--Принимает дату заказа поставки и дату приема поставки. Возварщает срок доставки поставки
DROP FUNCTION IF EXISTS get_delivery_days;
DELIMITER //
CREATE FUNCTION get_delivery_days(order_date DATE, receipt_date DATE)
RETURNS INT
DETERMINISTIC
BEGIN
    DECLARE delivery_days INT;
    SET delivery_days = TIMESTAMPDIFF(DAY, order_date, receipt_date);
    RETURN delivery_days;
END//
DELIMITER ;