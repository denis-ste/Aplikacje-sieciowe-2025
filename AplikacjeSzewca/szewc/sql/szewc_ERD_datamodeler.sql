-- pod Oracle SQL  Modeler


CREATE TABLE `roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(30) NOT NULL,
  `is_active` CHAR(1) NOT NULL DEFAULT 'Y',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_name` (`name`),
  CONSTRAINT `chk_roles_active` CHECK (`is_active` IN ('Y','N'))
) ENGINE=InnoDB;

CREATE TABLE `order_statuses` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(30) NOT NULL,
  `name` VARCHAR(50) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` CHAR(1) NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_order_statuses_code` (`code`),
  CONSTRAINT `chk_order_statuses_active` CHECK (`is_active` IN ('Y','N'))
) ENGINE=InnoDB;

CREATE TABLE `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(120) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role_id` INT NOT NULL,
  `is_active` CHAR(1) NOT NULL DEFAULT 'Y',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_users_username` (`username`),
  UNIQUE KEY `uk_users_email` (`email`),
  KEY `ix_users_role` (`role_id`),
  CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `chk_users_active` CHECK (`is_active` IN ('Y','N'))
) ENGINE=InnoDB;

CREATE TABLE `services` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `is_active` CHAR(1) NOT NULL DEFAULT 'Y',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_services_name` (`name`),
  KEY `ix_services_active` (`is_active`),
  CONSTRAINT `chk_services_active` CHECK (`is_active` IN ('Y','N'))
) ENGINE=InnoDB;

CREATE TABLE `products` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock_qty` INT NOT NULL DEFAULT 0,
  `is_active` CHAR(1) NOT NULL DEFAULT 'Y',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_products_name` (`name`),
  KEY `ix_products_active` (`is_active`),
  KEY `ix_products_stock` (`stock_qty`),
  CONSTRAINT `chk_products_active` CHECK (`is_active` IN ('Y','N')),
  CONSTRAINT `chk_products_stock` CHECK (`stock_qty` >= 0)
) ENGINE=InnoDB;

CREATE TABLE `orders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `client_user_id` INT NOT NULL,
  `assigned_user_id` INT NULL DEFAULT NULL,
  `order_type` VARCHAR(10) NOT NULL DEFAULT 'SERVICE',
  `service_id` INT NULL DEFAULT NULL,
  `pickup_date` DATE NULL DEFAULT NULL,
  `status_id` INT NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL,
  `note` VARCHAR(500) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ix_orders_client` (`client_user_id`),
  KEY `ix_orders_worker` (`assigned_user_id`),
  KEY `ix_orders_status` (`status_id`),
  KEY `ix_orders_pickup_date` (`pickup_date`),
  KEY `ix_orders_service` (`service_id`),
  KEY `ix_orders_type` (`order_type`),
  CONSTRAINT `fk_orders_client` FOREIGN KEY (`client_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_orders_worker` FOREIGN KEY (`assigned_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_orders_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  CONSTRAINT `fk_orders_status` FOREIGN KEY (`status_id`) REFERENCES `order_statuses` (`id`),
  CONSTRAINT `chk_orders_type` CHECK (`order_type` IN ('SERVICE','PRODUCT'))
) ENGINE=InnoDB;

CREATE TABLE `order_items` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `qty` INT NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `line_total` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ix_order_items_order` (`order_id`),
  KEY `ix_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `chk_order_items_qty` CHECK (`qty` >= 1),
  CONSTRAINT `chk_order_items_price` CHECK (`unit_price` >= 0 AND `line_total` >= 0)
) ENGINE=InnoDB;
