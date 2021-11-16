<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\OauthZoom;

//Requisita o code para solicitar o Code, enviando para o callback().
function Auth_Helpers() {
    //Obtendo o code.
    $paraments = http_build_query([
        'response_type' => 'code',
        'client_id' => config('api.CLIENT_ID'),
        'redirect_uri' => config('api.REDIRECT_URI'),
        'Access-Control-Allow-Headers' => 'Content-Type, x-requested-with',
        'Accept-Encoding' => 'deflate, gzip;q=1.0, *;q=0.5'
    ]);

    //Retorna para a rota Callback + parâmetros 'oauth/authorize, esse return irá retornar a função Call através do callback
    return redirect(config('api.URL_API').'oauth/authorize?'. $paraments);
}

//Recebe os parâmetros do callback.
function Call(Request $request) {
    //Carrega o site base do Zoom para a variável zoom;
    $client = new Client(['base_uri' => 'https://zoom.us/']);
    //Requisita o access_token e o refresh_token
    $response = $client->request('post', 'oauth/token', [
        'headers' => [
            'Authorization' => 'Basic '.base64_encode(config('api.CLIENT_ID').':'.config('api.CLIENT_SECRET')),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => config('api.REDIRECT_URI'),  
        ],
    ]);

//Inicializa a variável $dbZoom para salvar os dados no banco de dados.
    $dbZoom = new OauthZoom();
    //Salva o code;
    $dbZoom->code = $request->code;
    //Salva o access token;
    $dbZoom->access_token = json_decode($response->getBody())->access_token;
    //Salva o refresh_token (Usado logo após para validar o access_token, aumentando a validade do token);
    $dbZoom->refresh_token = json_decode($response->getBody())->refresh_token;
    
    //Requisita o access_token2 (com o refresh_token)
    $response = $client->request('post', 'oauth/token', [
        'headers' => [
            'Authorization' => 'Basic '.base64_encode(config('api.CLIENT_ID').':'.config('api.CLIENT_SECRET')),
            'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'form_params' => [
            'grant_type' => 'refresh_token',
            'refresh_token' => json_decode($response->getBody())->refresh_token,  
        ],

    ]);
    //Salva o access_token2 para validar com o método, caso o token não seja válido ou perdido a mesma.
    $dbZoom->access_token_pos_refres = json_decode($response->getBody())->access_token;
    //Salva todos os dados.
    $dbZoom->save();
    
    
    //dd(json_decode($response->getBody()));
}

        
    









