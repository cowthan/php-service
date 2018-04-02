DROP TABLE IF EXISTS `%table_prefix%requests`;
CREATE TABLE `%table_prefix%requests` (
  `request_id` bigint(32) NOT NULL AUTO_INCREMENT,
  `request_type` enum('upload','signup','account-edit','account-password-forgot','account-password-reset','account-resend-activation','account-email-needed','account-change-email','account-activate','login') NOT NULL,
  `request_user_id` bigint(32) DEFAULT NULL,
  `request_ip` varchar(255) NOT NULL,
  `request_date` datetime NOT NULL,
  `request_date_gmt` datetime NOT NULL,
  `request_result` enum('success','fail') NOT NULL,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;