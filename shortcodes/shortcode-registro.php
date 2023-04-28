<?php defined('ABSPATH') or die("adios adios"); 

//formulario crear cliente api rest wispro cloud


function console_log( $data ){
    echo '<script>';
    echo 'console.log('. json_encode( $data ) .')';
    echo '</script>';
} 
?> 
<form action="" method="POST" id="frm-registro">  
    <div class="br-2 bg-light mt-5">
        <div class="form-group">
            <label for="name">Nombre Completo*</label>
            <input type="text" class="form-control" id="nombre" name="name" placeholder="Nombre" required>
        </div>
        <div class="form-group">
            <label for="national_identification_number">Numero de Cedula*</label>
            <input class="form-control" type="number" id="national_identification_number" name="national_identification_number" placeholder="Identificación" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico*</label>
            <input class="form-control" id="email" type="email" name="email" placeholder="Email" required>
        </div>
        <div class="form-group">
            <label for="phone">Teléfono</label>
            <input class="form-control" id="phone" type="tel" name="phone" placeholder="Teléfono">
        </div>
        <div class="form-group">
            <label for="phone_mobile">Celular*</label>
            <input class="form-control" id="phone_mobile" type="number" name="phone_mobile" placeholder="Celular" required>
        </div>
        <div class="form-group">
            <label for="address">Dirección*</label>
            <input class="form-control" id="address" type="text" name="address" placeholder="Dirección" required>
        </div>
        <div class="form-group">
            <label for="city">Ciudad*</label>
            <select class="form-control" id="city" name="city" required>
                <option value="">Seleccione una cuidad</option>
                <option value="Miranda">Miranda</option>
                <option value="Padilla">Padilla</option>
                <option value="Florida">Florida</option>
            </select>  
        </div>
        <div class="form-group mt-3">
            <button id="submit-registro" type="button" class="btn color-terciario-bg">Registrar</button>
        </div>          
    </div>
</form>
<div class="error"> </div>

<script  type="text/javascript" > 
jQuery(document).ready(function($){ 
    $('#submit-registro').click(function(e){
        e.preventDefault(); 
        $('#submit-registro').html('<div class="loading"><img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) .'/images/loader.gif';?>" alt="loading" /></div>');

        var name = $('#nombre').val();
        var national_identification_number = $('#national_identification_number').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var phone_mobile = $('#phone_mobile').val();
        var address = $('#address').val();
        var city = $('#city').val();

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'wispro_tools_registrar_usuario',
                name: name,
                national_identification_number: national_identification_number,
                email: email,
                phone: phone,
                phone_mobile: phone_mobile,
                address: address,
                city: city,
            },
            success: function(data){
                console.log(data);
                $('#submit-registro').html('Registrar');
            },
            error: function(data){
                $('#submit-registro').html('Registrar');
                $('.error').html('<div class="alert alert-danger" role="alert">'+data.responseText+'</div>');
                console.log(data);
            }
        });
    });
});
</script>
