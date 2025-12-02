@props([
    'name',
    'label',
    'type' => 'text',
    'value' => null,
    'required' => false,
    'placeholder' => '',
    'error' => null,
    'help' => '',
])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>

    @if($type === 'textarea')
        <textarea
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition
                @if($error)
                    border-red-500 focus:ring-red-500 focus:border-red-500
                @else
                    border-gray-300 focus:ring-blue-500 focus:border-blue-500
                @endif"
            rows="4"
        >{{ old($name, $value) }}</textarea>
    @elseif($type === 'select')
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required @endif
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition
                @if($error)
                    border-red-500 focus:ring-red-500 focus:border-red-500
                @else
                    border-gray-300 focus:ring-blue-500 focus:border-blue-500
                @endif"
        >
            <option value="">{{ $placeholder ?: 'Sélectionner une option' }}</option>
            {{ $slot }}
        </select>
    @else
        <input
            type="{{ $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ old($name, $value) }}"
            @if($required) required @endif
            placeholder="{{ $placeholder }}"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition
                @if($error)
                    border-red-500 focus:ring-red-500 focus:border-red-500
                @else
                    border-gray-300 focus:ring-blue-500 focus:border-blue-500
                @endif"
        />
    @endif

    @if($error)
        <p class="mt-2 text-sm text-red-600 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586l-6.687-6.687a1 1 0 00-1.414 1.414l8.1 8.1a1 1 0 001.414 0l8.1-8.1z" clip-rule="evenodd" />
            </svg>
            {{ $error }}
        </p>
    @elseif($help)
        <p class="mt-2 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
