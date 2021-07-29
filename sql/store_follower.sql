CREATE TABLE IF NOT EXISTS `store_follower` (
    `store_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `createtime` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;