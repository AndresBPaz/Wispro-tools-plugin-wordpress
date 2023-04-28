<?php 

class shortcode_proceso_compra{
    private $respuesta = array();

    private function wispro_api(){
        return new wispro_tools_RestApi();
    }

    function proceso_compra(){
        if(isset($_POST['form_compra'])){
        
            //variables
            $planid = $_POST['plan']; 
            
            $client = array(
                'email' => $_POST['email'],
                'name' => $_POST['nombre'],
                'city' => $_POST['city'], 
                'address' => $_POST['address'],
                'phone_mobile' => $_POST['phone_mobile'],
                'national_identification_number' => $_POST['national_identification_number'],
                'state' => function(){ //TODO: Ciudades parametrizables
                    switch ($_POST['city']) {
                        case 'Miranda':
                        return 'Cauca';
                        break;
                        case 'Padilla':
                        return 'Cauca';
                        break;
                        case 'Florida':
                        return 'Valle';
                        break;
                    }
                }
            );

            //crear cliente
            $cliente = $this->crear_cliente($client);
            //crear contrato  
            $contrato = ($cliente)? function(){ 
                $data_contrato = [
                    'client_id' => $cliente->id,
                    'plan_id' => $planid,
                    'server_configuration_id' => 1,
                    'ip' => '192.168.1.200',
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude'],
                    'state' => 'active',
                ];
                return $this->crear_contrato($data_contrato);
            }: false;
            ////generar factura inicial wispro api
            $factura = ($contrato)? function(){
                $data_factura = [
                    'client_id' => $cliente->id,
                    'plan_id' => $planid,
                    'server_configuration_id' => 1,
                    'ip' => '',
                    'latitude' => $_POST['latitude'],
                    'longitude' => $_POST['longitude'],
                    'state' => 'active',
                ];
                return $this->generar_primera_factura($data_factura);
            }: false;

            //realizar pago de la factura inicial por gateway de pago
            $gateway = ($factura)? $this->gateway_pago() : false;
        }
        return $this->respuesta;
    }

    function crear_cliente($data_client) {
        //comprobar por cedula si existe el cliente
        $client_exist = $this->wispro_api()->remote_GET('/clients',[
            'national_identification_number_eq' => $data_client['national_identification_number']
        ]);

        if(!empty($client_exist) && $client_exist->meta->pagination->total_records != 0){
            $this->respuesta['message'] = '<div class="alert alert-danger" role="alert">
            El cliente con el numero de documento '.$data_client['national_identification_number'].' ya se encuentra registrado.<br>
            Accede desde <a href="'.get_option('wispro_tools_url_portal_cliente').'">aqui</a> al portal de cliente.
            </div>';
            return false;
        }else{
            $data = $this->wispro_api()->remote_POST('/clients',$data_client);
            if($data->status == '200'){
                //crear cliente por rest api wispro 
                $this->respuesta['message'] = '<div class="alert alert-success" role="alert">Client added successfully </div>';
                return true;
            }
        }
        return true;
    }

    function crear_contrato($data_contrato){
        
    }

    function generar_primera_factura($data_factura) {
        
        $data = $this->wispro_api()->remote_POST('/invoices',$data_factura);
        if($data->status == '200'){
            //crear factura por rest api wispro 
            $this->respuesta['message'] = '<div class="alert alert-success" role="alert">Factura generada exitosamente</div>';
            return true;
        }
        return true;
    }

    function gateway_pago(){
        //generar pago de la factura
    }

}