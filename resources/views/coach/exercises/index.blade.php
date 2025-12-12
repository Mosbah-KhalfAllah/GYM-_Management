@extends('layouts.app')

@section('title', 'Exercices - Coach')
@section('page-title', 'Gestion des Exercices')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec statistiques -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Mes Exercices</h1>
                <p class="text-gray-600 mt-1">Gérez vos exercices d'entraînement</p>
            </div>
            <a href="{{ route('coach.exercises.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg">
                <i class="fas fa-plus mr-2"></i>Nouvel Exercice
            </a>
        </div>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Exercices</p>
                        <p class="text-2xl font-bold">{{ $exercises->total() }}</p>
                    </div>
                    <i class="fas fa-dumbbell text-2xl opacity-80"></i>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Programmes Actifs</p>
                        <p class="text-2xl font-bold">{{ $exercises->pluck('program')->unique('id')->count() }}</p>
                    </div>
                    <i class="fas fa-running text-2xl opacity-80"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="Rechercher un exercice..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select id="programFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les programmes</option>
                @foreach($exercises->pluck('program')->unique('id') as $program)
                    <option value="{{ $program->id }}">{{ $program->title }}</option>
                @endforeach
            </select>
            <select id="sortBy" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="name">Trier par nom</option>
                <option value="program">Trier par programme</option>
                <option value="created_at">Trier par date</option>
            </select>
        </div>
    </div>

    <!-- Liste des exercices -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        @if($exercises->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exercice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programme</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Séries/Reps</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poids</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jour</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="exerciseTableBody">
                        @foreach($exercises as $exercise)
                        <tr class="hover:bg-gray-50 transition-colors exercise-row" data-program="{{ $exercise->program->id }}" data-name="{{ strtolower($exercise->name) }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                        <i class="fas fa-dumbbell text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $exercise->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($exercise->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-running mr-1"></i>
                                    {{ $exercise->program->title }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-medium">{{ $exercise->sets }}</span> × <span class="font-medium">{{ $exercise->reps }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $exercise->weight ? $exercise->weight . ' kg' : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $exercise->rest_seconds }}s
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Jour {{ $exercise->day_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex items-center justify-center space-x-1">
                                    <a href="{{ route('coach.exercises.show', $exercise) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-600 p-2 rounded-lg transition-all duration-200 transform hover:scale-105" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('coach.exercises.edit', $exercise) }}" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-600 p-2 rounded-lg transition-all duration-200 transform hover:scale-105" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('coach.exercises.destroy', $exercise) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ Êtes-vous sûr de vouloir supprimer cet exercice ?\n\nCette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-600 p-2 rounded-lg transition-all duration-200 transform hover:scale-105" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $exercises->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">🏋️</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun exercice trouvé</h3>
                <p class="text-gray-600 mb-6">Commencez par créer votre premier exercice d'entraînement.</p>
                <a href="{{ route('coach.exercises.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Créer un exercice
                </a>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const programFilter = document.getElementById('programFilter');
    const sortBy = document.getElementById('sortBy');
    const tableBody = document.getElementById('exerciseTableBody');
    const rows = Array.from(document.querySelectorAll('.exercise-row'));

    function filterAndSort() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedProgram = programFilter.value;
        const sortCriteria = sortBy.value;

        // Filtrer les lignes
        let filteredRows = rows.filter(row => {
            const name = row.dataset.name;
            const program = row.dataset.program;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesProgram = !selectedProgram || program === selectedProgram;
            
            return matchesSearch && matchesProgram;
        });

        // Trier les lignes
        filteredRows.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortCriteria) {
                case 'name':
                    aValue = a.dataset.name;
                    bValue = b.dataset.name;
                    break;
                case 'program':
                    aValue = a.querySelector('.bg-blue-100').textContent.trim();
                    bValue = b.querySelector('.bg-blue-100').textContent.trim();
                    break;
                case 'created_at':
                    // Utiliser l'ordre original pour la date
                    return rows.indexOf(a) - rows.indexOf(b);
                default:
                    return 0;
            }
            
            return aValue.localeCompare(bValue);
        });

        // Masquer toutes les lignes
        rows.forEach(row => row.style.display = 'none');
        
        // Afficher les lignes filtrées et triées
        filteredRows.forEach(row => {
            row.style.display = '';
            tableBody.appendChild(row);
        });
    }

    searchInput.addEventListener('input', filterAndSort);
    programFilter.addEventListener('change', filterAndSort);
    sortBy.addEventListener('change', filterAndSort);
});
</script>
@endsection