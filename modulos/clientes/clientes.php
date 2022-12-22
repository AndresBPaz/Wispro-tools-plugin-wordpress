<?php defined('ABSPATH') or die("Bye bye");
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));

require_once('class.table-clientes.php');

//Pagina de listado y registro de clientes desde la rest api wispro cloud. 

$wispro_class = new wispro_tools();
?>
<div class="wrap"> 
    <?php 
	//comprobar option wispro_tools_api_token
	if(!$wispro_class->check()){
		echo '<div class="error"><p>'.__('Para utilizar el plugin porfavor termine de configurar el plugin en la pagina de configuracion.').'</p></div>';
	}else{
        get_table();
        //boton abrir formulario de crear cliente
        echo '<a class="button button" href="?page='.$_REQUEST['page'].'&action=crear">Crear cliente</a>';
    } ?>    
</div>

<?php

function get_table(){
    echo '<div class="updated"><p>'.__('La siguiente lista es una lista obtenida del erp wispro_cloud.').'</p></div>';

    $testListTable = new table_clientes();
    $testListTable->prepare_items();
    $testListTable->display();
}

//clients_payment_gateways

