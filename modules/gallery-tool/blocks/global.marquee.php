<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

if( ! nv_function_exists( 'nv_block_glt_marquee' ) )
{
	function nv_block_config_glt_marquee( $module, $data_block, $lang_block )
	{
		global $db, $site_mods, $db_config, $lang_global, $global_config;
		
		$module_name = $module;
		$module_file = $site_mods[$module]['module_file'];
		$module_data = $site_mods[$module]['module_data'];
		
		require( NV_ROOTDIR . "/modules/" . $module_file . "/class.php" );
		
		$GLT = new nv_mod_gallery_tool( $module_data, $module_name, $module_file, NV_LANG_DATA, true );

		if( file_exists( NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css" ) )
		{
			$css_file = NV_BASE_SITEURL . "themes/" . $global_config['admin_theme'] . "/css/" . $module_file . ".css";
			$tpl_path = NV_ROOTDIR . "/themes/" . $global_config['admin_theme'] . "/modules/" . $module_file;
		}
		elseif( file_exists( NV_ROOTDIR . "/themes/admin_default/css/" . $module_file . ".css" ) )
		{
			$css_file = NV_BASE_SITEURL . "themes/admin_default/css/" . $module_file . ".css";
			$tpl_path = NV_ROOTDIR . "/themes/admin_default/modules/" . $module_file;
		}

		$xtpl = new XTemplate( "block.marquee.tpl", $tpl_path );
		$xtpl->assign( 'LANG', $GLT->language );
		$xtpl->assign( 'GLANG', $GLT->glanguage );
		$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
		$xtpl->assign( 'MODULE_NAME', $module_name );
		$xtpl->assign( 'CSS_FILE', $css_file );
		$xtpl->assign( 'JQUERY_PLUGIN', $GLT->getJqueryPlugin( 'root_admin' ) );
		
		// Checked
		$data_block['js'] = ! empty( $data_block['js'] ) ? ' checked="checked"' : '';
		$data_block['duplicated'] = ! empty( $data_block['duplicated'] ) ? ' checked="checked"' : '';
		$data_block['pauseOnHover'] = ! empty( $data_block['pauseOnHover'] ) ? ' checked="checked"' : '';
		
		// Lay thong tin album
		$album = $GLT->getAlbumsByID( $data_block['albumId'] );
		$data_block['albumTitle'] = empty( $album['title'] ) ? '' : $album['title'];
		unset( $album );
		
		$xtpl->assign( 'DATA', $data_block );
		
		$arrayDirection = array(
			'left' => $GLT->lang('blkMarqueeDirectionLeft'),
			'right' => $GLT->lang('blkMarqueeDirectionRight'),
			'up' => $GLT->lang('blkMarqueeDirectionUp'),
			'down' => $GLT->lang('blkMarqueeDirectionDown'),
		);
		foreach( $arrayDirection as $direction => $directionValue )
		{
			$xtpl->assign( 'DIRECTION', array( "key" => $direction, 'title' => $directionValue, "selected" => $direction == $data_block['direction'] ? " selected=\"selected\"" : "" ) );
			$xtpl->parse( 'main.direction' );
		}
		
		$xtpl->parse( 'main' );
		return $xtpl->text( 'main' );
	}

	function nv_block_config_glt_marquee_submit( $module, $lang_block )
	{
		global $nv_Request;
		$return = array();
		$return['error'] = array();
		$return['config'] = array();
		
		$return['config']['albumId'] = $nv_Request->get_int( 'config_albumId', 'post', 0 );
		$return['config']['js'] = $nv_Request->get_int( 'config_js', 'post', 0 );
		$return['config']['delayBeforeStart'] = $nv_Request->get_int( 'config_delayBeforeStart', 'post', 0 );
		$return['config']['direction'] = filter_text_input( 'config_direction', 'post', '', 1, 255 );
		$return['config']['duplicated'] = $nv_Request->get_int( 'config_duplicated', 'post', 0 );
		$return['config']['gap'] = $nv_Request->get_int( 'config_gap', 'post', 0 );
		$return['config']['duration'] = $nv_Request->get_int( 'config_duration', 'post', 0 );
		$return['config']['pauseOnHover'] = $nv_Request->get_int( 'config_pauseOnHover', 'post', 0 );
		$return['config']['pauseOnCycle'] = $nv_Request->get_int( 'config_pauseOnCycle', 'post', 0 );
		
		return $return;
	}

	function nv_block_glt_marquee( $block_config )
	{
		global $site_mods, $GLT, $module_info, $db, $module_name, $my_head;
		
		$module = $block_config['module'];
		$module_data = $site_mods[$module]['module_data'];
		$module_file = $site_mods[$module]['module_file'];

		// Goi class neu chua co
		if( empty( $GLT ) )
		{
			require( NV_ROOTDIR . "/modules/" . $module_file . "/class.php" );
			
			$GLT = new nv_mod_gallery_tool( $module_data, $module, $module_file, NV_LANG_DATA, true );
		}
		
		// Lay full thong tin cua album
		$array = $GLT->getFullAlbum( $block_config['albumId'] );
		
		if( ! empty( $array ) )
		{
			// Xac dinh giao dien chua block
			if( file_exists( NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file . "/block.marquee.tpl" ) )
			{
				$block_theme = $module_info['template'];
			}
			else
			{
				$block_theme = "default";
			}
			
			// Goi CSS module neu nhu khong phai module gallery tool
			if( $module != $module_name and ! defined( "NV_GLTOOL_CSS" ) )
			{
				define( 'NV_GLTOOL_CSS', true );
				
				if( is_file( NV_ROOTDIR . "/themes/" . $block_theme . "/css/" . $module_file . ".css" ) )
				{
					$my_head .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . NV_BASE_SITEURL . "themes/" . $block_theme . "/css/" . $module_file . ".css\" />" . NV_EOL;
				}
			}
			
			// Goi jquery.marquee
			if( $block_config['js'] )
			{
				$GLT->callJqueryPlugin( 'jquery.marquee' );
			}
			
			$xtpl = new XTemplate( 'block.marquee.tpl', NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $module_file );
			$xtpl->assign( 'LANG', $GLT->language );
			$xtpl->assign( 'GLANG', $GLT->glanguage );
			$xtpl->assign( 'TEMPLATE', $block_theme );
			
			$block_config['duplicated'] = $block_config['duplicated'] ? 'true' : 'false';
			$block_config['pauseOnHover'] = $block_config['pauseOnHover'] ? 'true' : 'false';
			
			$xtpl->assign( 'CONFIG', $block_config );
			$xtpl->assign( 'DATA', $array );
			
			// Xuat anh
			foreach( $array['pics'] as $pic )
			{
				$pic['link'] = $pic['link'] ? $pic['link'] : 'javascript:void(0);';
				
				$xtpl->assign( 'PIC', $pic );
				$xtpl->parse( 'main.loop' );
			}
					
			$xtpl->parse( 'main' );
			return $xtpl->text( 'main' );
		}
	}
}

if( defined( 'NV_SYSTEM' ) )
{
	$content = nv_block_glt_marquee( $block_config );
}

?>