@php
    $user = auth()->user();
    $layout = $user->isAdmin()
        ? 'layouts.admin'
        : ($user->isCollecteur()
            ? 'layouts.collecteur'
            : ($user->isPartenaire()
                ? 'layouts.partenaire'
                : 'layouts.menage'));
@endphp
@extends($layout)

@section('title', 'Mes notifications')

@section('content')
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div
            class="bg-gradient-to-r from-emerald-600 to-emerald-800 px-6 py-6 text-white flex justify-between items-center flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="text-4xl"></div>
                <div>
                    <h1 class="text-2xl font-bold">Mes notifications</h1>
                    <p class="text-emerald-100 text-sm">{{ $nonLues ?? 0 }} non lue(s) sur {{ $notifications->total() }}</p>
                </div>
            </div>
            <div class="flex gap-2">
                @if ($nonLues > 0)
                    <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                        @csrf
                        <button type="submit"
                            class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm transition">
                            <i class="fas fa-check-double mr-1"></i> Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="p-6">
            @if ($notifications->count() > 0)
                <div class="space-y-3">
                    @foreach ($notifications as $notification)
                        <div
                            class="flex items-start justify-between p-4 bg-gray-50 rounded-lg border hover:shadow-md transition
                        {{ $notification->est_lu ? 'border-gray-200' : 'border-l-4 border-emerald-500' }}">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h3 class="font-semibold text-gray-800">{{ $notification->titre }}</h3>
                                    @if (!$notification->est_lu)
                                        <span
                                            class="bg-emerald-100 text-emerald-800 text-xs px-2 py-0.5 rounded-full">Nouveau</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                @if (!$notification->est_lu)
                                    <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 hover:text-emerald-800 text-sm"
                                            title="Marquer comme lu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                @if ($notification->lien)
                                    <a href="{{ $notification->lien }}" class="text-blue-500 hover:text-blue-700 text-sm"
                                        title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                                <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}"
                                    onsubmit="return confirm('Supprimer cette notification ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 text-sm"
                                        title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-3 block"></i>
                    <p class="text-lg font-medium">Aucune notification</p>
                    <p class="text-sm">Vous serez notifié dès qu’un événement important se produira.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
