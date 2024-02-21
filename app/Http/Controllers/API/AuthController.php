<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests\API\AuthAPI;

class AuthController extends Controller
{
    public function auth(AuthAPI $request)
    {
        $request_oauth = Request::create(
            '/oauth/token', 
            'POST', 
            [
                'grant_type' => 'password',
                'client_id' => config('api.oauth2.client_id'),
                'client_secret' => config('api.oauth2.client_secret'),
                'scope' => '',
                'username' => $request->username,
                'password' => $request->password,
                'nu_cpf_cnpj' => $request->nu_cpf_cnpj           
            ]
        );

        $response_oauth = app()->handle($request_oauth);
        return json_decode($response_oauth->getContent());
    }
}
