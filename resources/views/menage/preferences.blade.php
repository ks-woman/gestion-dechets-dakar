@extends('layouts.menage')

@section('title', 'Mes préférences de collecte')

@section('content')
    <div class="bg-white rounded-xl shadow p-6">
        <h1 class="text-2xl font-bold mb-4"> Mes préférences de collecte</h1>

        <form method="POST" action="{{ route('menage.preferences.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Fréquence de collecte</label>
                <select name="frequence_collecte" id="frequence_collecte" class="w-full border rounded-lg p-2">
                    <option value="sur_demande" {{ auth()->user()->frequence_collecte == 'sur_demande' ? 'selected' : '' }}>À
                        la demande uniquement</option>
                    <option value="quotidienne" {{ auth()->user()->frequence_collecte == 'quotidienne' ? 'selected' : '' }}>
                        Tous les jours</option>
                    <option value="2x_semaine" {{ auth()->user()->frequence_collecte == '2x_semaine' ? 'selected' : '' }}>2
                        fois par semaine</option>
                    <option value="hebdomadaire"
                        {{ auth()->user()->frequence_collecte == 'hebdomadaire' ? 'selected' : '' }}>1 fois par semaine
                    </option>
                    <option value="bihebdomadaire"
                        {{ auth()->user()->frequence_collecte == 'bihebdomadaire' ? 'selected' : '' }}>1 fois toutes les 2
                        semaines</option>
                </select>
            </div>

            <!-- 2x par semaine -->
            <div id="2x_semaine_container" class="mb-6 hidden">
                <label class="block text-gray-700 mb-2">Jours de collecte</label>
                <div class="grid grid-cols-2 gap-2">
                    @php $joursActuels = json_decode(auth()->user()->jours_collecte, true) ?? []; @endphp
                    <label><input type="checkbox" name="jours[]" value="lundi"
                            {{ in_array('lundi', $joursActuels) ? 'checked' : '' }}> Lundi</label>
                    <label><input type="checkbox" name="jours[]" value="mardi"
                            {{ in_array('mardi', $joursActuels) ? 'checked' : '' }}> Mardi</label>
                    <label><input type="checkbox" name="jours[]" value="mercredi"
                            {{ in_array('mercredi', $joursActuels) ? 'checked' : '' }}> Mercredi</label>
                    <label><input type="checkbox" name="jours[]" value="jeudi"
                            {{ in_array('jeudi', $joursActuels) ? 'checked' : '' }}> Jeudi</label>
                    <label><input type="checkbox" name="jours[]" value="vendredi"
                            {{ in_array('vendredi', $joursActuels) ? 'checked' : '' }}> Vendredi</label>
                    <label><input type="checkbox" name="jours[]" value="samedi"
                            {{ in_array('samedi', $joursActuels) ? 'checked' : '' }}> Samedi</label>
                </div>
            </div>

            <!-- Hebdomadaire -->
            <div id="hebdomadaire_container" class="mb-6 hidden">
                <label class="block text-gray-700 mb-2">Jour de collecte</label>
                <select name="jour_hebdomadaire" class="w-full border rounded-lg p-2">
                    <option value="">Sélectionnez un jour</option>
                    <option value="lundi" {{ auth()->user()->jour_hebdomadaire == 'lundi' ? 'selected' : '' }}>Lundi
                    </option>
                    <option value="mardi" {{ auth()->user()->jour_hebdomadaire == 'mardi' ? 'selected' : '' }}>Mardi
                    </option>
                    <option value="mercredi" {{ auth()->user()->jour_hebdomadaire == 'mercredi' ? 'selected' : '' }}>
                        Mercredi</option>
                    <option value="jeudi" {{ auth()->user()->jour_hebdomadaire == 'jeudi' ? 'selected' : '' }}>Jeudi
                    </option>
                    <option value="vendredi" {{ auth()->user()->jour_hebdomadaire == 'vendredi' ? 'selected' : '' }}>
                        Vendredi</option>
                    <option value="samedi" {{ auth()->user()->jour_hebdomadaire == 'samedi' ? 'selected' : '' }}>Samedi
                    </option>
                </select>
            </div>

            <!-- Bihebdomadaire -->
            <div id="bihebdomadaire_container" class="mb-6 hidden">
                <label class="block text-gray-700 mb-2">Jour de collecte</label>
                <select name="jour_bihebdomadaire" class="w-full border rounded-lg p-2">
                    <option value="">Sélectionnez un jour</option>
                    <option value="lundi" {{ auth()->user()->jour_bihebdomadaire == 'lundi' ? 'selected' : '' }}>Lundi
                    </option>
                    <option value="mardi" {{ auth()->user()->jour_bihebdomadaire == 'mardi' ? 'selected' : '' }}>Mardi
                    </option>
                    <option value="mercredi" {{ auth()->user()->jour_bihebdomadaire == 'mercredi' ? 'selected' : '' }}>
                        Mercredi</option>
                    <option value="jeudi" {{ auth()->user()->jour_bihebdomadaire == 'jeudi' ? 'selected' : '' }}>Jeudi
                    </option>
                    <option value="vendredi" {{ auth()->user()->jour_bihebdomadaire == 'vendredi' ? 'selected' : '' }}>
                        Vendredi</option>
                    <option value="samedi" {{ auth()->user()->jour_bihebdomadaire == 'samedi' ? 'selected' : '' }}>Samedi
                    </option>
                </select>

                <label class="block text-gray-700 mb-2 mt-4">Semaine</label>
                <select name="semaine_type" class="w-full border rounded-lg p-2">
                    <option value="">Sélectionnez</option>
                    <option value="paire" {{ auth()->user()->semaine_type == 'paire' ? 'selected' : '' }}>Semaines paires
                    </option>
                    <option value="impaire" {{ auth()->user()->semaine_type == 'impaire' ? 'selected' : '' }}>Semaines
                        impaires</option>
                </select>
            </div>

            <button type="submit" class="bg-emerald-500 text-white px-4 py-2 rounded-lg"> Enregistrer mes
                préférences</button>
        </form>
    </div>

    <script>
        document.getElementById('frequence_collecte').addEventListener('change', function() {
            document.getElementById('2x_semaine_container').classList.add('hidden');
            document.getElementById('hebdomadaire_container').classList.add('hidden');
            document.getElementById('bihebdomadaire_container').classList.add('hidden');

            if (this.value === '2x_semaine') {
                document.getElementById('2x_semaine_container').classList.remove('hidden');
            } else if (this.value === 'hebdomadaire') {
                document.getElementById('hebdomadaire_container').classList.remove('hidden');
            } else if (this.value === 'bihebdomadaire') {
                document.getElementById('bihebdomadaire_container').classList.remove('hidden');
            }
        });

        // Déclencher au chargement
        document.getElementById('frequence_collecte').dispatchEvent(new Event('change'));
    </script>
@endsection
