<x-layout>
    <x-slot:title>Inscription - Mini-CMS</x-slot:title>
    <x-page-header title="Créer un compte" subtitle="Votre mot de passe est conservé uniquement sous forme chiffrée." />

    <form method="POST" action="{{ route('register.store') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium">Nom</label>
            <input id="name" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-md border px-3 py-2" required>
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border px-3 py-2" required>
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium">Mot de passe</label>
            <input id="password" type="password" name="password" class="mt-1 w-full rounded-md border px-3 py-2" required>
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium">Confirmation</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 w-full rounded-md border px-3 py-2" required>
        </div>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white">S'inscrire</button>
    </form>
</x-layout>
