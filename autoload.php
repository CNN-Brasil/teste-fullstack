<?php 

function meu_plugin_autoload($class)
{
    // Prefixo para garantir que o autoloader não entre em conflito
    $prefix = 'loterias\\classes\\';
    
    // Verificar se a classe pertence ao seu namespace
    if (strpos($class, $prefix) === 0) {
        // Remover o prefixo do nome da classe
        $relative_class = substr($class, strlen($prefix));
        
        // Substituir namespaces por barras e criar o caminho completo do arquivo
        $class_file = plugin_dir_path(__FILE__) . 'classes/class-' . str_replace('\\', '/', $relative_class) . '.php';
        
        // Debug: Mostrar o caminho do arquivo
        // exit($class_file);

        if (file_exists($class_file)) {
            require_once $class_file;
        } else {
            error_log('Arquivo não encontrado: ' . $class_file);
        }
    }
}
spl_autoload_register('meu_plugin_autoload');