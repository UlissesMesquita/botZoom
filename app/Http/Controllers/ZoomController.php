<?php

namespace App\Http\Controllers;

use App\Models\OauthZoom;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ZoomController extends Controller
{

    //Váriaveis Protegidas da Classe - Obrigatório para funcionamento
    protected $idDb;
    protected $code;
    protected $access_token;
    protected $refresh_token;
    protected $request;
    protected $client;
    protected $header;


    //Construtor da Classe - Obrigatório para funcionamento
    public function __construct(Request $request)
    {
        //Instancia o Model OauthZoom;
        $dbZoom = new OauthZoom;

        //Query, conta os registros do banco de dados;
        $this->idDb = DB::table("table_access_tokens")->count();

        //Solicita os dados do banco de dados e atribui a instancia aos respectivos;
        $this->access_token = $dbZoom->find(json_encode($this->idDb));
        $this->refresh_token = $dbZoom->find(json_encode($this->idDb));
        $this->code = $dbZoom->find(json_encode($this->idDb));

        //Injeta a depência REQUEST
        $this->request = $request;
        //Injeta a depêndencia CLIENTE/GuzzleHttp com a base URI da API
        $this->client = new Client(['base_uri' => 'https://api.zoom.us']);
        //Padroniza Header para resposta a solicitação da função a baixo
        $this->header = [
            'Authorization' => 'Bearer '.$this->access_token->access_token,
        ];
    }


    //Função User ZOOM - Teste  
    function index() {
        try{
            $response = $this->client->request('GET','/v2/users/me', [
                'headers' => $this->header,
                'form_params' => [
                    'grant_type' => 'authorization_code',
         
                ],
            ]);
            $data = [
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents()),
            ];

            //Retorno de Resposta
            return response()->json($data,200);

        }

        catch( \Exception $e) {
            if($e->getCode() == 400) {
                
                $response = $this->client->request('GET', 'oauth/token', [
                    'headers' => [
                        'Authorization' => 'Basic '.base64_encode(config('api.CLIENT_ID').':'.config('api.CLIENT_SECRET')),
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ],
            
                    'form_params' => [
                        'code' => $this->code,
                        'grant_type' => 'refresh_token',
                        'redirect_uri' => config('api.REDIRECT_URI'),  
                    ],
            
                ]);

                $data = [
                    'code' => $response->getStatusCode(),
                    'data' => json_encode($response->getBody()->getContents()),
                ];
    
                //Retorno de Resposta
                return response()->json($data,200);

            }
            else{
                return $e->getMessage();
            }
        }
  
    }

}
