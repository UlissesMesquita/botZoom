<?php

namespace App\Http\Controllers;


use App\Models\OauthZoom;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use function PHPSTORM_META\type;

class MeetingController extends Controller
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
        $this->access_token_pos_refres = $dbZoom->find(json_encode($this->idDb));
        $this->code = $dbZoom->find(json_encode($this->idDb));

        //Injeta a depência REQUEST
        $this->request = $request;
        //Injeta a depêndencia CLIENTE/GuzzleHttp com a base URI da API
        //$this->client = new Client(['base_uri' => 'https://api.zoom.us']);
        //Padroniza Header para resposta a solicitação da função a baixo
        $this->header = [
            'Authorization' => 'Bearer '.$this->access_token->access_token,
            'Content-Type' => 'application/json',
        ];
    }

    //Função Criação de Meeting
    function CreateMeeting($userId, Request $request) {

        //Entrada de Dados
        $topic = $request->input('topic');
        $duration = $request->input('duration');
        $start_time = Carbon::parse($request->input('start_time'))->format('Y-m-d\TH:i:s');
        $password = $request->input('password');

   
        //Requisita reunião criada  
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->access_token_pos_refres->access_token_pos_refres,
            'Content-Type' => 'application/json',
            ])->post('https://api.zoom.us/v2/users/'.$userId.'/meetings', [
            
            'form_params'=> [
                'headers' => $this->header, //Header da requisição
                'body'    => json_encode([
                    "topic"=> $topic, // Tópico da reunião
                    "type"=> 2, //Tipo de reunião -> 1 -> Instantânea / 2 -> Agendada
                    "start_time"=> $start_time, //Tempo de início
                    "duration"=> $duration, //Duração
                    //"schedule_for"=> $schedule_for, //Quem agenda a reunião
                    "timezone" => "America/Sao_Paulo", //TimeZone
                    "password"=> $password, //Password
                    "settings"=> [
                        "host_video"=> true, //Video do host ligado
                        "participant_video"=> true, //Video participante ligado
                        "join_before_host"=> true, //Entrar em reunião antes o host
                        "mute_upon_entry"=> true, //Entrar com microfone mute
                        "watermark"=> true, //Marca D'agua
                        "approval_type"=> 1, //Tipo de Aprovação
                        "registration_type"=> 0, //Cadastro pré-reunião
                        "auto_recording"=> "cloud", //local para gravação
                        "alternative_hosts"=> "thiago.passos@xpon.com.br", //host alternativo
                        "registrants_email_notification"=> true, //Enviar e-mail de notificação de criação de meeting via e-mail
                    ],
                ]),  
            ],
        ]);
    
        //Retorna resposta padronizada
        $data = [
                'code' => $response->getStatusCode(),
                'data' => json_decode($response->getBody()->getContents())->join_url,
            ];

        //Retorno de Resposta
        return response()->json($data,200);
    
  
    }

}
