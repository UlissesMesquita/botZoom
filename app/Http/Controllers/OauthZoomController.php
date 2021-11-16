<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Http\Helpers;
use GuzzleHttp\Client;


//Controla os redirecionamentos
class OauthZoomController extends Controller
{
    //redireciona a rota para o Helpers, função Auth().
    public function Auth() {
       return Auth_Helpers();
    }
    //Redireciona o callback para a função Callback() no Helpers.
    public function Callback(Request $request){
        return Call($request);
    }


}
