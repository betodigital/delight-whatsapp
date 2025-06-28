<?php
/**
 * Plugin Name: Delight WhatsApp
 * Plugin URI: https://www.robertogrozinski.com/delight-whatsapp
 * Description: Adiciona um ícone flutuante do WhatsApp no rodapé do site com opções avançadas de GTM, GA, analytics, escaneamento automático e mensagem de saudação.
 * Version: 2.0.0
 * Author: Roberto Grozinski
 * Author URI: https://www.robertogrozinski.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: delight-whatsapp
 * Domain Path: /languages
 *
 * @package Delight_WhatsApp
 * @author Roberto Grozinski <roberto.grozinski@gmail.com>
 */

// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

define('DELIGHT_WHATSAPP_VERSION', '2.0.0');
define('DELIGHT_WHATSAPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DELIGHT_WHATSAPP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Incluir arquivos necessários
require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'includes/class-delight-whatsapp.php';
require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'admin/class-delight-whatsapp-admin.php';
require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'public/class-delight-whatsapp-public.php';

// Registrar hooks de ativação e desativação
register_activation_hook(__FILE__, array('Delight_WhatsApp', 'activate'));
register_deactivation_hook(__FILE__, array('Delight_WhatsApp', 'deactivate'));

// Inicializar o plugin
function run_delight_whatsapp() {
    $plugin = new Delight_WhatsApp();
    $plugin->run();
}
run_delight_whatsapp();