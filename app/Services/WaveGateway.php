<?php

namespace App\Services\Payment;

use App\Models\Paiement;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaveGateway implements PaymentGateway
{
    protected $apiKey;
    protected $baseUrl;
    protected $webhookUrl;

    public function __construct()
    {
        $this->apiKey = config('services.wave.api_key');
        $this->baseUrl = config('services.wave.base_url', 'https://api.wave.com/v1');
        $this->webhookUrl = route('payment.webhook.wave');
    }

    public function initierPaiement(Paiement $paiement, array $options = []): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/payments', [
                'amount' => $paiement->montant,
                'currency' => 'XOF',
                'reference' => $paiement->reference,
                'description' => 'Abonnement Gestion Déchets Dakar',
                'customer' => [
                    'email' => $paiement->abonnement->user->email,
                    'phone' => $paiement->abonnement->user->telephone,
                ],
                'webhook_url' => $this->webhookUrl,
                'redirect_url' => route('payment.callback'),
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'redirect_url' => $data['payment_url'] ?? $data['redirect_url'],
                    'transaction_id' => $data['id'] ?? $data['transaction_id'],
                ];
            }

            Log::error('Wave init error', ['response' => $response->body()]);
            throw new \Exception('Erreur d\'initiation du paiement Wave.');
        } catch (\Exception $e) {
            Log::error('Wave init exception', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function verifierPaiement(Paiement $paiement): string
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->get($this->baseUrl . '/payments/' . $paiement->transaction_id);

            if ($response->successful()) {
                $status = $response->json()['status'] ?? 'unknown';
                return match ($status) {
                    'completed', 'success' => 'valide',
                    'failed', 'cancelled' => 'echoue',
                    default => 'en_attente',
                };
            }

            return 'en_attente';
        } catch (\Exception $e) {
            Log::error('Wave verify exception', ['message' => $e->getMessage()]);
            return 'en_attente';
        }
    }

    public function traiterCallback(array $data): ?Paiement
    {
        $transactionId = $data['transaction_id'] ?? $data['id'] ?? null;
        if (!$transactionId) {
            return null;
        }

        $paiement = Paiement::where('transaction_id', $transactionId)->first();
        if (!$paiement) {
            return null;
        }

        $status = $this->verifierPaiement($paiement);
        $paiement->statut = $status;
        $paiement->gateway_response = json_encode($data);

        if ($status === 'valide') {
            $paiement->date_validation = now();
            $this->activerAbonnement($paiement);
        }

        $paiement->save();
        return $paiement;
    }

    public function rembourser(Paiement $paiement): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
            ])->post($this->baseUrl . '/payments/' . $paiement->transaction_id . '/refund', [
                'amount' => $paiement->montant,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Wave refund exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    protected function activerAbonnement(Paiement $paiement)
    {
        $abonnement = $paiement->abonnement;
        $abonnement->statut = 'actif';
        $abonnement->date_debut_abonnement = now();
        $abonnement->save();

        $user = $abonnement->user;
        $user->statut_compte = 'abonne_actif';
        $user->save();

        \App\Models\Notification::create([
            'user_id' => $user->id,
            'titre' => ' Paiement confirmé',
            'message' => 'Votre paiement a été validé. Votre abonnement est actif.',
            'type' => 'abonnement',
            'est_lu' => false,
        ]);
    }
}
