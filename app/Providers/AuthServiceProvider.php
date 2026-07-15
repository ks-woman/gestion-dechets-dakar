<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Collecte;
use App\Models\KitTri;
use App\Models\Abonnement;
use App\Models\Commande;
use App\Models\Reclamation;
use App\Models\ZoneCollecte;
use App\Models\Collecteur;
use App\Policies\UserPolicy;
use App\Policies\CollectePolicy;
use App\Policies\KitTriPolicy;
use App\Policies\AbonnementPolicy;
use App\Policies\CommandePolicy;
use App\Policies\ReclamationPolicy;
use App\Policies\ZoneCollectePolicy;
use App\Policies\CollecteurPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => UserPolicy::class,
        Collecte::class => CollectePolicy::class,
        KitTri::class => KitTriPolicy::class,
        Abonnement::class => AbonnementPolicy::class,
        Commande::class => CommandePolicy::class,
        Reclamation::class => ReclamationPolicy::class,
        ZoneCollecte::class => ZoneCollectePolicy::class,
        // Partenaire n'est pas un modèle, c'est un rôle → pas de Policy
        Collecteur::class => CollecteurPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ PERMETTRE AUX ADMINISTRATEURS DE TOUT FAIRE
        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Gates pour les actions spécifiques (les admins les auront aussi grâce à before)
        Gate::define('activer-kit', [KitTriPolicy::class, 'activer']);
        Gate::define('valider-reception', function ($user) {
            return $user->isPartenaire();
        });
        Gate::define('voir-tournee', [CollecteurPolicy::class, 'viewTournee']);
        Gate::define('repondre-reclamation', [ReclamationPolicy::class, 'repondre']);
        Gate::define('resilier-abonnement', [AbonnementPolicy::class, 'resilier']);
    }
}
