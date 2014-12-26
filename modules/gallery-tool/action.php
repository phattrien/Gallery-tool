<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die('Stop!!!');

$sql_drop_module = array();

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_albums`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_pictures`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_thumbs`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config`";

$sql_create_module = $sql_drop_module;

// Các album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_albums` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID của album',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT 'Tên album',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả sơ bộ album',
  `numPics` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT 'Số ảnh trong album',
  `dataPics` mediumtext NOT NULL COMMENT 'Dữ liệu ảnh gồm ID các ảnh trong album và thứ tự ảnh',
  `bigW` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều rộng ảnh lớn',
  `bigH` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều cao ảnh lớn',
  `smallW` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều rộng ảnh nhỏ',
  `smallH` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều cao ảnh nhỏ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
)ENGINE=MyISAM";

// Các ảnh
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_pictures` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID của ảnh',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'Tên ảnh',
  `info1` varchar(255) NOT NULL DEFAULT '' COMMENT 'Thông tin 1',
  `info2` varchar(255) NOT NULL DEFAULT '' COMMENT 'Thông tin 2',
  `info3` varchar(255) NOT NULL DEFAULT '' COMMENT 'Thông tin 3',
  `info4` varchar(255) NOT NULL DEFAULT '' COMMENT 'Thông tin 4',
  `info5` varchar(255) NOT NULL DEFAULT '' COMMENT 'Thông tin 5',  
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'Liên kết nếu có',  
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT 'Mô tả sơ bộ', 
  `file` varchar(255) NOT NULL DEFAULT '' COMMENT 'File ảnh gốc',
  `thumb` varchar(255) NOT NULL DEFAULT '' COMMENT 'File ảnh nhỏ',
  `width` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều rộng',
  `height` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều cao',
  `size` double unsigned NOT NULL DEFAULT 0 COMMENT 'Dung lượng ảnh',
  `format` varchar(100) NOT NULL DEFAULT '' COMMENT 'Định dạng ảnh', 
  `albums` varchar(255) NOT NULL DEFAULT '' COMMENT 'Danh sách ID các album mà ảnh này thuộc', 
  PRIMARY KEY (`id`)
)ENGINE=MyISAM";

// Các ảnh trong album
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_thumbs` (
  `picId` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'ID của ảnh',
  `width` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều rộng',
  `height` mediumint(8) unsigned NOT NULL DEFAULT 0 COMMENT 'Chiều cao',
  `link` varchar(255) NOT NULL DEFAULT '' COMMENT 'File ảnh thumb',
  UNIQUE KEY `picId`(`picId`, `width`, `height`)
)ENGINE=MyISAM";

// Cấu hình module
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` (
  `config_name` varchar(30) NOT NULL,
  `config_value` mediumtext NOT NULL,
  UNIQUE KEY `config_name` (`config_name`)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config` VALUES
('chunk_size', '200'),
('chunk_size_unit', 'kb'),
('max_file_size', '800'),
('max_file_size_unit', 'kb')";

?>