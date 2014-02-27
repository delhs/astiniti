/*
	Extend file upload plugin - support list files and customize filelink
*/
if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};
 RedactorPlugins.extfupl = { 
     init: function()
	{
            redactor =$.extend({
fclass:'Класс ссылки на файл'
            },redactor);
            this.opts=$.extend({
                           extmodal_file:String() +
				'<div id="redactor_modal_content">' +
				'<div id="redactor_tabs">' +
					'<a href="javascript:void(null);" class="redactor_tabs_act">' + redactor.upload + '</a>' +
					'<a href="javascript:void(null);">' + redactor.choose + '</a>' +
				'</div>' +
                                '<form id="redactorUploadFileForm" method="post" action="" enctype="multipart/form-data">' +
                                    '<div id="redactor_tab1" class="redactor_tab">' +
					'<label>' + redactor.title + ' (optional)</label>' +
					'<input type="text" id="redactor_filename" class="redactor_input" />' +
                                        '<label>' + redactor.fclass + ' (optional)</label>' +
					'<input type="text" id="redactor_fileclass" class="redactor_input" />' +
					'<div style="margin-top: 7px;">' +
						'<input type="file" id="redactor_file" name="file" />' +
					'</div>' +
                                    '</div>' +
                                    '<div id="redactor_tab2" class="redactor_tab" style="display: none;">' +
                                    '<input type="hidden" id="redactor_selfilename" value="" />' +
						'<div id="redactor_file_box"><ul id="redactor_file_box_list"></ul></div>' +
				     '</div>' +
				'</form><br>' +
				'</div>' 
              },this.opts);
        },
        showFile: function()
		{
			this.saveSelection();

			var callback = $.proxy(function()
			{
                            var sel = this.getSelection();

				var text = '';

				if (this.oldIE())
				{
					text = sel.text;
				}
				else
				{
					text = sel.toString();
				}

				$('#redactor_filename').val(text);
                                $('#redactor_selfilename').val(text);
                            if (this.opts.fileGetJson !== false)
				{
					$.getJSON(this.opts.fileGetJson, $.proxy(function(data) {

						var folders = {};
						var z = 0;

						// folders
						$.each(data, $.proxy(function(key, val)
						{
							if (typeof val.folder !== 'undefined')
							{
								z++;
								folders[val.folder] = z;
							}

						}, this));

						var folderclass = false;
						$.each(data, $.proxy(function(key, val)
						{
							
							var folderkey = 0;
							if (!$.isEmptyObject(folders) && typeof val.folder !== 'undefined')
							{
								folderkey = folders[val.folder];
								if (folderclass === false)
								{
									folderclass = '.redactorfolder' + folderkey;
								}
							}

							 var fileclass=(val.fileclass)?'class="'+val.fileclass+'"':(this.opts.fileClass)?'class="'+this.opts.fileClass+'"':'';
                                                        var filename=$('#redactor_selfilename').val();
                                                        filename=(filename)?filename:val.filename;
                                                        var file=$('<li class="redactorfolder redactorfolder' + folderkey + '" ><a href="#" rel="'+val.filelink+'" '+fileclass+' data-name="'+filename+'">'+val.filename+'</a></li>');
                                                        $('#redactor_file_box_list').append(file);
                                                        $(file).click($.proxy(this.extfileSet, this));
                                                        


						}, this));

						// folders
						if (!$.isEmptyObject(folders))
						{
							$('.redactorfolder').hide();
							$(folderclass).show();

							var onchangeFunc = function(e)
							{
								$('.redactorfolder').hide();
								$('.redactorfolder' + $(e.target).val()).show();
							}

							var select = $('<select id="redactor_file_box_select">');
							$.each(folders, function(k,v)
							{
								select.append($('<option value="' + v + '">' + k + '</option>')); 
							});

							$('#redactor_file_box').before(select);
							select.change(onchangeFunc);
						}

					}, this));
				}
				else
				{
					$('#redactor_tabs a').eq(1).remove();
				}
				

				// dragupload
				if (this.opts.uploadCrossDomain === false && this.isMobile() === false)
				{
					$('#redactor_file').dragupload(
					{
						url: this.opts.fileUpload,
						uploadFields: this.opts.uploadFields,
						success: $.proxy(this.extfileUploadCallback, this),
						error: $.proxy(this.opts.fileUploadErrorCallback, this)
					});
				}

				this.uploadInit('redactor_file',
				{
					auto: true,
					url: this.opts.fileUpload,
					success: $.proxy(this.extfileUploadCallback, this),
					error: $.proxy(this.opts.fileUploadErrorCallback, this)
				});

			}, this);

			this.modalInit(redactor.file, this.opts.extmodal_file, 500, callback);
		},
                extfileUploadCallback: function(json)
		{
			this.restoreSelection();

			if (json !== false)
			{
				var text = $('#redactor_filename').val();
                                

				if (text === '')
				{
					text = json.filename;
				}
                                var fileclass=$('#redactor_fileclass').val();
                                if (fileclass === '')
				{
					fileclass = json.fileclass;
				}
                                fileclass=(fileclass)?'class="'+fileclass+'"':'';

				var link = '<a href="' + json.filelink + '" '+fileclass+'>' + text + '</a> &nbsp;';

				// chrome fix
				if ($.browser.webkit && !!this.window.chrome)
				{
					link = link + '&nbsp;';
				}

				this.execCommand('inserthtml', link);

				// file upload callback
				if (typeof this.opts.fileUploadCallback === 'function')
				{
					this.opts.fileUploadCallback(this, json);
				}
			}

			this.modalClose();
		},
                extfileSet: function(e)
		{
                    this.restoreSelection();
			var link='<a href="' + $(e.target).attr('rel') + '" class="'+$(e.target).attr('class')+'">'+$(e.target).attr('data-name')+'</a>&nbsp;';
                        this.execCommand('inserthtml', link);
                        this.modalClose();
		}
 }                   


