CREATE TABLE IF NOT EXISTS `cart` (
    `u_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `shipping_id` INT NOT NULL,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;