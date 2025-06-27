<?php
// Adicionar menu de configurações
function delight_whatsapp_add_admin_menu() {
    add_options_page('Delight WhatsApp Settings', 'Delight WhatsApp', 'manage_options', 'delight-whatsapp', 'delight_whatsapp_options_page');
}
add_action('admin_menu', 'delight_whatsapp_add_admin_menu');

// Registrar configurações
function delight_whatsapp_settings_init() {
    register_setting('delight_whatsapp', 'delight_whatsapp_phone');
    register_setting('delight_whatsapp', 'delight_whatsapp_position');
    register_setting('delight_whatsapp', 'delight_whatsapp_vertical_position');
    register_setting('delight_whatsapp', 'delight_whatsapp_gtm_id');
    register_setting('delight_whatsapp', 'delight_whatsapp_ga_id');
    register_setting('delight_whatsapp', 'delight_whatsapp_greeting_enabled');
    register_setting('delight_whatsapp', 'delight_whatsapp_greeting_message');

    add_settings_section(
        'delight_whatsapp_section',
        __('Configurações do Delight WhatsApp', 'delight-whatsapp'),
        'delight_whatsapp_section_callback',
        'delight_whatsapp'
    );

    add_settings_field(
        'delight_whatsapp_phone',
        __('Número do WhatsApp', 'delight-whatsapp'),
        'delight_whatsapp_phone_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_position',
        __('Posição Horizontal do Ícone', 'delight-whatsapp'),
        'delight_whatsapp_position_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_vertical_position',
        __('Posição Vertical do Ícone', 'delight-whatsapp'),
        'delight_whatsapp_vertical_position_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_gtm_id',
        __('ID do Google Tag Manager', 'delight-whatsapp'),
        'delight_whatsapp_gtm_id_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_ga_id',
        __('ID do Google Analytics', 'delight-whatsapp'),
        'delight_whatsapp_ga_id_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_greeting_enabled',
        __('Habilitar Mensagem de Saudação', 'delight-whatsapp'),
        'delight_whatsapp_greeting_enabled_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );

    add_settings_field(
        'delight_whatsapp_greeting_message',
        __('Mensagem de Saudação', 'delight-whatsapp'),
        'delight_whatsapp_greeting_message_render',
        'delight_whatsapp',
        'delight_whatsapp_section'
    );
}
add_action('admin_init', 'delight_whatsapp_settings_init');

function delight_whatsapp_section_callback() {
    echo __('Configure as opções do ícone flutuante do WhatsApp.', 'delight-whatsapp');
}

function delight_whatsapp_phone_render() {
    $phone = get_option('delight_whatsapp_phone');
    ?>
    <input type="text" name="delight_whatsapp_phone" value="<?php echo esc_attr($phone); ?>" id="delight_whatsapp_phone" class="regular-text">
    <p class="description"><?php _e('Digite o número no formato: 55(11)99999-9999', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_position_render() {
    $position = get_option('delight_whatsapp_position', 'right');
    ?>
    <select name="delight_whatsapp_position">
        <option value="left" <?php selected($position, 'left'); ?>><?php _e('Esquerda', 'delight-whatsapp'); ?></option>
        <option value="right" <?php selected($position, 'right'); ?>><?php _e('Direita', 'delight-whatsapp'); ?></option>
    </select>
    <?php
}

function delight_whatsapp_vertical_position_render() {
    $vertical_position = get_option('delight_whatsapp_vertical_position', '20');
    ?>
    <input type="number" name="delight_whatsapp_vertical_position" value="<?php echo esc_attr($vertical_position); ?>" min="0" max="100" step="1">
    <p class="description"><?php _e('Digite a distância em pixels do ícone em relação à parte inferior da tela (0-100)', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_gtm_id_render() {
    $gtm_id = get_option('delight_whatsapp_gtm_id');
    ?>
    <input type="text" name="delight_whatsapp_gtm_id" value="<?php echo esc_attr($gtm_id); ?>" class="regular-text">
    <p class="description"><?php _e('Digite o ID do GTM (ex: GTM-XXXXXXX)', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_ga_id_render() {
    $ga_id = get_option('delight_whatsapp_ga_id');
    ?>
    <input type="text" name="delight_whatsapp_ga_id" value="<?php echo esc_attr($ga_id); ?>" class="regular-text">
    <p class="description"><?php _e('Digite o ID do GA (ex: UA-XXXXXXXXX-X ou G-XXXXXXXXXX)', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_greeting_enabled_render() {
    $greeting_enabled = get_option('delight_whatsapp_greeting_enabled', '0');
    ?>
    <input type="checkbox" name="delight_whatsapp_greeting_enabled" value="1" <?php checked($greeting_enabled, '1'); ?>>
    <p class="description"><?php _e('Marque para exibir uma mensagem de saudação quando o botão do WhatsApp aparecer', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_greeting_message_render() {
    $greeting_message = get_option('delight_whatsapp_greeting_message', 'Olá! Como posso ajudar?');
    ?>
    <input type="text" name="delight_whatsapp_greeting_message" value="<?php echo esc_attr($greeting_message); ?>" class="regular-text">
    <p class="description"><?php _e('Digite a mensagem de saudação que será exibida', 'delight-whatsapp'); ?></p>
    <?php
}

function delight_whatsapp_options_page() {
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

// Adicionar máscara de telefone com JavaScript
function delight_whatsapp_admin_scripts($hook) {
    if ('settings_page_delight-whatsapp' !== $hook) {
        return;
    }
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', array('jquery'), '1.14.16', true);
    wp_add_inline_script('jquery-mask', '
        jQuery(document).ready(function($) {
            $("#delight_whatsapp_phone").mask("00(00)00000-0000");
        });
    ');
}
add_action('admin_enqueue_scripts', 'delight_whatsapp_admin_scripts');