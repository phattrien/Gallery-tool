<!-- BEGIN: main -->
<!-- BEGIN: error --><div class="infoerror">{ERROR}</div><!-- END: error -->
<form method="post" action="{FORM_ACTION}" id="post-form">
	<input type="hidden" name="id" id="post-id" value="{ID}"/>
	<table class="tab1">
		<caption>{TABLE_CAPTION}</caption>
		<colgroup>
			<col style="width:200px"/>
		</colgroup>
		<tbody>
			<tr>
				<td><strong>{LANG.albumTitle}</strong></td>
				<td><input type="text" name="title" value="{DATA.title}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.albumDes}</strong></td>
				<td><input type="text" name="description" value="{DATA.description}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.albumBigSize}</strong></td>
				<td>
					<input type="text" name="bigW" value="{DATA.bigW}" class="glt-input"/> x
					<input type="text" name="bigH" value="{DATA.bigH}" class="glt-input"/>
				</td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.albumSmallSize}</strong></td>
				<td>
					<input type="text" name="smallW" value="{DATA.smallW}" class="glt-input"/> x
					<input type="text" name="smallH" value="{DATA.smallH}" class="glt-input"/>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="tab1">
		<caption>{LANG.albumPics}</caption>
		<tbody>
			<tr>
				<td>
					<input type="hidden" name="pictures" value="{PICTURES}"/>
					<p>
						<strong>
							<a href="javascript:void(0);" id="pictures-add-one" class="nounderline glt-add-icon">{LANG.albumPicAddOne}</a>
							<a href="javascript:void(0);" id="pictures-add-list" class="nounderline glt-list-icon">{LANG.albumPicAddList}</a>
						</strong>
					</p>
					<ul id="pictures-area" class="fixbg glt-sorttable lg">
						<!-- BEGIN: picture -->
						<li class="{PICTURE.id}">
							<img src="{PICTURE.thumb}"/>
							{PICTURE.title}<span onclick="nv_del_item_on_list({PICTURE.id}, 'pictures-area', nv_is_del_confirm[0], 'pictures')" class="glt-delete-icon">&nbsp;</span>
						</li>
						<!-- END: picture -->
					</ul>					
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<input type="submit" name="submit" value="{LANG.save}" class="glt-button"/>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$( "#pictures-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('pictures-area', 'pictures'); }
	});
	$( "#pictures-area" ).disableSelection();
	$("a#pictures-add-one").click(function(){
		var pictures = $("input[name=pictures]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findOneAndReturn=1&area=pictures-area&input=pictures&pictures=" + pictures, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#pictures-add-list").click(function(){
		var pictures = $("input[name=pictures]").attr("value");
		nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&findListAndReturn=1&area=pictures-area&input=pictures&pictures=" + pictures, "NVImg", "850", "610", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
});
</script>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="infook center">
	<p>{MESSAGE}</p>
	<p><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<!-- END: complete -->