<!-- BEGIN: main -->
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Language" content="vi" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>{LANG.picFindTitle}</title>
		<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.admin_theme}/css/admin.css" type="text/css" />
		<link type="text/css" href="{NV_BASE_SITEURL}themes/{GLOBAL_CONFIG.module_theme}/css/{MODULE_FILE}.css" rel="stylesheet" />
		<script type="text/javascript">var nv_siteroot = "{NV_BASE_SITEURL}";</script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/language/{NV_LANG_INTERFACE}.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/global.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/admin.js"></script>
		<script type="text/javascript" src="{NV_BASE_SITEURL}js/jquery/jquery.min.js"></script>
	</head>
	<body>
	<div style="padding:5px">
	<table class="tab1">
		<thead>
			<tr>
				<td class="glt-col-id center">ID</td>
				<td class="glt-col-number">{LANG.picView}</td>
				<td>{LANG.picTitle}</td>
				<td class="glt-col-number">{LANG.albumPicSize}</td>
				<td class="center glt-col-feature">{LANG.select}</td>
			</tr>
		</thead>
	</table>
	<div id="data">
		<!-- BEGIN: data -->
		<table class="tab1">
			<!-- BEGIN: row -->
			<tbody{CLASS}>
				<tr>
					<td class="center glt-col-id"><strong>{ROW.id}</strong></td>
					<td class="center glt-col-number"><img src="{ROW.thumb}" alt="{ROW.title}" height="50"/></td>
					<td>{ROW.title}</td>
					<td class="glt-col-number">{ROW.width}x{ROW.height} px</td>
					<td class="center glt-col-feature"><input type="checkbox" name="pictureid" value="{ROW.id}"{ROW.checked} /></td>
				</tr>
			</tbody>
			<!-- END: row -->
			<!-- BEGIN: generate_page -->
			<tbody>
				<tr>
					<td colspan="5" class="center">
						<div id="loadpage">{GENERATE_PAGE}</div>
					</td>
				</tr>
			</tbody>
			<!-- END: generate_page -->
			<tfoot>
				<tr>
					<td colspan="5" style="text-align:right">
						<input class="glt-button-2" type="button" value="{LANG.checkall}" id="checkall" /> 
						<input class="glt-button-2" type="button" value="{LANG.uncheckall}" id="uncheckall" />
						<script type="text/javascript">
						$(document).ready(function(){
							$('#checkall').click(function(){
								$('input:checkbox').each(function(){
									pictures = pictures.split( "," );
									if ( pictures[0] == "" ) pictures = new Array();

									var inlist = 0;
									var i = 0;
									for ( i = 0; i < pictures.length; i ++ ){
										if ( $(this).attr('value') == pictures[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										pictures.push($(this).attr('value'));				
									}
									pictures = pictures.toString();
									$(this).attr('checked', 'checked');
								});
							});
							
							$('#uncheckall').click(function(){
								$('input:checkbox').each(function(){
									pictures = pictures.split( "," );
									if ( pictures[0] == "" ) pictures = new Array();

									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < pictures.length; i ++ ){
										if ( $(this).attr('value') != pictures[i] ){
											listtemp.push(pictures[i]);
										}
									}
									pictures = listtemp;				
									pictures = pictures.toString();
									$(this).removeAttr('checked');
								});
							});
							$("input[name=pictureid]").click(function() {
								pictures = pictures.split( "," );
								if ( pictures[0] == "" ) pictures = new Array();
								if ( $(this).attr('checked') ){
									var inlist = 0;
									var i = 0;
									for ( i = 0; i < pictures.length; i ++ ){
										if ( $(this).attr('value') == pictures[i] ){
											inlist = 1;
											break;
										}
									}
									if ( inlist == 0 ){
										pictures.push($(this).attr('value'));				
									}
								}else{								
									var listtemp = new Array();
									var i = 0;
									for ( i = 0; i < pictures.length; i ++ ){
										if ( $(this).attr('value') != pictures[i] ){
											listtemp.push(pictures[i]);
										}
									}
									pictures = listtemp;				
								}
								pictures = pictures.toString();
							});
						});
						</script>
					</td>
				</tr>
			</tfoot>
		</table>
		<!-- END: data -->
	</div>
	<div style="text-align:center"><input type="button" value="{LANG.complete}" name="complete" id="complete" class="glt-button"/></div>
	<script type="text/javascript">
		var pictures = "{PICTURES}"; 
		function nv_load_page( url, tagsid ){
			url = rawurldecode ( url ) + "&getdata=1&area={RETURNAREA}&input={RETURNINPUT}&pictures=" + pictures;
			$('div#data').html('<div style="padding:5px;text-align:center"><img alt="Loading" src="{NV_BASE_SITEURL}images/load_bar.gif" /></div>');
			$('div#data').load(url);
			return;
		}
		$("input[name=complete]").click(function(){
			$('#tmp-data').html('');
			$('#tmp-data').load( '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&findListAndReturn=1&loadname=1&area={RETURNAREA}&input={RETURNINPUT}&pictures=' + pictures, function(){
				nv_return();
			});
		});		
		function nv_return(){
			$("#{RETURNAREA}", opener.document).html($('#tmp-data').html());
			$("input[name={RETURNINPUT}]", opener.document).val(pictures);
			window.close();
		}
	</script>
	<div style="display:none;visibility:hidden" id="tmp-data"></div>
	</div>
	</body>
</html>
<!-- END: main -->