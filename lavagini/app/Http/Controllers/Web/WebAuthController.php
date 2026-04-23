<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class WebAuthController extends Controller
{
    // Afficher la page de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // Traiter la connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->with('error', 'Email ou mot de passe incorrect');
    }

    // Afficher la page d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // Traiter l'inscription
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'telephone' => 'nullable|string',
            'adresse' => 'nullable|string',
            'type_client' => 'nullable|in:particulier,agence'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'type_client' => $request->type_client
        ]);

        Auth::login($user);

        return redirect('/dashboard')->with('success', 'Inscription réussie !');
    }

    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Déconnexion réussie');
    }

    // Afficher le formulaire de demande de réinitialisation
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Envoyer le lien de réinitialisation par email
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Aucun compte trouvé avec cet email.');
        }

        // Générer un token
        $token = Str::random(64);

        // Supprimer les anciens tokens pour cet email
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insérer le nouveau token
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Envoyer l'email
        try {
            Mail::send('emails.reset-password', ['token' => $token, 'email' => $request->email], function($message) use ($request) {
                $message->to($request->email);
                $message->subject('Réinitialisation de votre mot de passe - LAVAGINI');
            });

            return back()->with('success', '✅ Un lien de réinitialisation a été envoyé à votre email.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    // Afficher le formulaire de réinitialisation
    public function showResetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    // Réinitialiser le mot de passe
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required'
        ]);

        // Vérifier le token
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$resetRecord) {
            return back()->with('error', 'Token invalide ou expiré.');
        }

        // Vérifier que le token correspond
        if (!Hash::check($request->token, $resetRecord->token)) {
            return back()->with('error', 'Token invalide.');
        }

        // Vérifier que le token n'est pas expiré (60 minutes)
        if (now()->diffInMinutes($resetRecord->created_at) > 60) {
            return back()->with('error', 'Ce lien a expiré. Veuillez faire une nouvelle demande.');
        }

        // Mettre à jour le mot de passe
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Supprimer le token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('success', '✅ Votre mot de passe a été réinitialisé avec succès !');
    }
}
