<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method bool estEnPeriodeEssai()
 * @method bool estAbonneActif()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'quartier',
        'latitude',
        'longitude',
        'mot_passe',
        'role',
        'statut_compte',
        'score_total',
        'date_inscription',
        'date_derniere_connexion',
        'frequence_collecte',
        'jours_collecte',
        'jour_hebdomadaire',
        'jour_bihebdomadaire',
        'semaine_type',
    ];

    protected $hidden = [
        'mot_passe',
        'remember_token',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
        'date_derniere_connexion' => 'datetime',
    ];

    // Relations
    public function menage()
    {
        return $this->hasOne(Menage::class);
    }

    public function entreprise()
    {
        return $this->hasOne(Entreprise::class);
    }

    public function collecteur()
    {
        return $this->hasOne(Collecteur::class);
    }

    public function abonnement()
    {
        return $this->hasOne(Abonnement::class);
    }

    public function kitTri()
    {
        return $this->hasOne(KitTri::class);
    }

    public function collectes()
    {
        return $this->hasMany(Collecte::class);
    }

    public function demandesCollecte()
    {
        return $this->hasMany(DemandeCollecte::class);
    }

    public function reclamations()
    {
        return $this->hasMany(Reclamation::class);
    }

    // Méthodes
    public function isMenage()
    {
        return $this->role === 'menage';
    }

    public function isEntreprise()
    {
        return $this->role === 'entreprise';
    }

    public function isCollecteur()
    {
        return $this->role === 'collecteur';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPartenaire()
    {
        return $this->role === 'partenaire';
    }

    public function getAuthPasswordName()
    {
        return 'mot_passe';
    }

    public function estEnPeriodeEssai()
    {
        return $this->statut_compte === 'essai_15j';
    }

    public function estAbonneActif()
    {
        return $this->statut_compte === 'abonne_actif';
    }

    public function ajouterPoints($points)
    {
        $this->score_total += $points;
        $this->save();
    }

    public function getNiveauAttribute()
    {
        if ($this->score_total >= 600) return 'platine';
        if ($this->score_total >= 301) return 'or';
        if ($this->score_total >= 101) return 'argent';
        return 'bronze';
    }

    public function getAuthPassword()
    {
        return $this->mot_passe;
    }

    public function estJourCollecte($date = null)
    {
        $date = $date ?: now();
        $jour = strtolower($date->format('l'));

        switch ($this->frequence_collecte) {
            case 'quotidienne':
                return true;

            case '2x_semaine':
                if (!$this->jours_collecte) return false;
                $jours = json_decode($this->jours_collecte, true);
                return is_array($jours) && in_array($jour, array_map('strtolower', $jours));

            case 'hebdomadaire':
                if (!$this->jour_hebdomadaire) return false;
                return $jour === strtolower($this->jour_hebdomadaire);

            case 'bihebdomadaire':
                if (!$this->jour_bihebdomadaire) return false;
                // Vérifier le jour
                if ($jour !== strtolower($this->jour_bihebdomadaire)) return false;
                // Vérifier la semaine (paire ou impaire)
                $semaine = (int) $date->weekOfYear;
                $estPaire = ($semaine % 2 == 0);
                return $estPaire ? $this->semaine_type === 'paire' : $this->semaine_type === 'impaire';

            case 'sur_demande':
            default:
                return false;
        }
    }

    public function echangeRecompenses()
    {
        return $this->hasMany(EchangeRecompense::class);
    }
}
