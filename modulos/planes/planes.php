<?php defined('ABSPATH') or die("Bye bye");
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));

require_once('class.table-planes.php');

$wispro_class = new wispro_tools();
?>
<div class="wrap"> 
    <?php 
	//comprobar option wispro_tools_api_token
	if(!$wispro_class->check()){
		echo '<div class="error"><p>'.__('Para utilizar el plugin porfavor termine de configurar el plugin en la pagina de configuracion.').'</p></div>';
	}else{
        get_table();
    } ?>
</div>

<?php

function get_table(){

    if (!isset($_GET['action'])) {
        //buttom add new
        $url = admin_url('admin.php?page=wispro_tools&tab=planes&action=add');
        $buttom_add_new = '<a href="'.$url.'" class="page-title-action">'.__('Agregar nuevo','Wispro_integraton').'</a>';
        echo $buttom_add_new;
    }

    //table
    $testListTable = new table_planes();
    $testListTable->prepare_items();
    $testListTable->display();
}

