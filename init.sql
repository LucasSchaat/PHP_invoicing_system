-- THIS FILE IS FOR CREATING THE DB TABLES NEEDED FOR THIS PROJECT

-- INVOICE TABLE
CREATE TABLE `invoice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL DEFAULT '',
  `customer_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `comments` varchar(3000) DEFAULT NULL,
  `invoice_date` varchar(50) DEFAULT NULL,
  `due_date` varchar(50) DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `tax_rate` float DEFAULT NULL,
  `est_tax` float DEFAULT NULL,
  `amt_due` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;


-- CATEGORY TABLE
CREATE TABLE `invoice_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- THIS COMMAND MAKES IT SO THAT THE MYSQL QUERY USING LIKE CAN BE USED FOR CASE INSENSITIVE QUERIES
ALTER TABLE invoice_category 
MODIFY COLUMN category_name VARCHAR(255) CHARACTER 
SET UTF8 COLLATE UTF8_GENERAL_CI;


-- INVOICE ITEM TABLE
CREATE TABLE `invoice_item` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_name` varchar(255) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `item_desc` varchar(600) DEFAULT NULL,
  `category_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `invoice_item_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `invoice_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- THIS COMMAND MAKES IT SO THAT THE MYSQL QUERY USING LIKE CAN BE USED FOR CASE INSENSITIVE QUERIES
ALTER TABLE invoice_item 
MODIFY COLUMN item_name VARCHAR(255) CHARACTER 
SET UTF8 COLLATE UTF8_GENERAL_CI;


-- INVOICE ITEMS JOIN TABLE
CREATE TABLE `invoice_items_join` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned NOT NULL,
  `item_id` int(11) unsigned DEFAULT NULL,
  `item_quantity` int(11) DEFAULT NULL,
  `item_desc_act` varchar(600) DEFAULT NULL,
  `item_price_act` float DEFAULT NULL,
  `item_total` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `invoice_items_join_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `invoice_items_join_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `invoice_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;
