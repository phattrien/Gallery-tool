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

$page_title = $GLT->lang('albumManager');

// Lay va khoi tao cac bien
$error = "";
$complete = false;
$id = $nv_Request->get_int( 'id', 'get, post', 0 );

// Xu ly
if( $id )
{
	$sql = "SELECT * FROM `" . $GLT->table_prefix . "_albums` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) != 1 )
	{
		nv_info_die( $GLT->glang('error_404_title'), $GLT->glang('error_404_title'), $GLT->glang('error_404_content') );
	}
	
	$row = $db->sql_fetchrow( $result );
	
	$array_old = $array = array(
		"title" => $row['title'],
		"description" => $row['description'],
		"bigW" => $row['bigW'],
		"bigH" => $row['bigH'],
		"smallW" => $row['smallW'],
		"smallH" => $row['smallH'],
		"pictures" => array_values( $GLT->string2array( $row['dataPics'] ) ),
	);
	
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;id=" . $id;
	$table_caption = $GLT->lang('albumEdit');
}
else
{
	$form_action = NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;
	$table_caption = $GLT->lang('albumAdd');
	
	$array = array(
		"title" => '',
		"description" => '',
		"bigW" => 490,
		"bigH" => 300,
		"smallW" => 200,
		"smallH" => 160,
		"pictures" => array(),
	);
}

if( $nv_Request->isset_request( 'submit', 'post' ) )
{
	$array['title'] = filter_text_input( 'title', 'post', '', 1, 255 );
	$array['description'] = filter_text_input( 'description', 'post', '', 1, 255 );
	$array['bigW'] = abs( $nv_Request->get_int( 'bigW', 'post', 0 ) );
	$array['bigH'] = abs( $nv_Request->get_int( 'bigH', 'post', 0  ));
	$array['smallW'] = abs( $nv_Request->get_int( 'smallW', 'post', 0 ) );
	$array['smallH'] = abs( $nv_Request->get_int( 'smallH', 'post', 0 ) );
	$array['pictures'] = $GLT->string2array( filter_text_input( 'pictures', 'post', '' ) );
	
	if( empty( $array['title'] ) )
	{
		$error = $GLT->lang('albumErrTitle');
	}
	elseif( ! $array['bigW'] or ! $array['bigH'] or ! $array['smallW'] or ! $array['smallH'] )
	{
		$error = $GLT->lang('albumErrorPicSize');
	}
	
	// Kiem tra lien ket tinh ton tai va tao lien ket tinh khac neu la luu ban nhap
	$sql = "SELECT * FROM `" . $GLT->table_prefix . "_albums` WHERE `title`=" . $db->dbescape( $array['title'] ) . ( ! empty( $id ) ? " AND `id`!=" . $id : "" );
	$result = $db->sql_query( $sql );
	
	if( $db->sql_numrows( $result ) )
	{
		$error = $GLT->lang('albumErrExists');
	}
	
	if( empty( $error ) )
	{
		if( empty( $id ) )
		{
			$sql = "INSERT INTO `" . $GLT->table_prefix . "_albums` VALUES(
				NULL,
				" . $db->dbescape( $array['title'] ) . ",
				" . $db->dbescape( $array['description'] ) . ",
				" . sizeof( $array['pictures'] ) . ",
				" . $db->dbescape( $GLT->buildSQLLids( $array['pictures'] ) ) . ", 
				" . $array['bigW'] . ", 
				" . $array['bigH'] . ", 
				" . $array['smallW'] . ", 
				" . $array['smallH'] . "
			)";
			
			$id = $db->sql_query_insert_id( $sql );
			
			if( $id )
			{
				if( ! empty( $array['pictures'] ) )
				{
					// Tang thoi gian thuc hien va bo nho
					if( $sys_info['allowed_set_time_limit'] )
					{
						set_time_limit( 0 );
					}
					
					if( $sys_info['ini_set_support'] )
					{
						$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
						if( $memoryLimitMB < 1024 )
						{
							ini_set( "memory_limit", "1024M" );
						}
					}
					
					$GLT->fixPics( $array['pictures'] );
				}
				
				nv_del_moduleCache( $module_name );
				$complete = true;
			}
			else
			{
				$error = $GLT->lang('errorSaveUnknow');
			}
		}
		else
		{
			$sql = "UPDATE `" . $GLT->table_prefix ."_albums` SET 
				`title`=" . $db->dbescape( $array['title'] ) . ",
				`description`=" . $db->dbescape( $array['description'] ) . ",
				`numPics`=" . sizeof( $array['pictures'] ) . ",
				`dataPics`=" . $db->dbescape( $GLT->buildSQLLids( $array['pictures'] ) ) . ", 
				`bigW`=" . $array['bigW'] . ", 
				`bigH`=" . $array['bigH'] . ", 
				`smallW`=" . $array['smallW'] . ", 
				`smallH`=" . $array['smallH'] . "
			WHERE `id`=" . $id;
			
			if( $db->sql_query( $sql ) )
			{
				// Tang thoi gian thuc hien va bo nho
				if( $sys_info['allowed_set_time_limit'] )
				{
					set_time_limit( 0 );
				}
				
				if( $sys_info['ini_set_support'] )
				{
					$memoryLimitMB = ( integer )ini_get( 'memory_limit' );
					if( $memoryLimitMB < 1024 )
					{
						ini_set( "memory_limit", "1024M" );
					}
				}
				
				$GLT->fixPics( array_merge_recursive( $array['pictures'], $array_old['pictures'] ) );
				
				nv_del_moduleCache( $module_name );
				$complete = true;
			}
			else
			{
				$error = $GLT->lang('errorUpdateUnknow');
			}
		}
	}
}

// Chuyen so thanh chuoi
if( empty( $array['bigW'] ) ) $array['bigW'] = "";
if( empty( $array['bigH'] ) ) $array['bigH'] = "";
if( empty( $array['smallW'] ) ) $array['smallW'] = "";
if( empty( $array['smallH'] ) ) $array['smallH'] = "";

// Lay danh sach anh album
if( ! empty( $array['pictures'] ) )
{
	$pics = $GLT->getPicsByID( $array['pictures'], true );
	
	$array['pictures'] = array();
	foreach( $pics as $pic )
	{
		$array['pictures'][$pic['id']] = array(
			'id' => $pic['id'],
			'title' => $pic['title'],
			'thumb' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/thumb/sys/' . $pic['thumb'],
			'file' => NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/images/' . $pic['file'],
		);
	}
}
else
{
	$array['pictures'] = array();
}

$xtpl = new XTemplate( "album-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->assign( 'ID', $id );
$xtpl->assign( 'DATA', $array );
$xtpl->assign( 'TABLE_CAPTION', $table_caption );
$xtpl->assign( 'FORM_ACTION', $form_action );

$xtpl->assign( 'PICTURES', implode( ",", array_keys( $array['pictures'] ) ) );

// Xuat cac anh
if( ! empty( $array['pictures'] ) )
{
	foreach( $array['pictures'] as $picture )
	{
		$xtpl->assign( 'PICTURE', $picture );
		$xtpl->parse( 'main.picture' );
	}
}

// Neu la xuat ban thanh cong
if( $complete )
{
	$my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=album-list\" />";		
	$xtpl->assign( 'MESSAGE', $GLT->lang('albumSaveOk') );

	$xtpl->parse( 'complete' );
	$contents = $xtpl->text( 'complete' );

	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
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
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>