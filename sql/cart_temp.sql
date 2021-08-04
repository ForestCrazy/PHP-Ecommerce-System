CREATE TABLE IF NOT EXISTS `cart_temp` (
    `cart_id` varchar(20) NOT NULL,
    `u_id` INT NOT NULL,
    `product_id` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;