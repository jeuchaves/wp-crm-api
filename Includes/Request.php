<?php

namespace WPCRM\Includes;

use Exception;
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

    public static function sanitize_phone_number($phone) {
        $sanitized_phone = preg_replace('/[^0-9+]/', '', $phone);
        return $sanitized_phone;
    }

    public static function add_deal() {
        
        // Token
        $token = get_option('wpcrm_settings_token');
        
        // Contact
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $whatsapp = Request::sanitize_phone_number($_POST['whatsapp']);

        // Deal
        $stage_id = '652d4831c1084900162b633b';
        $name_deal = 'Nova conta - ' . $nome;
        
        $data = [
            'deal' => [
                'deal_stage_id' => $stage_id,
                'name'          => $name_deal,
            ],
            'contacts' => [
                'name' => $nome,
                'emails' => [
                    [
                        'email' => $email
                    ]
                ],
                'phones' => [
                    [
                        'phone' => $whatsapp
                    ]
                ]
            ]
        ];

        if (Request::is_valid_token($token)) {
            $client = new Client();

            try {

                $response = $client->request('POST', 'https://crm.rdstation.com/api/v1/deals?token=' . $token, [
                    'body' => '{"deal":{"deal_stage_id":"'.$stage_id.'","name":"'.$name_deal.'"},"contacts":[{"emails":[{"email":"'.$email.'"}],"name":"'.$nome.'","phones":[{"phone":"'.$whatsapp.'"}]}]}',
                    'headers' => [
                        'accept' => 'application/json',
                        'content-type' => 'application/json',
                    ],
                ]);

                if($response->getStatusCode() == 200) {
                    echo json_encode(array('sucess' => true, 'message' => 'Sucesso: Negociação criada'));
                } else {
                    echo json_encode(array('sucess' => false, 'message' => 'Erro [requisição]: ' . $response));
                }

            } catch (Exception $e) {
                echo json_encode(array('sucess' => false, 'message' => 'Erro [servidor]: ' . $e . ' Parametros: ' . json_encode($data)));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Erro: Token inválido'));
        }

        exit();
    }

    public static function add_contact()
    {
        $token = get_option('wpcrm_settings_token');
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        $whatsapp = Request::sanitize_phone_number($_POST['whatsapp']);

        $data = [
          'contact' => [
            'name' => $nome,
            'emails' => [
                ['email' => $email]
            ],
            'phones' => [
                ['phone' => $whatsapp]
            ]
          ]
        ];

        if (Request::is_valid_token($token)) {
            $client = new Client();

            try {
                $response = $client->request('POST', 'https://crm.rdstation.com/api/v1/contacts?token=' . $token, [
                    'body' => json_encode($data),
                    'headers' => [
                        'accept' => 'application/json',
                        'content-type' => 'application/json',
                    ],
                ]);

                if($response->getStatusCode() == 200) {
                    echo json_encode(array('sucess' => true, 'message' => 'Contato adicionado com sucesso.'));
                } else {
                    echo json_encode(array('sucess' => false, 'message' => 'Erro ao adicionar o contato.'));
                }
            } catch (Exception $e) {
                echo json_encode(array('sucess' => false, 'message' => 'Erro ao adicionar o contato: ' . $e->getMessage()));
            }
        } else {
            echo json_encode(array('success' => false, 'message' => 'Token inválido.'));
        }

        exit();
    }
}
