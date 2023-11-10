<?php

use CnnPluginBr\Admin\LotteryPost;
use CnnPluginBr\Utils\LotteryUtils;

/** @var $template */
$lottery_data = $template['args'];
$loteria      = '';
$concurso     = '';
extract( $lottery_data );
$get_results = LotteryPost::getLottery( $loteria, $concurso );

if ( ! empty( $get_results ) ):
    $get_results = json_decode( $get_results, true );
    extract( $get_results );
    $week_day = date_i18n( 'l', strtotime( LotteryUtils::normalize_date( $data ) ) ); ?>
    
    <div class="lottery <?php echo esc_attr( $loteria ); ?>">
        <div class="lottery-header">
            <h3>Concurso <?php echo esc_html( $concurso ); ?>
                <span class="divider">&bull;</span>
                <span class="week-day"><?php echo esc_html( $week_day ) ?></span>
                <?php echo esc_html( $data ); ?>
            </h3>
        </div><!-- .lottery-header -->
        
        <div class="lottery-numbers">
            <?php foreach ( $dezenas as $dezena ): ?>
                <span><?php echo esc_html( $dezena ); ?></span>
            <?php endforeach; ?>
        </div><!-- .lottery-numbers -->
        
        <div class="lottery-prize">
            <strong>Prêmio</strong>
            <p>
                <?php if ( ! empty( $premiacoes[0]['valorPremio'] ) ): ?>
                    R$ <?php echo esc_html( LotteryUtils::forma_money( $premiacoes[0]['valorPremio'] ) ); ?>
                <?php else: ?>
                    Acumulou!
                <?php endif; ?>
            </p>
        </div><!-- .lottery-prize -->
        
        <div class="lottery-winners">
            <table>
                <thead>
                <tr>
                    <th>Faixas</th>
                    <th>Ganhadores</th>
                    <th>Prêmio</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ( $premiacoes as $premios ): ?>
                    <tr>
                        <td><?php echo esc_html( $premios['descricao'] ); ?></td>
                        <td><?php echo esc_html( $premios['ganhadores'] ); ?></td>
                        <td>R$ <?php echo esc_html( LotteryUtils::forma_money( $premios['valorPremio'] ) ); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div><!-- .lottery-winners -->
    
    </div><!-- .lottery-container -->
<?php else: ?>
    <div class="lottery-empty">
        Nenhum resultado para a loteria/concurso selecionado...
    </div><!-- .lottery-empty -->
<?php endif; ?>
