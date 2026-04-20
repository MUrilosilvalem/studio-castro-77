<?php
/**
 * Plugin Name: Studio Castro 77 Core
 * Description: Funcionalidades Core para o portal Studio Castro 77: CPTs e Shortcodes.
 * Version: 1.0
 * Author: Antigravity AI
 */

if (!defined('ABSPATH')) exit;

// [RESET TEMPORÁRIO] Resetar senha do admin caso tenha ocorrido erro na instalação
function sc77_reset_admin_password() {
    $user_name = 'admin'; // Verifique se seu usuário é 'admin'
    $new_password = 'Castro771920';
    
    $user = get_user_by('login', $user_name);
    if ($user) {
        wp_set_password($new_password, $user->ID);
    }
}
add_action('init', 'sc77_reset_admin_password');

// 1. REGISTRO DE CPTS
function sc77_register_cpts() {
    // CPT Patrocinadores
    register_post_type('patrocinador', [
        'labels' => [
            'name' => 'Patrocinadores',
            'singular_name' => 'Patrocinador',
            'add_new' => 'Adicionar Novo',
            'add_new_item' => 'Adicionar Novo Patrocinador',
            'edit_item' => 'Editar Patrocinador',
        ],
        'public' => true,
        'has_archive' => false,
        'menu_icon' => 'dashicons-money-alt',
        'supports' => ['title', 'thumbnail'],
    ]);

    // CPT Agenda
    register_post_type('agenda', [
        'labels' => [
            'name' => 'Agenda',
            'singular_name' => 'Compromisso',
            'add_new' => 'Adicionar Convidado',
            'add_new_item' => 'Novo Convidado na Agenda',
        ],
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => ['title'],
    ]);
}
add_action('init', 'sc77_register_cpts');

// 2. META BOXES PARA AGENDA E PATROCINADORES
function sc77_add_meta_boxes() {
    // Link do Patrocinador
    add_meta_box('sc77_patrocinador_link', 'Link de Destino', 'sc77_patrocinador_link_html', 'patrocinador', 'side');
    
    // Detalhes da Agenda
    add_meta_box('sc77_agenda_details', 'Detalhes do Convidado', 'sc77_agenda_details_html', 'agenda', 'normal');
}
add_action('add_meta_boxes', 'sc77_add_meta_boxes');

function sc77_patrocinador_link_html($post) {
    $value = get_post_meta($post->ID, '_sc77_link', true);
    echo '<input type="url" name="sc77_link" value="' . esc_attr($value) . '" style="width:100%" placeholder="https://...">';
}

function sc77_agenda_details_html($post) {
    $esp = get_post_meta($post->ID, '_sc77_especialidade', true);
    $tema = get_post_meta($post->ID, '_sc77_tema', true);
    $data = get_post_meta($post->ID, '_sc77_data_hora', true);
    ?>
    <p><label>Especialidade:</label><br><input type="text" name="sc77_especialidade" value="<?php echo esc_attr($esp); ?>" style="width:100%"></p>
    <p><label>Tema:</label><br><input type="text" name="sc77_tema" value="<?php echo esc_attr($tema); ?>" style="width:100%"></p>
    <p><label>Data e Hora (YYYY-MM-DD HH:MM):</label><br><input type="datetime-local" name="sc77_data_hora" value="<?php echo esc_attr($data); ?>" style="width:100%"></p>
    <?php
}

function sc77_save_meta($post_id) {
    if (isset($_POST['sc77_link'])) update_post_meta($post_id, '_sc77_link', sanitize_text_field($_POST['sc77_link']));
    if (isset($_POST['sc77_especialidade'])) update_post_meta($post_id, '_sc77_especialidade', sanitize_text_field($_POST['sc77_especialidade']));
    if (isset($_POST['sc77_tema'])) update_post_meta($post_id, '_sc77_tema', sanitize_text_field($_POST['sc77_tema']));
    if (isset($_POST['sc77_data_hora'])) update_post_meta($post_id, '_sc77_data_hora', sanitize_text_field($_POST['sc77_data_hora']));
}
add_action('save_post', 'sc77_save_meta');

// 3. SHORTCODES
function sc77_shortcode_patrocinadores() {
    $query = new WP_Query(['post_type' => 'patrocinador', 'posts_per_page' => -1]);
    if (!$query->have_posts()) return '';

    $output = '<div class="grid grid-cols-2 md:grid-cols-4 gap-6 p-4">';
    while ($query->have_posts()) {
        $query->the_post();
        $link = get_post_meta(get_the_ID(), '_sc77_link', true);
        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'medium');
        $output .= '
        <a href="' . esc_url($link) . '" target="_blank" class="flex items-center justify-center grayscale hover:grayscale-0 transition duration-300">
            <img src="' . esc_url($thumb) . '" alt="' . get_the_title() . '" class="max-h-20 object-contain">
        </a>';
    }
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('sc77_patrocinadores', 'sc77_shortcode_patrocinadores');

function sc77_shortcode_agenda() {
    $query = new WP_Query([
        'post_type' => 'agenda', 
        'posts_per_page' => 3, 
        'meta_key' => '_sc77_data_hora', 
        'orderby' => 'meta_value', 
        'order' => 'ASC'
    ]);
    if (!$query->have_posts()) return '';

    $output = '<div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-4">';
    while ($query->have_posts()) {
        $query->the_post();
        $esp = get_post_meta(get_the_ID(), '_sc77_especialidade', true);
        $tema = get_post_meta(get_the_ID(), '_sc77_tema', true);
        $data = get_post_meta(get_the_ID(), '_sc77_data_hora', true);
        
        $output .= '
        <div class="bg-white rounded-xl shadow-lg border-t-4 border-[#9eb53e] p-6 hover:translate-y-[-5px] transition duration-300">
            <span class="text-xs font-bold text-[#154c79] uppercase tracking-wider">' . esc_html($esp) . '</span>
            <h3 class="text-xl font-bold mt-2 text-gray-800">' . get_the_title() . '</h3>
            <p class="text-gray-600 text-sm italic mt-1">"' . esc_html($tema) . '"</p>
            <div class="mt-4 p-3 bg-[#f4f6f8] rounded-lg">
                <div class="text-[#154c79] font-mono font-bold text-center countdown" data-target="' . esc_attr($data) . '">
                    00d 00h 00m 00s
                </div>
            </div>
            <button onclick="openReminderModal(\'' . get_the_title() . '\')" class="w-full mt-4 bg-[#154c79] text-white py-2 rounded-lg font-bold hover:bg-opacity-90 transition">Lembrar-me</button>
        </div>';
    }
    $output .= '</div>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('sc77_agenda', 'sc77_shortcode_agenda');
