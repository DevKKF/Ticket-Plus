<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChangerPasswordController extends Controller
{
    //Formulaire de demande de changement de mot de passe
    public function ChangePassword(){
        
        if (Auth::check()){
            return redirect()->route('home');
        }

        return view('auth.passwords.email');
    }

    //Envoi de mail de changement de mot de passe
    public function ChangePasswordSave(Request $request){

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Aucun utilisateur avec cette adresse e-mail n\'a été trouvé.']);
        }

        $token = Str::random(60); // Génération d'un token unique

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        Mail::to($user->email)->send(new ResetPasswordMail($user, $token));

        return back()->with('success', 'Un e-mail de réinitialisation a été envoyé à votre adresse e-mail.');
    }

    //Formulaire de changement de mot de passe
    public function ChangePasswordForm($token){
        
        $passwordReset = DB::table('password_resets')->where('token', $token)->first();

        if (!$passwordReset) {
            return redirect()->route('password.change')->withErrors(['token' => 'Le token de réinitialisation de mot de passe est invalide.']);
        }

        return view('auth.passwords.reset', ['passwordReset' => $passwordReset]);
    }

    //Changement de mot de passe
    public function ChangePasswordFinalSave(Request $request){
        
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $passwordToken = DB::table('password_resets')->where('token', $request->token)->first();

        if (!$passwordToken) {
            return redirect()->route('password.change')->withErrors(['token' => 'Le token de réinitialisation de mot de passe est invalide.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.change')->withErrors(['email' => 'Aucun utilisateur avec cette adresse e-mail n\'a été trouvé.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Supprimer l'enregistrement de la table 'password_resets' pour éviter une réutilisation du token
        DB::table('password_resets')->where('email', $user->email)->delete();

        // Connecter automatiquement l'utilisateur après la mise à jour du mot de passe
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Votre mot de passe a été mis à jour avec succès.');
    }

}
