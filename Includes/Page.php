<?php

namespace WPCRM\Includes;

use WPCRM\Includes\Request;

defined('ABSPATH') || exit;

class Page
{

    private static $section_id = 'wpcrm_setting_section_id';
    private static $page = 'general';
    private static $configuracoes = array();

    public static function init()
    {
        self::criar_secao();
        self::criar_configuracoes();
        self::registrar_configuracoes();
        self::criar_inputs();
    }

    public static function criar_secao()
    {
        add_settings_section(
            self::$section_id,
            'Integração CRM',
            array(__CLASS__, 'renderizar_secao'),
            self::$page
        );
    }

    public static function renderizar_secao()
    {
        echo '<p>Essa é a descrição da seção</p>';
    }

    public static function criar_configuracoes()
    {
        $arr_1 = array(
            'option_group' => self::$page,
            'option_name' => 'wpcrm_settings_token',
            'option_label' => 'Token',
            'sanitize_callback' => function ($value) {
                if (!Request::is_valid_token($value)) {
                    add_settings_error(
                        'wpcrm_settings_token',
                        esc_attr('wpcrm_settings_token_error'),
                        'Chave API não é valida',
                        'error'
                    );
                    return get_option('wpcrm_settings_token');
                }
                return $value;
            }
        );
        $arr_2 = array(
            'option_group' => self::$page,
            'option_name' => 'wpcrm_settings_contact',
            'option_label' => 'ID do contato',
            'sanitize_callback' => null
        );
        self::$configuracoes[] = $arr_1;
        self::$configuracoes[] = $arr_2;
    }

    public static function registrar_configuracoes() {
        foreach (self::$configuracoes as $configuracao) {
            register_setting(
                $configuracao['option_group'],
                $configuracao['option_name'],
                [
                    'sanitize_callback' => $configuracao['sanitize_callback']
                ]
            );
        };
    }

    public static function criar_inputs() {
        foreach (self::$configuracoes as $configuracao) {
            add_settings_field(
                $configuracao['option_name'],
                $configuracao['option_label'],
                array(__CLASS__, 'renderizar_input'),
                $configuracao['option_group'],
                self::$section_id,
                [
                    'label_for' => $configuracao['option_name']
                ]
            );
        }
    }

    public static function renderizar_input($args) {
        $name = $args['name'];
        $value = $args['value'];
        echo '<input type="text" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" id="' . esc_attr($value) . '">';
    }

    protected function __construct()
    {
    }

    public static function add_settings_page()
    {

        add_settings_section(
            'wpcrm_secao',
            'Integração CRM',
            function () {
                echo ('<p>Insira aqui sua chave de integração e os formulários que o CRM irá manipular</p>');
            },
            'general'
        );

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

        register_setting(
            'general',
            'string_formulario_contato',
            [
                'sanitize_callback' => null
            ]
        );

        add_settings_field(
            'chave_api_minha_integracao',
            'chave API da minha integração',
            function ($args) {
                $options = get_option('chave_api_minha_integracao');
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

        add_settings_field(
            'string_formulario_contato',
            'String do formulário de contato',
            function ($args) {
                $options = get_option('string_formulario_contato');
        ?>
            <input type="text" id="id__string_formulario_contato" name="string_formulario_contato" value=<?php echo esc_attr($options) ?>>
<?php
            },
            'general',
            'wpcrm_secao',
            [
                'label_for' => 'id__string_formulario_contato'
            ]
        );
    }
}
