<?php

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OauthZoomController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\RecordingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});


//Método Funcionando -> Authenticação ClientID -> retorna Code no CallBack
Route::get('auth', [OauthZoomController::class, 'Auth'])->name('auth');
    
//Retorna Callback;
Route::get('call', [OauthZoomController::class, 'Callback']);

//Redireciona o Método para ZoomController - Teste
Route::get('zoom', [ZoomController::class, 'index'])->name('zoom');



//URL para Chat - OK
Route::post('/chat/users/messages', [ChatController::class, 'chat'])->name('chat');

//URL para Criação de Reunião - OK
Route::post('/users/{userId}/meetings', [MeetingController::class, 'CreateMeeting'])->name('meeting');

//URL para solicita Link - OK
Route::get('/meetings/{meetingId}/recordings', [RecordingController::class, 'GetRecording'])->name('GetRecording');

