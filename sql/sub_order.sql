CREATE TABLE IF NOT EXISTS `sub_order` (
    `sub_order_id` INT AUTO_INCREMENT,
    `order_id` INT NOT NULL,
    `product_id` INT NOT NULL,
    `quantity` INT NOT NULL,
    `total_price` INT NOT NULL,
    PRIMARY KEY(`sub_order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1;