CREATE TABLE IF NOT EXISTS `payment` (
    `payment_id` INT AUTO_INCREMENT,
    `u_id` INT NOT NULL,
    `pay_slip` varchar(100) NOT NULL,
    `approve` boolean DEFAULT false,
    `approve_time` datetime,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY(`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;