<?php

/**
 * Plugin Name:     WP-CRM API
 * Plugin URI:      https://risemkt.com.br/plugins/wp-crm-api
 * Description:     Crie e edite negociações no CRM automaticamente pelo site
 * Author:          Jeú Chaves
 * Author URI:      https://risemkt.com.br
 * Text Domain:     wp-crm-api
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Crm_Api
 */

defined('ABSPATH') || exit;

define('WPCRM_PLUGIN_FILE', __FILE__);
define('WPCRM_PLUGIN_PATH', untrailingslashit(plugin_dir_path(WPCRM_PLUGIN_FILE)));
define('WPCRM_PLUGIN_URL', untrailingslashit(plugins_url('/', WPCRM_PLUGIN_FILE)));

if (file_exists(WPCRM_PLUGIN_PATH . '/vendor/autoload.php')) {
    require_once WPCRM_PLUGIN_PATH . '/vendor/autoload.php';
}
require_once WPCRM_PLUGIN_PATH . '/includes/Plugin.php';

if (class_exists('Plugin')) {

    function WPCRM()
    {
        return Plugin::getInstance();
    }
    
    // Caregamento do plugin
    add_action('plugins_loaded', array(WPCRM(), 'init'));

    // Ativação do plugin
    register_activation_hook(WPCRM_PLUGIN_FILE, array(WPCRM(), 'activate'));

    // Desativação do plugin
    register_deactivation_hook(WPCRM_PLUGIN_FILE, array(WPCRM(), 'deactivate'));
}
