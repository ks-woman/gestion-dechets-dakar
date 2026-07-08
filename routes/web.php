<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CollecteController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\CollecteurController;
use App\Http\Controllers\RecompenseUserController;
use Illuminate\Support\Facades\Route;

// =============================================
// ROUTES PUBLIQUES
// =============================================
Route::get('/', function () {
    return view('home');
})->name('home');

// =============================================
// INSCRIPTION
// =============================================
Route::get('/register', [AuthController::class, 'chooseRole'])->name('register');

Route::get('/register/menage', [AuthController::class, 'showRegisterMenageForm'])->name('register.menage.form');
Route::post('/register/menage', [AuthController::class, 'registerMenage'])->name('register.menage');

Route::get('/register/entreprise', [AuthController::class, 'showRegisterEntrepriseForm'])->name('register.entreprise.form');
Route::post('/register/entreprise', [AuthController::class, 'registerEntreprise'])->name('register.entreprise');

Route::get('/register/collecteur', [AuthController::class, 'showRegisterCollecteurForm'])->name('register.collecteur.form');
Route::post('/register/collecteur', [AuthController::class, 'registerCollecteur'])->name('register.collecteur');

Route::get('/register/partenaire', [AuthController::class, 'showRegisterPartenaireForm'])->name('register.partenaire.form');
Route::post('/register/partenaire', [AuthController::class, 'registerPartenaire'])->name('register.partenaire');

// =============================================
// CONNEXION / DÉCONNEXION
// =============================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
// ROUTES MÉNAGE / ENTREPRISE
// =============================================
Route::middleware(['auth', 'menage'])->group(function () {
    Route::get('/mon-espace', [CollecteController::class, 'dashboard'])->name('menage.dashboard');

    Route::get('/kit/demander', [KitController::class, 'showDemanderKit'])->name('kit.demander');
    Route::post('/kit/demander', [KitController::class, 'demanderKit'])->name('kit.demander.post');

    Route::get('/mode-emploi', function () {
        return view('menage.mode-emploi');
    })->name('menage.mode-emploi');

    Route::get('/collecte/demander', [CollecteController::class, 'showDemanderCollecte'])->name('collecte.demander');
    Route::post('/collecte/demander', [CollecteController::class, 'demanderCollecte'])->name('collecte.demander.post');
    Route::get('/collectes', [CollecteController::class, 'historique'])->name('collectes');

    Route::get('/statistiques', [CollecteController::class, 'statistiques'])->name('statistiques');
    Route::get('/profil', [AuthController::class, 'profile'])->name('profil');
    Route::put('/profil', [AuthController::class, 'updateProfile'])->name('profil.update');
    Route::put('/profil/password', [AuthController::class, 'updatePassword'])->name('profil.password');
    Route::get('/preferences', [AuthController::class, 'preferences'])->name('menage.preferences');
    Route::put('/preferences', [AuthController::class, 'updatePreferences'])->name('menage.preferences.update');

    // Récompenses
    Route::get('/recompenses', [RecompenseUserController::class, 'catalogue'])->name('recompenses.catalogue');
    Route::get('/recompenses/{id}', [RecompenseUserController::class, 'show'])->name('recompenses.show');
    Route::post('/recompenses/{id}/echanger', [RecompenseUserController::class, 'echanger'])->name('recompenses.echanger');
    Route::get('/recompenses/historique', [RecompenseUserController::class, 'historique'])->name('recompenses.historique');
});

// =============================================
// ROUTES COLLECTEUR
// =============================================
Route::middleware(['auth', 'collecteur'])->prefix('collecteur')->name('collecteur.')->group(function () {
    Route::get('/dashboard', [CollecteurController::class, 'dashboard'])->name('dashboard');
    Route::get('/tournee', [CollecteurController::class, 'tournee'])->name('tournee');

    Route::get('/activer-kit', [CollecteurController::class, 'activerKitPage'])->name('activer-kit');
    Route::post('/kit/activer', [KitController::class, 'activerKit'])->name('kit.activer');
    Route::get('/scanner', function () {
        return view('collecteur.scanner');
    })->name('scanner');
    Route::get('/activer-kit/scan/{code}', [CollecteurController::class, 'activerKitParScan'])->name('activer-kit.par-scan');

    Route::get('/kits-a-livrer', [CollecteurController::class, 'kitsALivrer'])->name('kits-a-livrer');

    Route::get('/anomalie/{id}/creer', [CollecteurController::class, 'creerAnomalie'])->name('anomalie.creer');
    Route::post('/anomalie', [CollecteurController::class, 'storeAnomalie'])->name('anomalie.store');

    Route::get('/enregistrer-collecte', [CollecteurController::class, 'enregistrerCollectePage'])->name('enregistrer-collecte');
    Route::get('/collecte/{id}/enregistrer', [CollecteurController::class, 'formEnregistrerCollecte'])->name('collecte.form');
    Route::post('/collecte/enregistrer', [CollecteurController::class, 'enregistrerCollecte'])->name('collecte.enregistrer');

    Route::get('/historique', [CollecteurController::class, 'historique'])->name('historique');
    Route::get('/statistiques', [CollecteurController::class, 'statistiques'])->name('statistiques');
    Route::get('/primes', [CollecteurController::class, 'primes'])->name('primes');
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

    // Zones de collecte
    Route::resource('zones', App\Http\Controllers\Admin\ZoneCollecteController::class);

    // Récompenses
    Route::resource('recompenses', App\Http\Controllers\Admin\RecompenseController::class)->except(['show']);

    // Réclamations
    Route::get('/reclamations', [App\Http\Controllers\Admin\ReclamationController::class, 'index'])->name('reclamations.index');
    Route::get('/reclamations/{id}', [App\Http\Controllers\Admin\ReclamationController::class, 'show'])->name('reclamations.show');
    Route::put('/reclamations/{id}', [App\Http\Controllers\Admin\ReclamationController::class, 'update'])->name('reclamations.update');

    // Gestion des catégories de déchets
    Route::resource('categories', App\Http\Controllers\Admin\CategorieDechetController::class)->except(['show']);

    // =============================================
    //  GESTION DES STOCKS DE DÉCHETS
    // =============================================
    Route::get('/stocks', [App\Http\Controllers\Admin\StockDechetController::class, 'index'])->name('stocks.index');
    Route::get('/stocks/{id}/edit', [App\Http\Controllers\Admin\StockDechetController::class, 'edit'])->name('stocks.edit');
    Route::put('/stocks/{id}', [App\Http\Controllers\Admin\StockDechetController::class, 'update'])->name('stocks.update');

    // =============================================
    //  GESTION DES COMMANDES
    // =============================================
    Route::get('/commandes', [App\Http\Controllers\Admin\CommandeController::class, 'index'])->name('commandes.index');
    Route::get('/commandes/{id}', [App\Http\Controllers\Admin\CommandeController::class, 'show'])->name('commandes.show');
    Route::put('/commandes/{id}', [App\Http\Controllers\Admin\CommandeController::class, 'update'])->name('commandes.update');
});
// =============================================
// ROUTES PARTENAIRE
// =============================================
Route::middleware(['auth', 'partenaire'])->prefix('partenaire')->name('partenaire.')->group(function () {
    Route::get('/dashboard', [PartenaireController::class, 'dashboard'])->name('dashboard');
    Route::get('/dechets', [PartenaireController::class, 'dechetsRecus'])->name('dechets');
    Route::post('/valider/{id}', [PartenaireController::class, 'validerReception'])->name('valider');
    Route::get('/statistiques', [PartenaireController::class, 'statistiques'])->name('statistiques');

    // Offres et commandes
    Route::get('/offres', [PartenaireController::class, 'offres'])->name('offres');
    Route::get('/commander/{id}', [PartenaireController::class, 'showCommandeForm'])->name('commander.form');
    Route::post('/commander', [PartenaireController::class, 'storeCommande'])->name('commander.store');
    Route::get('/historique', [PartenaireController::class, 'historique'])->name('historique');

    // Certificats
    Route::get('/certificat/generer/{id}', [PartenaireController::class, 'genererCertificat'])->name('certificat.generer');
    Route::get('/certificat/{id}', [PartenaireController::class, 'showCertificat'])->name('certificat.show');

    // Profil (besoins)
    Route::put('/profil', [PartenaireController::class, 'updateProfil'])->name('profil.update');
});
