<?php

class wispro_tools_RestApi {
    
    private $api_url = 'https://www.cloud.wispro.co/api/v1/';
    private $api_key = '';
    private $api_secret = '';
    private $api_token = '';
   
    public function __construct($api_token = '', $api_url = '') {
        if(!empty($api_token) || !empty($api_url)){
            $this->api_url = $api_url;
            $this->api_token = $api_token;
        }else{
            $this->api_url = $this->get_api_url();
            $this->api_token = $this->getToken();
        }
    }

    public function remote_GET($endpoint, $params = []){
        $params = ($params != '') ? $this->get_params($params) : '';
        $url = $this->api_url . $endpoint . '?' . $params;
        $args = array(
            'headers' => array(
                'Authorization' => $this->api_token,
                'Content-Type' => 'application/json'
            )
        );
        $response = wp_remote_get($url, $args);
        $response_body = json_decode($response['body']);
        return $response_body;
    }

    public function remote_POST($endpoint, $params = []){
        $params = ($params != '') ? $this->get_params($params) : '';
        $url = $this->api_url . $endpoint. '?' . $params;
        $args = array(
            'headers' => array(
                'Authorization' => $this->api_token,
                'Content-Type' => 'application/json'
            )
        );
        $response = wp_remote_post($url, $args);
        $response_body = json_decode($response['body']);
        return $response_body;
    }

    //function create client on wispro cloud rest api


    public function get_params($params){
        //descomentar la siguiente linea si se utiliza la api_key  y la api_secret.
        //$params = array_merge($params, array('api_key' => $this->api_key, 'api_secret' => $this->api_secret));
        $params = http_build_query($params);
        return $params;
    }

    public function get_api_url() {
        $this->api_token = get_option('wispro_tools_api_url');
        return $this->api_token;
    }

    private function getToken() {
        $this->api_token = get_option('wispro_tools_api_token');
        return $this->api_token;
    }

    private function get_api_key() {
        $this->api_key = get_option('wispro_tools_api_key');
        return $this->api_key;
    }

    private function get_api_secret() {
        $this->api_secret = get_option('wispro_tools_api_secret');
        return $this->api_secret;
    }

    public function createCliente($email, $nombre, $apellido) {
        $params = array(
            'email' => $email,
            'nombre' => $nombre,
            'apellido' => $apellido
        );
        $response = $this->remote_POST('clientes', $params);
        return $response;
    }

    public function getClienteByEmail($email) {
        $params = array(
            'email' => $email
        );
        $response = $this->remote_GET('clientes', $params);
        return $response;
    }
}