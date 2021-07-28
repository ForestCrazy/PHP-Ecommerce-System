CREATE TABLE IF NOT EXISTS `product_review` (
    `review_id` INT AUTO_INCREMENT,
    `sub_order_id` INT NOT NULL,
    `review_msg` varchar(300),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;