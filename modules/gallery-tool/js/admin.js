/* *
 * @Project NUKEVIET GALLERY TOOL 3.x
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2014 PHAN TAN DUNG. All rights reserved
 * @Createdate May 01, 2014, 04:09:03 PM
 */


// Global
var tmp, nv_timer;

// Alias
function get_alias( id, returnId, mode ){
	tmp = returnId;
	var title = strip_tags( document.getElementById(id).value );
	if( title != '' ){
		nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_name_variable + '=' + nv_module_name + '&mode=' + mode + '&get_alias=' + encodeURIComponent( title ), '', 'get_alias_return');
	}
	return false;
}
function get_alias_return(res){
	if(res != ""){
		document.getElementById(tmp).value = res;
	}else{
		document.getElementById(tmp).value = '';
	}
	return false;
}

// Thao tac tra ve
function nv_delete_result( res ){
	if( res == 'OK' ){
		window.location.href = window.location.href;
	}else{
		alert( nv_is_del_confirm[2] );
	}
	return false;
}
function nv_change_status_result( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
		window.location.href = window.location.href;
	}
	return;
}
function nv_change_status_list_result( res ){
	if( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
	}
	window.location.href = window.location.href;
	return;
}
function nv_chang_weight_result( res ){
	if ( res != 'OK' ){
		alert( nv_is_change_act_confirm[2] );
	}
	clearTimeout( nv_timer );
	window.location.href = window.location.href;
	return;
}

// Thao tac voi album
function nv_album_action(oForm, nv_message_no_check, key) {
	var fa = oForm['idcheck[]'];
	var listid = [];
	
	if (fa.length){
		for ( var i = 0; i < fa.length; i++){
			if (fa[i].checked){
				listid.push(fa[i].value);
			}
		}
	}else{
		if(fa.checked){
			listid.push(fa.value);
		}
	}
	
	if (listid != ''){
		if (key == 1){
			if ( confirm(nv_is_del_confirm[0]) ){
				nv_ajax('post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album-list&del=1&listid=' + listid, '', 'nv_delete_result');
			}
		}
	}else{
		alert(nv_message_no_check);
	}
}
function nv_delete_album( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album-list&del=1&id=' + id, '', 'nv_delete_result' );
	}
	return false;
}

// Thao tac voi anh
function nv_delete_pic( id ){
	if ( confirm( nv_is_del_confirm[0] ) ){
		nv_ajax( 'post', script_name, nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&del=1&id=' + id, '', 'nv_delete_result' );
	}
	return false;
}

// Xu ly cac item
function nv_del_item_on_list( id, area, lang, inputname ){
	if( confirm( lang ) )
	{
		$("#" + area + " li." + id).remove();
		nv_sort_item( area, inputname );
	}
	return false;
}
function nv_sort_item( area, inputname ){
	var list = new Array();
	$("#" + area + " li").each(function(){
		list.push($(this).attr("class"));
	});
	list = list.toString();
	$("input[name=" + inputname + "]").val(list);
	return;
}
