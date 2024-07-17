<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;

    public function login() {
        return $this->ok('Hello 2, Login from Trait');
        /* return response()->json([
            'message' => 'Hello, login from Controller'
        ], 200); */
    }
}
