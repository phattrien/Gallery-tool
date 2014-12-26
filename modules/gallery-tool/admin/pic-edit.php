<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Goi js
$GLT->callJqueryPlugin( 'jquery.ui.sortable', 'jquery.tipsy', 'jquery.autosize', 'jquery.ui.autocomplete', 'jquery.ui.datepicker', 'shadowbox' );

$page_title = $GLT->lang('picManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int( 'id', 'get, post', 0 );

// Xu ly
if( $id )
{
	$sql = "SELECT * FROM `" . $GLT->table_prefix . "_pictures` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) != 1 )
	{
		nv_info_die( $GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content') );
	}
	
	$row = $db->sql_fetchrow( $result );
	
	$array_old = $array = array(
		"title" => $row['title'],
		"info1" => $row['info1'],
		"info2" => $row['info2'],
		"info3" => $row['info3'],
		"info4" => $row['info4'],
		"info5" => $row['info5'],
		"link" => $row['link'],
		"description" => $row['description'],
		"albums" => array(),
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $GLT->lang('picEdit');
}
else
{
	nv_info_die( $GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content') );
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$array['info1'] = filter_text_input( 'info1', 'post', '', 1, 255 );
	$array['info2'] = filter_text_input( 'info2', 'post', '', 1, 255 );
	$array['info3'] = filter_text_input( 'info3', 'post', '', 1, 255 );
	$array['info4'] = filter_text_input( 'info4', 'post', '', 1, 255 );
	$array['info5'] = filter_text_input( 'info5', 'post', '', 1, 255 );
	$array['link'] = filter_text_input( 'link', 'post', '', 1, 255 );
	$array['description'] = filter_text_input( 'description', 'post', '', 1, 255 );
	$array['albums'] = $nv_Request->get_typed_array( 'albums', 'post', 'int', array() );
	
	if( empty( $array['title'] ) )
	{
		$error = $GLT->lang('picErrorTitle');
	}
	
	if( empty( $error ) )
	{
		$sql = "UPDATE `" . $GLT->table_prefix ."_pictures` SET 
			`title`=" . $db->dbescape( $array['title'] ) . ",
			`info1`=" . $db->dbescape( $array['info1'] ) . ",
			`info2`=" . $db->dbescape( $array['info2'] ) . ",
			`info3`=" . $db->dbescape( $array['info3'] ) . ",
			`info4`=" . $db->dbescape( $array['info4'] ) . ",
			`info5`=" . $db->dbescape( $array['info5'] ) . ",
			`link`=" . $db->dbescape( $array['link'] ) . ",
			`description`=" . $db->dbescape( $array['description'] ) . ",
			`albums`=" . $db->dbescape( $GLT->buildSQLLids( $array['albums'] ) ) . "
		WHERE `id`=" . $id;
		
		if( $db->sql_query( $sql ) )
		{
			nv_del_moduleCache( $module_name );
			$complete = true;
		}
		else
		{
			$error = $GLT->lang('errorUpdateUnknow');
		}
	}
}

$xtpl = new XTemplate( "pic-edit.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'ID', $id );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );

// Neu la xuat ban thanh cong
if( $complete )
{
	$xtpl->assign( 'MESSAGE', $GLT->lang('albumSaveOk') );

	$xtpl->parse( 'complete' );
	$contents = $xtpl->text( 'complete' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents, false );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Xuat loi
if( ! empty( $error ) )
{
	$xtpl->assign( 'ERROR', $error );
	$xtpl->parse( 'main.error' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents, false );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>