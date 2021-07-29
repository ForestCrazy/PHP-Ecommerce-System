CREATE TABLE IF NOT EXISTS `user_notification` (
    `notification_id` INT AUTO_INCREMENT,
    `u_id` INT NOT NULL,
    `notification_msg` varchar(300) NOT NULL,
    `notification_url` varchar(100),
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;