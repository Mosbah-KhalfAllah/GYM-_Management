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
                                    @php
                                        $val = data_get($item, $col);
                                        $display = '';
                                        $displayHtml = null;

                                        // Detect timestamp-like columns
                                        $isTimeCol = preg_match('/(_at$|^check_in$|^check_out$|_time$)/', $col);

                                        if ($isTimeCol && $val) {
                                            try {
                                                $dt = \Carbon\Carbon::parse($val);
                                                $human = $dt->diffForHumans();
                                                $formatted = $dt->format('d/m/Y H:i');

                                                // color by column
                                                $bg = 'bg-gray-100 text-gray-800';
                                                if (str_contains($col, 'check_in')) $bg = 'bg-green-100 text-green-800';
                                                if (str_contains($col, 'check_out')) $bg = 'bg-red-100 text-red-800';
                                                if (str_contains($col, 'created_at')) $bg = 'bg-gray-50 text-gray-700';

                                                $displayHtml = '<span class="inline-flex items-baseline gap-2 px-2 py-1 rounded ' . $bg . '"><span class="text-sm font-medium">' . e($formatted) . '</span><span class="text-xs text-gray-500">(' . e($human) . ')</span></span>';
                                            } catch (\Exception $e) {
                                                $display = is_string($val) ? $val : json_encode($val);
                                            }
                                        } else {
                                            if (is_null($val)) {
                                                $display = '';
                                            } elseif (is_string($val) || is_numeric($val) || is_bool($val)) {
                                                $display = $val;
                                            } elseif (is_array($val)) {
                                                if (isset($val['first_name']) && isset($val['last_name'])) {
                                                    $display = $val['first_name'] . ' ' . $val['last_name'];
                                                } elseif (isset($val['name'])) {
                                                    $display = $val['name'];
                                                } else {
                                                    $display = json_encode($val);
                                                }
                                            } elseif (is_object($val)) {
                                                if (method_exists($val, 'full_name')) {
                                                    $display = $val->full_name();
                                                } elseif (isset($val->full_name)) {
                                                    $display = $val->full_name;
                                                } elseif (isset($val->first_name) && isset($val->last_name)) {
                                                    $display = $val->first_name . ' ' . $val->last_name;
                                                } elseif (isset($val->name)) {
                                                    $display = $val->name;
                                                } elseif (isset($val->email)) {
                                                    $display = ($val->first_name ?? '') . ' ' . ($val->last_name ?? '') . ' — ' . $val->email;
                                                } elseif (isset($val->id)) {
                                                    $display = 'ID: ' . $val->id;
                                                } else {
                                                    $display = json_encode($val);
                                                }
                                            } else {
                                                $display = json_encode($val);
                                            }
                                        }
                                    @endphp
                                    @if($displayHtml)
                                        <td class="px-4 py-3">{!! $displayHtml !!}</td>
                                    @else
                                        <td class="px-4 py-3">{{ $display }}</td>
                                    @endif
                                @endforeach
                            @else
                                <td class="px-4 py-3">{{ is_object($item) ? (string)$item : json_encode($item) }}</td>
                            @endif
                            <td class="px-4 py-3">
                                @php
                                    $isAttendance = isset($item->check_in) || array_key_exists('check_in', (array)$item);
                                    $memberId = null;
                                    if ($isAttendance) {
                                        $memberId = isset($item->user->id) ? $item->user->id : (data_get($item, 'user.id') ?? data_get($item, 'user_id'));
                                    }
                                @endphp

                                @if($isAttendance && $memberId)
                                    <a href="{{ route('admin.members.show', $memberId) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700 mr-2">
                                        <i class="fas fa-user mr-2"></i>Voir membre
                                    </a>

                                    <a href="{{ route('admin.attendance.record', ['member_id' => $memberId]) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700 mr-2">
                                        <i class="fas fa-door-open mr-2"></i>Enregistrer
                                    </a>

                                    @if(!empty($item->check_in) && empty($item->check_out))
                                        <button type="button" onclick="checkoutAttendance(this, '{{ route('admin.attendance.record') }}', {{ $memberId }})" class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Check-out
                                        </button>
                                    @endif
                                @else
                                    <a href="#" class="text-blue-600 mr-2">Voir</a>
                                    <a href="#" class="text-green-600 mr-2">Éditer</a>
                                    <a href="#" class="text-red-600">Supprimer</a>
                                @endif
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

@section('scripts')
    @parent
    <script>
        async function checkoutAttendance(button, url, userId) {
            if (!confirm('Enregistrer la sortie pour ce membre ?')) return;
            button.disabled = true;
            button.classList.add('opacity-60', 'cursor-not-allowed');

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ user_id: userId, action: 'check_out' })
                });

                const json = await res.json();
                if (json.success) {
                    // reload page to reflect change
                    location.reload();
                } else {
                    alert(json.error || json.message || 'Erreur lors de l\'opération');
                    button.disabled = false;
                    button.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            } catch (e) {
                alert('Erreur réseau: ' + e.message);
                button.disabled = false;
                button.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        }
    </script>
@endsection
