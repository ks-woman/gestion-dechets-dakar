<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Menage;
use App\Models\Entreprise;
use App\Models\Collecteur;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // =============================================
    // AFFICHAGE DES FORMULAIRES
    // =============================================

    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Page de choix du rôle (ménage, entreprise, collecteur, partenaire)
    public function chooseRole()
    {
        return view('auth.choose-role');
    }

    // Formulaire d'inscription Ménage
    public function showRegisterMenageForm()
    {
        return view('auth.register-menage');
    }

    // Formulaire d'inscription Entreprise
    public function showRegisterEntrepriseForm()
    {
        return view('auth.register-entreprise');
    }

    // Formulaire d'inscription Collecteur
    public function showRegisterCollecteurForm()
    {
        return view('auth.register-collecteur');
    }

    // Formulaire d'inscription Partenaire
    public function showRegisterPartenaireForm()
    {
        return view('auth.register-partenaire');
    }

    // =============================================
    // INSCRIPTIONS
    // =============================================

    // Traiter l'inscription d'un MÉNAGE
    public function registerMenage(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mot_passe' => 'required|string|min:6|confirmed',
            'nombre_personnes' => 'required|integer|min:1',
            'type_logement' => 'required|in:villa,appartement,studio,chambre,immeuble_collectif'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Création de l'utilisateur de base
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'mot_passe' => Hash::make($request->mot_passe),
            'role' => 'menage',
            'statut_compte' => 'inscrit',
            'score_total' => 0,
            'date_inscription' => now(),
        ]);

        // Création du profil ménage
        Menage::create([
            'user_id' => $user->id,
            'nombre_personnes' => $request->nombre_personnes,
            'type_logement' => $request->type_logement,
            'a_enfants' => $request->a_enfants ?? false
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        // Redirection vers le dashboard du ménage
        return redirect()->route('menage.dashboard')->with('success', 'Inscription réussie !');
    }

    // Traiter l'inscription d'une ENTREPRISE
    public function registerEntreprise(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mot_passe' => 'required|string|min:6|confirmed',
            'numero_registre_commerce' => 'required|string|max:50',
            'type_activite' => 'required|string|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Création de l'utilisateur de base
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'mot_passe' => Hash::make($request->mot_passe),
            'role' => 'entreprise',
            'statut_compte' => 'inscrit',
            'score_total' => 0,
            'date_inscription' => now(),
        ]);

        // Création du profil entreprise
        Entreprise::create([
            'user_id' => $user->id,
            'numero_registre_commerce' => $request->numero_registre_commerce,
            'type_activite' => $request->type_activite,
            'volume_moyen_dechet' => $request->volume_moyen_dechet ?? 0
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        // Redirection vers le dashboard du ménage (même interface que ménage)
        return redirect()->route('menage.dashboard')->with('success', 'Inscription réussie !');
    }

    // Traiter l'inscription d'un COLLECTEUR
    public function registerCollecteur(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mot_passe' => 'required|string|min:6|confirmed',
            'matricule' => 'required|string|max:50|unique:collecteurs',
            'vehicule_type' => 'required|in:charette,motocycliste,camion',
            'zone_couverture' => 'nullable|string|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Création de l'utilisateur de base
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'mot_passe' => Hash::make($request->mot_passe),
            'role' => 'collecteur',
            'statut_compte' => 'abonne_actif',
            'score_total' => 0,
            'date_inscription' => now(),
        ]);

        // Création du profil collecteur
        Collecteur::create([
            'user_id' => $user->id,
            'matricule' => $request->matricule,
            'vehicule_type' => $request->vehicule_type,
            'zone_couverture' => $request->zone_couverture,
            'disponibilite' => true,
            'note_moyenne' => 0
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        // Redirection vers le dashboard du collecteur
        return redirect()->route('collecteur.dashboard')->with('success', 'Inscription réussie !');
    }

    // Traiter l'inscription d'un PARTENAIRE
    public function registerPartenaire(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'quartier' => 'required|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'mot_passe' => 'required|string|min:6|confirmed',
            'type_partenaire' => 'required|string',
            'filiere' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Création de l'utilisateur de base
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'mot_passe' => Hash::make($request->mot_passe),
            'role' => 'partenaire',
            'statut_compte' => 'abonne_actif',
            'score_total' => 0,
            'date_inscription' => now(),
        ]);

        // Création du profil partenaire
        Partenaire::create([
            'user_id' => $user->id,
            'type_partenaire' => $request->type_partenaire,
            'filiere' => $request->filiere,
            'statut_partenariat' => 'actif'
        ]);

        // Connexion automatique après inscription
        Auth::login($user);

        // Redirection vers le dashboard du partenaire
        return redirect()->route('partenaire.dashboard')->with('success', 'Inscription réussie !');
    }

    // =============================================
    // CONNEXION / DÉCONNEXION
    // =============================================

    // Traiter la connexion d'un utilisateur
    public function login(Request $request)
    {
        // Validation des données
        $credentials = $request->validate([
            'email' => 'required|email',
            'mot_passe' => 'required|string'
        ]);

        // Chercher l'utilisateur par son email
        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && Hash::check($request->mot_passe, $user->mot_passe)) {
            // Connexion de l'utilisateur
            Auth::login($user);
            $request->session()->regenerate();

            // Redirection selon le rôle de l'utilisateur
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->isCollecteur()) {
                return redirect()->route('collecteur.dashboard');
            }
            if ($user->isPartenaire()) {
                return redirect()->route('partenaire.dashboard');
            }
            if ($user->isMenage() || $user->isEntreprise()) {
                return redirect()->route('menage.dashboard');
            }

            // Cas par défaut (ne devrait jamais arriver)
            return redirect('/');
        }

        // Échec de l'authentification
        return back()->withErrors([
            'email' => 'Email ou mot de passe incorrect',
        ])->onlyInput('email');
    }

    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    // =============================================
    // PROFIL UTILISATEUR
    // =============================================

    // Afficher le profil de l'utilisateur connecté
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function preferences()
    {
        // Affiche la vue des préférences de collecte pour le ménage
        return view('menage.preferences');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $user->frequence_collecte = $request->frequence_collecte;

        // Réinitialiser tous les champs spécifiques
        $user->jours_collecte = null;
        $user->jour_hebdomadaire = null;
        $user->jour_bihebdomadaire = null;
        $user->semaine_type = null;

        switch ($request->frequence_collecte) {
            case '2x_semaine':
                if ($request->has('jours')) {
                    $user->jours_collecte = json_encode($request->jours);
                }
                break;
            case 'hebdomadaire':
                $user->jour_hebdomadaire = $request->jour_hebdomadaire;
                break;
            case 'bihebdomadaire':
                $user->jour_bihebdomadaire = $request->jour_bihebdomadaire;
                $user->semaine_type = $request->semaine_type;
                break;
        }

        $user->save();

        return redirect()->route('menage.preferences')->with('success', 'Préférences enregistrées !');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'nullable|string|max:255',
        ]);

        $user->update([
            'nom' => $request->nom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'user' => $user
        ]);
    }
}
