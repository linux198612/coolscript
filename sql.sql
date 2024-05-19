CREATE TABLE `admin_users` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `admin_users` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$W9hvVqLady2ivV791Nz9zOeqvjASvUTYxlcA9kW25EROz1RgjVsai');

CREATE TABLE IF NOT EXISTS `shortlinks_list`(
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` varchar(250) NOT NULL, 
  `timer` int(32) NOT NULL, 
  `reward` decimal(20,8) NOT NULL, 
  `limit_view` int(32) NOT NULL,
  PRIMARY KEY (`id`)
  )ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `shortlinks_viewed`(
 `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
 `userid` int(32) NOT NULL, 
 `slid` int(32) NOT NULL, 
 `ip_address` varchar(150) NOT NULL, 
 `timestamp` int(32) NOT NULL, 
 `timestamp_expiry` int(32) NOT NULL,
  PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `shortlinks_views`(
 `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
 `userid` int(32) NOT NULL, 
 `slid` int(32) NOT NULL, 
 `claim_key` varchar(10) NOT NULL, 
 `shortlink` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
 ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `banned_address` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `banned_ip` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `settings` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
(1, 'faucet_name', 'Zero Faucet Script'),
(2, 'maintenance', 'off'),
(5, 'timer', '1'),
(6, 'min_reward', '50000'),
(7, 'max_reward', '75000'),
(8, 'zerochain_api', ''),
(9, 'zerochain_privatekey', ''),
(11, 'claim_enabled', 'yes'),
(14, 'vpn_shield', 'no'),
(15, 'referral_percent', '0'),
(16, 'reverse_proxy', 'no'),
(17, 'bonus_reward_coin', '0.01'),
(18, 'bonus_reward_xp', '10'),
(19, 'bonus_faucet_require', '10'),
(22, 'iphub_api_key', ''),
(23, 'min_withdrawal_gateway', '1000000'),
(26, 'hcaptcha_pub_key', ''),
(27, 'hcaptcha_sec_key', ''), 
(28, 'faucet_currency', 'Zerocoin'),
(29, 'currency_value', ''),
(30, 'reward_last_check', '');

CREATE TABLE IF NOT EXISTS `transactions` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(32) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,8) NOT NULL,
  `timestamp` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `users` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `address` varchar(75) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `balance` decimal(10,8) NOT NULL,
  `joined` int(32) NOT NULL,
  `level` int(32) DEFAULT 0,
  `xp` int(32) DEFAULT 0,
  `last_activity` int(32) NOT NULL,
  `referred_by` int(32) NOT NULL,
  `last_claim` int(32) NOT NULL,
  `credits` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `withdraw_history` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(32) NOT NULL,
  `address` varchar(100) NOT NULL,
  `amount` decimal(10,8) NOT NULL,
  `txid` text NOT NULL,
  `timestamp` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `condition` int(32) UNSIGNED NOT NULL,
  `reward` decimal(10,6) DEFAULT 0.000000,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `achievement_history` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `achievement_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(32) UNSIGNED NOT NULL,
  `claim_time` int(32) UNSIGNED,
  `amount` decimal(10,6) DEFAULT 0.000000,
  `claimed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `bonus_history` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(32) UNSIGNED NOT NULL,
  `bonus_id` int(32) UNSIGNED NOT NULL,
  `bonus_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `referralearn` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(32) NOT NULL,
  `amount` decimal(10,8) NOT NULL,
  `timestamp` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `coupons` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL,
    reward DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    limit_per_day INT NOT NULL,
    UNIQUE KEY unique_code (code)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `redeemed_coupons` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    coupon_id INT NOT NULL,
    date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (coupon_id) REFERENCES coupons(id),
    UNIQUE KEY unique_redemption (user_id, coupon_id, date)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `offerwalls_history` (
`id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userid` int(32) NOT NULL,
  `offerwalls` varchar(50) NOT NULL,
  `offerwalls_name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `timestamp` int(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `white_list` (
  `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;