CREATE TABLE IF NOT EXISTS `banner` (
    `banner_id` int AUTO_INCREMENT,
    `banner_tier` ENUM('0', '1', '2'),
    `banner_img` varchar(200),
    `banner_alt` varchar(100),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    `start_time` datetime,
    `end_time` datetime,
    PRIMARY KEY(`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;