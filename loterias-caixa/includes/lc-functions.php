<?php
function lc_formatFaixasName($data) {

    switch ($data) {
        case 1:
            return "Sena";
        case 2:
            return "Quina";
        case 3:
            return "Quadra";
        default:
            return "Faixa não encontrada";
    }

return "Faixa não encontrada";
}

function lc_formatDateName($data) {
    $dataObj = DateTime::createFromFormat('d/m/Y', $data);
    $dayWeekNumber = $dataObj->format('w');
    $dayWeekName = array(
        'Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'
    );
    $dayWeekName = $dayWeekName[$dayWeekNumber];
    return $dayWeekName;
}
?>