<?php
/**
 * Plugin Name: Delight WhatsApp
 * Plugin URI: https://www.robertogrozinski.com/delight-whatsapp
 * Description: Adiciona um ícone flutuante do WhatsApp no rodapé do site com opções de GTM, GA e mensagem de saudação.
 * Version: 1.0.5
 * Author: Roberto Grozinski
 * Author URI: https://www.robertogrozinski.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: delight-whatsapp
 *
 * @package Delight_WhatsApp
 * @author Roberto Grozinski <roberto.grozinski@gmail.com>
 */

// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

define('DELIGHT_WHATSAPP_VERSION', '1.0.5');
define('DELIGHT_WHATSAPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DELIGHT_WHATSAPP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Incluir o arquivo de administração
require_once DELIGHT_WHATSAPP_PLUGIN_DIR . 'admin/admin-page.php';

// Registrar a função de ativação do plugin
register_activation_hook(__FILE__, 'delight_whatsapp_activate');

function delight_whatsapp_activate() {
    add_option('delight_whatsapp_phone', '');
    add_option('delight_whatsapp_position', 'right');
    add_option('delight_whatsapp_vertical_position', '20');
    add_option('delight_whatsapp_gtm_id', '');
    add_option('delight_whatsapp_ga_id', '');
    add_option('delight_whatsapp_greeting_enabled', '0');
    add_option('delight_whatsapp_greeting_message', 'Olá! Como posso ajudar?');
}

// Registrar a função de desativação do plugin
register_deactivation_hook(__FILE__, 'delight_whatsapp_deactivate');

function delight_whatsapp_deactivate() {
    delete_option('delight_whatsapp_phone');
    delete_option('delight_whatsapp_position');
    delete_option('delight_whatsapp_vertical_position');
    delete_option('delight_whatsapp_gtm_id');
    delete_option('delight_whatsapp_ga_id');
    delete_option('delight_whatsapp_greeting_enabled');
    delete_option('delight_whatsapp_greeting_message');
}

// Adicionar scripts e estilos no front-end
function delight_whatsapp_enqueue_scripts() {
    wp_enqueue_style('delight-whatsapp-style', DELIGHT_WHATSAPP_PLUGIN_URL . 'public/css/delight-whatsapp-public.css', array(), DELIGHT_WHATSAPP_VERSION, 'all');
    wp_enqueue_script('delight-whatsapp-script', DELIGHT_WHATSAPP_PLUGIN_URL . 'public/js/delight-whatsapp-public.js', array('jquery'), DELIGHT_WHATSAPP_VERSION, true);
}
add_action('wp_enqueue_scripts', 'delight_whatsapp_enqueue_scripts');

// Adicionar GTM ao cabeçalho do site
function delight_whatsapp_add_gtm_head() {
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
add_action('wp_head', 'delight_whatsapp_add_gtm_head');

// Adicionar GTM ao corpo do site
function delight_whatsapp_add_gtm_body() {
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
add_action('wp_body_open', 'delight_whatsapp_add_gtm_body');

// Adicionar GA ao cabeçalho do site
function delight_whatsapp_add_ga() {
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
add_action('wp_head', 'delight_whatsapp_add_ga');

// Adicionar o ícone flutuante no footer
function delight_whatsapp_add_button() {
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
add_action('wp_footer', 'delight_whatsapp_add_button');

// Adicionar script para abrir o WhatsApp em uma nova aba
function delight_whatsapp_add_script() {
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
add_action('wp_footer', 'delight_whatsapp_add_script');

// Função para log (útil para depuração)
function delight_whatsapp_log($message) {
    if (WP_DEBUG === true) {
        if (is_array($message) || is_object($message)) {
            error_log(print_r($message, true));
        } else {
            error_log($message);
        }
    }
}