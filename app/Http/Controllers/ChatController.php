<?php

namespace App\Http\Controllers;


use App\Models\OauthZoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
//use GuzzleHttp\Psr7\Request;


class ChatController extends Controller
{
    
    //Váriaveis Protegidas da Classe - Obrigatório para funcionamento
    protected $idDb;
    protected $code;
    protected $access_token;
    protected $refresh_token;
    protected $access_token_pos_refres;
    protected $request;
    protected $client;
    protected $header;
    

    //Construtor da Classe - Obrigatório para funcionamento
    public function __construct(Request $request)
    {
        //Gera Tokens para autenticação
         

        //Instancia o Model OauthZoom;
        $dbZoom = new OauthZoom;

        //Query, conta os registros do banco de dados;
        $this->idDb = DB::table("table_access_tokens")->count();

        //Solicita os dados do banco de dados e atribui a instancia aos respectivos;
        $this->access_token = $dbZoom->find(json_encode($this->idDb));
        $this->access_token_pos_refres = $dbZoom->find(json_encode($this->idDb));
        $this->refresh_token = $dbZoom->find(json_encode($this->idDb));
        $this->code = $dbZoom->find(json_encode($this->idDb));

        //Injeta a depência REQUEST
        $this->request = $request;
        //Padroniza Header para resposta a solicitação da função a baixo
        //dd($this->$this->access_token->access_token);
        $this->header = [
            'Authorization' => 'Bearer '.$this->access_token_pos_refres->access_token_pos_refres,
            'Content-Type' => 'application/json',

        ];
    }

//Função CHAT ZOOM
    function chat (Request $request) {
        $robot_jid = 'v13h2och8kte-2_iahldv7na@xmpp.zoom.us';
        
        dd($request->all());
        //request de informações
        // $texto = $request->input('texto');
        // $contato = $request->input('contato');

        //path da requisição
        $path = 'https://api.zoom.us/v2/im/chat/messages';


        //Resposta para API
        $response = Http::withHeaders($this->header)
        ->post($path, [

            'robot_jid' => $robot_jid,
            'to_jid' =>'dsjhvfdhfvdfh',
            'account_id' => 'dmdbffgjbfhgfdj',
            'content' => [
                'head' => [
                  'text' => 'Hello World'
                ],
            ],
            'body'=> [
                [
                  'type' => 'message',
                  'text' => 'Greetings from the cool bot'
                ]
              ]

        ]);

        //Retorna resposta padronizada
        $data = [
            'code' => $response->getStatusCode(),
            'data' => $response->getBody()->getContents(),
        ];

            // //Caso não validar, validar com token 
            // if ($response->getStatusCode() == 401) {
                
            //     Auth_Helpers();
            //     //valida o Token com Access_Token já validado pelo Refresh
            //     $this->header = [
            //         'Authorization' => 'Bearer '.$this->access_token_pos_refres->access_token_pos_refres,
            //         'Content-Type' => 'application/json',
            //     ];

            //     //request de informações
            //     $texto = $request->input('texto');
            //     $contato = $request->input('contato');

            //     //path da requisição
            //     $path = 'https://api.zoom.us/v2/im/chat/messages';

            //     //Resposta para API
            //     $response = Http::withHeaders($this->header)
            //     ->post($path, [
            //         'message'=> $texto,
            //         'to_contact' => $contato,
            //     ]);

            //     //Retorna resposta padronizada
            //     $data = [
            //         'code' => $response->getStatusCode(),
            //         'data' => $response->getBody()->getContents(),
            //     ];

            //     //Retorno de Resposta
            //     return response()->json($data,200);
                
            // }
            // else {
                //Retorno de Resposta
                return response()->json($data,200);
                
            //}


    }

}
