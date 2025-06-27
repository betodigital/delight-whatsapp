<?php
/**
 * Executado quando o plugin é desinstalado
 *
 * @package Delight_WhatsApp
 */

// Se o uninstall não foi chamado pelo WordPress, sair
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Remover todas as opções do plugin
delete_option('delight_whatsapp_phone');
delete_option('delight_whatsapp_position');
delete_option('delight_whatsapp_vertical_position');
delete_option('delight_whatsapp_gtm_id');
delete_option('delight_whatsapp_ga_id');
delete_option('delight_whatsapp_greeting_enabled');
delete_option('delight_whatsapp_greeting_message');

// Limpar cache se necessário
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
}