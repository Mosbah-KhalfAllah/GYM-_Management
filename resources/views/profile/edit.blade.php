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
            <x-form-field
                name="first_name"
                label="Prénom"
                :value="$user->first_name ?? ''"
                required="true"
                pattern="^[a-zA-ZÀ-ÿ\s'\-]+$"
                maxlength="100"
                title="Le prénom ne doit contenir que des lettres"
                :error="$errors->first('first_name')"
            />

            <x-form-field
                name="last_name"
                label="Nom"
                :value="$user->last_name ?? ''"
                required="true"
                pattern="^[a-zA-ZÀ-ÿ\s'\-]+$"
                maxlength="100"
                title="Le nom ne doit contenir que des lettres"
                :error="$errors->first('last_name')"
            />
        </div>

        <x-form-field
            name="email"
            label="Email"
            type="email"
            :value="$user->email ?? ''"
            required="true"
            maxlength="255"
            title="Adresse email valide"
            :error="$errors->first('email')"
        />

        <div class="mt-4 grid grid-cols-2 gap-4">
            <x-form-field
                name="phone"
                label="Téléphone"
                type="tel"
                :value="$user->phone ?? ''"
                pattern="^[\d\s\+\-\(\)]+$"
                maxlength="20"
                placeholder="06 12 34 56 78"
                :error="$errors->first('phone')"
            />

            <x-form-field
                name="birth_date"
                label="Date de naissance"
                type="date"
                :value="optional($user->birth_date)->format('Y-m-d') ?? ''"
                :max="now()->subYears(10)->format('Y-m-d')"
                min="1920-01-01"
                :error="$errors->first('birth_date')"
                help="L'age minimum requis est 10 ans, date minimale: 01-01-1920"
            />
        </div>

        <x-form-field
            name="address"
            label="Adresse"
            type="textarea"
            :value="$user->address ?? ''"
            maxlength="1000"
            :error="$errors->first('address')"
        />

        <div class="mt-4 flex items-center justify-between">
            <a href="{{ route('password.edit') }}" class="text-blue-600">Changer le mot de passe</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
        </div>
    </form>

    <hr class="my-6">

    <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer votre compte ?');">
        @csrf
        @method('DELETE')
        <x-form-field
            name="password"
            label="Mot de passe (confirmation avant suppression)"
            type="password"
            required="true"
            :error="$errors->first('password')"
        />
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Supprimer mon compte</button>
        </div>
    </form>
</div>
@endsection

