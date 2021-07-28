CREATE TABLE IF NOT EXISTS `address` (
    `address_id` INT AUTO_INCREMENT,
    `address_token` varchar(20) NOT NULL,
    `name` varchar(300),
    `phone` varchar(20),
    `address` varchar(300),
    `city` varchar(20),
    `province` varchar(20),
    `zip_code` varchar(10),
    `u_id` INT NOT NULL,
    PRIMARY KEY(`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;