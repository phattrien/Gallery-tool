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
				<td><strong>{LANG.picTitle}</strong></td>
				<td><input type="text" name="title" value="{DATA.title}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.picDescription}</strong></td>
				<td><input type="text" name="description" value="{DATA.description}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.picOther} 1</strong></td>
				<td><input type="text" name="info1" value="{DATA.info1}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.picOther} 2</strong></td>
				<td><input type="text" name="info2" value="{DATA.info2}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.picOther} 3</strong></td>
				<td><input type="text" name="info3" value="{DATA.info3}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.picOther} 4</strong></td>
				<td><input type="text" name="info4" value="{DATA.info4}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody>
			<tr>
				<td><strong>{LANG.picOther} 5</strong></td>
				<td><input type="text" name="info5" value="{DATA.info5}" class="glt-input glt-txt-h"/></td>
			</tr>
		</tbody>
		<tbody class="second">
			<tr>
				<td><strong>{LANG.picLink}</strong></td>
				<td><input type="text" name="link" value="{DATA.link}" class="glt-input glt-txt-h"/></td>
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

});
</script>
<!-- END: main -->

<!-- BEGIN: complete -->
<div class="infook center">
	<p>{MESSAGE}</p>
	<p><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<script type="text/javascript">
setTimeout( "parent.location.href=parent.location.href;", 1000 );
</script>
<!-- END: complete -->