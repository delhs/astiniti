if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};
                    
RedactorPlugins.extimgupl = { 
    init: function()
	{	 
           redactor=$.extend({
iclass:'Класс для изображения',
hclass:'Класс ссылки изображения',
hrel:'Аттрибут rel ссылки изображения',
choosewl:'Выбор без превью'
            },redactor);
            this.opts=$.extend({
                extimgmodal:String() +
				'<div id="redactor_modal_content">' +
				'<label>' + redactor.title + '</label>' +
				'<input id="redactor_file_alt" class="redactor_input" />' +
				'<label>' + redactor.link + '</label>' +
				'<input id="redactor_file_link" class="redactor_input" />' +
                                '<label>' + redactor.iclass + '</label>' +
				'<input id="redactor_form_image_class" class="redactor_input" />' +
                                '<label>' + redactor.hclass + '</label>' +
				'<input id="redactor_form_link_class" class="redactor_input" />' +
                                '<label>' + redactor.hrel + '</label>' +
				'<input id="redactor_form_link_rel" class="redactor_input" />' +
				'<label>' + redactor.image_position + '</label>' +
				'<select id="redactor_form_image_align">' +
					'<option value="none">' + redactor.none + '</option>' +
					'<option value="left">' + redactor.left + '</option>' +
					'<option value="right">' + redactor.right + '</option>' +
				'</select>' +
				'</div>' +
				'<div id="redactor_modal_footer">' +
					'<a href="javascript:void(null);" id="redactor_image_delete_btn" class="redactor_modal_btn">' + redactor._delete + '</a>&nbsp;&nbsp;&nbsp;' +
					'<a href="javascript:void(null);" class="redactor_modal_btn redactor_btn_modal_close">' + redactor.cancel + '</a>' +
					'<input type="button" name="save" class="redactor_modal_btn" id="redactorSaveBtn" value="' + redactor.save + '" />' +
				'</div>',
               extmodal_image: String() +
				'<div id="redactor_modal_content">' +
				'<div id="redactor_tabs">' +
					'<a href="javascript:void(null);" class="redactor_tabs_act">' + redactor.upload + '</a>' +
					'<a href="javascript:void(null);">' + redactor.choose + '</a>' +
                                        '<a href="javascript:void(null);">' + redactor.choosewl + '</a>' +
					'<a href="javascript:void(null);">' + redactor.link + '</a>' +
				'</div>' +
				'<form id="redactorInsertImageForm" method="post" action="" enctype="multipart/form-data">' +
					'<div id="redactor_tab1" class="redactor_tab">' +
						'<input type="file" id="redactor_file" name="file" />' +
					'</div>' +
					'<div id="redactor_tab2" class="redactor_tab" style="display: none;">' +
						'<div id="redactor_image_box"></div>' +
					'</div>' +
                                        '<div id="redactor_tab3" class="redactor_tab" style="display: none;">' +
						'<div id="redactor_imagewl_box"></div>' +
					'</div>' +
				'</form>' +
				'<div id="redactor_tab4" class="redactor_tab" style="display: none;">' +
					'<label>' + redactor.image_web_link + '</label>' +
					'<input type="text" name="redactor_file_link" id="redactor_file_link" class="redactor_input"  />' +
				'</div>' +
				'</div>' +
				'<div id="redactor_modal_footer">' +
					'<a href="javascript:void(null);" class="redactor_modal_btn redactor_btn_modal_close">' + redactor.cancel + '</a>' +
					'<input type="button" name="upload" class="redactor_modal_btn" id="redactor_upload_btn" value="' + redactor.insert + '" />' +
				'</div>'
    
				
            },this.opts);
	},  
		showImage: function()
		{
                    
			this.saveSelection();

			var callback = $.proxy(function()
			{
				// json
				if (this.opts.imageGetJson !== false)
				{
					$.getJSON(this.opts.imageGetJson, $.proxy(function(data) {

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
							// title
							var thumbtitle = '';
							if (typeof val.title !== 'undefined')
							{
								thumbtitle = val.title;
							}

							var folderkey = 0;
							if (!$.isEmptyObject(folders) && typeof val.folder !== 'undefined')
							{
								folderkey = folders[val.folder];
								if (folderclass === false)
								{
									folderclass = '.redactorfolder' + folderkey;
								}
							}

							var img = $('<img src="' + val.thumb + '" class="redactorfolder redactorfolder' + folderkey + '" rel="' + val.image + '" title="' + thumbtitle + '" />');
							$('#redactor_image_box').append(img);
                                                        $(img).click($.proxy(this.extimageSetThumb, this));
                                                        var imgwl = $('<img src="' + val.thumb + '" class="wl redactorfolder redactorfolder' + folderkey + '" rel="' + val.image + '" title="' + thumbtitle + '" />');
                                                        $('#redactor_imagewl_box').append(imgwl);
							$(imgwl).click($.proxy(this.extimageSetThumbWL, this));


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

							var select = $('<select id="redactor_image_box_select">');
                                                        var selectwl = $('<select id="redactor_imagewl_box_select">');
							$.each(folders, function(k,v)
							{
								select.append($('<option value="' + v + '">' + k + '</option>'));
                                                                selectwl.append($('<option value="' + v + '">' + k + '</option>'));
							});

							$('#redactor_image_box').before(select);
                                                        $('#redactor_imagewl_box').before(selectwl);
							select.change(onchangeFunc);
                                                        selectwl.change(onchangeFunc);
						}

					}, this));
				}
				else
				{
					$('#redactor_tabs a').eq(1).remove();
				}

				if (this.opts.imageUpload !== false)
				{

					// dragupload
					if (this.opts.uploadCrossDomain === false && this.isMobile() === false)
					{

						if ($('#redactor_file').size() !== 0)
						{
							$('#redactor_file').dragupload(
							{
								url: this.opts.imageUpload,
								uploadFields: this.opts.uploadFields,
								success: $.proxy(this.extimageUploadCallback, this),
								error: $.proxy(this.opts.imageUploadErrorCallback, this)
							});
						}
					}

					// ajax upload
					this.uploadInit('redactor_file',
					{
						auto: true,
						url: this.opts.imageUpload,
						success: $.proxy(this.extimageUploadCallback, this),
						error: $.proxy(this.opts.imageUploadErrorCallback, this)
					});
				}
				else
				{
					$('.redactor_tab').hide();
					if (this.opts.imageGetJson === false)
					{
						$('#redactor_tabs').remove();
						$('#redactor_tab3').show();
					}
					else
					{
						var tabs = $('#redactor_tabs a');
						tabs.eq(0).remove();
						tabs.eq(1).addClass('redactor_tabs_act');
						$('#redactor_tab2').show();
                                                tabs.eq(2).addClass('redactor_tabs_act');
						$('#redactor_tab3').show();
					}
				}

				$('#redactor_upload_btn').click($.proxy(this.extimageUploadCallbackLink, this));

				if (this.opts.imageUpload === false && this.opts.imageGetJson === false)
				{
					setTimeout(function()
					{
						$('#redactor_file_link').focus();
					}, 200);

				}

			}, this);

			this.modalInit(redactor.image, this.opts.extmodal_image, 610, callback);

		}, 
		extimageSetThumb: function(e)
		{
                     var ldop=(this.opts.thumbLinkRel)?'rel="'+this.opts.thumbLinkRel+'" ':'';
                                    ldop+=(this.opts.thumbLinkClass)?'class="'+this.opts.thumbLinkClass+'" ':'';
			this._extimageSet('<a href="' + $(e.target).attr('rel') + '" '+ldop+'><img src="' + $(e.target).attr('src') + '" alt="' + $(e.target).attr('title') + '" class="'+this.opts.thumbClass+'" /></a>', true);
		},
                extimageSetThumbWL: function(e)
		{
			this._extimageSet('<img src="' + $(e.target).attr('rel') + '" alt="' + $(e.target).attr('title') + '" />', true);
		},
		extimageUploadCallbackLink: function()
		{
			if ($('#redactor_file_link').val() !== '')
			{
				var data = '<img src="' + $('#redactor_file_link').val() + '" />';
				this._extimageSet(data, true);
			}
			else
			{
				this.modalClose();
			}
		},
		extimageUploadCallback: function(data)
		{
                    if(this.opts.defaultUplthumb===true)
			this._extimageSet(data);
                        else
                            this._extimageSetWl(data);
		},
		_extimageSet: function(json, link)
		{
			this.restoreSelection();

			if (json !== false)
			{
				var html = '';
				if (link !== true)
				{
                                    var ldop=(this.opts.thumbLinkRel)?'rel="'+this.opts.thumbLinkRel+'" ':'';
                                    ldop+=(this.opts.thumbLinkClass)?'class="'+this.opts.thumbLinkClass+'" ':'';
					html = '<a href="' + json.link + '" '+ldop+'><img src="' + json.filelink + '" class="'+this.opts.thumbClass+'"/></a>';
				}
				else
				{
					html = json;
				}

				this.execCommand('inserthtml', html);

				// upload image callback
				if (link !== true && typeof this.opts.imageUploadCallback === 'function')
				{
					this.opts.imageUploadCallback(this, json);
				}
			}

			this.modalClose();
			this.observeImages();
		},
                _extimageSetWl: function(json)
		{
			this.restoreSelection();

			if (json !== false)
			{
				var html = '';
			          html = '<img src="' + json.filelink + '" />';

				this.execCommand('inserthtml', html);

				// upload image callback
				if (typeof this.opts.imageUploadCallback === 'function')
				{
					this.opts.imageUploadCallback(this, json);
				}
			}

			this.modalClose();
			this.observeImages();
		},
                imageDelete: function(el)
		{
                    var parent = $(el).parent();
                    if ($(parent).get(0).tagName === 'A')
                    {
			$(parent).remove();
		    }
				 
			$(el).remove();
			this.modalClose();
			this.syncCode();
		},
                imageEdit: function(e)
		{
			var $el = $(e.target);
			var parent = $el.parent();

			var callback = $.proxy(function()
			{
				$('#redactor_file_alt').val($el.attr('alt'));
				$('#redactor_image_edit_src').attr('href', $el.attr('src'));
				$('#redactor_form_image_align').val($el.css('float'));
                                $('#redactor_form_image_class').val($el.attr('class'));
                                if ($(parent).get(0).tagName === 'A')
                                 {
                                     $('#redactor_form_link_class').val($(parent).attr('class'));
                                      $('#redactor_form_link_rel').val($(parent).attr('rel'));
                                      $('#redactor_file_link').val($(parent).attr('href'));
                                }
    
				$('#redactor_image_delete_btn').click($.proxy(function() { this.imageDelete($el); }, this));
				$('#redactorSaveBtn').click($.proxy(function() { this.imageSave($el); }, this));

			}, this);

			this.modalInit(redactor.image,this.opts.extimgmodal, 380, callback);

		},
                imageSave: function(el)
		{
			var parent = $(el).parent();

			$(el).attr('alt', $('#redactor_file_alt').val());
                        $(el).attr('class', $('#redactor_form_image_class').val());

			var floating = $('#redactor_form_image_align').val();

			if (floating === 'left')
			{
				$(el).css({ 'float': 'left', margin: '0 10px 10px 0' });
			}
			else if (floating === 'right')
			{
				$(el).css({ 'float': 'right', margin: '0 0 10px 10px' });
			}
			else
			{
				$(el).css({ 'float': 'none', margin: '0' });
			}

			// as link
			var link = $.trim($('#redactor_file_link').val());
                        var lrel=$.trim($('#redactor_form_link_rel').val());
                        var lclass=$.trim($('#redactor_form_link_class').val());
                        var ldop=(lrel!=='')?'rel="'+lrel+'" ':'';
                        ldop+=(lclass!=='')?'class="'+lclass+'" ':'';
			if (link !== '')
			{
				if ($(parent).get(0).tagName !== 'A')
				{
					$(el).replaceWith('<a href="' + link + '" '+ldop+'>' + this.outerHTML(el) + '</a>');
				}
				else
				{
					$(parent).attr('href', link);
                                        $(parent).attr('class', lclass);
                                         $(parent).attr('rel',lrel);
				}
			}
			else
			{
				if ($(parent).get(0).tagName === 'A')
				{
					$(parent).replaceWith(this.outerHTML(el));
				}
			}

			this.modalClose();
			this.observeImages();
			this.syncCode();

		}
}
