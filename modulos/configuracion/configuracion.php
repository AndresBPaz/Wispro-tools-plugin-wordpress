<?php
if (! current_user_can ('manage_options')) wp_die (__ ('No tienes suficientes permisos para acceder a esta página.'));
?>
<div class="wrap"> 
   <?php actions(); ?>
   <div class="">
      <form method="post" action="<?php echo admin_url('admin.php?page=wispro_tools&tab=config'); ?>">
         <?php settings_fields( 'wispro_tools_settings' ); ?>
         <?php do_settings_sections( 'wispro_tools_settings' ); ?>
         <table class="form-table">
            <!-- input llenar option api token -->
            <tr valign="top">
               <th scope="row">API Token</th>
               <td><input type="text" name="api_token" value="<?php echo esc_attr( get_option('wispro_tools_api_token') ); ?>" /></td>
            </tr>
            <!-- input llenar option url api token -->
            <tr valign="top">
               <th scope="row">URL API Token</th>
               <td><input type="text" name="url_api_token" value="<?php echo esc_attr( get_option('wispro_tools_api_url') ); ?>" /></td>
            </tr>
         </table>
         <?php submit_button(); ?>
      </form>
      <?php 
      //comprobar option wispro_tools_api_token
      $wispro_class = new wispro_tools();
      if(!$wispro_class->check()){
         echo '<div class="error"><p>'.__('Para utilizar el plugin porfavor termine de configurar el plugin en la pagina de configuracion.').'</p></div>';
      }else{
         ?>
         <!-- llenar tabla planes desde wispro cloud -->
         <form method="post" action="<?php echo admin_url('admin.php?page=wispro_tools&tab=config&action=importar_planes_wispro_cloud'); ?>">
            <?php settings_fields( 'wispro_tools_settings' ); ?>
            <?php do_settings_sections( 'wispro_tools_settings' ); ?>
            <table class="form-table">
               <tr valign="top">
                  <th scope="row">Importar planes desde wispro cloud</th>
                  <td><input type="submit" name="wispro_tools_llenar_tabla_planes" value="Importar" /></td>
               </tr>
            </table>
         </form>
         <?php
      }
      ?>
   </div>
</div>
<?php

function actions(){
   //get actions 
   if(isset($_GET['action'])){
      switch($_GET['action']){
            case 'importar_planes_wispro_cloud':
               wispro_cloud_importar_planes();
               break;
      }
   }
  
   //post actions
   //action llenar api_token
   if (isset ($_POST['api_token'])) {
      update_option ('wispro_tools_api_token', $_POST['api_token']);
   }

   // action llenar wispro_tools_api_url
   if (isset ($_POST['url_api_token'])) {
      update_option ('wispro_tools_api_url', $_POST['url_api_token']);
   }

   
   echo '<div class="updated"><p>' . __('Configuracion guardada.') . '</p></div>';
}

//llenar tabla planes desde wispro cloud
function wispro_cloud_importar_planes(){
   global $wpdb;
   $wispro_api = new wispro_tools_RestApi();
   $planes = $wispro_api->remote_GET('plans');
   //echo '<script> console.log('.$planes.')</script>';
   foreach ($planes->data as $key) {
      //comprobar si existe el plan en la tabla sql
      $plan = $key->id;
      $sql = "SELECT * FROM ". $wpdb->prefix ."wispro_tools_planes WHERE id = '$plan'";
      $result = $wpdb->get_results($sql);
      //error al realizar la consulta
      if ($result === false) {
         return 'error';
      }

      if(count($result) == 0){
         //si no existe el plan en la tabla mysql insertarlo
         $insert = $wpdb->insert(
            $wpdb->prefix . 'wispro_tools_planes', array(
               'id' => $key->id,
               'nombre' => $key->name,
               'estrato' => '1, 2 y 3',
               'num_dispositivos' => '',
               'subida_kb' => $key->ceil_down_kbps,
               'descarga_kb' => $key->ceil_up_kbps,
               'woocomerce_product_id' => '',
            )
         );
         if( !$insert ) {
            echo '<div class="error"><p>' . __('Error al importar plan.'). $key->name . $wpdb->last_error . '</p></div>';
            return 'error';
         }
      } else {
         //si existe el plan en la tabla sql actualizarlo
         $update = $wpdb->update( $wpdb->prefix . 'wispro_tools_planes', array(
            'nombre' => $key->name,
            'subida_kb' => $key->ceil_down_kbps,
            'descarga_kb' => $key->ceil_up_kbps
         ), array('id' => $key->id),array(),array());
         if( false === $update ) {
            echo '<div class="error"><p>' . __('Error al actualizar plan.'). $key->name . $wpdb->last_error  . '</p></div>';
            return 'error';
         }
      }
   }
   echo '<div class="updated"><p>' . __ ('Planes importados correctamente.') . '</p></div>';
}