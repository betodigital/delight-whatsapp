<?php
/**
 * Classe para escaneamento automático
 *
 * @package Delight_WhatsApp
 * @subpackage Delight_WhatsApp/includes
 */

class Delight_WhatsApp_Scanner {

    /**
     * Escaneia a página inicial em busca de GTM ID e telefone
     */
    public function scan_homepage() {
        $home_url = home_url('/');
        $response = wp_remote_get($home_url, array(
            'timeout' => 30,
            'user-agent' => 'Delight WhatsApp Scanner'
        ));

        if (is_wp_error($response)) {
            return array('gtm_id' => '', 'phone' => '');
        }

        $body = wp_remote_retrieve_body($response);
        
        return array(
            'gtm_id' => $this->extract_gtm_id($body),
            'phone' => $this->extract_phone($body)
        );
    }

    /**
     * Extrai GTM ID do HTML
     */
    private function extract_gtm_id($html) {
        // Procura por padrões comuns do GTM
        $patterns = array(
            '/GTM-[A-Z0-9]{6,}/',
            '/googletagmanager\.com\/gtm\.js\?id=(GTM-[A-Z0-9]{6,})/',
            '/gtm_id["\']?\s*[:=]\s*["\']?(GTM-[A-Z0-9]{6,})["\']?/',
            '/google_tag_manager["\']?\s*[:=]\s*["\']?(GTM-[A-Z0-9]{6,})["\']?/'
        );

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $html, $matches)) {
                return isset($matches[1]) ? $matches[1] : $matches[0];
            }
        }

        return '';
    }

    /**
     * Extrai número de telefone que comece com 55119
     */
    private function extract_phone($html) {
        // Remove tags HTML para buscar apenas no texto
        $text = strip_tags($html);
        
        // Padrões para telefone brasileiro começando com 55119
        $patterns = array(
            '/55\s*\(?11\)?\s*9\s*\d{4}[-\s]?\d{4}/',
            '/\+55\s*\(?11\)?\s*9\s*\d{4}[-\s]?\d{4}/',
            '/55119\d{8}/',
            '/\+55119\d{8}/',
            '/55\s*11\s*9\s*\d{8}/',
            '/\(55\)\s*\(11\)\s*9\s*\d{4}[-\s]?\d{4}/'
        );

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                // Limpa o número encontrado
                $phone = preg_replace('/[^\d]/', '', $matches[0]);
                
                // Verifica se realmente começa com 55119
                if (substr($phone, 0, 5) === '55119' && strlen($phone) >= 13) {
                    // Formata o número
                    return $this->format_phone($phone);
                }
            }
        }

        return '';
    }

    /**
     * Formata o número de telefone
     */
    private function format_phone($phone) {
        // Remove caracteres não numéricos
        $phone = preg_replace('/[^\d]/', '', $phone);
        
        // Se tem 13 dígitos (55 + 11 + 9 + 8 dígitos)
        if (strlen($phone) === 13 && substr($phone, 0, 5) === '55119') {
            return substr($phone, 0, 2) . '(' . substr($phone, 2, 2) . ')' . substr($phone, 4, 5) . '-' . substr($phone, 9, 4);
        }
        
        return $phone;
    }
}