<?php

namespace WPCRM\Includes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

defined('ABSPATH') || exit;

class Page
{
    protected function __construct()
    {
    }

    public static function is_valid_token($value)
    {
        $client = new Client();

        try {

            $response = $client->request(
                'GET',
                'https://crm.rdstation.com/api/v1/token/check?token=' . $value,
                [
                    'headers' => [
                        'accept' => 'application/json',
                    ],
                ]
            );

            // Verifique se o código de resposta é 200 OK.
            if ($response->getStatusCode() === 200) {
                // O token é válido, retorne true.
                return true;
            } else {
                // O token não é válido, retorne false.
                return false;
            }
        } catch (ClientException $e) {

            // Se a exceção for lançada, isso indica um erro na solicitação.
            // Verifique o código de resposta para determinar se o token é inválido.
            $response = $e->getResponse();
            if ($response && $response->getStatusCode() === 401) {
                // O token é inválido, retorne false.
                return false;
            } else {
                // Outro erro ocorreu, você pode lidar com ele de acordo com suas necessidades.
                // Por exemplo, você pode lançar a exceção novamente ou registrar o erro.
                // ...
                return false;
            }
        }
    }

    public static function add_settings_page()
    {

        register_setting(
            'general',
            'chave_api_minha_integracao',
            [
                'sanitize_callback' => function ($value) {
                    if (!self::is_valid_token($value)) {
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
