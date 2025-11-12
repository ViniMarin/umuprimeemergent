<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Para onde redirecionar após login.
     */
    protected $redirectTo = '/admin';

    public function __construct()
    {
        // Usuários autenticados não acessam /login (exceto logout)
        $this->middleware('guest')->except('logout');

        // Garante que o logout só seja permitido autenticado
        $this->middleware('auth')->only('logout');
    }

    /**
     * Para onde redirecionar após logout.
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('login'); // http://localhost:8000/login
    }
}
