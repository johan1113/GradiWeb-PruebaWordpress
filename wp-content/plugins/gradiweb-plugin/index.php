<?php
/**
 * @package GradiwebPlugin
 * 
 * Plugin Name: NASA_astronautas
 * Plugin URI:
 * Description: NASA Astronaut Aspiring Reception Form for 2022
 * Author: Johan Medina
 * Author URI:
 * Version: 0.1
 */

//register_activation_hook( __FILE__, 'myplugin_update_db_check' );
add_action( 'plugins_loaded', 'jal_install' );

global $jal_db_version;
$jal_db_version = '1.0';

function jal_install() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'NASA_';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		data text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}

function myplugin_update_db_check() {
    global $jal_db_version;
    if ( get_site_option( 'jal_db_version' ) != $jal_db_version ) {
        jal_install();
    }
}


//implement new menu options at the admin menu of wrodpress back-end
add_action("admin_menu", "addMenu");

//add the main tag name of the plugin "NASA clientes" at the wordpress menu
//add "Configuraciones" and "Reporte" as submenu options of "NASA clientes"
 function addMenu(){
    add_menu_page('NASA Cotact Form', 'NASA clientes', 'manage_options', 'nasa-contact-form', 'nasa_scripts_page', 'dashicons-feedback',200);
    add_submenu_page( 'nasa-contact-form', 'Configuration', 'Configuración','manage_options', 'nasa-contact-form-configuration', 'config_nasa_scripts_page', 1);
    add_submenu_page( 'nasa-contact-form', 'Report', 'Reporte','manage_options', 'nasa-contact-form-report', 'report_nasa_scripts_page', 2);
 }

 //display content of "NASA clientes" section
 function nasa_scripts_page(){
     echo '<div class="wrap"> Bienvenido al Formulario de Astronautas aspirantes a la NASA 2020</div>';
 }


//display content of "Configuracion" submenu section of "NASA clientes"
 function config_nasa_scripts_page(){

    $intro_text = get_option('introduction_text_db', '');
    $thanks_text = get_option('thanks_text_db', '');

    // Enqueue WordPress media
    wp_enqueue_media();
    // Enqueue custom script that will interact with wp.media
    wp_enqueue_script( 'main_script', plugins_url( '/js/script.js' , __FILE__ ), array('jquery'), '0.1' );

    // already uploaded image verifcation
    $image_id = get_option('myprefix_image_id');
    if( intval( $image_id ) > 0 ) {
        // Change with the image size you want to use
        $image = wp_get_attachment_image( $image_id, 'small', false, array( 'id' => 'myprefix-preview-image' ) );
    } else {
        // Some default image
        $image = '<img id="myprefix-preview-image" src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" />';
    }
    
    // update global variables for the form personalization content
    if(array_key_exists('submit_form_configs',$_POST)){
        update_option('introduction_text_db',$_POST['introduction_text'] );
        update_option('thanks_text_db',$_POST['thanks_text'] );
    }

    ?>
    <div class="wrap">
        <h1>Configura el contenido del Formulario </h1>

        <form method="post" action="">
        <!-- IMAGE LOGO -->
        <label for="myprefix_image_id"><h3>Logo:</h3>Sube una imagen para el encabezado del formulario:</label>
        <br />
        <?php echo $image; ?>
        <br />
        <input type="hidden" name="myprefix_image_id" id="myprefix_image_id" value="<?php echo esc_attr( $image_id ); ?>" class="regular-text" />
        <input type='button' class="button-primary" value="<?php esc_attr_e( 'Selecciona una imagen', 'mytextdomain' ); ?>" id="myprefix_media_manager"/>
        <br />
        <br />

        <!-- INTRODUCTION TEXT -->
        <label for="introduction_text"><h3>Texto de Introducción:</h3>Puedes agregar un texto de introducción al formualrio:</label>
        <br />
        <textarea name="introduction_text"><?php print $intro_text; ?></textarea>
        <br />
        <br />

        <!-- THANK YOU TEXT -->
        <label for="thanks_text"><h3>Texto de Agradecimiento:</h3>Personaliza el mensaje de agardecimiento que se le mostrará a los candidatos una vez hayan enviado el formulario:</label>
        <br />
        <textarea name="thanks_text"><?php print $thanks_text; ?></textarea>
        <br />
        <br />
        <br />

        <input type="submit" name="submit_form_configs" value="Guardar cambios">
        </form>
    </div> 
    <?php
}

// Ajax action to refresh the user image
add_action( 'wp_ajax_myprefix_get_image', 'myprefix_get_image'   );
function myprefix_get_image() {
    if(isset($_GET['id']) ){
        //update gobal variable for the logo-icon source image form
        $src_image = wp_get_attachment_image_src(  filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ) , 'full');
        update_option('logo_img_db', $src_image[0]);
        //build the image to preview it at the configuration section of the plugin
        $image = wp_get_attachment_image( filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ), 'medium', false, array( 'id' => 'myprefix-preview-image' ) );
        $data = array(
            'image'    => $image,
        );
        wp_send_json_success( $data );
    } else {
        wp_send_json_error();
    }
}

//display content of "Reporte" submenu section of "NASA clientes"
function report_nasa_scripts_page(){
    echo '<div class="wrap"> Bienvenido a Reportes</div>';
}

//all the tags content require for the Nasa contact form
function nasa_contact_form(){

    //get db variables
    $logo_img = get_option('logo_img_db', '');
    $intro_text = get_option('introduction_text_db', '');
    $thanks_text = get_option('thanks_text_db', '');

    // Enqueue WordPress media
    wp_enqueue_media();
    // Enqueue custom css that will interact with wp.media
    wp_enqueue_style('Form Styles', plugins_url( '/styles/form.css' , __FILE__ ), array(), '0.1.0', 'all');


    /*content variable*/
    $content = '';

    $content .= '<section class="nasa-contact-form">';

    
        $content .= '<div class="header">';
        if($logo_img!=''){
            $content .= '<img src="'.$logo_img.'">';
        }
        if($intro_text!=''){
            $content .= '<p class="intro-text">'.$intro_text.'</p>';
        }
        $content .= '</div>';
        $content .= '<br />';
        
        //$content .= '<p>'.$thanks_text.'</p>';

        $content .= '<form class="form-content" method="post" action="/wordpress/nasa-gracias">';

            $content .= '<div>';    
            $content .= '<input type="text" name="full_name" placeholder="Nombre Completo" />';
            $content .= '<input type="number" name="age" placeholder="Edad (solo números)" />';
            $content .= '</div>';
            $content .= '<br />';

            $content .= '<div>';    
            $content .= '<select name="gender">';
            $content .= '<option value="" disabled selected>Selecciona tu sexo</option>';
            $content .= '<option value="female">Femenino</option>';
            $content .= '<option value="male">Masculino</option>';
            $content .= '<option value="undefined_sex">Otro</option>';
            $content .= '</select>';
            $content .= '<input type="email" name="email" placeholder="Correo electrónico" />';
            $content .= '</div>';
            $content .= '<br />';

            $content .= '<textarea name="motivation" placeholder="¿Cuál es tu motivo para ir a la luna?"></textarea>';
            $content .= '<br />';

            $content .= '<div>';   
            $content .= '<label for="alien-meeting-date">¿Cuándo fue la última vez que tuviste contacto con extraterrestres?</label>';
            $content .= '<input type="date" id="alien-meeting-date" name="alien-meeting-date"/>';
            $content .= '</div>';
            $content .= '<br />';

            $content .= '<div class="submit-form-container">';   
            $content .= '<input class="submit-form" type="submit" name="submit-nasa-form" value="ENVIAR INFORMACIÓN" />';
            $content .= '</div>';
        
        $content .= '</form>';
    $content .= '</section>';

    return $content;
}

add_shortcode('nasa_contact_form', 'nasa_contact_form');

// add the thanks message configured at the configuration section of the plugin
function nasa_thanks_message(){

    //get db variables
    $thanks_text = get_option('thanks_text_db', '');

    /*content variable*/
    $content = '';
    $content .= $thanks_text;
    return $content;
}

add_shortcode('nasa_thanks_message', 'nasa_thanks_message');

// get all the input values from the form, and then upload it at the database "WP_NASA_"
function nasa_form_capture(){

    global $post, $wpdb;

    if(array_key_exists('submit-nasa-form',$_POST)){
        $name = 'Nombre: '.$_POST['full_name'];
        $useremail = 'Correo: '.$_POST['email'];
        $age = 'Edad: '.$_POST['age'];
        $gender = 'Sexo: '.$_POST['gender'];
        $motivation = 'Mesnaje de motivación: '.$_POST['motivation'];
        $alien_meeting_date = 'Última fecha de encuentro con Aliens: '.$_POST['alien-meeting-date'];

        $content_db = '';
        $content_db .= $name.' <br /> ';
        $content_db .= $useremail.' <br /> ';
        $content_db .= $age.' <br /> ';
        $content_db .= $gender.' <br /> ';
        $content_db .= $motivation.' <br /> ';
        $content_db .= $alien_meeting_date.' <br /> ';

        $table_name = $wpdb->prefix . 'NASA_';

        /* Add the submission to the database using the table we created*/
        $wpdb->insert( 
            $table_name, 
            array( 
                'data' => $content_db, 
            ) 
        );
    }
}

add_action('wp_head','nasa_form_capture');

?>