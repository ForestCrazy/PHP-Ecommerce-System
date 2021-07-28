CREATE TABLE IF NOT EXISTS `user` (
    `u_id` INT AUTO_INCREMENT,
    `username` varchar(20) NOT NULL,
    `password` varchar(300) NOT NULL,
    `email` varchar(100),
    `last_notification_id` INT NOT NULL,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;