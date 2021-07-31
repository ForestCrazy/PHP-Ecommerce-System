CREATE TABLE IF NOT EXISTS `product_favorite` (
    `u_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    PRIMARY KEY(`product_id`)
) ENGINE=InnoDB;