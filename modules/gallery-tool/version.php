<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "NukeViet Gallery Tool",
	"modfuncs" => "main",
	"submenu" => "main",
	"is_sysmod" => 0,
	"virtual" => 1,
	"version" => "3.4.01",
	"date" => "Thu, 24 Apr 2014 00:00:00 GMT",
	"author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
	"note" => "Module Gallery Tool For Many Slideshow",
	"uploads_dir" => array(
		$module_name,
		$module_name . "/images",
		$module_name . "/thumb",
		$module_name . "/thumb/sys",
	)
);

?>