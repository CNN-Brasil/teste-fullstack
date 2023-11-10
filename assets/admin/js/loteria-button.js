(function() {

    const switchModal = () => {
        const modal = document.querySelector('.modalLoteriasPlugin')
        const actualStyle = modal.style.display
        if(actualStyle == 'block') {
          modal.style.display = 'none'
        }
        else {
          modal.style.display = 'block'
        }
      }
      
    //   const btn = document.querySelector('.modalBtn')
    //   btn.addEventListener('click', switchModal)
      
      window.onclick = function(event) {
          const modal = document.querySelector('.modalLoteriasPlugin')
        if (event.target == modal) {
          switchModal()
        }
      }

    var divModalLoterias = document.createElement("div");
    divModalLoterias.classList.add("modalLoteriasPlugin");

    var divModalLoteriasContent = document.createElement("div");
    divModalLoteriasContent.classList.add("content");
    divModalLoterias.appendChild(divModalLoteriasContent);

    var divModalLoteriasHeader = document.createElement("h3");
    divModalLoteriasHeader.innerText = "Selecione a loteria";
    divModalLoteriasContent.appendChild(divModalLoteriasHeader);

    var divModalLoteriasCheckbox = document.createElement("input");
    divModalLoteriasCheckbox.type = "checkbox";
    divModalLoteriasCheckbox.id = "modal_loteria_checkbox";
    divModalLoteriasContent.appendChild(divModalLoteriasCheckbox);


    const divModalLoteriasSelect = document.createElement("select");

    selectLoterias.forEach(element => {

        var select = document.createElement("option");
        select.value = element;
        select.text = element;
        divModalLoteriasSelect.add(select, null);
        
    });

    var divModalLoteriasCheckboxLabel = document.createElement("label");
    divModalLoteriasCheckboxLabel.innerText = "Exibir ultimo concurso";
    divModalLoteriasCheckboxLabel.htmlFor = "modal_loteria_checkbox";
    divModalLoteriasContent.appendChild(divModalLoteriasCheckboxLabel);


    divModalLoteriasContent.appendChild(divModalLoteriasSelect);
    document.body.appendChild(divModalLoterias);

    tinymce.PluginManager.add('loteria_button', function(editor, url) {
        editor.addButton('loteria_button', {
            text: 'Inserir Loteria',
            icon: 'loteria-icon',
            onclick: switchModal
        });
    });
})();