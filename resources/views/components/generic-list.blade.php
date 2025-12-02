@php
    $items = $items ?? ($classes ?? $programs ?? $members ?? $coaches ?? $equipment ?? $payments ?? $challenges ?? $exercises ?? $attendances ?? $workouts ?? $progresses ?? null);
@endphp

@if(!$items)
    <div class="p-6">
        <p class="text-gray-600">Aucune donnée disponible pour l'instant.</p>
    </div>
@else
    @php
        $first = is_callable([$items, 'first']) ? $items->first() : (is_array($items) ? ($items[0] ?? null) : null);
        $rows = [];
        $columns = [];
        if ($first) {
            if (is_object($first) && method_exists($first, 'toArray')) {
                $columns = array_keys($first->toArray());
            } elseif (is_array($first)) {
                $columns = array_keys($first);
            }
        }
    @endphp

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow-sm">
            <thead class="bg-gray-100 text-sm text-gray-600">
                <tr>
                    @if(count($columns) > 0)
                        @foreach($columns as $col)
                            <th class="px-4 py-3 text-left">{{ ucwords(str_replace(['_','-'], ' ', $col)) }}</th>
                        @endforeach
                        <th class="px-4 py-3">Actions</th>
                    @else
                        <th class="px-4 py-3">Aucune colonne détectée</th>
                    @endif
                </tr>
            </thead>
            <tbody class="text-sm text-gray-800">
                @if(is_callable([$items, 'isEmpty']) ? !$items->isEmpty() : (is_array($items) && count($items) > 0))
                    @foreach($items as $item)
                        <tr class="border-t">
                            @if(count($columns) > 0)
                                @foreach($columns as $col)
                                    <td class="px-4 py-3">{{ data_get($item, $col) }}</td>
                                @endforeach
                            @else
                                <td class="px-4 py-3">{{ is_object($item) ? (string)$item : json_encode($item) }}</td>
                            @endif
                            <td class="px-4 py-3">
                                <!-- Actions placeholders -->
                                <a href="#" class="text-blue-600 mr-2">Voir</a>
                                <a href="#" class="text-green-600 mr-2">Éditer</a>
                                <a href="#" class="text-red-600">Supprimer</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="px-4 py-6 text-center text-gray-600" colspan="{{ max(1, count($columns) + 1) }}">Aucun enregistrement trouvé.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endif
