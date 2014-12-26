<!-- BEGIN: main -->
<table class="tab1">
	<tbody>
		<tr>
			<td>
				<form id="filter-form" method="get" action="" onsubmit="return false;">
					<input class="glt-input text" type="text" name="q" value="{DATA_SEARCH.q}" placeholder="{LANG.searchPost}"/>
					<input class="glt-button" type="button" name="do" value="{LANG.filter_action}"/>
					<input class="glt-button" type="button" name="cancel" value="{LANG.filter_cancel}" onclick="window.location='{URL_CANCEL}';"{DATA_SEARCH.disabled}/>
					<input class="glt-button" type="button" name="clear" value="{LANG.filter_clear}"/>
				</form>
			</td>
		</tr>
	</tbody>
</table>
<script type="text/javascript">
$(document).ready(function(){
	$('input[name=clear]').click(function(){
		$('#filter-form .text').val('');
	});
	$('input[name=do]').click(function(){
		var f_q = $('input[name=q]').val();

		if( f_q != ''  ){
			$('#filter-form input, #filter-form select').attr('disabled', 'disabled');
			window.location = '{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&q=' + f_q;	
		}else{
			alert ('{LANG.filter_err_submit}');
		}
	});
});
</script>
<div class="glt-piclist clearfix">
	<!-- BEGIN: row -->
	<div class="item">
		<div class="ct">
			<div class="center"><h3 class="tl">{ROW.title}</h3></div>
			<div class="center glt-m-bottom">
				<a href="{ROW.file}" rel="shadowbox[miss]">
					<img src="{ROW.thumb}" class="img tooltip" title="<ul class='glt-ul'><li>{LANG.picULPicSize}: {ROW.width}x{ROW.height} px</li><li>{LANG.picSize}: {ROW.size}</li><li>{LANG.picFormat}: {ROW.format}</li></ul>"/>
				</a>
			</div>
			<div class="center">
				<a href="javascript:void(0);" class="glt-edit-icon tooltip" title="{LANG.picEdit}" rel="{ROW.id}">&nbsp;</a>
				<!--a href="javascript:void(0);" class="glt-unselect-icon tooltip" title="{LANG.picChoose}">&nbsp;</a-->
				<a href="javascript:void(0);" class="glt-delete-icon tooltip" title="{LANG.picDel}" onclick="nv_delete_pic({ROW.id});">&nbsp;</a>
			</div>
		</div>
	</div>
	<!-- END: row -->
</div>
<!-- BEGIN: generate_page -->
<div class="generate_page">
	{GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<script type="text/javascript">
$(document).ready(function(){
	$('.tooltip').tipsy({
		trigger: 'hover',
		gravity: 's',
		live: true,
		html: true,
	});
	$('.glt-edit-icon').click(function(){
		Shadowbox.open({
			content : '{URL_EDIT}' + $(this).attr('rel'),
			player : 'iframe',
			title: '{LANG.picEdit}',
			width:	600,
			height: 500,
		});
	});
});
</script>
<!-- END: main -->