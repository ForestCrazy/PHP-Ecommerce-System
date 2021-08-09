CREATE TABLE IF NOT EXISTS `product` (
    `product_id` INT AUTO_INCREMENT,
    `product_name` varchar(200) NOT NULL,
    `product_description` varchar(1000) NOT NULL,
    `product_quantity` INT NOT NULL,
    `store_id` INT(10) NOT NULL,
    `product_price` INT(8) NOT NULL,
    `category_id` INT NOT NULL,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;