<?php

/*
Plugin Name:  IP Geolocation Info
Description:  Add IP geolocation tooltips to the comments and article content
Version:      1.0.1
Author:       Whois XML API, LLC
Author URI:   https://geoipify.whoisxmlapi.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  ip-geolocation-info
*/


defined('ABSPATH') or die('Not allowed');

require_once plugin_dir_path( __FILE__ ) . '/src/WhoisXmlApiCom_Geoip_Plugin.php';
require_once plugin_dir_path( __FILE__ ) . '/src/WhoisXmlApiCom_Geoip_Settings.php';


$whoisxmlapicom_geoip_core = new WhoisXmlApiCom_Geoip_Plugin();

add_action('wp_enqueue_scripts', 'whoisxmlapicom_geoip_add_scripts');
add_action('wp_enqueue_scripts', 'whoisxmlapicom_geoip_add_styles');

if (! function_exists('whoisxmlapicom_geoip_add_scripts')) {
    function whoisxmlapicom_geoip_add_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('whoisxmlapicom-geoip-tooltipster-js', plugins_url('/js/whoisxmlapicom-geoip-tooltipster.js', __FILE__));
        wp_enqueue_script('whoisxmlapicom-geoip-plugin-js', plugins_url('/js/whoisxmlapicom-geoip-plugin.js', __FILE__));
    }
}

if (! function_exists('whoisxmlapicom_geoip_add_styles')) {
    function whoisxmlapicom_geoip_add_styles()
    {
        wp_enqueue_script('load-fa', 'https://use.fontawesome.com/releases/v5.7.0/js/all.js');
        wp_enqueue_style('whoisxmlapicom-geoip-plugin-styles', plugins_url('/css/whoisxmlapicom-geoip-plugin.css', __FILE__));
        wp_enqueue_style('whoisxmlapicom-geoip-tooltipster-css', plugins_url('/css/whoisxmlapicom-geoip-tooltipster.css', __FILE__));
        wp_enqueue_style('whoisxmlapicom-geoip-tooltipster-light-css', plugins_url('/css/whoisxmlapicom-geoip-tooltipster-light.css', __FILE__));
    }
}

if( is_admin() )
    $whoisxmlapicom_geoip_settings_page = new WhoisXMLAPICom_Geoip_Settings();