<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_email_and_mot_passe(): void
    {
        $user = User::create([
            'nom' => 'Demo',
            'prenom' => 'User',
            'email' => 'demo@example.com',
            'telephone' => '770000000',
            'adresse' => 'Dakar',
            'mot_passe' => Hash::make('secret123'),
            'role' => 'menage',
            'statut_compte' => 'inscrit',
            'score_total' => 0,
            'date_inscription' => now(),
            'date_derniere_connexion' => now(),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'demo@example.com',
            'mot_passe' => 'secret123',
        ]);

        $response->assertRedirect(route('menage.dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
