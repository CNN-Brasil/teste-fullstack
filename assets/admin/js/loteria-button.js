(function() {

    var divModalLoterias = document.createElement("div");
    divModalLoterias.classList.add("modalLoteriasPlugin");

    var divModalLoteriasContent = document.createElement("div");
    divModalLoteriasContent.classList.add("content");
    divModalLoterias.appendChild(divModalLoteriasContent);

    const divModalLoteriasSelect = document.createElement("select");

    selectLoterias.forEach(element => {

        var select = document.createElement("option");
        select.value = element;
        select.text = element;
        divModalLoteriasSelect.add(select, null);
        
    });

    divModalLoteriasContent.appendChild(divModalLoteriasSelect);
    document.body.appendChild(divModalLoterias);

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