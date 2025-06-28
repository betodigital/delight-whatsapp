<?php
/**
 * Classe para analytics e tracking
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/includes
 */

class Delight_WhatsApp_Analytics {

    /**
     * Registra um clique no WhatsApp
     */
    public static function register_click($page_url = '', $page_title = '', $utm_params = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'delight_whatsapp_clicks';
        
        $data = array(
            'click_date' => current_time('mysql'),
            'page_url' => sanitize_url($page_url),
            'page_title' => sanitize_text_field($page_title),
            'utm_source' => isset($utm_params['utm_source']) ? sanitize_text_field($utm_params['utm_source']) : '',
            'utm_medium' => isset($utm_params['utm_medium']) ? sanitize_text_field($utm_params['utm_medium']) : '',
            'utm_campaign' => isset($utm_params['utm_campaign']) ? sanitize_text_field($utm_params['utm_campaign']) : '',
            'utm_term' => isset($utm_params['utm_term']) ? sanitize_text_field($utm_params['utm_term']) : '',
            'utm_content' => isset($utm_params['utm_content']) ? sanitize_text_field($utm_params['utm_content']) : '',
            'user_ip' => self::get_user_ip(),
            'user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? sanitize_text_field($_SERVER['HTTP_USER_AGENT']) : ''
        );
        
        $wpdb->insert($table_name, $data);
    }

    /**
     * Obtém o IP do usuário
     */
    private static function get_user_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    /**
     * AJAX handler para registrar clique
     */
    public static function handle_click_tracking() {
        check_ajax_referer('delight_whatsapp_click', 'nonce');
        
        $page_url = isset($_POST['page_url']) ? sanitize_url($_POST['page_url']) : '';
        $page_title = isset($_POST['page_title']) ? sanitize_text_field($_POST['page_title']) : '';
        $utm_params = isset($_POST['utm_params']) ? $_POST['utm_params'] : array();
        
        self::register_click($page_url, $page_title, $utm_params);
        
        wp_send_json_success(array('message' => 'Click registered'));
    }

    /**
     * Cria tabela de analytics
     */
    public static function create_analytics_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'delight_whatsapp_clicks';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            click_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            page_url varchar(500) DEFAULT '' NOT NULL,
            page_title varchar(200) DEFAULT '' NOT NULL,
            utm_source varchar(100) DEFAULT '' NOT NULL,
            utm_medium varchar(100) DEFAULT '' NOT NULL,
            utm_campaign varchar(100) DEFAULT '' NOT NULL,
            utm_term varchar(100) DEFAULT '' NOT NULL,
            utm_content varchar(100) DEFAULT '' NOT NULL,
            user_ip varchar(45) DEFAULT '' NOT NULL,
            user_agent text DEFAULT '' NOT NULL,
            PRIMARY KEY (id),
            KEY click_date (click_date),
            KEY page_url (page_url(191))
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Remove tabela de analytics
     */
    public static function drop_analytics_table() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'delight_whatsapp_clicks';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}