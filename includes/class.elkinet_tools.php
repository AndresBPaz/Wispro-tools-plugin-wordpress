<?php

class wispro_tools {

    //funcion para comprobar requisitos del plugin
    public function check(){
        //comprobar option wispro_tools_api_token
        if(!get_option('wispro_tools_api_token')){
            return false;
        }
        //comprobar option wispro_tools_api_url
        if(!get_option('wispro_tools_api_url')){
            return false;
        }
        return true;
    }
}