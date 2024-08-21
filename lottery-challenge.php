<?php
/**
 * Plugin Name: Lottery Challenge
 * Plugin URI: https://github.com/rafaelscouto/lottery-challenge
 * Description: Desafio prático para desenvolvedores Fullstack com PHP - CNN Brasil.
 * Author: Rafael Couto
 * Author URI: https://rafaelscouto.com.br
 * Version: 1.0
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lottery-challenge
 */

namespace LotteryChallenge;

// Impede o acesso direto ao arquivo
if(!defined('ABSPATH')){
    exit;
}

// Requer o arquivo autoload do Composer, se disponível
if(file_exists(__DIR__ . '/vendor/autoload.php')){
    require_once __DIR__ . '/vendor/autoload.php';
}

// Inicializa o plugin
LotteryPlugin::init();

// Hook de ativação do plugin.
register_activation_hook(__FILE__, ['LotteryChallenge\LotteryPlugin', 'activate']);

// Hook de desativação do plugin.
register_deactivation_hook(__FILE__, ['LotteryChallenge\LotteryPlugin', 'deactivate']);

// Hook de desinstalação do plugin.
register_uninstall_hook(__FILE__, ['LotteryChallenge\LotteryPlugin', 'uninstall']);