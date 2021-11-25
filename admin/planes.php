<?php
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));
$wispro_class = new wisprointegration();

?>
<div class="wrap">
    <h2><?php _e( 'Planes','Wispro_integraton' ) ?></h2>
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
    $testListTable = new table_planes();
    $testListTable->prepare_items();
    $testListTable->display();
}

