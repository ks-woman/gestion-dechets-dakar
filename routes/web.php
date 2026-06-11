<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\CollecteurController;
use Illuminate\Support\Facades\Route;

// =============================================
// ROUTES PUBLIQUES (accessibles sans connexion)
// =============================================

// Page d'accueil
Route::get('/', function () {
    return view('home');
})->name('home');

// =============================================
// INSCRIPTION - Choix du rôle et formulaires
// =============================================

// Page de choix du type de compte
Route::get('/register', [AuthController::class, 'chooseRole'])->name('register');

// Formulaire d'inscription MÉNAGE
Route::get('/register/menage', [AuthController::class, 'showRegisterMenageForm'])->name('register.menage.form');
Route::post('/register/menage', [AuthController::class, 'registerMenage'])->name('register.menage');

// Formulaire d'inscription ENTREPRISE
Route::get('/register/entreprise', [AuthController::class, 'showRegisterEntrepriseForm'])->name('register.entreprise.form');
Route::post('/register/entreprise', [AuthController::class, 'registerEntreprise'])->name('register.entreprise');

// Formulaire d'inscription COLLECTEUR
Route::get('/register/collecteur', [AuthController::class, 'showRegisterCollecteurForm'])->name('register.collecteur.form');
Route::post('/register/collecteur', [AuthController::class, 'registerCollecteur'])->name('register.collecteur');

// Formulaire d'inscription PARTENAIRE
Route::get('/register/partenaire', [AuthController::class, 'showRegisterPartenaireForm'])->name('register.partenaire.form');
Route::post('/register/partenaire', [AuthController::class, 'registerPartenaire'])->name('register.partenaire');

// =============================================
// CONNEXION / DÉCONNEXION
// =============================================

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
// ROUTES PROTÉGÉES (nécessitent une connexion)
// =============================================

// =============================================
// ROUTES POUR MÉNAGES ET ENTREPRISES
// =============================================
Route::middleware(['auth', 'menage'])->group(function () {

    // Tableau de bord
    Route::get('/mon-espace', [CollecteController::class, 'dashboard'])->name('menage.dashboard');

    // Gestion du kit de tri
    Route::get('/kit/demander', [KitController::class, 'showDemanderKit'])->name('kit.demander');
    Route::post('/kit/demander', [KitController::class, 'demanderKit'])->name('kit.demander.post');

    // Gestion des collectes
    Route::get('/collecte/demander', [CollecteController::class, 'showDemanderCollecte'])->name('collecte.demander');
    Route::post('/collecte/demander', [CollecteController::class, 'demanderCollecte'])->name('collecte.demander.post');
    Route::get('/collectes', [CollecteController::class, 'historique'])->name('collectes');

    // Statistiques et profil
    Route::get('/statistiques', [CollecteController::class, 'statistiques'])->name('statistiques');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profil');
    Route::get('/preferences', [AuthController::class, 'preferences'])->name('menage.preferences');
    Route::put('/preferences', [AuthController::class, 'updatePreferences'])->name('menage.preferences.update');
});

// =============================================
// ROUTES POUR COLLECTEURS
// =============================================
Route::middleware(['auth', 'collecteur'])->prefix('collecteur')->name('collecteur.')->group(function () {

    Route::get('/dashboard', [CollecteurController::class, 'dashboard'])->name('dashboard');
    Route::get('/tournee', [CollecteurController::class, 'tournee'])->name('tournee');

    // Routes pour l'activation du kit
    Route::get('/activer-kit', [CollecteurController::class, 'activerKitPage'])->name('activer-kit');
    Route::post('/kit/activer', [KitController::class, 'activerKit'])->name('kit.activer');

    // Routes pour l'enregistrement des collectes
    Route::get('/enregistrer-collecte', [CollecteurController::class, 'enregistrerCollectePage'])->name('enregistrer-collecte');
    Route::get('/collecte/{id}/enregistrer', [CollecteurController::class, 'formEnregistrerCollecte'])->name('collecte.form');
    Route::post('/collecte/enregistrer', [CollecteurController::class, 'enregistrerCollecte'])->name('collecte.enregistrer');

    Route::get('/scanner', function () {
        return view('collecteur.scanner');
    })->name('scanner');
    Route::get('/activer-kit/scan/{code}', [CollecteurController::class, 'activerKitParScan'])->name('activer-kit.par-scan');
});

// =============================================
// ROUTES POUR ADMINISTRATEURS
// =============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Gestion des utilisateurs (CRUD)
    Route::get('/utilisateurs', [AdminController::class, 'utilisateurs'])->name('utilisateurs');
    Route::get('/utilisateurs/create', [AdminController::class, 'createUtilisateur'])->name('utilisateurs.create');
    Route::post('/utilisateurs', [AdminController::class, 'storeUtilisateur'])->name('utilisateurs.store');
    Route::get('/utilisateurs/{id}/edit', [AdminController::class, 'editUtilisateur'])->name('utilisateurs.edit');
    Route::put('/utilisateurs/{id}', [AdminController::class, 'updateUtilisateur'])->name('utilisateurs.update');
    Route::delete('/utilisateurs/{id}', [AdminController::class, 'destroyUtilisateur'])->name('utilisateurs.destroy');

    // Autres pages admin
    Route::get('/collectes', [AdminController::class, 'collectes'])->name('collectes');
    Route::get('/statistiques', [AdminController::class, 'statistiques'])->name('statistiques');
    Route::get('/kits', [AdminController::class, 'kits'])->name('kits');
});

// =============================================
// ROUTES POUR PARTENAIRES
// =============================================
Route::middleware(['auth', 'partenaire'])->prefix('partenaire')->name('partenaire.')->group(function () {

    Route::get('/dashboard', [PartenaireController::class, 'dashboard'])->name('dashboard');
    Route::get('/dechets-recus', [PartenaireController::class, 'dechetsDisponibles'])->name('dechets');
    Route::post('/valider-reception/{id}', [PartenaireController::class, 'validerReception'])->name('valider');
});
