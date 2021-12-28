<?php defined('ABSPATH') or die("Bye bye");
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));

require_once('class.table-pagos.php');


//pagina de listado de pagos en rest api wispro cloud.
//tabla pagos wispro cloud
$wispro_class = new wisprointegration();
// script echo console.log pagos
?>
<div class="wrap">
    <h2><?php _e( 'Pagos','Wispro_integraton' ) ?></h2>
    <?php 
    //comprobar option wisprointegration_api_token
    if(!$wispro_class->check()){
        echo '<div class="error"><p>'.__('Para utilizar el plugin porfavor termine de configurar el plugin en la pagina de configuracion.').'</p></div>';
    }else{
        get_table();
    } ?>
</div>

<?php

function get_table(){
    $testListTable = new table_pagos();
    $testListTable->prepare_items();
    $testListTable->display();
}