CREATE TABLE `users` (
	`user_id` INT NOT NULL AUTO_INCREMENT,
	`username` varchar(32) NOT NULL UNIQUE,
	`password_hash` varchar(60) NOT NULL,
	`first_last_name` varchar(64) NOT NULL,
	`year_of_birth` INT NOT NULL,
	`city` varchar(32) NOT NULL,
	`email` varchar(32) NOT NULL,
	`role` INT NOT NULL DEFAULT '1',
	PRIMARY KEY (`user_id`)
);

CREATE TABLE `species` (
	`species_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL UNIQUE,
	`family_id` INT NOT NULL,
	`size` TEXT,
	`nutrition` TEXT,
	`predators` TEXT,
	`lifetime` TEXT,
	`habitat` TEXT,
	`lifestyle` TEXT,
	`reproduction` TEXT,
	`distribution` TEXT,
	`location_x` INT NOT NULL,
	`location_y` INT NOT NULL,
	PRIMARY KEY (`species_id`)
);

CREATE TABLE `classes` (
	`class_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL UNIQUE,
	PRIMARY KEY (`class_id`)
);

CREATE TABLE `orders` (
	`order_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL UNIQUE,
	`parent_class_id` INT NOT NULL,
	PRIMARY KEY (`order_id`)
);

CREATE TABLE `families` (
	`family_id` INT NOT NULL AUTO_INCREMENT,
	`name` varchar(32) NOT NULL UNIQUE,
	`parent_order_id` INT NOT NULL,
	PRIMARY KEY (`family_id`)
);

CREATE TABLE `mammal_animals` (
	`animal_id` INT NOT NULL AUTO_INCREMENT,
	`species_id` INT NOT NULL,
	`name` varchar(32) NOT NULL,
	`age` INT NOT NULL,
	`sex` varchar(1) NOT NULL,
	`birth_location` varchar(32) NOT NULL,
	`arrival_date` DATE NOT NULL,
	`photo_path` varchar(256) NOT NULL,
	`interesting_facts` TEXT NOT NULL,
	PRIMARY KEY (`animal_id`)
);

CREATE TABLE `adoptions` (
	`visitor_id` INT NOT NULL,
	`animal_id` INT NOT NULL,
	PRIMARY KEY (`visitor_id`,`animal_id`)
);

CREATE TABLE `visits` (
	`visitor_id` INT NOT NULL,
	`species_id` INT NOT NULL,
	PRIMARY KEY (`visitor_id`,`species_id`)
);

CREATE TABLE `adopter_exclusive_facts` (
	`animal_id` INT NOT NULL,
	`fact` TEXT NOT NULL
);

CREATE TABLE `adopter_exclusive_photos` (
	`animal_id` INT NOT NULL,
	`photo_path` varchar(256) NOT NULL
);

CREATE TABLE `adopter_exclusive_videos` (
	`animal_id` INT NOT NULL,
	`video_path` varchar(256) NOT NULL
);

CREATE TABLE `guard_assigned_animals` (
	`guard_id` INT NOT NULL,
	`animal_id` INT NOT NULL,
	PRIMARY KEY (`guard_id`,`animal_id`)
);

ALTER TABLE `species` ADD CONSTRAINT `species_fk0` FOREIGN KEY (`family_id`) REFERENCES `families`(`family_id`);

ALTER TABLE `orders` ADD CONSTRAINT `orders_fk0` FOREIGN KEY (`parent_class_id`) REFERENCES `classes`(`class_id`);

ALTER TABLE `families` ADD CONSTRAINT `families_fk0` FOREIGN KEY (`parent_order_id`) REFERENCES `orders`(`order_id`);

ALTER TABLE `mammal_animals` ADD CONSTRAINT `mammal_animals_fk0` FOREIGN KEY (`species_id`) REFERENCES `species`(`species_id`);

ALTER TABLE `adoptions` ADD CONSTRAINT `adoptions_fk0` FOREIGN KEY (`visitor_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `adoptions` ADD CONSTRAINT `adoptions_fk1` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals`(`animal_id`);

ALTER TABLE `visits` ADD CONSTRAINT `visits_fk0` FOREIGN KEY (`visitor_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `visits` ADD CONSTRAINT `visits_fk1` FOREIGN KEY (`species_id`) REFERENCES `species`(`species_id`);

ALTER TABLE `adopter_exclusive_facts` ADD CONSTRAINT `adopter_exclusive_facts_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals`(`animal_id`);

ALTER TABLE `adopter_exclusive_photos` ADD CONSTRAINT `adopter_exclusive_photos_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals`(`animal_id`);

ALTER TABLE `adopter_exclusive_videos` ADD CONSTRAINT `adopter_exclusive_videos_fk0` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals`(`animal_id`);

ALTER TABLE `guard_assigned_animals` ADD CONSTRAINT `guard_assigned_animals_fk0` FOREIGN KEY (`guard_id`) REFERENCES `users`(`user_id`);

ALTER TABLE `guard_assigned_animals` ADD CONSTRAINT `guard_assigned_animals_fk1` FOREIGN KEY (`animal_id`) REFERENCES `mammal_animals`(`animal_id`);

