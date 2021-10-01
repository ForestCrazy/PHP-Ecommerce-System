CREATE TABLE IF NOT EXISTS `withdraw_request` (
    `withdraw_id` INT AUTO_INCREMENT,
    `store_id` INT NOT NULL,
    `withdraw_balance` INT NOT NULL,
    `fees` INT NOT NULL,
    `bank_id` varchar(10) NOT NULL,
    `account_number` varchar(20) NOT NULL,
    `createtime` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `status` ENUM('pending', 'approve', 'decline') DEFAULT 'pending',
    `update_status_time` DATETIME,
    `withdraw_slip` varchar(80),
    PRIMARY KEY(`withdraw_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8 AUTO_INCREMENT = 1;