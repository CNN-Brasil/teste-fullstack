<?php if(!empty($results)) : ?>
    <section class="resultadosLoterias --<?php echo esc_html($results->loteria); ?>">
        <article class="resultadosLoterias__item">
            <div class="resultadosLoterias__head">
                <span>Concurso <?php echo esc_html($results->concurso); ?></span>
                <span>•</span> 
                <span><?php echo esc_html($results->diaSemana); ?> <?php echo esc_html($results->data); ?></span>
            </div>
            <div class="resultadosLoterias__dezenasOrdemSorteio">
                <?php foreach ($results->dezenas as $key => $dezena): ?>
                    <span class="resultadosLoterias__dezenas --<?php echo esc_html($dezena); ?>" title="Dezena <?php echo esc_attr($dezena); ?>"><?php echo esc_html($dezena); ?></span>
                <?php endforeach; ?>
            </div>
            <div class="resultadosLoterias__premio">
                <strong class="resultadosLoterias__premioTitle">Prêmio</strong> <br />
                <strong class="resultadosLoterias__premioValor">R$ <?php echo number_format(esc_html($results->valorEstimadoProximoConcurso),2,",","."); ?></strong>
            </div>
            <div class="resultadosLoterias__ganhadores">
                <div class="resultadosLoterias__ganhadoresItem --head">
                    <span>Faixas</span>
                    <span>Ganhadores</span>
                    <span>Prêmio</span>
                </div>
                <?php foreach ($results->premiacoes as $key => $premio): ?>
                    <div class="resultadosLoterias__ganhadoresItem">
                        <span><?php echo esc_html($premio->faixa); ?></span>
                        <span><?php echo esc_html($premio->ganhadores); ?></span>
                        <span>R$ <?php echo number_format(esc_html($premio->valorPremio), 2,",","."); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </article>
    </section>
<?php endif; ?>