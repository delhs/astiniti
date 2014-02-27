var intID = setInterval(function(){
	if( typeof(core.adminAirMode)!=undefined ){
		clearInterval(intID);

		core.initObject = {
			
			init:function(){
				clearInterval(intID);
				core.adminAirMode = true;
				this.redactorStart();
				this.mmenuSortableStart();
				this.setEvents();
			},
			setEvents:function(){
				$("a[href!='^http'][href!='!#'][href!='#'][href!='javascript:;'][rel!='tofancy'][data-type!='click'][href]").on("click", function(e){

					e.preventDefault();
		
					var href = $(this).attr("href");
					var target = $(this).attr("target");
					var pattern = document.location.host;

					if( href.match(/^javascript/)!=null ) return;

					var s = href.replace(/^http:\/\//gi,'');
					s = s.replace(/^www/gi,'');
					s = s.replace(/^\/\//gi,'');
					s = s.replace(pattern, '');
					
					if(!s.match(/^\//gi)){
						window.parent.admin.infoBox("Переход по внешней ссылке &laquo;"+href+"&raquo;");
						return;
					}
										
					if( target!=undefined && target=='_blank' ){
						window.parent.admin.infoBox("Открытие страницы в новой вкладке браузера");
						return;
					}
					
					window.parent.admin.block();
					$('.air', window.parent.document).hide();
					var src =  href + "/edited/"+Math.floor(Math.random()*9999)+"/";
					src = src.replace(/\/\//gi,'/');
					window.parent.admin.ajax("content", {url:src, airMode:"airMode", action: "getId"}, function(id){
						if( id==" " )return;
						window.parent.admin.setPageId( id.trim(), function(){
							$("#air", window.parent.document).attr("src",src);
							
						});

					});
				});
				
			},
			redactorStart:function(){
				
				var $textarea = $("textarea#air_mode_content");
				if( $textarea==undefined ) return;
				
				if( $textarea.hasClass("-ckeditor-inst") ) return;
				var editorId = "editor_" + Math.floor( Math.random()*9999 );
	
				$textarea.addClass("-ckeditor-inst").attr("id", editorId);
				
				var editorHandle = CKEDITOR.inline(editorId, {
					language : 'ru',
					toolbarGroups : [
						{ name: 'mode'},
						{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
						{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
						{ name: 'links' },
						{ name: 'insert' },
						{ name: 'colors' },
						{ name: 'styles' },
						{ name: 'others' },
						{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
						{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
						{ name: 'tools' }
					],
					extraPlugins : 'elfinder,dragresize,savecontent,youtube,loremIpsum',
					extraAllowedContent :{
						'img':{
							attributes:'data-fancy,data-type'
						}
					},
					filebrowserBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=file',
					filebrowserImageBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=image',
					filebrowserFlashBrowseUrl : '/system/admin/editor/js/elfinder.html?mode=flash'

				});

				editorHandle.on('change', function(){
					$textarea.val(CKEDITOR.instances[editorId].getData());
				});
			},
			mmenuSortableStart:function(){
				$("nav:first ul").sortable({
					stop: function(event, ui){
						var dataArray = {};
						var range = 0;
						$(this).children("li").each(function(){	
							var id = $(this).children("a[data-action]").attr("data-action");
							dataArray[ range ] = id;
							range++;
						});
						
						dataArray['action'] = 'rangeChange';
						window.parent.admin.ajax("parts", dataArray, function(){
							window.parent.admin.infotip('Порядок следования разделов успешно изменен');
						});
					}		
				});	
			}
		}
	}
}, 200);