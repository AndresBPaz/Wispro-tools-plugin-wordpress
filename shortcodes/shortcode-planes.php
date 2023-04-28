<?php defined('ABSPATH') or die("adios adios"); 
?>
<!-- BEGIND: COLUMNAS DE PLANES -->
<div class="row row-cols-1 row-cols-md-3 g-5">
          <?php 
            global $wpdb; 
            $table_name = $wpdb->prefix."wispro_tools_planes";
            $planes = $wpdb->get_results("SELECT * FROM $table_name"); 

            foreach ($planes as $plan) {
                echo  '<div class="col-lg-6">';
                echo  '<div class="card br-2">';
                echo  '<div class="card-header text-center fw-bold color-primario-bg color-title"
                      style="border-top-left-radius: 2rem !important; border-top-right-radius: 2rem !important; ">
                      <h4 class="fw-bold ">';
                        echo $plan->nombre;
                        echo '</h4>';
                    echo '</div>
                    <div class="card-body">
                      <!--p class="m-0 text-center BwSurcoDemo-Regular">Hasta ';
                      echo $plan->num_dispositivos;
                      echo ' dispositivos</p-->
					  <p class="m-0 text-center montserrat-extrabold ">Fibra Óptica</p>
                      <p class="m-0 text-center color-secundario montserrat-extrabold fs-2"> Pregúntanos</p>
                      <p class="m-0 text-center BwSurcoDemo-Regular">Estrato ';
                      echo $plan->estrato; 
                      echo '</p>
                      <p class="m-0 text-center BwSurcoDemo-Regular"> Instalación en 24 horas</p>
                      <a href="http://wa.me/'.get_option('wispro_tools_whatsapp_number').'" target="_blank"
                        class="btn color-terciario-bg mb-2 BwSurcoDemo-Regular w-75 mx-auto d-block"><svg xmlns="http://www.w3.org/2000/svg"
                          width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                          <path fill-rule="evenodd"
                            d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z">
                          </path>
                        </svg> Te llamamos</a>
                      <a href="?add-to-cart='.$plan->woocomerce_product_id.'" target="_blank" class="btn color-primario-bg text-white mb-2 BwSurcoDemo-Regular w-75 mx-auto d-block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                          class="bi bi-cart-fill text-white" viewBox="0 0 16 16">
                          <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z">
                          </path>
                        </svg> Compra en línea </a>
                      <p class="text-center monserrat-extrabold">
                        <small class="text-muted"><a rel="nofollow" href="javascript:modal_post('.$plan->woocomerce_product_id.')" data-bs-toggle="modal" data-bs-target="#modal25megas">Más especificaciones</a></small>
                      </p>
                    </div>
                  </div>
                </div>';
            }
          ?>
          
          <div class="col-lg-6" >
            <div class="card br-2">
              <div class="card-header text-center fw-bold color-primario-bg color-title"
                style="border-top-left-radius: 2rem !important; border-top-right-radius: 2rem !important; ">
                <h3>Planes Empresariales</h3>
              </div>
              <div class="card-body">
                <p class="p-0 text-center BwSurcoDemo-Regular  fs-4"> Mega dedicada<br> + IP
                  Pública </p>
                <p class="p-0 text-center BwSurcoDemo-Regular  fs-4"> Desde 10MB </p>
                <a href="http://wa.me/<?php echo get_option('wispro_tools_whatsapp_number'); ?>" target="_blank"
                  class="btn color-terciario-bg mb-2 BwSurcoDemo-Regular w-75 mx-auto d-block"><svg xmlns="http://www.w3.org/2000/svg"
                    width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                      d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z">
                     </path>
                  </svg> Pregùntanos</a>
              </div>
            </div>
          </div>
        </div>
        <!-- END: COLUMNAS DE PLANES -->

        <!-- BEGIND: MODAL MAS especificaciones -->
      <div class="modal fade" id="modaldetalles" tabindex="-1" aria-labelledby="modal50megaslabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title modal-title-post" id="modal50megaslabel">50 Megas</h5>
                    <button type="button" class="btn-close btn-close-modaldetalles" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    
                </div>
            </div>
        </div>
      </div>
      <!-- END: modal mas especificaciones -->
      <script type='text/javascript'>
        function modal_post(id){
          jQuery(document).ready(function($){
            //obtener contenido del post por api wordpress
            //get domain from site
            $.ajax({
              url: '<?php echo admin_url('admin-ajax.php'); ?>',
              type: 'GET',
              data: {
                action: 'wispro_tools_get_product',
                id: id
              },
              success: function(data){;
                console.log(data);
                $('#modaldetalles').modal('show');
                //get title
                $('#modaldetalles .modal-title').html(data.name);
                $('#modaldetalles .modal-body').html(data.description_rendered);
              }
            });
          });
        }
      </script>
      <script type='text/javascript'>
        jQuery(document).ready(function($){
          $('.btn-close-modaldetalles').click(function(){
            $('#modaldetalles').modal('hide');
          });
        });
      </script>