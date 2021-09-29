CREATE TABLE IF NOT EXISTS `bank_withdraw` (
    `bank_id` varchar(10) NOT NULL,
    `bank_name` varchar(30) NOT NULL,
    `bank_code` varchar(10) NOT NULL,
    `enable` BOOLEAN DEFAULT true
)ENGINE=InnoDB DEFAULT CHARSET=utf8;