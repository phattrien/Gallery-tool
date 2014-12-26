<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if ( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/class.php" );
$GLT = new nv_mod_gallery_tool();

$submenu['pic-content'] = $GLT->lang('picAdd');
$submenu['album-list'] = $GLT->lang('albumList');
$submenu['album-content'] = $GLT->lang('albumAdd');
$submenu['config-master'] = $GLT->lang('cfg');

$allow_func = array( 'main', 'pic-content', 'album-list', 'album-content', 'pic-edit', 'config-master' );

define( 'NV_BLOG_ADMIN', true );

?>