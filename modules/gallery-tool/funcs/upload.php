<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Khong cho phep cache
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
header( "Cache-Control: no-store, no-cache, must-revalidate" );
header( "Cache-Control: post-check=0, pre-check=0", false );
header( "Pragma: no-cache" );

// Cross domain
// header("Access-Control-Allow-Origin: *");

// Kiem tra phien lam viec
$checksess = filter_text_input( 'checksess', 'get', '' );
if( $checksess != md5( $nv_Request->session_id . $global_config['sitekey'] ) )
{
	gltJsonResponse( array( 'code' => 200, 'message' => $GLT->lang( 'uploadErrorSess' ) ) );
}

// Chi admin moi co quyen upload
if( ! defined( 'NV_IS_MODADMIN' ) )
{
	gltJsonResponse( array( 'code' => 200, 'message' => $GLT->lang( 'uploadErrorPermission' ) ) );
}

// Tang thoi luong phien lam viec
if( $sys_info['allowed_set_time_limit'] )
{
	set_time_limit( 5 * 3600 );
}

// Get request value
$fileName = filter_text_input( 'name', 'post', '' );
$fileExt = nv_getextension( $fileName );
$fileName = change_alias( substr( $fileName, 0, -( strlen( $fileExt ) + 1 ) ) ) . '.' . $fileExt;

$chunk = $nv_Request->get_int( 'chunk', 'post', 0 );
$chunks = $nv_Request->get_int( 'chunks', 'post', 0 );

if( empty( $fileName ) or empty( $fileExt ) )
{
	gltJsonResponse( array( 'code' => 200, 'message' => $GLT->lang( 'uploadErrorFile' ) ) );
}

// Kiem tra file ton tai
$fileName2 = $fileName;
$i = 1;
while ( file_exists( NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $fileName2 ) )
{
    $fileName2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1-' . $i . '\2', $fileName );
    ++$i;
}
$fileName = $fileName2;
$filePath = NV_ROOTDIR . '/' . NV_TEMP_DIR . '/' . $fileName;

// Open temp file
if( ! $out = @fopen( "{$filePath}.part", $chunks ? "ab" : "wb" ) )
{
	gltJsonResponse( array( 'code' => 102, 'message' => "Failed to open output stream." ) );
}

if( ! empty( $_FILES ) )
{
	if( $_FILES["file"]["error"] || ! is_uploaded_file( $_FILES["file"]["tmp_name"] ) )
	{
		gltJsonResponse( array( 'code' => 103, 'message' => "Failed to move uploaded file." ) );
	}

	// Read binary input stream and append it to temp file
	if( ! $in = @fopen( $_FILES["file"]["tmp_name"], "rb" ) )
	{
		gltJsonResponse( array( 'code' => 101, 'message' => "Failed to open input stream." ) );
	}
}
else
{
	if( ! $in = @fopen( "php://input", "rb" ) )
	{
		gltJsonResponse( array( 'code' => 101, 'message' => "Failed to open input stream." ) );
	}
}

while( $buff = fread( $in, 4096 ) )
{
	fwrite( $out, $buff );
}

@fclose( $out );
@fclose( $in );

// Check if file has been uploaded
if( ! $chunks || $chunk == $chunks - 1 )
{
	// Strip the temp .part suffix off
	$check = @rename( "{$filePath}.part", $filePath );
	
	if( empty( $check ) )
	{
		gltJsonResponse( array( 'code' => 200, 'message' => $GLT->lang('uploadErrorRenameFile') ) );
	}
}

gltJsonResponse( array(), array( 'name' => $filePath, 'basename' => $fileName, 'ext' => $fileExt ) );

?>