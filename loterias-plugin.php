<?php
/*
 * Plugin Name:     Desafio Fullstack CNN Brasil - Loterias
 * Description:     O teste consiste em criar um plugin WordPress com um shortcode que exibirá os resultados dos jogos das Loterias Caixa.
 * Plugin URI:      https://github.com/elisonsilva/teste-fullstack
 * Version:         1.0.0
 * Author:          Welison silva
 * Author URI:      https://github.com/elisonsilva
 * Text Domain:     testfullstackcnnbrasil
 * Domain Path:     /languages
*/

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/autoload.php';

// Plugin name
define( 'CNNBR_NAME',			'Desafio Fullstack CNN Brasil [Welison silva]' );

// Plugin version
define( 'CNNBR_VERSION',		'1.0.0' );

// Plugin Root File
define( 'CNNBR_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'CNNBR_PLUGIN_BASE',	plugin_basename( CNNBR_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'CNNBR_PLUGIN_DIR',	plugin_dir_path( CNNBR_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'CNNBR_PLUGIN_URL',	plugin_dir_url( CNNBR_PLUGIN_FILE ) );


use Cnnbr\TesteFullstack\Classes\Loterias;

$LoteriasPlugin = new Loterias();