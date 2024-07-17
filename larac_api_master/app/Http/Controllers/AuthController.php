<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiLoginRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    public function login(ApiLoginRequest $request) {
        return $this->ok($request->get('email'));
        /* return $this->ok('Hello 2, Login from Trait'); */
        /* return response()->json([
            'message' => 'Hello, login from Controller'
        ], 200); */
    }

    public function register() {
        return $this->ok('register');
    }
}
