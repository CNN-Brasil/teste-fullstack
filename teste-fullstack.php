<?php
/**
 * Plugin Name: Loterias Caixa
 * Plugin URI:  https://github.com/estevaoacioli/teste-fullstack
 * Description: Exibe os resultados das Loterias da Caixa via shortcode.
 * Version: 1.0
 * Author: Estevao Acioli
 * Author URI: https://www.linkedin.com/in/estevao-acioli-ce/
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: loterias-caixa
 *
 * @package LoteriasCaixa
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Load translations
add_action('plugins_loaded', 'lotcaixa__load_textdomain');
function lotcaixa__load_textdomain() {
    load_plugin_textdomain('loterias-caixa', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// Definir Constants
define ('LOTCAIXA_VERSION', '1.0.0');
define('LOTCAIXA_SITE_URL', get_home_url());
define('LOTCAIXA_PATH', plugin_dir_path(__FILE__));
define('LOTCAIXA_URL', plugin_dir_url(__FILE__));

$loterias_validas = array(
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
define('LOTERIAS_VALIDAS', $loterias_validas);

require_once( 'src/includes/functions.php' );
require_once( 'class.php' );
require_once( 'src/includes/api-loteria.php' );
require_once( 'src/includes/cpt-loteria.php' );
require_once( 'src/includes/documentation.php' );
require_once( 'src/views/view-resultado.php' );

