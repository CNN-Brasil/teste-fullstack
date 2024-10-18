<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Query para pegar todos os resultados armazenados no CPT
$query = new WP_Query( array(
    'post_type' => 'loterias',
    'posts_per_page' => -1,
    'orderby' => 'meta_value_num',
    'meta_key' => '_loteria_concurso',
    'order' => 'DESC'
) );

if ( $query->have_posts() ) : 
    $jogos = array();
    ?>

    <div class="loterias-container">

        <!-- Lista de Jogos no Topo -->
        <div id="jogos-list">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $jogo = get_post_meta( get_the_ID(), '_loteria_jogo', true );
                // Guarda o jogo para os botões
                $jogos[ $jogo ] = $jogo;
            endwhile; 
            wp_reset_postdata();
            ?>
            <?php foreach ( $jogos as $jogo_slug => $jogo_nome ) : ?>
                <button class="jogo-item" data-jogo="<?php echo esc_attr( $jogo_slug ); ?>">
                    <?php echo ucfirst( esc_html( $jogo_nome ) ); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Área de Resultados -->
        <div id="resultado-container">
            <?php
            // Exibir inicialmente o resultado da Mega-Sena
            while ( $query->have_posts() ) : $query->the_post();
                $jogo = get_post_meta( get_the_ID(), '_loteria_jogo', true );

                // Exibir apenas o resultado da Mega-Sena inicialmente
                if ( $jogo === 'megasena' ) :
                    ?>
                    <div class="resultado" id="<?php echo esc_attr( $jogo ); ?>" style="display:block;">
                        <?php the_content(); ?>
                    </div>
                    <?php
                else:
                    ?>
                    <div class="resultado" id="<?php echo esc_attr( $jogo ); ?>" style="display:none;">
                        <?php the_content(); ?>
                    </div>
                    <?php
                endif;
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

    </div>

<?php else : ?>
    <p>Nenhum resultado encontrado.</p>
<?php endif; ?>