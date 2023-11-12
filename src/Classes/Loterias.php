<?php
namespace Cnnbr\TesteFullstack\Classes;

/**
 * Resultados da Loteria
 */
class Loterias {

    private string  $slug = "loterias";
    public  string  $loteria;
    public  string  $concurso;

    public function __construct() 
    {
        
        add_action( 'init', array( $this, 'registerLoteriasPostType' ) );
        add_shortcode( 'loteriasResultados', array( $this, 'renderLoteriasResultados' ) );
        add_action('wp_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    /**
     * Create Custom post type Loterias
     *
     * @return void
     */
    public function registerLoteriasPostType() 
    {
        register_post_type( $this->slug,
            array(
                'labels' => array(
                    'name' => __( 'Loterias' ),
                    'singular_name' => __( 'Loteria' )
                ),
                'public' => true,
                'has_archive' => false,
                'rewrite' => array('slug' => 'loterias'),
                'menu_icon'             => 'dashicons-heart',
                'supports'              => false,
                'hierarchical'          => false,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'menu_position'         => 5,
                'show_in_admin_bar'     => false,
                'show_in_nav_menus'     => false,
                'can_export'            => true,
                'exclude_from_search'   => true,
                'publicly_queryable'    => true,
                'capability_type'       => 'post',
                'capabilities' => array(
                    'create_posts' => false, // Removes support for the "Add New"
                )
            )
        );
    }

    /**
     * Create Shortcodes for Resultados loteria
     *
     * @param [type] $atts
     * @return void
     */
    public function renderLoteriasResultados( $atts ) 
    {
        $atts = shortcode_atts( array(
            'loteria' => 'megasena',
            'concurso' => 'latest',
        ), $atts );
        
        $this->loteria = $atts['loteria'];
        $this->concurso = $atts['concurso'];

        
        if($this->checkTypeLoteria()){
            return;
        }

        // Results
        $results = $this->prepareDataShortcode( $atts );

        ob_start();
        include CNNBR_PLUGIN_DIR . '/src/Resources/templates/resultados-loterias.php';
        return ob_get_clean();
    }

    /**
     * Check if the lottery is a permitted amount
     *
     * @return void
     */
    public function checkTypeLoteria()
    {
        return !in_array($this->loteria, $this->getTypeLoterias());
    }

    /**
     * Prepare Data Shortcode
     *
     * @return void
     */    
    public function prepareDataShortcode()
    {
        $LoteriaDTO = new LoteriaDTO($this->loteria, $this->concurso);
        $resultado = new Resultados($LoteriaDTO);
        return $resultado->getResults();
    }

    /**
     * Enfileirar scripts que serão utiliados
     *
     * @return void
     */
    public function enqueueScripts() : void
    {
        // Scripts
        wp_enqueue_script('jquery');
        wp_enqueue_script('teste-fullstack-app-js', CNNBR_PLUGIN_URL.'dist/app.js', array('jquery'), '1.0', true);

        // Styles
        wp_enqueue_style( 'teste-fullstack-app-font', '//fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;800;900&display=swap', array(), '' );
        wp_enqueue_style( 'teste-fullstack-app-styles', CNNBR_PLUGIN_URL.'dist/app.css', array(), '' );
        
    }

    /**
     * Types Loteria
     *
     * @return array
     */
    private function getTypeLoterias() :array
    {
        return array(
            "maismilionaria",
            "megasena",
            "lotofacil",
            "quina",
            "lotomania",
            "timemania",
            "duplasena",
            "federal",
            "diadesorte",
            "supersete"
        );
    }
}
