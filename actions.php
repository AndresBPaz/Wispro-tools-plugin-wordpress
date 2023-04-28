<?php defined('ABSPATH') or die("adios adios");

add_action('wp_ajax_nopriv_wispro_tools_registrar_usuario', 'crear_cliente');
add_action('wp_ajax_wispro_tools_registrar_usuario', 'crear_cliente');
//action woocomerce get product attributes
add_action('wp_ajax_nopriv_wispro_tools_get_product', 'get_product_data');
add_action('wp_ajax_wispro_tools_get_product', 'get_product_data');

function get_product_data(){
    if(isset($_REQUEST['id'])){
        $id = $_REQUEST['id'];
        $product = wc_get_product($id);
        $response = array(
            'id' => $product->get_id(),
            'name' => $product->get_name(),
            'price' => $product->get_price(),
            'image' => $product->get_image_id(),
            'description_rendered' => $product->get_description(),
            'attributes' => $product->get_attributes(),
        );
        wp_send_json($response);
        wp_die();
    }else{
        wp_send_json(array('error' => 'property id not found'));
        wp_die();
    }
}
function wispro_api(){
    return new wispro_tools_RestApi();
} 

function crear_cliente(){ 
    $data = [];
    $errors = [];
    $empty = false;
    
    $phone = isset($_REQUEST['phone']) ? sanitize_text_field( $_REQUEST['phone']) : '';

    if(isset($_REQUEST['name'])){
        $name = sanitize_text_field( $_REQUEST['name']);
        if(empty($name)){
            $errors[] = 'El nombre es requerido';
            $empty = true;
        }
    }else{
        $errors[] = 'El nombre es requerido';
        $empty = true;
    }

    if(isset($_REQUEST['national_identification_number'])){
        $national_identification_number = sanitize_text_field( $_REQUEST['national_identification_number']);
        if(empty($national_identification_number)){
            $errors[] = 'El numero de cedula es requerido';
            $empty = true;
        }
    }else{
        $errors[] = 'El numero de cedula es requerido';
        $empty = true;
    }

    if(isset($_REQUEST['email'])){
        $email = sanitize_text_field( $_REQUEST['email']);
        if(empty($email)){
            $errors[] = 'El correo electronico es requerido';
            $empty = true;
        }
    }else{
        $errors[] = 'El correo electronico es requerido';
        $empty = true;
    }

    if(isset($_REQUEST['phone_mobile'])){
        $phone_mobile = sanitize_text_field( $_REQUEST['phone_mobile']);
        if(empty($phone_mobile)){
            $errors[] = 'El numero de celular es requerido';
            $empty = true;
        }
    }else{
        $errors[] = 'El numero de celular es requerido';
        $empty = true;
    }

    if(isset($_REQUEST['address'])){
        $address = sanitize_text_field( $_REQUEST['address']);
        if(empty($address)){
            $errors[] = 'La direccion es requerida';
            $empty = true;
        }
    }else{
        $errors[] = 'La direccion es requerida';
        $empty = true;
    }

    if(isset($_REQUEST['city'])){
        $city = sanitize_text_field( $_REQUEST['city']);
        if(empty($city)){
            $errors[] = 'La ciudad es requerida';
            $empty = true;
        }
    }else{
        $errors[] = 'La ciudad es requerida';
        $empty = true;
    }

    if(!$empty){ 
        $wispro_api = new wispro_tools_RestApi();
        $client_exist = $wispro_api->remote_GET('/clients',[
            'national_identification_number_eq' => $national_identification_number
            ]);

        $data['status'] = $client_exist->status;
        if($client_exist->status == 200){
            if ( $client_exist->meta->pagination->total_records != 0) {
                $data['message'] = 'ya existe un cliente registrado con esta cedula.';
            }else{
                $create = $wispro_api->remote_POST('/clients', [
                    'name' => $name,
                    'national_identification_number' => $national_identification_number,
                    'email' => $email,
                    'phone' => $phone,
                    'phone_mobile' => $phone_mobile,
                    'city' => $city,
                    'address' =>  $address,
                    'details' => sanitize_text_field('Cliente registrado a traves de la pagina web'),
                ]);
                if($create->status == '200'){
                    //crear cliente por rest api wispro 
                    $data['data'] = $create->data;
                    $data['message'] = 'Registrado con exito.';
                }
                $data['responseWispro'] = $create;
            }
        }
    }else{
        $data['status'] = 400;
        $data['errors'] = $errors;
    }
    
    wp_send_json($data);
    wp_die();
}