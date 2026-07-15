<?php

namespace App\Services\Payment;

use App\Models\Paiement;

interface PaymentGateway
{
    public function initierPaiement(Paiement $paiement, array $options = []): array;
    public function verifierPaiement(Paiement $paiement): string; // valide, echoue, en_attente
    public function traiterCallback(array $data): ?Paiement;
    public function rembourser(Paiement $paiement): bool;
}
