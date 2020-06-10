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

add_shortcode('example', 'addContent');
add_action("admin_menu", "addMenu");


 function addContent(){
     $content = "NASA contact form";
     return $content;
 }

 function addMenu(){
    add_menu_page('NASA Cotact Form', 'NASA clientes', 'manage_options', 'nasa-contact-form', 'nasa_scripts_page', 'dashicons-feedback',200);
    add_submenu_page( 'nasa-contact-form', 'Configuration', 'Configuración','manage_options', 'nasa-contact-form-configuration', 'config_nasa_scripts_page', 1);
    add_submenu_page( 'nasa-contact-form', 'Report', 'Reporte','manage_options', 'nasa-contact-form-report', 'report_nasa_scripts_page', 2);
 }

 function nasa_scripts_page(){
     echo '<div class="wrap"> Bienvenido al Formulario de Astronautas aspirantes a la NASA 2020</div>';
 }

 function config_nasa_scripts_page(){
    echo '<div class="wrap"> Bienvenido a Configuración</div>';
}

function report_nasa_scripts_page(){
    echo '<div class="wrap"> Bienvenido a Reportes</div>';
}
 ?>