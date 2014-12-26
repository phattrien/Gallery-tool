<!-- BEGIN: main -->
<tr style="display:none">
	<td><link rel="stylesheet" href="{CSS_FILE}" type="text/css" /></td>
	<td>{JQUERY_PLUGIN}</td>
</tr>
<tr>
	<td>{LANG.blkAlbumId} <a href="javascript:void(0);" class="glt-select-icon nounderline" id="album-choose">{LANG.select}</a></td>
	<td>
		<input type="hidden" name="config_albumId" value="{DATA.albumId}"/>
		<ul id="albumTitle" class="glt-sorttable glt-sorttable-no">
			<li class="{DATA.albumId}">{DATA.albumTitle}<span onclick="nv_del_item_on_list({DATA.albumId}, 'albumTitle', nv_is_del_confirm[0], 'config_albumId')" class="glt-delete-icon">&nbsp;</span></li>
		</ul>
	</td>
</tr>
<tr>
	<td>{LANG.blkJs}</td>
	<td>
		<input type="checkbox" name="config_js"{DATA.js} value="1"/>
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueeDelayBeforeStart}</td>
	<td>
		<input type="text" name="config_delayBeforeStart" class="glt-input glt-col-day" value="{DATA.delayBeforeStart}"/> (ms)
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueeDirection}</td>
	<td>
		<select name="config_direction" class="glt-input">	
			<!-- BEGIN: direction --><option value="{DIRECTION.key}"{DIRECTION.selected}>{DIRECTION.title}</option><!-- END: direction -->
		</select>
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueeDuplicated}</td>
	<td>
		<input type="checkbox" name="config_duplicated" value="1"{DATA.duplicated}/>
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueeGap}</td>
	<td>
		<input type="text" name="config_gap" class="glt-input glt-col-day" value="{DATA.gap}"/> (px)
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueeDuration}</td>
	<td>
		<input type="text" name="config_duration" class="glt-input glt-col-day" value="{DATA.duration}"/> (ms)
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueePauseOnHover}</td>
	<td>
		<input type="checkbox" name="config_pauseOnHover" value="1"{DATA.pauseOnHover}/>
	</td>
</tr>
<tr>
	<td>{LANG.blkMarqueePauseOnCycle}</td>
	<td>
		<input type="text" name="config_pauseOnCycle" class="glt-input glt-col-day" value="{DATA.pauseOnCycle}"/> (ms)
	</td>
</tr>
<tr style="display:none">
	<td></td>
	<td>
		<script type="text/javascript">
		$(document).ready(function(){
			$("a#album-choose").click(function(){
				nv_open_browse_file( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "={MODULE_NAME}&" + nv_fc_variable + "=album-list&findOneAndReturn=1&area=albumTitle&input=config_albumId&multi=0", "NVImg", "850", "620", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
			});
		});
		</script>
	</td>
</tr>
<!-- END: main -->