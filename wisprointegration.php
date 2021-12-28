<?php
/** Archivo principal del plugin, 
 * Contiene una cabecera estándar con los datos mínimos que WordPress necesita 
 * para realizar la instalación del plugin. Además, se definen las constantes y 
 * se realizan acciones iniciales.  
*/
/**
 * @package wispro_integration
 * @version 0.0.1
 */
/*
Plugin Name: Wispro Integration
Plugin URI: http://dominatecode-co.com/wispro_api_worpress_plugin/
Description: Este es un plugin que permite la integracion de wordpress con la api de wispro. permite convertir a wordpress integrarse con algunas de las capacidades de wispro.
Author: DominateCode
Version: 0.0.1
Author URI: http://DominateCode-co.com/
Licence: 
*/
defined('ABSPATH') or die("Bye bye");
define('WISPROINTEGRATION_PLUGIN_DIR', plugin_dir_path(__FILE__));

include(WISPROINTEGRATION_PLUGIN_DIR.'/opciones.php');
include(WISPROINTEGRATION_PLUGIN_DIR.'/actions.php');

//cargar classes 
if(!class_exists('WP_List_Table')){
   require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require_once(WISPROINTEGRATION_PLUGIN_DIR.'/includes/class.wisproIntegrationRestApi.php');
require_once(WISPROINTEGRATION_PLUGIN_DIR.'/includes/class.wisprointegration.php');

//Función que se ejecuta cuando el plugin es activado
function wispro_integration_activar(){
   load_plugin_textdomain('wisprointegration');
   //crear options para el plugin.
   add_option('wispro_integration_costo_instalacion','',NULL,'yes');
   add_option('wisprointegration_api_token', '', NULL, 'yes');
   add_option('wisprointegration_api_url', '', NULL, 'yes');
   add_option('wisprointegration_pagina_proceso_compras', '', NULL, 'yes');
   add_option('wisprointegration_whatsapp_number', '', NULL, 'yes');
   add_option('wisprointegration_url_portal_cliente','', NULL, 'yes');

   //crear tabla de planes
   global $wpdb;
   $tabla_planes = $wpdb->prefix . 'wispro_integration_planes';
   if ( $wpdb->get_var("SHOW TABLES LIKE '$tabla_planes'") != $tabla_planes ) {
      // Table was not created !!
      $charset_collate = $wpdb->get_charset_collate();
      $sql = "CREATE TABLE $tabla_planes (
         id varchar(38) NOT NULL,
         nombre VARCHAR(255) NOT NULL,
         estrato varchar(16),
         post_id bigint(20),
         precio VARCHAR(255) NOT NULL,
         subida_kb int(3) NOT NULL,
         descarga_kb int(3) NOT NULL,
         num_dispositivos int(2),
         PRIMARY KEY (id)
      ) $charset_collate;";
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);
   }
}
register_activation_hook(__FILE__, 'wispro_integration_activar');

//función que se ejecuta cuando el plugin es desactivado
function wispro_integration_desactivar(){

} 
register_deactivation_hook(__FILE__, 'wispro_integration_desactivar');

//scripts para el frontend
function scripts(){
   wp_enqueue_style('wispro_integration_css', plugins_url('/css/wispro_integration.css', __FILE__));
   wp_enqueue_script('wispro_integration_js', plugins_url('/js/wispro_integration.js', __FILE__), array('jquery'));
}
add_action('wp_enqueue_scripts', 'scripts');