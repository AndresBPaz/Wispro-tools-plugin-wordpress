<?php defined('ABSPATH') or die("Bye bye");
 
function shortcode_planes($atts) {
    ob_start();
    include wispro_tools_PLUGIN_DIR.'shortcodes/shortcode-planes.php';
    return ob_get_clean();
}
add_shortcode( 'wispro_tools_planes', 'shortcode_planes' );

function shortcode_payu_respuesta($atts) {
    ob_start();
    include wispro_tools_PLUGIN_DIR.'shortcodes/shortcode-payu_respuesta.php';
    return ob_get_clean();
}
add_shortcode( 'wispro_tools_payu_respuesta', 'shortcode_payu_respuesta' );

function shortcode_registro($atts) {
    ob_start();
    include wispro_tools_PLUGIN_DIR.'shortcodes/shortcode-registro.php';
    return ob_get_clean();
}
add_shortcode( 'wispro_tools_registro', 'shortcode_registro' );
