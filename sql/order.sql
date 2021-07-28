CREATE TABLE IF NOT EXISTS `order` (
    `order_id` INT AUTO_INCREMENT,
    `shipping_provider_id` INT NOT NULL,
    `track_code` varchar(300),
    `address_id` INT NOT NULL,
    `u_id` INT NOT NULL,
    `status` ENUM('pending', 'processing', 'shipped', 'cancelled'),
    `payment_id` INT,
    `payment_method` ENUM('tranfer', 'cod'),
    `context` varchar(300),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;