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
    
    const validateLoterias = () => {
        const loteria = document.querySelector('#modal_loteria_select').value;
        const checkbox = document.querySelector('#modal_loteria_checkbox').checked;
        const concurso = checkbox ? 'ultimo' : document.querySelector('#modal_loteria_concurso').value;
        const shortcode = `[loteria loteria="${loteria}" concurso="${concurso}"]`;
        
        if (loteria !== '' && concurso !== '' && concurso !== null) {
            tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
            switchModal();
        } else {
            document.querySelector('.modalLoteriasPlugin span').classList.add('error');
            setTimeout(() => {
                document.querySelector('.modalLoteriasPlugin span').classList.remove('error');
            }, 3000);
        }
    }
      
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

    var divModalLoteriasValidate = document.createElement("span");
    divModalLoteriasValidate.innerText = "Selecionar loteria, numero ou último concurso";
    divModalLoteriasContent.appendChild(divModalLoteriasValidate);

    var divModalLoteriasHeader = document.createElement("h3");
    divModalLoteriasHeader.innerText = "Loterias";
    divModalLoteriasContent.appendChild(divModalLoteriasHeader);

    const modalLoteriasSelect = document.createElement("select");
    modalLoteriasSelect.id = "modal_loteria_select";

    selectLoterias.forEach(element => {

        var select = document.createElement("option");
        select.value = element;
        select.text = element;
        modalLoteriasSelect.add(select, null);
        
    });

    var divModalLoteriasselect = document.createElement("div");
    divModalLoteriasselect.innerText = "Selecione o tipo:";
    divModalLoteriasselect.appendChild(modalLoteriasSelect);
    divModalLoteriasContent.appendChild(divModalLoteriasselect);

    var divModalLoteriasseCheckbox = document.createElement("div");
    divModalLoteriasContent.appendChild(divModalLoteriasseCheckbox);
   
    var divModalLoteriasCheckbox = document.createElement("input");
    divModalLoteriasCheckbox.type = "checkbox";
    divModalLoteriasCheckbox.id = "modal_loteria_checkbox";
    divModalLoteriasseCheckbox.appendChild(divModalLoteriasCheckbox);

    var divModalLoteriasCheckboxLabel = document.createElement("label");
    divModalLoteriasCheckboxLabel.innerText = "Exibir último concurso";
    divModalLoteriasCheckboxLabel.htmlFor = "modal_loteria_checkbox";
    divModalLoteriasseCheckbox.appendChild(divModalLoteriasCheckboxLabel);


    var divModalLoteriasConcursoSeparador = document.createElement("div");
    divModalLoteriasConcursoSeparador.classList.add("separador");
    divModalLoteriasContent.appendChild(divModalLoteriasConcursoSeparador);

    var divModalLoteriasseNumber = document.createElement("div");
    divModalLoteriasseNumber.innerText = "Nº do concurso:";
    divModalLoteriasContent.appendChild(divModalLoteriasseNumber);

    var divModalLoteriasConcurso = document.createElement("input");
    divModalLoteriasConcurso.type = "number";
    divModalLoteriasConcurso.id = "modal_loteria_concurso";
    divModalLoteriasseNumber.appendChild(divModalLoteriasConcurso);

    var divModalLoteriasSubmit = document.createElement("input");
    divModalLoteriasSubmit.type = "submit";
    divModalLoteriasSubmit.value = "Inserir";
    divModalLoteriasSubmit.id = "modal_loteria_submit";
    divModalLoteriasContent.appendChild(divModalLoteriasSubmit);

    document.body.appendChild(divModalLoterias);

    const btn = document.querySelector('#modal_loteria_submit');
    btn.addEventListener('click', validateLoterias)
      

    tinymce.PluginManager.add('loteria_button', function(editor, url) {
        editor.addButton('loteria_button', {
            text: 'Inserir Loteria',
            icon: 'loteria-icon',
            onclick: switchModal
        });
    });
})();