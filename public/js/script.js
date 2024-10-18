jQuery(document).ready(function($) {

    // Quando o usuário clica em um botão de jogo
    $('.jogo-item').on('click', function() {
        var jogo = $(this).data('jogo');

        // Esconder todos os resultados
        $('.resultado').hide();

        // Exibir apenas o resultado do jogo selecionado
        $('#' + jogo).show();
    });

});