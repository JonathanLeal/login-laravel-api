<?php

namespace App\Http\Controllers;

use App\Helpers\Http;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function register(){
        return Http::respuesta(http::retOK, "funcionando");
    }
}
