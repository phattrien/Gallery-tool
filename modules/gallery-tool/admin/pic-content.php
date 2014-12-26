<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_BLOG_ADMIN' ) ) die( 'Stop!!!' );

// Xu ly thu muc uploads
$currentpath = $GLT->uploadDirInit( $module_name . '/images/' . date( 'Y_m' ) );

// Xuat giao dien
$xtpl = new XTemplate( "pic-content.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

$step = $nv_Request->get_int( 'step', 'get,post', 1 );

// Buoc 4 - Hoan thanh
if( $step == 4 )
{
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	
	$my_head = "<meta http-equiv=\"refresh\" content=\"3;url=" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "\" />";
	
	$xtpl->parse( 'step4' );
	$contents = $xtpl->text( 'step4' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Buoc 3
if( $step == 3 )
{
	$GLT->callJqueryPlugin( 'jquery.ui.core', 'jquery.ui.autocomplete' );
	
	$ids = $GLT->string2array( filter_text_input( 'ids', 'get', '' ) );
	
	if( empty( $ids ) )
	{
		header( "location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}
	
	$error = '';
	$array = array(
		'id' => $nv_Request->get_int( 'albumId', 'post', 0 ),
		'title' => filter_text_input( 'albumTitle', 'post', '', 1, 255 ),
	);
	
	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		if( empty( $array['id'] ) or empty( $array['title'] ) )
		{
			$error = $GLT->lang('picULErrorChooseEmpty');
		}
		else
		{
			$sql = "SELECT * FROM `" . $GLT->table_prefix . "_albums` WHERE `title`=" . $db->dbescape( $array['title'] ) . " AND `id`=" . $array['id'];
			$result = $db->sql_query( $sql );
			
			if( $db->sql_numrows( $result ) != 1 )
			{
				$error = sprintf( $GLT->lang('picULErrorChooseNoExists'), $array['title'] );
			}
			
			$album = $db->sql_fetch_assoc( $result );
		}
		
		if( empty( $error ) )
		{
			$sql = "UPDATE `" . $GLT->table_prefix . "_pictures` SET `albums`=" . $db->dbescape( $GLT->buildSQLLids( array( $array['id'] ) ) ) . " WHERE `id` IN(" . implode( ',', $ids ) . ")";
			$db->sql_query( $sql );
			
			// Lay thong tin
			$album['dataPics'] .= ',' . implode( ',', $ids );
			$album['dataPics'] = $GLT->string2array( $album['dataPics'] );
			
			$sql = "UPDATE `" . $GLT->table_prefix . "_albums` SET `dataPics`=" . $db->dbescape( $GLT->buildSQLLids( $album['dataPics'] ) ) . ", `numPics`=" . sizeof( $album['dataPics'] ) . " WHERE `id`=" . $album['id'];
			$db->sql_query( $sql );
			
			$GLT->fixPics( $ids );
			
			nv_del_moduleCache( $module_name );
			
			header( "location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . '&step=4' );
			die();
		}
	}
	
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=3&ids=" . implode( ',', $ids ) );
	$xtpl->assign( 'NEXT_STEP', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=4" );
	$xtpl->assign( 'DATA', $array );
	
	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'step3.error' );
	}
	
	$xtpl->parse( 'step3' );
	$contents = $xtpl->text( 'step3' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Buoc 2 nhap thong tin
if( $step == 2 )
{
	// Goi ra js
	$GLT->callJqueryPlugin( 'shadowbox' );

	$error = '';
	$array = array();
	
	$totalFile = $nv_Request->get_int( 'uploader_count', 'post', 0 );
	if( $totalFile < 1 )
	{
		header( "location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op );
		die();
	}

	$mime = nv_parse_ini_file( NV_ROOTDIR . '/includes/ini/mime.ini', true );

	for( $i = 0; $i < $totalFile; $i ++ )
	{
		$array[$i] = array(
			'stt' => $i,
			'title' => filter_text_input( 'title_' . $i, 'post', '' ),
			'info1' => filter_text_input( 'info1_' . $i, 'post', '' ),
			'info2' => filter_text_input( 'info2_' . $i, 'post', '' ),
			'info3' => filter_text_input( 'info3_' . $i, 'post', '' ),
			'info4' => filter_text_input( 'info4_' . $i, 'post', '' ),
			'info5' => filter_text_input( 'info5_' . $i, 'post', '' ),
			'link' => filter_text_input( 'link_' . $i, 'post', '' ),
			'description' => filter_text_input( 'description_' . $i, 'post', '' ),
			'file' => filter_text_input( 'uploader_' . $i . '_name', 'post', '' ),
			'status' => filter_text_input( 'uploader_' . $i . '_status', 'post', '' ),
			'thumb' => filter_text_input( 'thumb_' . $i, 'post', '' ),
			'width' => 0,
			'height' => 0,
			'size' => 0,
			'format' => '',
		);
		
		if( empty( $array[$i]['title'] ) )
		{
			$array[$i]['title'] = $GLT->lang('picDefaultTitle') . ' ' . ( $i + 1 );
		}
		
		// Kiem tra file ton tai
		if( $array[$i]['status'] != 'done' or empty( $array[$i]['file'] ) or ! file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file'] ) )
		{
			$error .= $GLT->lang('picULErrorExists') . ' ' . $array[$i]['file'];
			unset( $array[$i] );
		}

		// Kiem tra anh hop le
		if( isset( $array[$i] ) )
		{
			$image_info = nv_is_image( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/'  . $array[$i]['file'] );

			if( empty( $image_info ) or ! isset( $mime['images'][$image_info['ext']] ) )
			{
				$error .= $GLT->lang('picULErrorMime') . ' ' . $array[$i]['file'];
				@nv_deletefile( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $array[$i]['file'] );
				unset( $array[$i] );
			}
			else
			{
				$array[$i]['width'] = $image_info['width'];
				$array[$i]['height'] = $image_info['height'];
				$array[$i]['format'] = $image_info['mime'];
				$array[$i]['size'] = filesize( $image_info['src'] );
			}
		}
		
		// Tao anh thumb
		if( isset( $array[$i] ) and empty( $array[$i]['thumb'] ) )
		{
			$array[$i]['thumb'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $GLT->creatThumb( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/'  . $array[$i]['file'], NV_ROOTDIR . '/' . NV_TEMP_DIR, 90, 72 );
		}
	}

	if( $nv_Request->isset_request( 'submit', 'post' ) )
	{
		foreach( $array as $row )
		{
			if( empty( $row['title'] ) )
			{
				$error .= $GLT->lang('picULErrorTitle') . ' ' . $row['file'] . '. ';
			}
		}
		
		if( empty( $error ) )
		{
			$added_ids = array();
			
			foreach( $array as $row )
			{
				// Copy file anh goc
				$fileName = $row['file'];
				$fileName2 = $fileName;
				$i = 1;
				while ( file_exists( NV_ROOTDIR . '/' . $currentpath . '/' . $fileName2 ) )
				{
				    $fileName2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $fileName );
				    ++$i;
				}
				$fileName = $fileName2;
				$filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $row['file'];
				$newFilePath = NV_ROOTDIR . '/' . $currentpath . '/' . $fileName;
				
				$rename = nv_copyfile( $filePath, $newFilePath );
				
				if( ! $rename )
				{
					$error .= $GLT->lang('picULErrorCopy') . basename( $filePath ) . ' ';
					unset( $array[$row['stt']] );
				}
				else
				{
					// Xoa anh tam
					@nv_deletefile( $filePath );
					$row['file'] = substr( $newFilePath, strlen( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/images/' ) );
					
					// Copy file thumb
					$thumbName = $fileName = substr( $row['thumb'], strlen( NV_BASE_SITEURL . NV_TEMP_DIR . '/' ) );
					$fileName2 = $fileName;
					$i = 1;
					while ( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' . $fileName2 ) )
					{
					    $fileName2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $fileName );
					    ++$i;
					}
					$fileName = $fileName2;
					$filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $thumbName;
					$newFilePath = NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' . $fileName;
				
					$rename = nv_copyfile( $filePath, $newFilePath );
					
					if( ! $rename )
					{
						$error .= $GLT->lang('picULErrorCopy') . basename( $filePath ) . ' ';
						unset( $array[$row['stt']] );
					}
					else
					{
						// Xoa anh tam
						@nv_deletefile( $filePath );
						$row['thumb'] = substr( $newFilePath, strlen( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/thumb/sys/' ) );
						
						$sql = "INSERT INTO `" . $GLT->table_prefix . "_pictures` VALUES(
							NULL, 
							" . $db->dbescape_string( $row['title'] ) . ",
							" . $db->dbescape_string( $row['info1'] ) . ",
							" . $db->dbescape_string( $row['info2'] ) . ",
							" . $db->dbescape_string( $row['info3'] ) . ",
							" . $db->dbescape_string( $row['info4'] ) . ",
							" . $db->dbescape_string( $row['info5'] ) . ",
							" . $db->dbescape_string( $row['link'] ) . ",
							" . $db->dbescape_string( $row['description'] ) . ",
							" . $db->dbescape_string( $row['file'] ) . ",
							" . $db->dbescape_string( $row['thumb'] ) . ",
							" . $row['width'] . ",
							" . $row['height'] . ",
							" . $row['size'] . ",
							" . $db->dbescape_string( $row['format'] ) . ",
							''
						)";
						
						$added_ids[] = $db->sql_query_insert_id( $sql );
					}
				}
			}
			
			if( empty( $error ) )
			{
				header( "location:" . NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&step=3&ids=" . implode( ',', $added_ids ) );
				die();
			}
		}
	}

	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=2" );
	$xtpl->assign( 'TOTALFILE', $totalFile );
	
	foreach( $array as $row )
	{
		$row['class'] = $row['stt'] % 2 == 0 ? '' : ' class="second"';
		$row['filePath'] = NV_BASE_SITEURL . NV_TEMP_DIR . '/' . $row['file'];
		
		if( empty( $row['thumb'] ) )
		{
			$row['thumb'] = NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . '/d-thumb.png';
		}
		
		$xtpl->assign( 'ROW', $row );
		$xtpl->parse( 'step2.loop' );
	}
	
	if( ! empty( $error ) )
	{
		$xtpl->assign( 'ERROR', $error );
		$xtpl->parse( 'step2.error' );
	}
	
	$xtpl->parse( 'step2' );
	$contents = $xtpl->text( 'step2' );
	
	include ( NV_ROOTDIR . "/includes/header.php" );
	echo nv_admin_theme( $contents );
	include ( NV_ROOTDIR . "/includes/footer.php" );
	die();
}

// Goi ra js
$GLT->callJqueryPlugin( 'jquery.plupload.queue' );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;step=2" );
$xtpl->assign( 'SETTING', $GLT->setting );

// Frameworks dir
$xtpl->assign( 'FRAMEWORKS_DIR', NV_BASE_SITEURL . 'modules/' . $module_file . '/frameworks/plupload' );
$xtpl->assign( 'UPLOAD_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=upload&checksess=' . md5( $nv_Request->session_id . $global_config['sitekey'] ) );

$xtpl->parse( 'step1' );
$contents = $xtpl->text( 'step1' );

include ( NV_ROOTDIR . "/includes/header.php" );
echo nv_admin_theme( $contents );
include ( NV_ROOTDIR . "/includes/footer.php" );

?>