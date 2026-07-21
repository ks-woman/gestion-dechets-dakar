@extends('layouts.admin')

@section('title', 'Statistiques des collecteurs')

@section('content')
    <div class="space-y-6">
        <!-- En-tête -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-chart-line text-emerald-500"></i> Statistiques des collecteurs
                    </h1>
                    <p class="text-gray-500 mt-1">Analyse des performances et suivi des collectes</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn-gray text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="bg-white rounded-xl shadow-soft p-4">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Année</label>
                    <select name="annee" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        @for ($y = now()->year; $y >= now()->year - 2; $y--)
                            <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Mois</label>
                    <select name="mois" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('fr')->monthName }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Collecteur</label>
                    <select name="collecteur_id" class="border rounded-lg p-2 focus:ring-2 focus:ring-emerald-500">
                        <option value="">Tous</option>
                        @foreach ($allCollecteurs as $c)
                            <option value="{{ $c->id }}" {{ $collecteurId == $c->id ? 'selected' : '' }}>
                                {{ $c->user->prenom ?? '' }} {{ $c->user->nom ?? '' }} ({{ $c->matricule }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.statistiques.collecteurs') }}" class="btn-gray ml-2">
                        <i class="fas fa-times mr-2"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- KPIs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collectes</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $totalCollectes }}</p>
                        <p class="text-xs text-gray-400">sur la période</p>
                    </div>
                    <i class="fas fa-truck text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Poids total</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalPoids, 1) }} kg</p>
                    </div>
                    <i class="fas fa-weight-hanging text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Points générés</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($totalPoints, 0) }}</p>
                    </div>
                    <i class="fas fa-star text-3xl text-emerald-500"></i>
                </div>
            </div>
            <div class="card">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-500 text-sm">Collecteurs actifs</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ $collecteursActifs }}</p>
                    </div>
                    <i class="fas fa-users text-3xl text-emerald-500"></i>
                </div>
            </div>
        </div>

        <!-- Top 5 collecteurs -->
        <div class="bg-white rounded-xl shadow-soft p-6">
            <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i> Top 5 des collecteurs (poids total)
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px]">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">#</th>
                            <th class="text-left py-2">Collecteur</th>
                            <th class="text-left py-2 hidden sm:table-cell">Matricule</th>
                            <th class="text-right py-2 hidden md:table-cell">Collectes</th>
                            <th class="text-right py-2 hidden lg:table-cell">Recyclable</th>
                            <th class="text-right py-2 hidden lg:table-cell">Organique</th>
                            <th class="text-right py-2 hidden lg:table-cell">Résiduel</th>
                            <th class="text-right py-2">Total (kg)</th>
                            <th class="text-right py-2 hidden sm:table-cell">Points</th>
                            <th class="text-center py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($top5 as $index => $c)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 font-bold">
                                    @if ($index == 0)
                                    @elseif($index == 1)

                                    @elseif($index == 2)
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </td>
                                <td class="py-2">{{ $c->user->prenom ?? '' }} {{ $c->user->nom ?? '' }}</td>
                                <td class="py-2 hidden sm:table-cell">{{ $c->matricule }}</td>
                                <td class="text-right py-2 hidden md:table-cell">{{ $c->collectes_count ?? 0 }}</td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_recyclable ?? 0, 1) }}
                                </td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_organique ?? 0, 1) }}
                                </td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_residuel ?? 0, 1) }}
                                </td>
                                <td class="text-right py-2 font-bold text-emerald-600">
                                    {{ number_format($c->poids_total, 1) }}</td>
                                <td class="text-right py-2 hidden sm:table-cell">
                                    {{ number_format($c->collectes_sum_points_obtenus ?? 0) }}</td>
                                <td class="text-center py-2">
                                    <a href="{{ route('admin.statistiques.collecteurs', ['collecteur_id' => $c->id, 'annee' => $annee, 'mois' => $mois]) }}"
                                        class="text-blue-500 hover:text-blue-700 text-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-6 text-center text-gray-500">Aucune donnée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tableau complet des collecteurs -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="font-semibold text-gray-700 flex items-center gap-2">
                    <i class="fas fa-list text-emerald-500"></i> Tous les collecteurs
                    <span class="text-sm font-normal text-gray-500 ml-2">({{ $collecteurs->count() }})</span>
                </h3>
            </div>
            <div class="overflow-x-auto p-4">
                <table class="w-full min-w-[700px]">
                    <thead>
                        <tr class="border-b text-left text-sm text-gray-500">
                            <th class="py-2">Collecteur</th>
                            <th class="py-2 hidden sm:table-cell">Matricule</th>
                            <th class="text-right py-2 hidden md:table-cell">Collectes</th>
                            <th class="text-right py-2 hidden lg:table-cell">Recyclable</th>
                            <th class="text-right py-2 hidden lg:table-cell">Organique</th>
                            <th class="text-right py-2 hidden lg:table-cell">Résiduel</th>
                            <th class="text-right py-2">Total (kg)</th>
                            <th class="text-right py-2 hidden sm:table-cell">Points</th>
                            <th class="text-center py-2">Primes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collecteurs as $c)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2">{{ $c->user->prenom ?? '' }} {{ $c->user->nom ?? '' }}</td>
                                <td class="py-2 hidden sm:table-cell">{{ $c->matricule }}</td>
                                <td class="text-right py-2 hidden md:table-cell">{{ $c->collectes_count ?? 0 }}</td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_recyclable ?? 0, 1) }}</td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_organique ?? 0, 1) }}
                                </td>
                                <td class="text-right py-2 hidden lg:table-cell">
                                    {{ number_format($c->collectes_sum_poids_residuel ?? 0, 1) }}
                                </td>
                                <td class="text-right py-2 font-bold text-emerald-600">
                                    {{ number_format($c->poids_total, 1) }}</td>
                                <td class="text-right py-2 hidden sm:table-cell">
                                    {{ number_format($c->collectes_sum_points_obtenus ?? 0) }}
                                </td>
                                <td class="text-center py-2">
                                    <a href="{{ route('admin.primes.collecteur', $c->id) }}"
                                        class="text-purple-500 hover:text-purple-700 text-sm">
                                        <i class="fas fa-coins"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-6 text-center text-gray-500">Aucun collecteur</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
