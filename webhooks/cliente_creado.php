<?php 
require_once('../vendor/autoload.php');

header('Content-Type: application/json'); 
$request = file_get_contents('php://input');
$reg_dump = print_r($request, true);
$json_data = file_put_contents('request.log', $reg_dump);

$action = json_decode($request, true);

$msg = '';
$billing = $action['billing'];
$data = [
    'name' => $action['first_name'].' '.$action['last_name'],
    'email' => $action['email'],
    'phone_mobile' => $billing['phone'],
    'city' => $billing['city'],
    'state' => $billing['state'],
    'address' =>  $billing['address_1'],
    'details' => 'Cliente registrado a traves de woocommerce',
];

$client = new \GuzzleHttp\Client();
$params = ($data != '') ? get_params($data) : '';
$url = 'https://www.cloud.wispro.co/api/v1/clients'. '?' . $params;
$response = $client->request('POST', $url , [
    'headers' => [
      'Accept' => 'application/json',
      'Authorization' => '8c1cb249-e3e9-412b-93b5-7a278d676ba3',
    ],
    'data' => $params
  ]);

$msg .= '<h2> Nuevo cliente registrado: </h2>';
$msg .= '<p> Se registro un nuevo cliente de forma automatica en la tienda.</p>';
$msg .= '<p>Nombre: '.$action['billing']['first_name'].' '.$action['billing']['last_name'].'</p>';
$msg .= '<p>Email: '.$action['billing']['email'].'</p>';
$msg .= '<p>Telefono: '.$action['billing']['phone'].'</p>';
$msg .= '<p>Ciudad: '.$action['billing']['city'].'</p>';
$msg .= '<p>Dirección: '.$action['billing']['address_1'].'</p>';
$msg .= '/n<p>Este es un correo automatico, no responder.</p>';

mail("andabopa@gmail.com","Nuevo cliente registrado.", $msg);    

function get_params($params){
    //descomentar la siguiente linea si se utiliza la api_key  y la api_secret.
    //$params = array_merge($params, array('api_key' => $this->api_key, 'api_secret' => $this->api_secret));
    $params = http_build_query($params);
    return $params;
}