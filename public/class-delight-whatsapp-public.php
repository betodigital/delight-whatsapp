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
        
        if (!empty($phone)) {
            $formatted_phone = preg_replace('/[^0-9]/', '', $phone);
            $whatsapp_url = "https://wa.me/{$formatted_phone}";
            
            $style = $position . ': 20px; bottom: ' . esc_attr($vertical_position) . 'px;';
            
            echo '<div class="delight-whatsapp-container" style="' . $style . '">';
            if ($greeting_enabled == '1') {
                echo '<div class="delight-whatsapp-greeting">' . esc_html($greeting_message) . '</div>';
            }
            echo '<a href="' . esc_url($whatsapp_url) . '" class="delight-whatsapp" data-whatsapp-url="' . esc_url($whatsapp_url) . '">';
            echo '<img src="' . DELIGHT_WHATSAPP_PLUGIN_URL . 'assets/whatsapp-icon.svg" alt="WhatsApp">';
            echo '</a>';
            echo '</div>';
        }
    }

    /**
     * Adiciona script para abrir WhatsApp
     */
    public function add_script() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var whatsappLink = document.querySelector('.delight-whatsapp');
            if (whatsappLink) {
                whatsappLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    var url = this.getAttribute('data-whatsapp-url');
                    window.open(url, '_blank');
                });
            }
        });
        </script>
        <?php
    }
}