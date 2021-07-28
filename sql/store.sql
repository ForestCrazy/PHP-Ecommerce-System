CREATE TABLE IF NOT EXISTS `store` (
    `store_id` INT AUTO_INCREMENT,
    `u_id` INT NOT NULL,
    `store_name` varchar(100) NOT NULL,
    `store_description` varchar(800) NOT NULL,
    `store_img` varchar(200),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;