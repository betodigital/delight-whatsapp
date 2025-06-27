/**
 * JavaScript do frontend
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/public/js
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Funcionalidade do botão WhatsApp
        $('.delight-whatsapp').on('click', function(e) {
            e.preventDefault();
            
            var url = $(this).data('whatsapp-url');
            
            // Log para análises
            console.log('WhatsApp button clicked');
            
            // Enviar evento para Google Analytics se disponível
            if (typeof gtag !== 'undefined') {
                gtag('event', 'click', {
                    'event_category': 'WhatsApp',
                    'event_label': 'Floating Button',
                    'value': 1
                });
            }
            
            // Enviar evento para Google Tag Manager se disponível
            if (typeof dataLayer !== 'undefined') {
                dataLayer.push({
                    'event': 'whatsapp_click',
                    'event_category': 'WhatsApp',
                    'event_action': 'click',
                    'event_label': 'Floating Button'
                });
            }
            
            // Abrir WhatsApp
            window.open(url, '_blank');
        });

        // Animação da mensagem de saudação
        var greeting = $('.delight-whatsapp-greeting');
        if (greeting.length) {
            // Esconder a saudação após 5 segundos
            setTimeout(function() {
                greeting.fadeOut(500);
            }, 5000);
            
            // Mostrar novamente ao passar o mouse sobre o botão
            $('.delight-whatsapp').hover(
                function() {
                    greeting.fadeIn(300);
                },
                function() {
                    setTimeout(function() {
                        greeting.fadeOut(300);
                    }, 2000);
                }
            );
        }

        // Efeito de pulsação no botão
        setInterval(function() {
            $('.delight-whatsapp img').addClass('pulse');
            setTimeout(function() {
                $('.delight-whatsapp img').removeClass('pulse');
            }, 1000);
        }, 10000);

    });

    // Adicionar classe CSS para efeito de pulsação
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .delight-whatsapp img.pulse {
                animation: pulse 1s ease-in-out;
            }
            
            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }
        `)
        .appendTo('head');

})(jQuery);