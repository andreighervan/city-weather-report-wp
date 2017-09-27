<?php
/**
 * Plugin Name:City weather report
 * Description:Widget that displays a specified city weather
 * Version:1.0
 * Author:Andrei Agvan
 */
if(!defined('ABSPATH')){
    exit;
}
$ffl_options=get_option('ffl_settings');
//load scripts
require_once(plugin_dir_path(__FILE__).'/includes/city-weather-report-scripts.php');
require_once(plugin_dir_path(__FILE__).'/includes/city-weather-report-class.php');
require_once(plugin_dir_path(__FILE__).'/includes/geoplugin.class.php');

function register_city_weather_report(){
    register_widget('City_Weather_Report_Widget');
}
add_action('widgets_init','register_city_weather_report');