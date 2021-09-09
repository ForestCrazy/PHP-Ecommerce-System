CREATE TABLE IF NOT EXISTS `address` (
    `address_id` INT AUTO_INCREMENT,
    `first_name` varchar(20) NOT NULL,
    `last_name` varchar(300),
    `phone` varchar(20),
    `address` varchar(300),
    `city` varchar(20),
    `province` varchar(20),
    `zip_code` varchar(10),
    `u_id` INT NOT NULL,
    `createtime` DATETIME default CURRENT_TIMESTAMP,
    PRIMARY KEY(`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;