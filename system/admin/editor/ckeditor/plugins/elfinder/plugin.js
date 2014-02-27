CKEDITOR.plugins.add('elfinder',{
    init: function(editor){
        var cmd = editor.addCommand('elfinder', {
            closeElfinder:function(){
                
                if( $("#elfinder").size()==0 ) return;
                $("audio").remove();
                $('.elfinder-contextmenu-ltr').remove();
                $('.elfinder-quicklook').remove();
                $('#elfinder').fadeOut(200, function(){
                    $(this).remove();
                });
            },
            exec:function(editor){
                var plug = this;

                var opts = {
                        url : '/system/admin/editor/php/connector.files.php',
                        lang: 'ru',
                        getFileCallback:function(file){
                            var parts;
                            var ext = ( parts = file.split("/").pop().split(".") ).length > 1 ? parts.pop() : "";

                            if( ext=='bmp' || ext=='gif' || ext=='tiff' || ext=='jpg' || ext=='jpeg' || ext=='png' ){
                                editor.insertHtml('<img fancy=\'on\' title=\'\' alt=\'\' src=\''+file+'\' />');
                            }else if(ext=='swf'){
                                editor.insertHtml('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="quality" value="high" /><param name="movie" value="'+file+'" /><embed pluginspage="http://www.macromedia.com/go/getflashplayer" quality="high" src="'+file+'" type="application/x-shockwave-flash"></embed></object>');
                            }else{
                                editor.insertHtml('<a title="Скачать файл '+file+' href="'+file+'">'+file+'</a>');
                            }

                            plug.closeElfinder();
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
                    plug.closeElfinder();
                });
            }
        });

        cmd.modes = { wysiwyg : 1, source: 0 };
        editor.ui.addButton('elfinder',{
            label: 'Файловый менеджер',
            command: 'elfinder',
            toolbar: 'insert'
        });
    },
    icons:'elfinder'
});