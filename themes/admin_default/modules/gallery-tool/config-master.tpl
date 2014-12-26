<!-- BEGIN: main -->
<form method="post" action="{FORM_ACTION}">
<table class="tab1">
	<caption>{LANG.cfgUpload}</caption>
	<col class="glt-col-left-largest"/>
	<tbody>
		<tr>
			<td>
				<strong>{LANG.cfgchunk_size}</strong>
			</td>
			<td>
				<input type="text" name="chunk_size" value="{DATA.chunk_size}" class="glt-input glt-col-day"/> 
				<select name="chunk_size_unit" class="glt-input">
					<!-- BEGIN: size_unit_1 --><option value="{SIZEUNIT.key}"{SIZEUNIT.chunk_size}>{SIZEUNIT.title}</option><!-- END: size_unit_1 -->
				</select> 
				({LANG.cfgchunk_size_note})
			</td>
		</tr>
	</tbody>
	<tbody class="second">
		<tr>
			<td>
				<strong>{LANG.cfgmax_file_size}</strong>
			</td>
			<td>
				<input type="text" name="max_file_size" value="{DATA.max_file_size}" class="glt-input glt-col-day"/> 
				<select name="max_file_size_unit" class="glt-input">
					<!-- BEGIN: size_unit_2 --><option value="{SIZEUNIT.key}"{SIZEUNIT.max_file_size}>{SIZEUNIT.title}</option><!-- END: size_unit_2 -->
				</select> 
				{LANG.cfgmaxNote} {MAX_SIZE_NOTE}
			</td>
		</tr>
	</tbody>
</table>
<table class="tab1">
	<tbody>
		<tr>
			<td class="center"><input class="glt-button" type="submit" name="submit" value="{LANG.save}"/></td>
		</tr>
	</tbody>
</table>
</form>
<!-- END: main -->