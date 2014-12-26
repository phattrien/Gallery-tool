(function($, o){
	var uploaders = {};

	function _(str){
		return plupload.translate(str) || str;
	}

	function renderUI(id, target){
		target.contents().each(function(i, node){
			node = $(node);

			if (!node.is('.plupload')){
				node.remove();
			}
		});

		target.prepend(
			'<div class="plupload_wrapper plupload_scroll">' +
				'<div id="' + id + '_container" class="plupload_container">' +
					'<div class="plupload">' +
						'<div class="plupload_header">' +
							'<div class="plupload_header_content">' +
								'<div class="plupload_header_title">' + _('Select files') + '</div>' +
								'<div class="plupload_header_runtime">' + _('Current runtime:') + ' <span class="plupload_header_runtime_text"></span></div>' + 
								'<div class="plupload_header_text">' + _('Add files to the upload queue and click the start button.') + '</div>' +
							'</div>' +
						'</div>' +

						'<div class="plupload_content">' +
							'<div class="plupload_filelist_header">' +
								'<div class="plupload_file_name">' + _('Filename') + '</div>' +
								'<div class="plupload_file_action">&nbsp;</div>' +
								'<div class="plupload_file_status"><span>' + _('Status') + '</span></div>' +
								'<div class="plupload_file_size">' + _('Size') + '</div>' +
								'<div class="plupload_clearer">&nbsp;</div>' +
							'</div>' +

							'<ul id="' + id + '_filelist" class="plupload_filelist"></ul>' +

							'<div class="plupload_filelist_footer">' +
								'<div class="plupload_file_name">' +
									'<div class="plupload_buttons">' +
										'<a href="javascript:void(0);" class="plupload_button plupload_add" id="' + id + '_browse">' + _('Add Files') + '</a>' +
										'<a href="javascript:void(0);" class="plupload_button plupload_start">' + _('Start Upload') + '</a>' +
									'</div>' +
									'<span class="plupload_upload_status"></span>' +
									'<a href="javascript:void(0);" class="plupload_button plupload_submit plupload_hide">' + _('Next Step') + '</a>' +
								'</div>' +
								'<div class="plupload_file_action"></div>' +
								'<div class="plupload_file_status"><span class="plupload_total_status">0%</span></div>' +
								'<div class="plupload_file_size"><span class="plupload_total_file_size">0 b</span></div>' +
								'<div class="plupload_progress">' +
									'<div class="plupload_progress_container">' +
										'<div class="plupload_progress_bar"></div>' +
									'</div>' +
								'</div>' +
								'<div class="plupload_clearer">&nbsp;</div>' +
							'</div>' +
						'</div>' +
					'</div>' +
				'</div>' +
				'<input type="hidden" id="' + id + '_count" name="' + id + '_count" value="0" />' +
			'</div>'
		);
	}

	$.fn.pluploadQueue = function(settings){
		if (settings){
			this.each(function(){
				var uploader, target, id, contents_bak;

				target = $(this);
				id = target.attr('id');

				if (!id){
					id = plupload.guid();
					target.attr('id', id);
				}

				contents_bak = target.html();
				renderUI(id, target);

				settings = $.extend({
					dragdrop : true,
					browse_button : id + '_browse',
					container : id
				}, settings);

				if (settings.dragdrop){
					settings.drop_element = id + '_filelist';
				}

				uploader = new plupload.Uploader(settings);

				uploaders[id] = uploader;

				function handleStatus(file, response){
					var actionClass;
					
					if( response !== null ){
						if( response.error.code ){
							file.status = plupload.FAILED;
							file.hint = response.error.message;
							uploader.total.uploaded --;
							uploader.total.failed ++;
						}
						if( response.data.basename ){
							file.name = response.data.basename;
						}
						
						$.each(uploader.files, function(i, f){
							if( f.id == file.id ){
								uploader.files[i].status = file.status;
								uploader.files[i].hint = file.hint;
								uploader.files[i].name = file.name;
							}
						});
					}
					
					if (file.status == plupload.DONE){
						actionClass = 'plupload_done';
					}

					if (file.status == plupload.FAILED){
						actionClass = 'plupload_failed';
					}

					if (file.status == plupload.QUEUED){
						actionClass = 'plupload_delete';
					}

					if (file.status == plupload.UPLOADING){
						actionClass = 'plupload_uploading';
					}

					var icon = $('#' + file.id).attr('class', actionClass).find('a').css('display', 'block');
					if (file.hint){
						icon.attr('title', file.hint);	
					}
				}

				function updateTotalProgress(){
					$('span.plupload_total_status', target).html(uploader.total.percent + '%');
					$('div.plupload_progress_bar', target).css('width', uploader.total.percent + '%');
					$('span.plupload_upload_status', target).html(
						o.sprintf(_('Uploaded %d/%d files'), uploader.total.uploaded, uploader.files.length)
					);
				}

				function updateList(){
					var fileList = $('ul.plupload_filelist', target).html(''), inputCount = 0, inputHTML;

					$.each(uploader.files, function(i, file){
						inputHTML = '';

						if (file.status == plupload.DONE){
							if (file.target_name){
								inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_tmpname" value="' + plupload.xmlEncode(file.target_name) + '" />';
							}

							inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_name" value="' + plupload.xmlEncode(file.name) + '" />';
							inputHTML += '<input type="hidden" name="' + id + '_' + inputCount + '_status" value="' + (file.status == plupload.DONE ? 'done' : 'failed') + '" />';
	
							inputCount++;

							$('#' + id + '_count').val(inputCount);
						}

						fileList.append(
							'<li id="' + file.id + '">' +
								'<div class="plupload_file_name"><span>' + file.name + '</span></div>' +
								'<div class="plupload_file_action"><a href="#"></a></div>' +
								'<div class="plupload_file_status">' + file.percent + '%</div>' +
								'<div class="plupload_file_size">' + plupload.formatSize(file.size) + '</div>' +
								'<div class="plupload_clearer">&nbsp;</div>' +
								inputHTML +
							'</li>'
						);

						handleStatus(file, null);

						$('#' + file.id + '.plupload_delete a').click(function(e){
							$('#' + file.id).remove();
							uploader.removeFile(file);

							e.preventDefault();
						});
					});

					$('span.plupload_total_file_size', target).html(plupload.formatSize(uploader.total.size));

					if (uploader.total.queued === 0){
						$('span.plupload_add_text', target).html(_('Add Files'));
					}else{
						$('span.plupload_add_text', target).html(o.sprintf(_('%d files queued'), uploader.total.queued));
					}

					$('a.plupload_start', target).toggleClass('plupload_disabled', uploader.files.length == (uploader.total.uploaded + uploader.total.failed));

					fileList[0].scrollTop = fileList[0].scrollHeight;

					updateTotalProgress();

					if (!uploader.files.length && uploader.features.dragdrop && uploader.settings.dragdrop){
						$('#' + id + '_filelist').append('<li class="plupload_droptext">' + _("Drag files here.") + '</li>');
					}
				}

				function destroy(){
					delete uploaders[id];
					uploader.destroy();
					target.html(contents_bak);
					uploader = target = contents_bak = null;
				}

				uploader.bind("UploadFile", function(up, file){
					$('#' + file.id).addClass('plupload_current_file');
				});

				uploader.bind('Init', function(up, res){
					if (!settings.unique_names && settings.rename){
						target.on('click', '#' + id + '_filelist div.plupload_file_name span', function(e){
							var targetSpan = $(e.target), file, parts, name, ext = "";

							file = up.getFile(targetSpan.parents('li')[0].id);
							name = file.name;
							parts = /^(.+)(\.[^.]+)$/.exec(name);
							if (parts){
								name = parts[1];
								ext = parts[2];
							}

							targetSpan.hide().after('<input type="text" />');
							targetSpan.next().val(name).focus().blur(function(){
								targetSpan.show().next().remove();
							}).keydown(function(e){
								var targetInput = $(this);

								if (e.keyCode == 13){
									e.preventDefault();

									file.name = targetInput.val() + ext;
									targetSpan.html(file.name);
									targetInput.blur();
								}
							});
						});
					}

					$('#' + id + '_container .plupload_header_runtime_text').html(res.runtime);

					$('a.plupload_start', target).click(function(e){
						if (!$(this).hasClass('plupload_disabled')){
							uploader.start();
						}

						e.preventDefault();
					});

					$('a.plupload_stop', target).click(function(e){
						e.preventDefault();
						uploader.stop();
					});

					$('a.plupload_start', target).addClass('plupload_disabled');
				});

				uploader.bind("Error", function(up, err){
					var file = err.file, message;

					if (file){
						message = err.message;

						if (err.details){
							message += " (" + err.details + ")";
						}

						if (err.code == plupload.FILE_SIZE_ERROR){
							alert(_("Error: File too large:") + " " + file.name);
						}

						if (err.code == plupload.FILE_EXTENSION_ERROR){
							alert(_("Error: Invalid file extension:") + " " + file.name);
						}
						
						file.hint = message;
						$('#' + file.id).attr('class', 'plupload_failed').find('a').css('display', 'block').attr('title', message);
					}

					if (err.code === plupload.INIT_ERROR){
						setTimeout(function(){
							destroy();
						}, 1);
					}
				});

				uploader.bind("PostInit", function(up){
					if (up.settings.dragdrop && up.features.dragdrop){
						$('#' + id + '_filelist').append('<li class="plupload_droptext">' + _("Drag files here.") + '</li>');
					}
				});

				uploader.init();

				uploader.bind('StateChanged', function(){
					if (uploader.state === plupload.STARTED){
						$('li.plupload_delete a,div.plupload_buttons', target).hide();
						$('span.plupload_upload_status,div.plupload_progress,a.plupload_stop', target).css('display', 'inline-block');
						$('span.plupload_upload_status', target).html('Uploaded ' + uploader.total.uploaded + '/' + uploader.files.length + ' files');

						if (settings.multiple_queues){
							$('span.plupload_total_status,span.plupload_total_file_size', target).show();
						}
					}else{
						updateList();
						$('a.plupload_stop,div.plupload_progress', target).hide();
						$('a.plupload_delete', target).css('display', 'block');

						if (settings.multiple_queues && uploader.total.uploaded + uploader.total.failed == uploader.files.length){
							$(".plupload_buttons,.plupload_upload_status", target).css("display", "inline");
							$(".plupload_start", target).addClass("plupload_disabled");
							$('span.plupload_total_status,span.plupload_total_file_size', target).hide();
						}
					}
				});

				uploader.bind('FilesAdded', updateList);

				uploader.bind('FilesRemoved', function(){
					var scrollTop = $('#' + id + '_filelist').scrollTop();
					updateList();
					$('#' + id + '_filelist').scrollTop(scrollTop);
				});

				uploader.bind('FileUploaded', function(up, file, response){
					response = $.parseJSON( response.response );
					handleStatus(file, response);
					
					// Minimun one file uploaded
					if( uploader.total.uploaded > 0 ){
						$('a.plupload_submit', target).removeClass('plupload_hide').click(function(){
							$(target).parent('form').submit();
						});
					}
				});

				uploader.bind("UploadProgress", function(up, file){
					$('#' + file.id + ' div.plupload_file_status', target).html(file.percent + '%');
					handleStatus(file, null);
					updateTotalProgress();
				});

				if (settings.setup){
					settings.setup(uploader);
				}
			});

			return this;
		}else{
			return uploaders[$(this[0]).attr('id')];
		}
	};
})(jQuery, mOxie);