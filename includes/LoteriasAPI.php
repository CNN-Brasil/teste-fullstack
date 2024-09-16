<?php
// Função para buscar resultados da API das Loterias Caixa
function lotteryResults($loteria, $concurso) {

    // Define o transiente para cachear os resultados
    $transient_key = "loteria_{$loteria}_{$concurso}";
    $cached_result = get_transient($transient_key);

    // Retorna o resultado cacheado se estiver disponível
    if ($cached_result !== false) {
        return $cached_result;
    }

    // Define a URL da API
    $url = "https://loteriascaixa-api.herokuapp.com/api/{$loteria}/" . ($concurso === 'latest' ? 'latest' : $concurso);

    // Faz a requisição GET para a API
    $response = wp_remote_get($url);

    // Verifica se houve erro na requisição
    if (is_wp_error($response)) {
        return false;
    }

    // Pega o corpo da resposta e converte para array
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Verifica se a resposta contém dados
    if (empty($data)) {
        return false;
    }

    // Cachear os resultados por 12 horas
    set_transient($transient_key, $data, 12 * HOUR_IN_SECONDS);

    return $data;
}
?>
