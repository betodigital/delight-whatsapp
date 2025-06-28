<?php
/**
 * Funcionalidades do frontend
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/public
 */

class Delight_WhatsApp_Public {

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
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            $this->plugin_name,
            DELIGHT_WHATSAPP_PLUGIN_URL . 'public/css/delight-whatsapp-public.css',
            array(),
            $this->version,
            'all'
        );

        wp_enqueue_script(
            $this->plugin_name,
            DELIGHT_WHATSAPP_PLUGIN_URL . 'public/js/delight-whatsapp-public.js',
            array('jquery'),
            $this->version,
            true
        );

        // Localizar script com dados necessários
        wp_localize_script($this->plugin_name, 'delightWhatsApp', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('delight_whatsapp_click'),
            'autoPageInfo' => get_option('delight_whatsapp_auto_page_info', '0'),
            'utmTracking' => get_option('delight_whatsapp_utm_tracking', '0'),
            'pageTitle' => get_the_title(),
            'pageUrl' => get_permalink(),
            'homeUrl' => home_url()
        ));
    }

    /**
     * Adiciona GTM ao cabeçalho
     */
    public function add_gtm_head() {
        $gtm_id = get_option('delight_whatsapp_gtm_id');
        if (!empty($gtm_id)) {
            ?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo esc_js($gtm_id); ?>');</script>
<!-- End Google Tag Manager -->
            <?php
        }
    }

    /**
     * Adiciona GTM ao corpo
     */
    public function add_gtm_body() {
        $gtm_id = get_option('delight_whatsapp_gtm_id');
        if (!empty($gtm_id)) {
            ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr($gtm_id); ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
            <?php
        }
    }

    /**
     * Adiciona GA ao cabeçalho
     */
    public function add_ga() {
        $ga_id = get_option('delight_whatsapp_ga_id');
        if (!empty($ga_id)) {
            ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_js($ga_id); ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo esc_js($ga_id); ?>');
</script>
            <?php
        }
    }

    /**
     * Adiciona o botão flutuante
     */
    public function add_button() {
        $phone = get_option('delight_whatsapp_phone');
        $position = get_option('delight_whatsapp_position', 'right');
        $vertical_position = get_option('delight_whatsapp_vertical_position', '20');
        $greeting_enabled = get_option('delight_whatsapp_greeting_enabled', '0');
        $greeting_message = get_option('delight_whatsapp_greeting_message', 'Olá! Como posso ajudar?');
        $auto_page_info = get_option('delight_whatsapp_auto_page_info', '0');
        
        if (!empty($phone)) {
            $formatted_phone = preg_replace('/[^0-9]/', '', $phone);
            
            // Preparar mensagem automática se habilitada
            $message = '';
            if ($auto_page_info == '1') {
                $page_title = get_the_title();
                $page_url = get_permalink();
                
                if (is_front_page()) {
                    $page_title = get_bloginfo('name');
                    $page_url = home_url();
                }
                
                $message = urlencode("Olá! Estou na página '{$page_title}' ({$page_url}) e gostaria de mais detalhes.");
            }
            
            $whatsapp_url = "https://wa.me/{$formatted_phone}";
            if (!empty($message)) {
                $whatsapp_url .= "?text={$message}";
            }
            
            $style = $position . ': 20px; bottom: ' . esc_attr($vertical_position) . 'px;';
            
            echo '<div class="delight-whatsapp-container" style="' . $style . '">';
            if ($greeting_enabled == '1') {
                echo '<div class="delight-whatsapp-greeting">' . esc_html($greeting_message) . '</div>';
            }
            echo '<a href="' . esc_url($whatsapp_url) . '" class="delight-whatsapp" data-whatsapp-url="' . esc_url($whatsapp_url) . '" data-phone="' . esc_attr($formatted_phone) . '">';
            echo '<img src="' . DELIGHT_WHATSAPP_PLUGIN_URL . 'assets/whatsapp-icon.svg" alt="WhatsApp">';
            echo '</a>';
            echo '</div>';
        }
    }

    /**
     * Adiciona script para UTM tracking
     */
    public function add_utm_script() {
        $utm_tracking = get_option('delight_whatsapp_utm_tracking', '0');
        if ($utm_tracking == '1') {
            ?>
            <script>
            // Capturar e manter parâmetros UTM
            (function() {
                var urlParams = new URLSearchParams(window.location.search);
                var utmParams = {};
                var hasUtm = false;
                
                ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'].forEach(function(param) {
                    if (urlParams.has(param)) {
                        utmParams[param] = urlParams.get(param);
                        hasUtm = true;
                    }
                });
                
                if (hasUtm) {
                    // Armazenar UTM params na sessão
                    sessionStorage.setItem('delight_utm_params', JSON.stringify(utmParams));
                    
                    // Enviar para GTM se disponível
                    if (typeof dataLayer !== 'undefined') {
                        dataLayer.push({
                            'event': 'utm_capture',
                            'utm_source': utmParams.utm_source || '',
                            'utm_medium': utmParams.utm_medium || '',
                            'utm_campaign': utmParams.utm_campaign || '',
                            'utm_term': utmParams.utm_term || '',
                            'utm_content': utmParams.utm_content || ''
                        });
                    }
                }
            })();
            </script>
            <?php
        }
    }
}