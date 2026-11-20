<x-layout>
    <x-slot:title>Connexion - Mini-CMS</x-slot:title>
    <x-page-header title="Connexion" subtitle="Accédez aux fonctions réservées aux membres." />

    <form method="POST" action="{{ route('login.store') }}" class="max-w-md space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-md border px-3 py-2" required>
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium">Mot de passe</label>
            <input id="password" type="password" name="password" class="mt-1 w-full rounded-md border px-3 py-2" required>
        </div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" value="1">
            <span class="text-sm">Se souvenir de moi</span>
        </label>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white">Se connecter</button>
    </form>
</x-layout>
