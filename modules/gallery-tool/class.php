<?php

/**
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

class nv_mod_gallery_tool
{
	private $lang_data = '';
	private $mod_data = '';
	private $mod_name = '';
	private $mod_file = '';
	private $db = null;
	
	public $db_prefix = '';
	public $db_prefix_lang = "";
	public $table_prefix = "";
	
	public $cache_prefix = "";

	private $base_site_url = null;
	private $root_dir = null;
	private $upload_dir = null;
	private $currenttime = null;
	
	public $language = array();
	public $glanguage = array();
	
	private $js_data = array();
	
	public $setting = null;
	
	public function __construct( $d = "", $n = "", $f = "", $lang = "", $get_lang = false )
	{
		global $module_data, $module_name, $module_file, $db_config, $db, $lang_global;
		
		// Ten CSDL
		if( ! empty( $d ) ) $this->mod_data = $d;
		else $this->mod_data = $module_data;
		
		// Ten module
		if( ! empty( $n ) ) $this->mod_name = $n;
		else $this->mod_name = $module_name;
		
		// Ten thu muc
		if( ! empty( $f ) ) $this->mod_file = $f;
		else $this->mod_file = $module_file;
		
		// Ngon ngu
		if( ! empty( $lang ) ) $this->lang_data = $lang;
		else $this->lang_data = NV_LANG_DATA;
		
		$this->db_prefix = $db_config['prefix'];
		$this->db_prefix_lang = $this->db_prefix . '_' . $this->lang_data;
		$this->table_prefix = $this->db_prefix_lang . '_' . $this->mod_data;
		$this->db = $db;
		
		$this->setting = $this->get_setting();
		
		$this->cache_prefix = NV_CACHE_PREFIX;
		$this->base_site_url = NV_BASE_SITEURL;
		$this->root_dir = NV_ROOTDIR;
		$this->upload_dir = NV_UPLOADS_DIR;
		$this->currenttime = NV_CURRENTTIME;
		
		// Ngon ngu
		if( $get_lang === false )
		{
			global $lang_module;
		}
		else
		{
			$file_lang_path = $this->root_dir . "/modules/" . $this->mod_file . "/language/";
			$file_lang_name = defined( 'NV_ADMIN' ) ? "admin_" . $this->lang_data . ".php" : $this->lang_data . ".php";
			if( is_file( $file_lang_path . $file_lang_name ) )
			{
				include( $file_lang_path . $file_lang_name );
			}
			else
			{
				$lang_module = array();
			}
		}
		
		$this->language = $lang_module;
		$this->glanguage = $lang_global;
		
		$this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.core'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.core'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.core.min.js\"></script>\n";
		
		$this->js_data['jquery.ui.sortable'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.sortable.min.js\"></script>\n";
		$this->js_data['jquery.ui.autocomplete'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.ui.autocomplete.js\"></script>\n";
		
		$this->js_data['jquery.tipsy'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.tipsy.js\"></script>\n";
		$this->js_data['jquery.tipsy'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/tipsy.css\" rel=\"stylesheet\" />\n";
		
		$this->js_data['jquery.autosize'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/jquery.autosize.js\"></script>\n";
		
		$this->js_data['shadowbox'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/shadowbox/shadowbox.js\"></script>\n";
		$this->js_data['shadowbox'][] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $this->base_site_url . "js/shadowbox/shadowbox.css\" />\n";
		$this->js_data['shadowbox'][] = "<script type=\"text/javascript\">Shadowbox.init();</script>\n";
		
		$this->js_data['jquery.ui.datepicker'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.ui.datepicker'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
		$this->js_data['jquery.ui.datepicker'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";
		
		$this->js_data['jquery.plupload.queue'][] = "<link type=\"text/css\" href=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/plupload/jquery.plupload.queue/css/jquery.plupload.queue.css\" rel=\"stylesheet\" />\n";
		$this->js_data['jquery.plupload.queue'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/plupload/plupload.full.min.js\"></script>\n";
		$this->js_data['jquery.plupload.queue'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/plupload/jquery.plupload.queue/jquery.plupload.queue.js\"></script>\n";
		$this->js_data['jquery.plupload.queue'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/plupload/i18n/" . $this->lang_data . ".js\"></script>\n";
		
		$this->js_data['jquery.marquee'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/frameworks/jquery.marquee/jquery.marquee.min.js\"></script>\n";

		$this->js_data['root_admin'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/admin.js\"></script>\n";
		$this->js_data['root_site'][] = "<script type=\"text/javascript\" src=\"" . $this->base_site_url . "modules/" . $this->mod_file . "/js/user.js\"></script>\n";
	}
	
	private function get_setting()
	{
		$sql = "SELECT `config_name`, `config_value` FROM `" . $this->table_prefix . "_config`";
		$result = $this->db_cache( $sql );
		
		$array = array();
		foreach ( $result as $values )
		{
			$array[$values['config_name']] = $values['config_value'];
		}

		return $array;
	}
	
	private function handle_error( $messgae = '' )
	{
		trigger_error( "Error! " . ( $messgae ? ( string ) $messgae : "You are not allowed to access this feature now" ) . "!", 256 );
	}
	
	private function check_admin()
	{
		if( ! defined( 'NV_IS_MODADMIN' ) ) $this->handle_error();
	}
	
	private function nl2br( $string )
	{
		return nv_nl2br( $string );
	}
	
	private function db_cache( $sql, $id = '', $module_name = '' )
	{
		return nv_db_cache( $sql, $id, $module_name );
	}
	
	private function del_cache( $module_name )
	{
		return nv_del_moduleCache( $module_name );
	}
	
	private function change_alias( $alias )
	{
		return change_alias( $alias );
	}
	
	private function checkJqueryPlugin( $numargs, $arg_list )
	{
		$return = array();
		for( $i = 0; $i < $numargs; $i ++ )
		{
			if( isset( $this->js_data[$arg_list[$i]] ) )
			{
				if( $arg_list[$i] == 'jquery.ui.sortable' ) $return['jquery.ui.core'] = implode( "", $this->js_data['jquery.ui.core'] );
				if( $arg_list[$i] == 'jquery.ui.datepicker' ) $return['jquery.ui.core'] = implode( "", $this->js_data['jquery.ui.core'] );
				$return[$arg_list[$i]] =  implode( "", $this->js_data[$arg_list[$i]] );
			}
		}
		return $return;
	}
	
	private function sortArrayFromArrayKeys( $keys, $array )
	{
		$return = array();
		
		foreach( $keys as $key )
		{
			if( isset( $array[$key] ) )
			{
				$return[$key] = $array[$key];
			}
		}
		return $return;
	}
	
	private function IdHandle( $stroarr, $defis = "," )
	{
		$return = array();
		
		if( is_array( $stroarr ) )
		{
			$return = array_filter( array_unique( array_map( "intval", $stroarr ) ) );
		}
		elseif( strpos( $stroarr, $defis ) !== false )
		{
			$return = array_map( "intval", $this->string2array( $stroarr, $defis ) );
		}
		else
		{
			$return = array( intval( $stroarr ) );
		}
		
		return $return;
	}
	
	private function getCache( $cacheFile )
	{
		return nv_get_cache( $cacheFile );
	}
	
	private function setCache( $cacheFile, $cache )
	{
		return nv_set_cache( $cacheFile, $cache );
	}
	
	public function callJqueryPlugin()
	{
		global $my_head;
		
		$return = $this->checkJqueryPlugin( func_num_args(), func_get_args() );
		
		if( ! empty( $return ) )
		{
			if( empty( $my_head ) )
			{
				$my_head = implode( "", $return );
			}
			else
			{
				$my_head .= implode( "", $return );
			}
		}
	}
	
	public function getJqueryPlugin()
	{
		$return = $this->checkJqueryPlugin( func_num_args(), func_get_args() );
		return implode( "", $return );
	}
	
	public function lang( $key )
	{
		return isset( $this->language[$key] ) ? $this->language[$key] : $key;
	}
	
	public function glang( $key )
	{
		return isset( $this->glanguage[$key] ) ? $this->glanguage[$key] : $key;
	}
	
	public function string2array( $str, $defis = ",", $unique = false, $empty = false )
	{
		if( empty( $str ) ) return array();
		
		$str = array_map( "trim", explode( ( string ) $defis, ( string ) $str ) );
		
		if( ! $unique )
		{
			$str = array_unique( $str );
		}
		
		if( ! $empty )
		{
			$str = array_filter( $str );
		}
		
		return $str;
	}
	
	public function creatThumb( $file, $dir, $width, $height = 0 )
	{
		require_once( $this->root_dir . '/includes/class/image.class.php' );
		
		$image = new image( $file, NV_MAX_WIDTH, NV_MAX_HEIGHT );
		
		if( empty( $height ) )
		{
			$image->resizeXY( $width, NV_MAX_HEIGHT );
		}
		else
		{
			if( ( $width * $image->fileinfo['height'] / $image->fileinfo['width'] ) > $height )
			{
				$image->resizeXY( $width, NV_MAX_HEIGHT );
			}
			else
			{
				$image->resizeXY( NV_MAX_WIDTH, $height );
			}
			
			$image->cropFromCenter( $width, $height );
		}
		
		// Kiem tra anh ton tai
		$fileName = $width . 'x' . $height . '-' . basename( $file );
		$fileName2 = $fileName;
		$i = 1;
		while( file_exists( $dir . '/' . $fileName2 ) )
		{
		    $fileName2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1-' . $i . '\2', $fileName );
		    ++ $i;
		}
		$fileName = $fileName2;
		
		// Luu anh
		$image->save( $dir, $fileName );
		$image->close();
		
		return substr( $image->create_Image_info['src'], strlen( $dir . '/' ) );
	}
	
	public function buildSQLLids( $arr )
	{
		if( empty( $arr ) )
		{
			return '0,0,0';
		}
		return '0,' . implode( ',', $arr ) . ',0';
	}
	
	public function getPicsByID( $id, $sort = false )
	{
		$pics = array();
		
		if( is_array( $id ) )
		{
			$result = $this->db->sql_query( " SELECT * FROM `" . $this->table_prefix . "_pictures` WHERE `id` IN(" . implode( ",", $id ) . ")" );
			
			while( $row = $this->db->sql_fetch_assoc( $result ) )
			{
				$pics[$row['id']] = $row;
			}
			
			if( $sort === true ) $pics = $this->sortArrayFromArrayKeys( $id, $pics );
		}
		else
		{
			$result = $this->db->sql_query( "SELECT * FROM `" . $this->table_prefix . "_pictures` WHERE `id`=" . $id );
			$pics = $this->db->sql_fetch_assoc( $result );
		}
		
		return $pics;
	}
	
	public function getAlbumsByID( $id, $sort = false )
	{
		$albums = array();
		
		if( is_array( $id ) )
		{
			$result = $this->db->sql_query( " SELECT * FROM `" . $this->table_prefix . "_albums` WHERE `id` IN(" . implode( ",", $id ) . ")" );
			
			while( $row = $this->db->sql_fetch_assoc( $result ) )
			{
				$albums[$row['id']] = $row;
			}
			
			if( $sort === true ) $albums = $this->sortArrayFromArrayKeys( $id, $albums );
		}
		else
		{
			$result = $this->db->sql_query( "SELECT * FROM `" . $this->table_prefix . "_albums` WHERE `id`=" . intval( $id ) );
			$albums = $this->db->sql_fetch_assoc( $result );
		}
		
		return $albums;
	}
	
	public function fixPics( $ids )
	{
		$ids = $this->IdHandle( $ids );
		$pictures = $this->getPicsByID( $ids );
		
		// Cap nhat lai danh sach album cua anh
		// Xoa cac anh thumb khong dung
		// Tao anh thumb can thiet
		
		$albums = array();
		$thumbDir = $this->uploadDirInit( $this->mod_name . '/thumb/' . date( 'Y_m' ) );
		
		foreach( $pictures as $picture )
		{
			// Lay cac album chua pic nay
			$album = $thumbSize = array();
			$sql = "SELECT * FROM `" . $this->table_prefix . "_albums` WHERE " . $this->sqlSearchId( $picture['id'], 'dataPics' );
			$result = $this->db->sql_query( $sql );
			
			while( $row = $this->db->sql_fetchrow( $result ) )
			{
				$album[] = $row['id'];
				
				// Kich thuoc anh lon
				$thumbSize[$row['bigW'] . '-' . $row['bigH']] = array(
					'width' => $row['bigW'],
					'height' => $row['bigH'],
				);
				
				// Kich thuoc anh nho
				$thumbSize[$row['smallW'] . '-' . $row['smallH']] = array(
					'width' => $row['smallW'],
					'height' => $row['smallH'],
				);
			}
			
			// Cap nhat lai album cho pic
			$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_pictures` SET `albums`=" . $this->db->dbescape( $this->buildSQLLids( $album ) ) . " WHERE `id`=" . $picture['id'] );
			
			$sqlWhere = array();
			foreach( $thumbSize as $size )
			{
				$sqlWhere[] = '(`width`!=' . $size['width'] . ' OR `height`!=' . $size['height'] . ')';
			}
			$sqlWhere = implode( ' AND ', $sqlWhere );
			
			// Xoa het thumb ma khong thuoc kich thuoc hien co
			$sql = "SELECT * FROM `" . $this->table_prefix . "_thumbs` WHERE `picId`=" . $picture['id'] . ( $sqlWhere ? " AND " . $sqlWhere : '' );
			$result = $this->db->sql_query( $sql );
			
			while( $row = $this->db->sql_fetchrow( $result ) )
			{
				$row['link'] = $this->db->unfixdb( $row['link'] );
				$row['link'] = $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];
				
				@nv_deletefile( $row['link'] );
			}
			
			// Xoa thumb trong CSDL
			$this->db->sql_query( "DELETE FROM `" . $this->table_prefix . "_thumbs` WHERE `picId`=" . $picture['id'] . ( $sqlWhere ? " AND " . $sqlWhere : '' ) );
			
			// Lay cac kich thuoc thumb da co
			$arrayThumbExists = array();
			
			$sql = "SELECT * FROM `" . $this->table_prefix . "_thumbs` WHERE `picId`=" . $picture['id'];
			$result = $this->db->sql_query( $sql );
			
			while( $row = $this->db->sql_fetchrow( $result ) )
			{
				$arrayThumbExists[$row['width'] . '-' . $row['height']] = array(
					'width' => $row['width'],
					'height' => $row['height'],
				);
			}
			
			// Tim ra cac kich thuoc chua co thumb
			$arrayDiff = array_diff_key( $thumbSize, $arrayThumbExists );
			
			if( ! empty( $arrayDiff ) )
			{
				foreach( $arrayDiff as $row )
				{
					$row['link'] = $this->creatThumb( $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/images/' . $picture['file'], $this->root_dir . '/' . $thumbDir, $row['width'], $row['height'] );
					$row['link'] = substr( $this->root_dir . '/' . $thumbDir . '/' . $row['link'], strlen( $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name . '/thumb/' ) );
					
					$this->db->sql_query( "INSERT INTO `" . $this->table_prefix . "_thumbs` VALUES( " . $picture['id'] . ", " . $row['width'] . ", " . $row['height'] . ", " . $this->db->dbescape( $row['link'] ) . " )" );
				}
			}
		}
	}
	
	public function fixAlbums( $ids )
	{
		$ids = $this->IdHandle( $ids );
		$albums = $this->getAlbumsByID( $ids );
		
		// Cap nhat so anh cua album
		// Cap nhat danh sach anh cua album
		
		foreach( $albums as $album )
		{
			$album['dataPics'] = $this->string2array( $album['dataPics'] );
			
			if( ! empty( $album['dataPics'] ) )
			{
				$pictures = $this->getPicsByID( $album['dataPics'] );
				$pictures = array_keys( $pictures );
				$pictures = array_values( array_intersect( $album['dataPics'], $pictures ) );
				
				if( $pictures != $album['dataPics'] )
				{
					$this->db->sql_query( "UPDATE `" . $this->table_prefix . "_albums` SET `dataPics`=" . $this->db->dbescape( $this->buildSQLLids( $pictures ) ) . ", `numPics`=" . sizeof( $pictures ) . " WHERE `id`=" . $album['id'] );
				}
			}
		}
	}
	
	public function sqlSearchId( $id, $field, $logic = 'OR' )
	{
		$id = $this->IdHandle( $id );
		if( empty( $id ) ) return $field . "=''";
		
		$query = array();
		foreach( $id as $_id )
		{
			$query[] = $field . " LIKE '%," . $_id . ",%'";
		}
		$query = implode( " " . $logic . " ", $query );
		
		return $query;
	}
	
	public function uploadDirInit( $path )
	{
		if( file_exists( $this->root_dir . '/' . $this->upload_dir . '/' . $path ) )
		{
			$upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $path;
		}
		else
		{
			$upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $this->mod_name;
			$e = explode( "/", $path );
			
			if( ! empty( $e ) )
			{
				$cp = "";
				foreach( $e as $p )
				{
					if( ! empty( $p ) and ! is_dir( $this->root_dir . '/' . $this->upload_dir . '/' . $cp . $p ) )
					{
						$mk = nv_mkdir( $this->root_dir . '/' . $this->upload_dir . '/' . $cp, $p );
						nv_loadUploadDirList( false );
						if( $mk[0] > 0 )
						{
							$upload_real_dir_page = $mk[2];
						}
					}
					elseif( ! empty( $p ) )
					{
						$upload_real_dir_page = $this->root_dir . '/' . $this->upload_dir . '/' . $cp . $p;
					}
					$cp .= $p . '/';
				}
			}
			$upload_real_dir_page = str_replace( "\\", "/", $upload_real_dir_page );
		}
		
		return str_replace( $this->root_dir . "/", "", $upload_real_dir_page );
	}
	
	public function getFullAlbum( $id )
	{
		$id = intval( $id );
		$cacheFile = $this->lang_data . "_" . $this->mod_name . "_" . $id . "_" . $this->cache_prefix . ".cache";
		
		if( ( $cache = $this->getCache( $cacheFile ) ) != false )
		{
			$array = unserialize( $cache );
		}
		else
		{
			$array = array();
			$sql = "SELECT * FROM `" . $this->table_prefix . "_albums` WHERE `id`=" . $id;
			$result = $this->db->sql_query( $sql );
			
			if( $this->db->sql_numrows( $result ) )
			{
				$array = $this->db->sql_fetch_assoc( $result );
				$array['dataPics'] = $this->string2array( $array['dataPics'] );
				$array['pics'] = array();
				
				// Lay thong tin anh
				$pics = $this->getPicsByID( $array['dataPics'], true );
				
				// Lay anh da cat cho album
				$thumbs = array();
				$sql = "SELECT * FROM `" . $this->table_prefix . "_thumbs` WHERE `picId` IN(" . implode( ',', array_keys( $pics ) ) . ") AND( ( `width`=" . $array['bigW'] . " AND `height`=" . $array['bigH'] . " ) OR ( `width`=" . $array['smallW'] . " AND `height`=" . $array['smallH'] . " ) )";
				$result = $this->db->sql_query( $sql );
				
				while( $row = $this->db->sql_fetchrow( $result ) )
				{
					if( $row['width'] == $array['bigW'] and $row['height'] == $array['bigH'] )
					{
						$thumbs[$row['picId']]['big'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];
					}
					else
					{
						$thumbs[$row['picId']]['small'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/' . $row['link'];
					}
				}
				
				foreach( $pics as $pic )
				{
					$pic['file'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/images/' . $pic['file'];
					$pic['thumb'] = $this->base_site_url . $this->upload_dir . '/' . $this->mod_name . '/thumb/sys/' . $pic['thumb'];
					$pic['thumbBig'] = $pic['thumbSmall'] = $pic['thumb'];
					$pic['albums'] = $this->string2array( $pic['albums'] );
					
					if( ! empty( $thumbs[$pic['id']]['big'] ) )
					{
						$pic['thumbBig'] = $thumbs[$pic['id']]['big'];
					}
					
					if( ! empty( $thumbs[$pic['id']]['small'] ) )
					{
						$pic['thumbSmall'] = $thumbs[$pic['id']]['small'];
					}
					
					$array['pics'][$pic['id']] = $pic;
				}
				
				$cache = serialize( $array );
				$this->setCache( $cacheFile, $cache );
			}
		}
		
		return $array;
	}
}

?>