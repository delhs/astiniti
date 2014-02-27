if(typeof(RedactorPlugins) === 'undefined') var RedactorPlugins = {};
RedactorPlugins.elfinder = {
	init: function ()
	{
		var that = this;
		this.buttonAdd('elfinder', 'Открыть файловый менеджер для выбора файла на сервере', 
			function( buttonName, buttonDOM, buttonObj, e)
			{
				var opts = {
						url : '/system/admin/editor/php/connector.files.php',
						lang: 'ru',
						getFileCallback:function(file){
							var parts;
							var ext = ( parts = file.split("/").pop().split(".") ).length > 1 ? parts.pop() : "";

							if( ext=='bmp' || ext=='gif' || ext=='tiff' || ext=='jpg' || ext=='jpeg' || ext=='png' ){
								that.pasteFile('<img fancy=\'on\' title=\'\' alt=\'\' src=\''+file+'\' />');
							}else if(ext=='swf'){
								that.pasteFileSwf(file);
							}else{
								that.pasteFile('<a title=\'Скачать файл '+file+'\' href=\''+file+'\' >'+file+'</a>');
							}

							that.close();
						}
				}	
					
				if( $("#elfinder").size()==0 ){
					var elfinder = $('<div>',{id:"elfinder"});
					$("body").append( elfinder );
					$("#elfinder").draggable({ containment: "body", scroll: false });
				}
				
			
				var elf = $('#elfinder').elfinder(opts).elfinder('instance');
				if( $("#elfinderclose").size()==0 ) $("#elfinder").prepend('<a id="elfinderclose" href="#"></a>');
				$(document).on("click", "#elfinderclose", function(e){
					e.preventDefault();
					that.close();
				});
			});
	},
	pasteFile: function (html){
		this.insertHtml(html);
	},
	pasteFileSwf: function(file){
		var id = Math.floor( Math.random() * 9999 );
		this.insertHtml('<object class=\'redactor-swfobj\' style=\'width:100px;height:50px;\' data-width=\'200\' data-height=\'50\' data-file=\''+file+'\' id=\''+id+'\'><object></object></object>');
		
	},
	close:function(){
		if( $("#elfinder").size()==0 ) return;
		$("audio").remove();
		$('.elfinder-contextmenu-ltr').remove();
		$('.elfinder-quicklook').remove();
		$('#elfinder').fadeOut(200, function(){
			$(this).remove();
		});	
	}
};