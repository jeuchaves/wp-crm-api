<?php

namespace WPCRM\Includes;

use WPCRM\Includes\Request;

defined('ABSPATH') || exit;

class Page
{
    protected function __construct()
    {
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
            'default',
            [
                'label_for' => 'id__chave_api_minha_integracao'
            ]
        );
    }
}
