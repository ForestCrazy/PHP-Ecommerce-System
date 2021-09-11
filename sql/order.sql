CREATE TABLE IF NOT EXISTS `order` (
    `order_id` INT AUTO_INCREMENT,
    `shipping_provider_id` INT NOT NULL,
    `track_code` varchar(300),
    `first_name` varchar(20) NOT NULL,
    `last_name` varchar(300),
    `phone` varchar(20),
    `address` varchar(300),
    `city` varchar(20),
    `province` varchar(20),
    `zip_code` varchar(10),
    `u_id` INT NOT NULL,
    `status` ENUM('pending', 'processing', 'shipped', 'cancelled'),
    `payment_id` INT,
    `payment_method` ENUM('tranfer', 'cod'),
    `context` varchar(300),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`order_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;