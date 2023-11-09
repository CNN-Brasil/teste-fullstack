(function() {
    tinymce.PluginManager.add('loteria_button', function(editor, url) {
        editor.addButton('loteria_button', {
            text: 'Inserir Loteria',
            icon: 'loteria-icon',
            onclick: function() {
                editor.insertContent('[loteria concurso="5" tipo_concurso="megasena"]');
            }
        });
    });
})();