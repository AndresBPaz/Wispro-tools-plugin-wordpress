<?php


class table_planes extends WP_List_Table {
    
    /** ************************************************************************
     * Normally we would be querying data from a database and manipulating that
     * for use in your list table. For this example, we're going to simplify it
     * slightly and create a pre-built array. Think of this as the data that might
     * be returned by $wpdb->query()
     * 
     * In a real-world scenario, you would make your own custom query inside
     * this class' prepare_items() method.
     * 
     * @var array 
     **************************************************************************/
    public $data;

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We 
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'plan',     //singular name of the listed records
            'plural'    => 'planes',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title() 
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as 
     * possible. 
     * 
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     * 
     * For more detailed insight into how columns are handled, take a look at 
     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default($item, $column_name){
        switch($column_name){
            case 'descarga_kb':
            case 'subida_kb':
            case 'precio':
            case 'title':
                return $item->$column_name;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named 
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     * 
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     * 
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_title($item){
        
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=wispro_tools&tab=planes&action=%s&plan=%s">Editar</a>','edit',$item->id),
            'delete'    => sprintf('<a href="?page=wispro_tools&tab=planes&action=%s&plan=%s">Borrar</a>','delete',$item->id),
        );
        
         //item post name from id
        $post_name = ($item->id !== 0 ) ? get_post_field( 'post_name', $item->id ) : 'non post';
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(Post:%2$s)</span>%3$s',
            /*$1%s*/ $item->nombre,
            /*$2%s*/ $post_name,
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_precio($item){
        $id_producto_wc = $item->woocomerce_product_id;
        if ($id_producto_wc != 0) {
            $product_wc = wc_get_product($id_producto_wc);
            $precio = $product_wc->get_price();
            return '$'.$precio;
        }else{
            return 'incompleto';
        }
    }


    function column_descarga_kb($item){
        if($item->descarga_kb >= 1000){
            return $item->descarga_kb/1000 . ' MB';
        }
        return $item->descarga_kb . ' KB';
    }

    function column_subida_kb($item){
        if($item->subida_kb >= 1000){
            return $item->subida_kb/1000 . ' MB';
        }
        return $item->subida_kb . ' KB';
    }


    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item->id                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value 
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     * 
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     * 
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'       => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'   => 'Nombre',
            'precio'   => 'Precio',
            'subida_kb'   => 'Subida',
            'descarga_kb' => 'Descarga',
        );
        return $columns;
    }


    /** ************************************************************************
     * Optional. If you want one or more columns to be sortable (ASC/DESC toggle), 
     * you will need to register it here. This should return an array where the 
     * key is the column that needs to be sortable, and the value is db column to 
     * sort by. Often, the key and value will be the same, but this is not always
     * the case (as the value is a column name from the database, not the list table).
     * 
     * This method merely defines which columns should be sortable and makes them
     * clickable - it does not handle the actual sorting. You still need to detect
     * the ORDERBY and ORDER querystring variables within prepare_items() and sort
     * your data accordingly (usually by modifying your query).
     * 
     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)
     **************************************************************************/
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('nombre',false),     //true means it's already sorted
            'precio'    => array('precio',false),
            'subida_kb'  => array('subida_kb',false),
            'descarga_kb'  => array('descarga_kb',false),
        );
        return $sortable_columns;
    }


    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     * 
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     * 
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     * 
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    /*function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete',
        );
        return $actions;
    }*/


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     * 
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            $this->delete_plan();
        }

        if( 'edit' === $this->current_action() ) {
            $this->edit_plan();
        }
        //add
        if( 'add' === $this->current_action() ) {
            $this->add_plan();
        }
        
    }

    function delete_plan(){
        global $wpdb; 

        if(isset($_POST['delete'])){
            $id = $_POST['id'];
            $delete = $wpdb->delete($wpdb->prefix . 'wispro_tools_planes', array ('id' => $id));
            if(false === $delete){
                echo '<div class="wrap"><h2>No se pudo eliminar el plan</h2></div>';
            }else{
                echo '<script>window.location.href = "?page=wispro_tools&tab=planes";</script>';
            }
        }

        $id = $_GET['plan'];

        //form confirm delete plan
        $confirm = '<div class="wrap">
            <h3>¿Está seguro que desea eliminar el plan?</h3>
            <form method="post" action="">
                <input type="hidden" name="id" value="'.$id.'">
                <input type="submit" name="delete" value="Eliminar" class="button-primary">
                <a href="?page=wispro_tools&tab=planes" class="button-secondary">Cancelar</a>
            </form>
        </div>';
        echo $confirm;

        wp_die();
    }

    function edit_plan(){
        global $wpdb; 

        if(isset($_POST['submit'])){
            $plan_name = $_POST['plan_name'];
            $plan_subida = $_POST['plan_subida'];
            $plan_descarga = $_POST['plan_descarga'];
            $plan_id = $_POST['id'];
            $dispositivos = $_POST['num_dispositivos'];
            $estrato = $_POST['plan_estrato'];
            $woocomerce_product_id = $_POST['woocomerce_product_id'];
            $update = $wpdb->update($wpdb->prefix . 'wispro_tools_planes', array(
                'nombre' => $plan_name,
                'subida_kb' => $plan_subida,
                'descarga_kb' => $plan_descarga,
                'num_dispositivos' => $dispositivos,
                'estrato' => $estrato,
                'woocomerce_product_id' => $woocomerce_product_id
            ), array( 'id' => $plan_id ));
            if(false === $update){
                echo '<script>alert("Error al actualizar el plan'.$update.'");</script>';
            }else{
                echo 'informacion guardada';
                echo '<script>window.location.href = "?page=wispro_tools&tab=planes";</script>';
            }
        }
            
        //form edit plan
        $id = $_GET['plan'];
        $plan = $wpdb->get_row($wpdb->prepare("SELECT * FROM $wpdb->prefix" . "wispro_tools_planes WHERE id = '%s'", $id));
        $plan_name = $plan->nombre;
        $plan_subida = $plan->subida_kb;
        $plan_descarga = $plan->descarga_kb;
        $plan_id = $plan->id;
        $estrato = $plan->estrato;
        $dispositivos = $plan->num_dispositivos;
        $woocomerce_product_id = $plan->woocomerce_product_id;
        

        $html = '<div class="wrap">';
        $html .= '<h3>Editar plan</h3>';
        $html .= '<form method="post" action="">';
        $html .= '<input type="hidden" name="id" value="'.$plan_id.'">';
        $html .= '<table class="form-table">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_name">Nombre</label></th>';
        $html .= '<td><input name="plan_name" type="text" id="plan_name" value="'.$plan_name.'" class="regular-text"></td>';
        $html .= '</tr>';
        //input campo estrato
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_estrato">Estrato</label></th>';
        $html .= '<td><input name="plan_estrato" type="text" id="plan_estrato" value="'.$estrato.'" class="regular-text"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_subida">Subida</label></th>';
        $html .= '<td><input name="plan_subida" type="text" id="plan_subida" value="'.$plan_subida.'" class="regular-text"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_descarga">Descarga</label></th>';
        $html .= '<td><input name="plan_descarga" type="text" id="plan_descarga" value="'.$plan_descarga.'" class="regular-text"></td>';        
        $html .= '</tr>';
        // cantidad de dispositivos recomendados
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_descarga">Dispositivos recomendados </label></th>';
        $html .= '<td><input name="num_dispositivos" type="text" id="num_dispositivos" value="'.$dispositivos.'" class="regular-text"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="payment_url">product woocomerce</label></th>';
        $html .= '<td><select name="woocomerce_product_id" id="woocomerce_product_id">';
        $html .= '<option value="0">Seleccione un producto</option>';
        $products = get_posts(array('post_type' => 'product', 'numberposts' => -1));
        foreach($products as $product){
            $html .= '<option value="'.$product->ID.'" ';
            if($product->ID == $woocomerce_product_id){
                $html .= 'selected';
            }
            $html .= '>'.$product->post_title.'</option>';
        }
        $html .= '</select><a href="'.get_admin_url().'post-new.php?post_type=product" class="button-secondary">Crear nuevo producto</a></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar">';
        $html .= '<a href="?page=wispro_tools&tab=planes" class="button button-secondary">Cancelar</a></p>';
        $html .= '</form>';
        $html .= '</div>';
        echo $html;
        wp_die();
    }

    function add_plan(){
        global $wpdb; 
        if(isset($_POST['submit'])){
            $plan_name = $_POST['plan_name'];
            $plan_subida = $_POST['plan_subida'];
            $plan_descarga = $_POST['plan_descarga'];
            $dispositivos = $_POST['num_dispositivos'];
            $estrato = $_POST['plan_estrato'];
            $woocomerce_product_id = $_POST['woocomerce_product_id'];
            $insert = $wpdb->insert(
                $wpdb->prefix . 'wispro_tools_planes',
                array(
                    'nombre' => $plan_name,
                    'subida_kb' => $plan_subida,
                    'descarga_kb' => $plan_descarga,
                    'num_dispositivos' => $dispositivos,
                    'estrato' => $estrato,
                    'woocomerce_product_id' => $woocomerce_product_id
                ),
                array(
                    '%s',
                    '%s',
                    '%s',
                    '%s',
                    '%d',
                    '%d',
                    '%d',
                    '%s',
                    '%s',
                    '%d'
                )
            );
            if($insert){
                $this->show_message('Plan creado correctamente');
                //echo json_encode($insert);
                echo '<script>window.location.href = "?page=wispro_tools&tab=planes";</script>';

            }else{
                $this->show_message('Error al crear el plan');
            }
        }

        //form add plan
        $html = '<div class="wrap">';
        $html .= '<h3>Añadir plan</h3>';
        $html .= '<form method="post" action="">';
        $html .= '<table class="form-table">';
        $html .= '<tbody>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_name">Nombre</label></th>';
        $html .= '<td><input name="plan_name" type="text" id="plan_name" placeholder="Titulo del plan" class="regular-text"></td>';
        $html .= '</tr>';
        //input campo estrato
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_estrato">Estrato</label></th>';
        $html .= '<td><input name="plan_estrato" type="text" id="plan_estrato" placeholder="liste los estratos para este plan" class="regular-text"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_subida">Subida</label></th>';
        $html .= '<td><input name="plan_subida" type="text" id="plan_subida" class="regular-text"><p>Digite la velocidad de subida ej: 25000 para un plan de 25Mb </p></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_descarga">Descarga</label></th>';
        $html .= '<td><input name="plan_descarga" type="text" id="plan_descarga" class="regular-text"><p>Digite la velocidad de bajada ej: 25000 para un plan de 25Mb </p></td>';        
        $html .= '</tr>';
        // cantidad de dispositivos recomendados
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="plan_descarga">Dispositivos recomendados </label></th>';
        $html .= '<td><input name="num_dispositivos" type="text" id="num_dispositivos" placeholder="Digite la cantidad de dispositivos recomendados " class="regular-text"></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<th scope="row"><label for="payment_url">product woocomerce</label></th>';
        $html .= '<td><select name="woocomerce_product_id" id="woocomerce_product_id">';
        $html .= '<option value="0">Seleccione un producto</option>';
        $products = get_posts(array('post_type' => 'product', 'numberposts' => -1));
        foreach($products as $product){
            $html .= '<option value="'.$product->ID.'">'.$product->post_title.'</option>';
        }
        $html .= '</select><a href="'.get_admin_url().'post-new.php?post_type=product" class="button-secondary">Crear nuevo producto</a></td>';
        $html .= '</tr>';
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar">';
        $html .= '<a href="?page=wispro_tools&tab=planes" class="button button-secondary">Cancelar</a></p>';
        $html .= '</form>';
        $html .= '</div>';
        echo $html;
        wp_die();
    }

    function show_message($message){
        echo '<script>alert("'.$message.'");</script>';
    }


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
       //This is used only if making any database queries

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = 5;
        
        
        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
        
        /**
         * REQUIRED. Finally, we build an array to be used by the class for column 
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
        
        
        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example 
         * package slightly different than one you might build on your own. In 
         * this example, we'll be using array manipulation to sort and paginate 
         * our data. In a real-world implementation, you will probably want to 
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
        global $wpdb;
        $table_name = $wpdb->prefix.'wispro_tools_planes';
        $data = $wpdb->get_results("SELECT * FROM $table_name");
        
        /**
         * This checks for sorting input and sorts the data in our array accordingly.
         * 
         * In a real-world situation involving a database, you would probably want 
         * to handle sorting by passing the 'orderby' and 'order' values directly 
         * to a custom query. The returned data will be pre-sorted, and this array
         * sorting technique would be unnecessary.
         */
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'nombre'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a->$orderby, $b->$orderby); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        
        
        /***********************************************************************
         * ---------------------------------------------------------------------
         * vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
         * 
         * In a real-world situation, this is where you would place your query.
         *
         * For information on making queries in WordPress, see this Codex entry:
         * http://codex.wordpress.org/Class_Reference/wpdb
         * 
         * ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
         * ---------------------------------------------------------------------
         **********************************************************************/
        
                
        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently 
         * looking at. We'll need this later, so you should always include it in 
         * your own package classes.
         */
        $current_page = $this->get_pagenum();
        
        /**
         * REQUIRED for pagination. Let's check how many items are in our data array. 
         * In real-world use, this would be the total number of items in your database, 
         * without filtering. We'll need this later, so you should always include it 
         * in your own package classes.
         */
        $total_items = count($data);
        
        
        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to 
         */
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        
        
        
        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where 
         * it can be used by the rest of the class.
         */
        $this->items = $data;
        
        
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}