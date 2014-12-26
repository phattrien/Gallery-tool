<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

$page_title = $GLT->lang('cfg');
$array = array();

$array_size_unit = array( 'b', 'kb', 'mb', 'gb', 'tb' );
$array_size_mul = array(
	'b' => 1,
	'kb' => 1024,
	'mb' => 1024 * 1024,
	'gb' => 1024 * 1024 * 1024,
	'tb' => 1024 * 1024 * 1024 * 1024,
);

// Lay thong tin submit
if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['chunk_size'] = abs( $nv_Request->get_float( 'chunk_size', 'post', 0 ) );
	$array['chunk_size_unit'] = filter_text_input( 'chunk_size_unit', 'post', '', 1, 255 );
	$array['max_file_size'] = abs( $nv_Request->get_float( 'max_file_size', 'post', 0 ) );
	$array['max_file_size_unit'] = filter_text_input( 'max_file_size_unit', 'post', '', 1, 255 );
	
	// Kiem tra xac nhan
	if( ! in_array( $array['chunk_size_unit'], $array_size_unit ) )
	{
		$array['chunk_size_unit'] = $array_size_unit[0];
	}
	if( ! in_array( $array['max_file_size_unit'], $array_size_unit ) )
	{
		$array['max_file_size_unit'] = $array_size_unit[0];
	}
	
	// Fix cac dung luong tap tin
	$chunk_size = $array['chunk_size'] * $array_size_mul[$array['chunk_size_unit']];
	$max_file_size = $array['max_file_size'] * $array_size_mul[$array['max_file_size_unit']];
	
	if( $chunk_size > NV_UPLOAD_MAX_FILESIZE )
	{
		$array['chunk_size'] = NV_UPLOAD_MAX_FILESIZE;
		$array['chunk_size_unit'] = $array_size_unit[0];
	}
	if( $max_file_size == 0 or $max_file_size > NV_UPLOAD_MAX_FILESIZE )
	{
		$array['max_file_size'] = NV_UPLOAD_MAX_FILESIZE;
		$array['max_file_size_unit'] = $array_size_unit[0];
	}
	
	foreach( $array as $config_name => $config_value )
	{
		$sql = "REPLACE INTO `" . $GLT->table_prefix . "_config` VALUES (" . $db->dbescape( $config_name ) . "," . $db->dbescape( $config_value ) . ")";
		$db->sql_query( $sql );
	}

	nv_del_moduleCache( $module_name );

	Header( "Location: " . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
	die();
}

$xtpl = new XTemplate( "config-master.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op );
$xtpl->assign( 'DATA', $GLT->setting );
$xtpl->assign( 'MAX_SIZE_NOTE', nv_convertfromBytes( NV_UPLOAD_MAX_FILESIZE ) );

// Xuat cac don vi dung luong anh
foreach( $array_size_unit as $sizeunit )
{
	$xtpl->assign( 'SIZEUNIT', array(
		"key" => $sizeunit,
		"title" => $sizeunit,
		"chunk_size" => $sizeunit == $GLT->setting['chunk_size_unit'] ? " selected=\"selected\"" : "",
		"max_file_size" => $sizeunit == $GLT->setting['max_file_size_unit'] ? " selected=\"selected\"" : "",
	) );
	$xtpl->parse( 'main.size_unit_1' );
	$xtpl->parse( 'main.size_unit_2' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>