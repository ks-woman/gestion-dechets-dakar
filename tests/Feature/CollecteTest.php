<?php

namespace Tests\Feature;

use App\Models\Collecte;
use App\Models\Collecteur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CollecteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_a_collecte_and_collecte_record_includes_date_demande(): void
    {
        $user = User::create([
            'nom' => 'Diallo',
            'prenom' => 'Awa',
            'email' => 'awa@example.com',
            'telephone' => '771234567',
            'adresse' => 'Dakar',
            'mot_passe' => Hash::make('password'),
            'role' => 'menage',
            'statut_compte' => 'essai_15j',
            'score_total' => 0,
            'date_inscription' => now(),
            'date_derniere_connexion' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post(route('collecte.demander.post'), [
            'date_souhaitee' => now()->addDay()->toDateString(),
            'adresse' => 'Dakar Plateau',
            'instructions' => 'Cest un test',
        ]);

        $response->assertRedirect(route('collectes'));

        $this->assertDatabaseHas('collectes', [
            'user_id' => $user->id,
            'statut' => 'planifiee',
        ]);

        $collecte = Collecte::where('user_id', $user->id)->latest()->first();

        $this->assertNotNull($collecte);
        $this->assertNotNull($collecte->date_demande);
        $this->assertEquals($collecte->date_collecte->toDateString(), $collecte->date_demande->toDateString());
    }

    public function test_collecteur_dashboard_lists_planified_collectes_to_record(): void
    {
        $collecteurUser = User::create([
            'nom' => 'Sarr',
            'prenom' => 'Moussa',
            'email' => 'moussa@example.com',
            'telephone' => '776543210',
            'adresse' => 'Dakar',
            'mot_passe' => Hash::make('password'),
            'role' => 'collecteur',
            'statut_compte' => 'inscrit',
            'score_total' => 0,
            'date_inscription' => now(),
            'date_derniere_connexion' => now(),
        ]);

        Collecteur::create([
            'user_id' => $collecteurUser->id,
            'matricule' => 'COL-001',
            'vehicule_type' => 'motocycliste',
            'zone_couverture' => 'Dakar',
            'disponibilite' => true,
        ]);

        $menage = User::create([
            'nom' => 'Diop',
            'prenom' => 'Fatou',
            'email' => 'fatou@example.com',
            'telephone' => '775000000',
            'adresse' => 'Mermoz',
            'mot_passe' => Hash::make('password'),
            'role' => 'menage',
            'statut_compte' => 'essai_15j',
            'score_total' => 0,
            'date_inscription' => now(),
            'date_derniere_connexion' => now(),
        ]);

        Collecte::create([
            'user_id' => $menage->id,
            'date_demande' => now()->toDateString(),
            'date_collecte' => now()->addDay()->toDateString(),
            'adresse' => 'Mermoz',
            'instructions' => 'Test',
            'statut' => 'planifiee',
        ]);

        $this->actingAs($collecteurUser);

        $response = $this->get(route('collecteur.dashboard'));

        $response->assertOk();
        $response->assertSee('Fatou');
        $response->assertSee('Diop');
    }
}
