CREATE TABLE IF NOT EXISTS `category` (
    `category_id` INT AUTO_INCREMENT,
    `category_weight` int(2) NOT NULL,
    `category_name` varchar(200) NOT NULL,
    `category_img` varchar(200) NOT NULL,
    `category_alt` varchar(200) NOT NULL,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;