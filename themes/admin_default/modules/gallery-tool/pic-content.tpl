<!-- BEGIN: step1 -->
<ul class="glt-upload-step clearfix">
	<li class="active">
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep1}</span>
	</li>
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep2}</span>
	</li>
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep3}</span>
	</li>
</ul>
<form method="post" action="{FORM_ACTION}">	
	<div id="uploader">
		<p>{LANG.picULErrorSetupNote}</p>
	</div>
</form>
<script type="text/javascript">
$(function(){
	$("#uploader").pluploadQueue({
		runtimes : 'html5,flash,silverlight,html4',
		url : '{UPLOAD_URL}',
		chunk_size: '{SETTING.chunk_size}{SETTING.chunk_size_unit}',
		max_retries: 3,
		rename : false,
		dragdrop: true,
		filters : {
			max_file_size : '{SETTING.max_file_size}{SETTING.max_file_size_unit}',
			mime_types: [
				{title : "Image files", extensions : "jpg,gif,png"},
			]
		},
		flash_swf_url : '{FRAMEWORKS_DIR}/Moxie.swf',
		silverlight_xap_url : '{FRAMEWORKS_DIR}/Moxie.xap',
		multi_selection: true,
		prevent_duplicates: true,
		multiple_queues: false,
	});
});
</script>
<!-- END: step1 -->
<!-- BEGIN: step2 -->
<ul class="glt-upload-step clearfix">
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep1}</span>
	</li>
	<li class="active">
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep2}</span>
	</li>
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep3}</span>
	</li>
</ul>
<!-- BEGIN: error -->
<div class="infoerror">{ERROR}</div>
<!-- END: error -->
<form action="{FORM_ACTION}" method="post">
	<table class="tab1">
		<thead>
			<tr>
				<td style="width:100px">{LANG.picView}</td>
				<td>{LANG.picTitle} - {LANG.picDescription}</td>
				<td>{LANG.picOther}</td>
			</tr>
		</thead>
		<!-- BEGIN: loop -->
		<tbody{ROW.class}>
			<tr>
				<td>
					<input type="hidden" name="uploader_{ROW.stt}_name" value="{ROW.file}"/>
					<input type="hidden" name="uploader_{ROW.stt}_status" value="{ROW.status}"/>
					<input type="hidden" name="thumb_{ROW.stt}" value="{ROW.thumb}"/>
					<a href="{ROW.filePath}" rel="shadowbox[miss]" class="glt-upload2-thumb">
						<span>
							<img src="{ROW.thumb}" width="90"/>
						</span>
					</a>
				</td>
				<td>
					<input type="text" name="title_{ROW.stt}" value="{ROW.title}" class="glt-input glt-txt-h glt-m-bottom" placeholder="{LANG.picTitle}"/>
					<input type="text" name="description_{ROW.stt}" value="{ROW.description}" class="glt-input glt-txt-fh glt-m-bottom" placeholder="{LANG.picDescription}"/>
				</td>
				<td>
					<input type="text" name="info1_{ROW.stt}" value="{ROW.info1}" class="glt-input glt-txt-fh glt-m-bottom" placeholder="{LANG.picOther} 1"/>
					<input type="text" name="info2_{ROW.stt}" value="{ROW.info2}" class="glt-input glt-txt-fh glt-m-bottom" placeholder="{LANG.picOther} 2"/>
				</td>
			</tr>
		</tbody>
		<!-- END: loop -->
		<tfoot>
			<tr>
				<td colspan="3">
					<input type="hidden" name="uploader_count" value="{TOTALFILE}"/>
					<input type="submit" name="submit" value="{LANG.save}" class="glt-button"/>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<!-- END: step2 -->
<!-- BEGIN: step3 -->
<ul class="glt-upload-step clearfix">
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep1}</span>
	</li>
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep2}</span>
	</li>
	<li class="active">
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep3}</span>
	</li>
</ul>
<!-- BEGIN: error -->
<div class="infoerror">{ERROR}</div>
<!-- END: error -->
<div class="glt-upload3">
	<form method="post" action="{FORM_ACTION}">
		<input type="text" name="albumTitle" value="{DATA.title}" id="glt-search-album" class="glt-input glt-col-left-largest" placeholder="{LANG.picULEnterAlbumName}"/>
		<input type="hidden" name="albumId" id="glt-id-album" value="{DATA.id}"/>
		<input type="submit" name="submit" value="{LANG.save}" class="glt-button"/>
		 {LANG.or} 
	 	<input type="button" value="{LANG.picULStepPass}" class="glt-button-2" id="glt-next-step"/>
	</form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#glt-search-album').autocomplete({
		source: function (request, response){
			$.ajax({
				url: script_name,
				type: "POST",
				dataType: "json",
				data: nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=album-list&ajaxAlbum=' + encodeURIComponent( request.term ),
				success: function (data){
					response( $.map( data, function (item){
						return {
							label: item.label,
							value: item.value
						}
					}));
				}
			});
		},
		minLength: 2,
		select: function( event, ui ){
			if( ui.item ){
				$('#glt-search-album').val(ui.item.label);
				$('#glt-id-album').val(ui.item.value);
			}
			
			return false;
		},
		focus: function(){
			return false;
		}
	});
	$('#glt-next-step').click(function(){
		window.location = '{NEXT_STEP}';
	});
});
</script>
<!-- END: step3 -->
<!-- BEGIN: step4 -->
<ul class="glt-upload-step clearfix">
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep1}</span>
	</li>
	<li>
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep2}</span>
	</li>
	<li class="active">
		<span class="stepicon"><span>&nbsp;</span></span>
		<span class="steptext">{LANG.picULStep3}</span>
	</li>
</ul>
<div class="infook center">
	<p>{LANG.picULComplete}</p>
	<p><img src="{NV_BASE_SITEURL}images/load_bar.gif" alt="Loading..." height="8"/></p>
</div>
<!-- END: step4 -->