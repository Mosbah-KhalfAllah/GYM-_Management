@extends('layouts.app')

@section('title', 'Modifier le profil')

@section('content')
<div class="container mx-auto py-8 max-w-3xl">
    <h1 class="text-2xl font-bold mb-6">Modifier le profil</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénom</label>
                <input type="text" name="first_name" value="{{ old('first_name', $user->first_name ?? '') }}" class="mt-1 block w-full rounded border-gray-300">
                @error('first_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="last_name" value="{{ old('last_name', $user->last_name ?? '') }}" class="mt-1 block w-full rounded border-gray-300">
                @error('last_name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="mt-1 block w-full rounded border-gray-300">
            @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="mt-4 grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 block w-full rounded border-gray-300">
                @error('phone')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d') ?? '') }}" class="mt-1 block w-full rounded border-gray-300">
                @error('birth_date')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Adresse</label>
            <textarea name="address" class="mt-1 block w-full rounded border-gray-300">{{ old('address', $user->address ?? '') }}</textarea>
            @error('address')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('password.edit') }}" class="text-blue-600">Changer le mot de passe</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
        </div>
    </form>

    <hr class="my-6">

    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
        @csrf
        @method('DELETE')
        <div>
            <label class="block text-sm font-medium text-gray-700">Mot de passe (confirmation avant suppression)</label>
            <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300">
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Supprimer mon compte</button>
        </div>
    </form>
</div>
@endsection
