<?php
/**
 * Classe principal do plugin
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/includes
 */

class Delight_WhatsApp {

    /**
     * Instância única da classe
     */
    protected static $instance = null;

    /**
     * Versão do plugin
     */
    protected $version;

    /**
     * Nome do plugin
     */
    protected $plugin_name;

    /**
     * Construtor da classe
     */
    public function __construct() {
        $this->version = DELIGHT_WHATSAPP_VERSION;
        $this->plugin_name = 'delight-whatsapp';

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_ajax_hooks();
    }

    /**
     * Retorna instância única da classe
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Carrega as dependências necessárias
     */
    private function load_dependencies() {
        require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'includes/class-delight-whatsapp-scanner.php';
        require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'includes/class-delight-whatsapp-analytics.php';
    }

    /**
     * Define os hooks do admin
     */
    private function define_admin_hooks() {
        $plugin_admin = new Delight_WhatsApp_Admin($this->get_plugin_name(), $this->get_version());

        add_action('admin_enqueue_scripts', array($plugin_admin, 'enqueue_scripts'));
        add_action('admin_menu', array($plugin_admin, 'add_admin_menu'));
        add_action('admin_init', array($plugin_admin, 'settings_init'));
    }

    /**
     * Define os hooks do frontend
     */
    private function define_public_hooks() {
        $plugin_public = new Delight_WhatsApp_Public($this->get_plugin_name(), $this->get_version());

        add_action('wp_enqueue_scripts', array($plugin_public, 'enqueue_scripts'));
        add_action('wp_head', array($plugin_public, 'add_gtm_head'));
        add_action('wp_body_open', array($plugin_public, 'add_gtm_body'));
        add_action('wp_head', array($plugin_public, 'add_ga'));
        add_action('wp_head', array($plugin_public, 'add_utm_script'));
        add_action('wp_footer', array($plugin_public, 'add_button'));
    }

    /**
     * Define os hooks AJAX
     */
    private function define_ajax_hooks() {
        $plugin_admin = new Delight_WhatsApp_Admin($this->get_plugin_name(), $this->get_version());
        
        add_action('wp_ajax_delight_whatsapp_auto_scan', array($plugin_admin, 'handle_auto_scan'));
        add_action('wp_ajax_delight_whatsapp_track_click', array('Delight_WhatsApp_Analytics', 'handle_click_tracking'));
        add_action('wp_ajax_nopriv_delight_whatsapp_track_click', array('Delight_WhatsApp_Analytics', 'handle_click_tracking'));
    }

    /**
     * Executa o plugin
     */
    public function run() {
        // Plugin já está sendo executado através dos hooks
    }

    /**
     * Retorna o nome do plugin
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * Retorna a versão do plugin
     */
    public function get_version() {
        return $this->version;
    }

    /**
     * Função de ativação do plugin
     */
    public static function activate() {
        add_option('delight_whatsapp_phone', '');
        add_option('delight_whatsapp_position', 'right');
        add_option('delight_whatsapp_vertical_position', '20');
        add_option('delight_whatsapp_gtm_id', '');
        add_option('delight_whatsapp_ga_id', '');
        add_option('delight_whatsapp_greeting_enabled', '0');
        add_option('delight_whatsapp_greeting_message', 'Olá! Como posso ajudar?');
        add_option('delight_whatsapp_auto_page_info', '0');
        add_option('delight_whatsapp_utm_tracking', '0');
        add_option('delight_whatsapp_auto_scan_enabled', '0');

        // Criar tabela de analytics
        Delight_WhatsApp_Analytics::create_analytics_table();
    }

    /**
     * Função de desativação do plugin
     */
    public static function deactivate() {
        delete_option('delight_whatsapp_phone');
        delete_option('delight_whatsapp_position');
        delete_option('delight_whatsapp_vertical_position');
        delete_option('delight_whatsapp_gtm_id');
        delete_option('delight_whatsapp_ga_id');
        delete_option('delight_whatsapp_greeting_enabled');
        delete_option('delight_whatsapp_greeting_message');
        delete_option('delight_whatsapp_auto_page_info');
        delete_option('delight_whatsapp_utm_tracking');
        delete_option('delight_whatsapp_auto_scan_enabled');

        // Remover tabela de analytics
        Delight_WhatsApp_Analytics::drop_analytics_table();
    }

    /**
     * Função para log (útil para depuração)
     */
    public static function log($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true));
            } else {
                error_log($message);
            }
        }
    }
}