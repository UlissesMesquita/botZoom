<?php

namespace App\Http\Controllers;


use App\Models\OauthZoom;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

use function PHPSTORM_META\type;

class RecordingController extends Controller
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

    //Função CHAT ZOOM
    function GetRecording($meetingId) {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->access_token_pos_refres->access_token_pos_refres,
            'Content-Type' => 'application/json',
            ])->get('https://api.zoom.us/v2/meetings/'.$meetingId.'/recordings', []);
        
        //Retorno de Resposta
        $data = [
            'code' => $response->getStatusCode(),
            'data' => json_decode($response->getBody()->getContents())->share_url,
        ];

        //Retorno de Resposta
        return response()->json($data,200);
  
    }

}
