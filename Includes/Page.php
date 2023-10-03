<?php

namespace WPCRM\Includes;

use WPCRM\Includes\Request;

defined('ABSPATH') || exit;

class Page
{

    private static $option_group = 'general';
    private static $option_name = 'wpcrm_integracao';

    private static function get_option_group() {
        return self::$option_group;
    }

    private static function get_option_name() {
        return self::get_option_name();
    }

    protected function __construct()
    {
    }

    private static function add_input() {
        register_setting(
            'get_option_group', 
            'get_option_name', 
            $args = array())
    }

    public static function add_settings_page()
    {

        register_setting(
            'general',
            'chave_api_minha_integracao',
            [
                'sanitize_callback' => function ($value) {
                    if (!Request::is_valid_token($value)) {
                        add_settings_error(
                            'chave_api_minha_integracao',
                            esc_attr('chave_api_minha_integracao_error'),
                            'Chave API não é valida',
                            'error'
                        );
                        return get_option('chave_api_minha_integracao');
                    }
                    return $value;
                }
            ]
        );

        add_settings_section(
            'wpcrm_secao',
            'Integração CRM',
            function () {
                echo ('<p>Insira aqui sua chave de integração e os formulários que o CRM irá manipular</p>');
            },
            'general'
        );

        add_settings_field(
            'chave_api_minha_integracao',
            'chave API da minha integração',
            function ($args) {
                $options = get_option('chave_api_minha_integracao')
?>
            <input id="id__chave_api_minha_integracao" type="text" name="chave_api_minha_integracao" value=<?php echo esc_attr($options) ?>>
<?php
            },
            'general',
            'wpcrm_secao',
            [
                'label_for' => 'id__chave_api_minha_integracao'
            ]
        );
    }
}
