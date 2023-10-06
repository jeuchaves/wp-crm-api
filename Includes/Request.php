<?php

namespace WPCRM\Includes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class Request
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

    public static function add_contact($contato)
    {
        $token = get_option('wpcrm_settings_token');
        $nome = $contato['nome'];
        $email = $contato['email'];
        $whatsapp = $contato['whatsapp'];
        if (Request::is_valid_token($token)) {
            $client = new Client();

            $response = $client->request('POST', 'https://crm.rdstation.com/api/v1/contacts?token=' . $token, [
                'body' => '{"contact":{"emails":[{"email":"' . $email . '"}],"name":"' . $nome . '","phones":[{"phone":"' . $whatsapp . '"}]}}',
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);

            echo $response->getBody();
        }
    }
}
