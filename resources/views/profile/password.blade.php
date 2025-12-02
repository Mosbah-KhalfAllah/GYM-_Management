@extends('layouts.app')

@section('title', 'Changer le mot de passe')

@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Changer le mot de passe</h1>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
            <input type="password" name="current_password" class="mt-1 block w-full rounded border-gray-300">
            @error('current_password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
            <input type="password" name="password" class="mt-1 block w-full rounded border-gray-300">
            @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded border-gray-300">
        </div>

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Mettre à jour le mot de passe</button>
        </div>
    </form>
</div>
@endsection
