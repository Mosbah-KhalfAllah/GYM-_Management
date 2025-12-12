@extends('layouts.app')

@section('title', 'Changer le mot de passe')

@section('content')
<div class="container mx-auto py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-6">Changer le mot de passe</h1>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        @method('PATCH')

        <x-form-field
            name="current_password"
            label="Mot de passe actuel"
            type="password"
            required="true"
            placeholder="Entrez votre mot de passe actuel"
            :error="$errors->first('current_password')"
        />

        <x-form-field
            name="password"
            label="Nouveau mot de passe"
            type="password"
            required="true"
            minlength="8"
            title="Min. 8 caractères"
            placeholder="Min. 8 caractères"
            :error="$errors->first('password')"
        />

        <x-form-field
            name="password_confirmation"
            label="Confirmer le nouveau mot de passe"
            type="password"
            required="true"
            minlength="8"
            placeholder="Répétez le mot de passe"
        />

        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Mettre à jour le mot de passe</button>
        </div>
    </form>
</div>
@endsection

