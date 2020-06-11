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
    echo '<div class="wrap"> Bienvenido a Configuración</div>';
}

//display content of "Reporte" submenu section of "NASA clientes"
function report_nasa_scripts_page(){
    echo '<div class="wrap"> Bienvenido a Reportes</div>';
}

function nasa_contact_form(){
    /*content variable*/
    $content = '';
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

        //$content .= '<label for="alien">¿Cuándo fue la última vez que tuviste contacto con extraterrestres?</label>'
        $content .= `<input type="text" id="alien-meeting-date" name="alien-meeting-date" placeholder="¿Cuándo fue la última vez que tuviste contacto con extraterrestres?" onfocus="(this.type='date')"
        onblur="(this.type='text')" />`;
        $content .= '<br />';

        $content .= '<input type="submit" name="submit-nasa-form" value="ENVIAR INFORMACIÓN" />';
    
    $content .= '</form>';

    return $content;
}

add_shortcode('nasa_contact_form', 'nasa_contact_form');

?>