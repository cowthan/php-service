DROP TABLE IF EXISTS `%table_prefix%albums`;
CREATE TABLE `%table_prefix%albums` (
  `album_id` bigint(32) NOT NULL AUTO_INCREMENT,
  `album_name` varchar(100) NOT NULL,
  `album_user_id` bigint(32) NOT NULL,
  `album_date` datetime NOT NULL,
  `album_date_gmt` datetime NOT NULL,
  `album_privacy` enum('public','password','private','private_but_link','custom') DEFAULT 'public',
  `album_privacy_extra` text,
  `album_image_count` bigint(32) NOT NULL DEFAULT '0',
  `album_description` text,
  PRIMARY KEY (`album_id`),
  FULLTEXT KEY `searchindex` (`album_name`,`album_description`)
) ENGINE=%table_engine% DEFAULT CHARSET=utf8;