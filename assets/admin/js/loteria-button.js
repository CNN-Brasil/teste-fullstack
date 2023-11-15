(function () {

  const createModalElement = (type, properties) => {
    const element = document.createElement(type);
    Object.assign(element, properties);
    return element;
  }

  const divModalLoterias = createModalElement('div', { classList: ['modalLoteriasPlugin'] });
  const divModalLoteriasContent = createModalElement('div', { classList: ['content'] });
  const divModalLoteriasValidate = createModalElement('span', { innerText: 'Selecionar loteria, numero ou último concurso' });
  const divModalLoteriasHeader = createModalElement('h3', { innerText: 'Loterias' });
  const modalLoteriasSelect = createModalElement('select', { id: 'modal_loteria_select' });

  selectLoterias.forEach(element => {
      const option = createModalElement('option', { value: element, text: element });
      modalLoteriasSelect.add(option, null);
  });

  const divModalLoteriasselect = createModalElement('div', { innerText: 'Selecione o tipo:' });
  divModalLoteriasselect.appendChild(modalLoteriasSelect);

  const divModalLoteriasseCheckbox = createModalElement('div');
  const divModalLoteriasCheckbox = createModalElement('input', { type: 'checkbox', id: 'modal_loteria_checkbox' });
  const divModalLoteriasCheckboxLabel = createModalElement('label', { innerText: 'Exibir último concurso', htmlFor: 'modal_loteria_checkbox' });
  divModalLoteriasseCheckbox.append(divModalLoteriasCheckbox, divModalLoteriasCheckboxLabel);

  const divModalLoteriasConcursoSeparador = createModalElement('div', { classList: ['separador'] });

  const divModalLoteriasseNumber = createModalElement('div', { innerText: 'Nº do concurso:' });
  const divModalLoteriasConcurso = createModalElement('input', { type: 'number', id: 'modal_loteria_concurso' });
  divModalLoteriasseNumber.append(divModalLoteriasConcurso);

  const divModalLoteriasSubmit = createModalElement('input', { type: 'submit', value: 'Inserir', id: 'modal_loteria_submit' });

  divModalLoterias.appendChild(divModalLoteriasContent);
  divModalLoteriasContent.append(divModalLoteriasValidate, divModalLoteriasHeader, divModalLoteriasselect, divModalLoteriasseCheckbox, divModalLoteriasConcursoSeparador, divModalLoteriasseNumber, divModalLoteriasSubmit);

  document.body.appendChild(divModalLoterias);

  const modal = document.querySelector('.modalLoteriasPlugin');
  const loteriaSelect = document.querySelector('#modal_loteria_select');
  const checkbox = document.querySelector('#modal_loteria_checkbox');
  const concursoInput = document.querySelector('#modal_loteria_concurso');
  const errorSpan = document.querySelector('.modalLoteriasPlugin span');

  const switchModal = () => {
      modal.style.display = (modal.style.display === 'block') ? 'none' : 'block';
  }

  const validateLoterias = () => {
      const loteria = loteriaSelect.value;
      const concurso = checkbox.checked ? 'ultimo' : concursoInput.value;
      const shortcode = `[loteria tipo_concurso="${loteria}" concurso="${concurso}"]`;

      if (loteria && concurso) {
          tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
          switchModal();
      } else {
          errorSpan.classList.add('error');
          setTimeout(() => errorSpan.classList.remove('error'), 3000);
      }
  }

  window.onclick = (event) => {
      if (event.target === modal) {
          switchModal();
      }
  }


  const btn = document.querySelector('#modal_loteria_submit');
  btn.addEventListener('click', validateLoterias);

  tinymce.PluginManager.add('loteria_button', function (editor, url) {
      editor.addButton('loteria_button', {
          text: 'Inserir Loteria',
          icon: 'loteria-icon',
          onclick: switchModal
      });
  });
})();