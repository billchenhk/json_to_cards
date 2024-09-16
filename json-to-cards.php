<?php
/*
Plugin Name: JSON to Cards
Description: A plugin to upload a JSON file and display its data in card format.
Version: 1.0
Author: Bill Chen
*/

// Hook to add admin menu
add_action('admin_menu', 'json_to_cards_menu');

function json_to_cards_menu() {
    add_menu_page('JSON to Cards', 'JSON to Cards', 'manage_options', 'json-to-cards', 'json_to_cards_page');
}

function json_to_cards_page() {
    ?>
    <div class="wrap">
        <h1>Upload JSON File</h1>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="json_file" />
            <input type="submit" name="upload_json" value="Upload" />
        </form>
    </div>
    <?php

    if (isset($_POST['upload_json'])) {
        $json_file = $_FILES['json_file']['tmp_name'];
        $json_data = file_get_contents($json_file);
        $data = json_decode($json_data, true);

        // Save the data to the database
        update_option('json_to_cards_data', $data);
    }
}

// Shortcode to display the data
add_shortcode('json_to_cards', 'display_json_to_cards');

function display_json_to_cards() {
    $data = get_option('json_to_cards_data');
    if (!$data) {
        return 'No data found.';
    }

    $output = '<div class="cards-container">';
    foreach ($data as $item) {
        $output .= '<div class="card">';
        $output .= '<h2>' . esc_html($item['title']) . '</h2>';
        $output .= '<p>' . esc_html($item['description']) . '</p>';
        $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
}

// Enqueue styles
add_action('wp_enqueue_scripts', 'json_to_cards_styles');

function json_to_cards_styles() {
    wp_enqueue_style('json-to-cards-styles', plugins_url('styles.css', __FILE__));
}
?>
