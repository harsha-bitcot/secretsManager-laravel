<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SecretsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    dd(env('APP_URL'));
    dd(isset(Config::get('secrets')->key2)?Config::get('secrets')->key2:null);
    return view('welcome');
});

Route::get('/manual/{key}', function ($key) {
    dd(isset(Config::get('secrets')->$key)?Config::get('secrets')->$key:null);
});

Route::get('/automatic/{key}/{value}', function ($key, $expectedValue) {
    function apiCallSimulation($key, $expectedValue, $secondTry = false){
//        if (!isset(Config::get('secrets')->$key)) return null;
        if (isset(Config::get('secrets')->$key) && Config::get('secrets')->$key === $expectedValue){
            return Config::get('secrets')->$key;
        }else {
            $secretsController = new SecretsController;
            if ($secretsController->isLatest()){
                // todo add something that registers this api key as not working so that we can stop pinging AWS until it is resolved
                if (!isset(Config::get('secrets')->$key)) return 'no key exists with the name: ' . $key;
                return 'Latest secret from aws does not match with the expected value';
            }
            if (!$secondTry){
                return apiCallSimulation($key, $expectedValue,true);
            }
            // todo add something that registers this api key as not working so that we can stop pinging AWS until it is resolved
            return 'Unknown failure/unable to save latest secrets from aws';
        }
    }
    dd(apiCallSimulation($key, $expectedValue));
});


Route::get('/test', function () {
    $retryCount = 'asd';
    switch (true) {
//        case !is_numeric($retryCount):
//            $final = 'yes';
//            break;

        case $retryCount <= 10:
            $final = 'less';
            break;

        case $retryCount > 10:
            return 'lsks';
            break;

        default:
            $final = 'none';
            break;
    }
    dd($retryCount);
});
