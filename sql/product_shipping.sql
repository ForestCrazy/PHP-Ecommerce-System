CREATE TABLE IF NOT EXISTS `product_shipping` (
  `product_id` INT NOT NULL,
  `shipping_provider_id` INT NOT NULL,
  `shipping_price` INT NOT NULL,
  `shipping_time` varchar(20) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;