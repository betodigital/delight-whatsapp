<?php
/**
 * Funcionalidades do admin
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/admin
 */

class Delight_WhatsApp_Admin {

    /**
     * Nome do plugin
     */
    private $plugin_name;

    /**
     * Versão do plugin
     */
    private $version;

    /**
     * Construtor da classe
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Enfileira scripts do admin
     */
    public function enqueue_scripts($hook) {
        if ('settings_page_delight-whatsapp' !== $hook && 'toplevel_page_delight-whatsapp-dashboard' !== $hook) {
            return;
        }

        wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
        wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
        
        wp_add_inline_script('jquery-mask', '
            jQuery(document).ready(function($) {
                $("#delight_whatsapp_phone").mask("00(00)00000-0000");
                
                // Auto scan functionality
                $("#auto-scan-btn").click(function() {
                    var $btn = $(this);
                    $btn.prop("disabled", true).text("Escaneando...");
                    
                    $.post(ajaxurl, {
                        action: "delight_whatsapp_auto_scan",
                        nonce: "' . wp_create_nonce('delight_whatsapp_scan') . '"
                    }, function(response) {
                        if (response.success) {
                            if (response.data.gtm_id) {
                                $("#delight_whatsapp_gtm_id").val(response.data.gtm_id);
                            }
                            if (response.data.phone) {
                                $("#delight_whatsapp_phone").val(response.data.phone);
                            }
                            alert("Escaneamento concluído! " + response.data.message);
                        } else {
                            alert("Erro no escaneamento: " + response.data);
                        }
                        $btn.prop("disabled", false).text("Escanear Automaticamente");
                    });
                });
            });
        ');

        wp_enqueue_style(
            $this->plugin_name . '-admin',
            DELIGHT_WHATSAPP_PLUGIN_URL . 'admin/css/delight-whatsapp-admin.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Adiciona menu de configurações
     */
    public function add_admin_menu() {
        // Menu principal
        add_menu_page(
            'Delight WhatsApp Dashboard',
            'Delight WhatsApp',
            'manage_options',
            'delight-whatsapp-dashboard',
            array($this, 'dashboard_page'),
            'dashicons-whatsapp',
            30
        );

        // Submenu Dashboard
        add_submenu_page(
            'delight-whatsapp-dashboard',
            'Dashboard',
            'Dashboard',
            'manage_options',
            'delight-whatsapp-dashboard',
            array($this, 'dashboard_page')
        );

        // Submenu Configurações
        add_submenu_page(
            'delight-whatsapp-dashboard',
            'Configurações',
            'Configurações',
            'manage_options',
            'delight-whatsapp-settings',
            array($this, 'options_page')
        );
    }

    /**
     * Registra configurações
     */
    public function settings_init() {
        register_setting('delight_whatsapp', 'delight_whatsapp_phone');
        register_setting('delight_whatsapp', 'delight_whatsapp_position');
        register_setting('delight_whatsapp', 'delight_whatsapp_vertical_position');
        register_setting('delight_whatsapp', 'delight_whatsapp_gtm_id');
        register_setting('delight_whatsapp', 'delight_whatsapp_ga_id');
        register_setting('delight_whatsapp', 'delight_whatsapp_greeting_enabled');
        register_setting('delight_whatsapp', 'delight_whatsapp_greeting_message');
        register_setting('delight_whatsapp', 'delight_whatsapp_auto_page_info');
        register_setting('delight_whatsapp', 'delight_whatsapp_utm_tracking');
        register_setting('delight_whatsapp', 'delight_whatsapp_auto_scan_enabled');

        add_settings_section(
            'delight_whatsapp_section',
            __('Configurações do Delight WhatsApp', 'delight-whatsapp'),
            array($this, 'section_callback'),
            'delight_whatsapp'
        );

        $this->add_settings_fields();
    }

    /**
     * Adiciona campos de configuração
     */
    private function add_settings_fields() {
        $fields = array(
            'auto_scan' => __('Escaneamento Automático', 'delight-whatsapp'),
            'phone' => __('Número do WhatsApp', 'delight-whatsapp'),
            'position' => __('Posição Horizontal do Ícone', 'delight-whatsapp'),
            'vertical_position' => __('Posição Vertical do Ícone', 'delight-whatsapp'),
            'gtm_id' => __('ID do Google Tag Manager', 'delight-whatsapp'),
            'ga_id' => __('ID do Google Analytics', 'delight-whatsapp'),
            'greeting_enabled' => __('Habilitar Mensagem de Saudação', 'delight-whatsapp'),
            'greeting_message' => __('Mensagem de Saudação', 'delight-whatsapp'),
            'auto_page_info' => __('Auto Preenchimento com Info da Página', 'delight-whatsapp'),
            'utm_tracking' => __('Rastreamento UTM', 'delight-whatsapp')
        );

        foreach ($fields as $field => $label) {
            add_settings_field(
                'delight_whatsapp_' . $field,
                $label,
                array($this, $field . '_render'),
                'delight_whatsapp',
                'delight_whatsapp_section'
            );
        }
    }

    /**
     * Callback da seção
     */
    public function section_callback() {
        echo __('Configure as opções do ícone flutuante do WhatsApp.', 'delight-whatsapp');
    }

    /**
     * Campo de escaneamento automático
     */
    public function auto_scan_render() {
        ?>
        <button type="button" id="auto-scan-btn" class="button button-secondary">
            <?php _e('Escanear Automaticamente', 'delight-whatsapp'); ?>
        </button>
        <p class="description">
            <?php _e('Escaneia automaticamente a página inicial do site em busca de GTM ID e número de telefone.', 'delight-whatsapp'); ?>
        </p>
        <?php
    }

    /**
     * Campo do telefone
     */
    public function phone_render() {
        $phone = get_option('delight_whatsapp_phone');
        ?>
        <input type="text" name="delight_whatsapp_phone" value="<?php echo esc_attr($phone); ?>" id="delight_whatsapp_phone" class="regular-text">
        <p class="description"><?php _e('Digite o número no formato: 55(11)99999-9999', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo da posição
     */
    public function position_render() {
        $position = get_option('delight_whatsapp_position', 'right');
        ?>
        <select name="delight_whatsapp_position">
            <option value="left" <?php selected($position, 'left'); ?>><?php _e('Esquerda', 'delight-whatsapp'); ?></option>
            <option value="right" <?php selected($position, 'right'); ?>><?php _e('Direita', 'delight-whatsapp'); ?></option>
        </select>
        <?php
    }

    /**
     * Campo da posição vertical
     */
    public function vertical_position_render() {
        $vertical_position = get_option('delight_whatsapp_vertical_position', '20');
        ?>
        <input type="number" name="delight_whatsapp_vertical_position" value="<?php echo esc_attr($vertical_position); ?>" min="0" max="100" step="1">
        <p class="description"><?php _e('Digite a distância em pixels do ícone em relação à parte inferior da tela (0-100)', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo do GTM ID
     */
    public function gtm_id_render() {
        $gtm_id = get_option('delight_whatsapp_gtm_id');
        ?>
        <input type="text" name="delight_whatsapp_gtm_id" value="<?php echo esc_attr($gtm_id); ?>" id="delight_whatsapp_gtm_id" class="regular-text">
        <p class="description"><?php _e('Digite o ID do GTM (ex: GTM-XXXXXXX)', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo do GA ID
     */
    public function ga_id_render() {
        $ga_id = get_option('delight_whatsapp_ga_id');
        ?>
        <input type="text" name="delight_whatsapp_ga_id" value="<?php echo esc_attr($ga_id); ?>" class="regular-text">
        <p class="description"><?php _e('Digite o ID do GA (ex: UA-XXXXXXXXX-X ou G-XXXXXXXXXX)', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo para habilitar saudação
     */
    public function greeting_enabled_render() {
        $greeting_enabled = get_option('delight_whatsapp_greeting_enabled', '0');
        ?>
        <input type="checkbox" name="delight_whatsapp_greeting_enabled" value="1" <?php checked($greeting_enabled, '1'); ?>>
        <p class="description"><?php _e('Marque para exibir uma mensagem de saudação quando o botão do WhatsApp aparecer', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo da mensagem de saudação
     */
    public function greeting_message_render() {
        $greeting_message = get_option('delight_whatsapp_greeting_message', 'Olá! Como posso ajudar?');
        ?>
        <input type="text" name="delight_whatsapp_greeting_message" value="<?php echo esc_attr($greeting_message); ?>" class="regular-text">
        <p class="description"><?php _e('Digite a mensagem de saudação que será exibida', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo para auto preenchimento com info da página
     */
    public function auto_page_info_render() {
        $auto_page_info = get_option('delight_whatsapp_auto_page_info', '0');
        ?>
        <input type="checkbox" name="delight_whatsapp_auto_page_info" value="1" <?php checked($auto_page_info, '1'); ?>>
        <p class="description"><?php _e('Marque para incluir automaticamente o título e URL da página atual na mensagem do WhatsApp', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Campo para rastreamento UTM
     */
    public function utm_tracking_render() {
        $utm_tracking = get_option('delight_whatsapp_utm_tracking', '0');
        ?>
        <input type="checkbox" name="delight_whatsapp_utm_tracking" value="1" <?php checked($utm_tracking, '1'); ?>>
        <p class="description"><?php _e('Marque para manter e enviar parâmetros UTM para o GTM/GA', 'delight-whatsapp'); ?></p>
        <?php
    }

    /**
     * Página do dashboard
     */
    public function dashboard_page() {
        $stats = $this->get_whatsapp_stats();
        ?>
        <div class="wrap">
            <h1><?php _e('Dashboard Delight WhatsApp', 'delight-whatsapp'); ?></h1>
            
            <div class="delight-dashboard-grid">
                <div class="delight-card">
                    <h3><?php _e('Cliques Hoje', 'delight-whatsapp'); ?></h3>
                    <div class="delight-stat-number"><?php echo $stats['today']; ?></div>
                </div>
                
                <div class="delight-card">
                    <h3><?php _e('Cliques Esta Semana', 'delight-whatsapp'); ?></h3>
                    <div class="delight-stat-number"><?php echo $stats['week']; ?></div>
                </div>
                
                <div class="delight-card">
                    <h3><?php _e('Cliques Este Mês', 'delight-whatsapp'); ?></h3>
                    <div class="delight-stat-number"><?php echo $stats['month']; ?></div>
                </div>
                
                <div class="delight-card">
                    <h3><?php _e('Total de Cliques', 'delight-whatsapp'); ?></h3>
                    <div class="delight-stat-number"><?php echo $stats['total']; ?></div>
                </div>
            </div>

            <div class="delight-chart-container">
                <h3><?php _e('Cliques nos Últimos 7 Dias', 'delight-whatsapp'); ?></h3>
                <canvas id="whatsapp-chart" width="400" height="200"></canvas>
            </div>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var ctx = document.getElementById('whatsapp-chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo json_encode($stats['chart_labels']); ?>,
                        datasets: [{
                            label: '<?php _e('Cliques', 'delight-whatsapp'); ?>',
                            data: <?php echo json_encode($stats['chart_data']); ?>,
                            borderColor: '#25D366',
                            backgroundColor: 'rgba(37, 211, 102, 0.1)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
            </script>
        </div>
        <?php
    }

    /**
     * Página de opções
     */
    public function options_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('delight_whatsapp');
                do_settings_sections('delight_whatsapp');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Obtém estatísticas do WhatsApp
     */
    private function get_whatsapp_stats() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'delight_whatsapp_clicks';
        
        $today = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE DATE(click_date) = CURDATE()");
        $week = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE click_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
        $month = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE click_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        // Dados para o gráfico dos últimos 7 dias
        $chart_data = array();
        $chart_labels = array();
        
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_name WHERE DATE(click_date) = %s", $date));
            $chart_data[] = intval($count);
            $chart_labels[] = date('d/m', strtotime($date));
        }

        return array(
            'today' => intval($today),
            'week' => intval($week),
            'month' => intval($month),
            'total' => intval($total),
            'chart_data' => $chart_data,
            'chart_labels' => $chart_labels
        );
    }

    /**
     * AJAX handler para escaneamento automático
     */
    public function handle_auto_scan() {
        check_ajax_referer('delight_whatsapp_scan', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permissão negada', 'delight-whatsapp'));
        }

        $scanner = new Delight_WhatsApp_Scanner();
        $results = $scanner->scan_homepage();

        if ($results['gtm_id'] || $results['phone']) {
            wp_send_json_success(array(
                'gtm_id' => $results['gtm_id'],
                'phone' => $results['phone'],
                'message' => __('GTM ID e/ou telefone encontrados!', 'delight-whatsapp')
            ));
        } else {
            wp_send_json_success(array(
                'gtm_id' => '',
                'phone' => '',
                'message' => __('Nenhum GTM ID ou telefone encontrado.', 'delight-whatsapp')
            ));
        }
    }
}