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

    // Enqueue WordPress media scripts
    wp_enqueue_media();
    // Enqueue custom script that will interact with wp.media
    wp_enqueue_script( 'myprefix_script', plugins_url( '/js/script.js' , __FILE__ ), array('jquery'), '0.1' );

    // already uploaded image verifcation
    $image_id = get_option('myprefix_image_id');
    if( intval( $image_id ) > 0 ) {
        // Change with the image size you want to use
        $image = wp_get_attachment_image( $image_id, 'small', false, array( 'id' => 'myprefix-preview-image' ) );
    } else {
        // Some default image
        $image = '<img id="myprefix-preview-image" src="https://www.generationsforpeace.org/wp-content/uploads/2018/03/empty.jpg" />';
    }
    
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
        <!--<input type="file" name="logoimage" id="logoimage" size="25" />-->
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

        $src_image = wp_get_attachment_image_src(  filter_input( INPUT_GET, 'id', FILTER_VALIDATE_INT ) , 'full');
        update_option('logo_img_db', $src_image[0]);

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

function nasa_contact_form(){

    //get db variables
    $logo_img = get_option('logo_img_db', '');
    $intro_text = get_option('introduction_text_db', '');
    $thanks_text = get_option('thanks_text_db', '');

    /*content variable*/
    $content = '';

    $content .= '<p>'.$logo_img.'</p>';
    $content .= '<p>'.$intro_text.'</p>';
    $content .= '<p>'.$thanks_text.'</p>';

    //onfocus="(this.type='date')" onblur="(this.type='text')" 

    $content .= '<form method="post" action="/nasa-gracias">';

        $content .= '<input type="text" name="full_name" placeholder="Nombre Completo" />';
        $content .= '<br />';

        $content .= '<input type="number" name="age" placeholder="Edad (solo números)" />';
        $content .= '<br />';

        $content .= '<select name="gender">';
        $content .= '<option value="" disabled selected>Selecciona tu sexo</option>';
        $content .= '<option value="female">Femenino</option>';
        $content .= '<option value="male">Masculino</option>';
        $content .= '<option value="undefined_sex">Otro</option>';
        $content .= '</select>';
        $content .= '<br />';
        $content .= '<br />';

        $content .= '<input type="email" name="email" placeholder="Correo electrónico" />';
        $content .= '<br />';

        $content .= '<textarea name="motivation" placeholder="¿Cuál es tu motivo para ir a la luna?"></textarea>';
        $content .= '<br />';

        $content .= '<label for="alien-meeting-date">¿Cuándo fue la última vez que tuviste contacto con extraterrestres?</label>';
        $content .= '<input type="date" id="alien-meeting-date"/>';
        $content .= '<br />';

        $content .= '<input type="submit" name="submit-nasa-form" value="ENVIAR INFORMACIÓN" />';
    
    $content .= '</form>';

    return $content;
}

add_shortcode('nasa_contact_form', 'nasa_contact_form');

//referencias

//HOW TO UPLOAD AN IMGE WITH PHP WORDPRESS
//https://rudrastyh.com/wordpress/how-to-add-images-to-media-library-from-uploaded-files-programmatically.html

//https://pqina.nl/blog/uploading-files-to-wordpress-media-library/

//https://www.youtube.com/watch?v=6oKSYD_mTTU

//https://blog.idrsolutions.com/2014/07/creating-wordpress-plugin-part-2-uploading-media-linking-web-service/

?>