CKEDITOR.plugins.add('savecontent',{
    init: function(editor){
        var cmd = editor.addCommand('savecontent', {
            exec:function(editor){
                window.parent.admin.mode.air.saveContent();
            }
        });

        cmd.modes = { wysiwyg : 1, source: 0 };
        editor.ui.addButton('savecontent',{
            label: 'Сохранить контент',
            command: 'savecontent',
            toolbar: 'insert'
        });
    },
    icons:'savecontent'
});