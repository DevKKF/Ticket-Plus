<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated($request, $user)
    {
        // Vérifier si le compte de l'utilisateur est valide
        if ($user->user_statut !== 'VALIDE') {
            $this->guard()->logout();

            $request->session()->invalidate();

            return back()->with(['echec' => "Connexion impossible ! Veuillez contacter l'administration"]);
        }

        return redirect()->intended($this->redirectPath());
    }

    public function username()
    {
        $login = request()->input('login');
                
        // Déterminer si l'entrée est un e-mail ou un téléphone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'telephone';

        // Fusionner la valeur correcte dans la requête
        request()->merge([$field => $login]);

        return $field;
    }
}
