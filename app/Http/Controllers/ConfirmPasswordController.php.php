<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    /**
     * Onde redirecionar os usuários depois da confirmação de senha.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Cria uma nova instância de controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
