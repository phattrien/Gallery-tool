<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );

define( 'NV_IS_MOD_GLTOOL', true );

// Class cua module
require_once( NV_ROOTDIR . "/modules/" . $module_file . "/class.php" );
$GLT = new nv_mod_gallery_tool();

function gltJsonResponse( $error = array(), $data = array() )
{
	$contents = array(
		"jsonrpc" => "2.0",
		"error" => $error,
		"data" => $data,
	);
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo json_encode( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

?>