CREATE TABLE IF NOT EXISTS `shipping_provider` (
    `shipping_provider_id` INT AUTO_INCREMENT,
    `shipping_name` varchar(100) NOT NULL,
    `enable` BOOLEAN DEFAULT true,
    PRIMARY KEY(`shipping_provider_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;