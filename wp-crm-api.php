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

require_once WPCRM_PLUGIN_PATH . '/vendor/autoload.php';
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

    // Adição da página de configurações
    add_action('admin_init', array(WPCRM(), 'add_settings_page'));

    // Registrar rota AJAX para adicionar contato
    add_action('wp_ajax_add_contato', array(WPCRM(), 'add_contato'));
    add_action('wp_ajax_nopriv_add_contato', array(WPCRM(), 'add_contato'));

    // Registrar o arquivo contato.js
    function enqueue_contato_js() {
        wp_enqueue_script('jquery');

        wp_register_script('contato-script', WPCRM_PLUGIN_URL . '/includes/js/Contato.js', array('jquery'), '1.0', true);

        wp_localize_script('contato-script', 'custom_ajax_vars', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));

        wp_enqueue_script('contato-script');
    }
    add_action('wp_enqueue_scripts', 'enqueue_contato_js');
}
