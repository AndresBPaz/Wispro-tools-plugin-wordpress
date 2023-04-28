<?php 

header('Content-Type: application/json'); 
$request = file_get_contents('php://input');
$reg_dump = print_r($request, true);
$json_data = file_put_contents('request.log', $reg_dump);
$action = json_decode($request, true);

$data_client = $action['billing'];

mail("andabopa@gmail.com","Información recibida", $request);

//Create client in wispro cloud rest api
require_once(wispro_tools_PLUGIN_DIR.'/includes/class.wispro_tools_RestApi.php');
$wispro_tools_RestApi = new wispro_tools_RestApi();
//verificar con correo si el cliente existe
$cliente = $wispro_tools_RestApi->getClienteByEmail($action['email']);
if(!$cliente){
    $create = $wispro_api->remote_POST('/clients', [
        'name' => $data_client['first_name'].' '.$data_client['last_name'],
        'email' => $data_client['email'],
        'phone_mobile' => $data_client['phone_mobile'],
        'city' => $data_client['city'],
        'address' =>  $data_client['address_1'],
        'details' => sanitize_text_field('Cliente registrado a traves de woocommerce'),
    ]);
}
