CREATE TABLE `Customers` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`firstname` VARCHAR(255) NOT NULL AUTO_INCREMENT,
	`lastname` VARCHAR(255) NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(255) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
);

CREATE TABLE `customer_rewards` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NOT NULL AUTO_INCREMENT,
	`points` INT NOT NULL AUTO_INCREMENT,
	`expiry_date` DATE NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
);

CREATE TABLE `orders` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NOT NULL,
	`total_amount` DECIMAL NOT NULL,
	`status` INT NOT NULL,
	PRIMARY KEY (`id`)
);

CREATE TABLE `products` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) NOT NULL AUTO_INCREMENT,
	`image` VARCHAR(255) NOT NULL AUTO_INCREMENT,
	`price` DECIMAL NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
);

CREATE TABLE `order_details` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`order_id` INT AUTO_INCREMENT,
	`product_id` INT NOT NULL AUTO_INCREMENT,
	`price` DECIMAL NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`id`)
);

CREATE TABLE `settings` (
	`field` BINARY NOT NULL,
	`value` BINARY NOT NULL
);

CREATE TABLE `reward_histories` (
	`id` INT NOT NULL AUTO_INCREMENT,
	`customer_id` INT NOT NULL AUTO_INCREMENT,
	`order_id` INT NOT NULL AUTO_INCREMENT,
	`date` DATE NOT NULL,
	`reward_points` INT NOT NULL,
	`reward_amounts` INT NOT NULL,
	PRIMARY KEY (`id`)
);

ALTER TABLE `customer_rewards` ADD CONSTRAINT `customer_rewards_fk0` FOREIGN KEY (`customer_id`) REFERENCES `Customers`(`id`);

ALTER TABLE `orders` ADD CONSTRAINT `orders_fk0` FOREIGN KEY (`customer_id`) REFERENCES `Customers`(`id`);

ALTER TABLE `order_details` ADD CONSTRAINT `order_details_fk0` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`);

ALTER TABLE `order_details` ADD CONSTRAINT `order_details_fk1` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`);

ALTER TABLE `reward_histories` ADD CONSTRAINT `reward_histories_fk0` FOREIGN KEY (`customer_id`) REFERENCES `Customers`(`id`);

ALTER TABLE `reward_histories` ADD CONSTRAINT `reward_histories_fk1` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`);








