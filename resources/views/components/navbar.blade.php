<header class="bg-white border-b border-gray-200">
    <nav class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center gap-2" aria-label="Navigation principale">
        <a href="{{ route('home') }}" class="font-bold text-lg text-indigo-600 mr-4">Mini-CMS</a>

        <x-nav-link to="home">Accueil</x-nav-link>
        <x-nav-link to="posts.index" match="posts.*">Articles</x-nav-link>
        <x-nav-link to="time">Heure</x-nav-link>
        <x-nav-link to="about">À propos</x-nav-link>
        @auth
            @if (auth()->user()->isAdmin())
                <x-nav-link to="admin.categories.index">Catégories</x-nav-link>
                <x-nav-link to="admin.tags.index">Tags</x-nav-link>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="ml-auto">
                @csrf
                <button type="submit" class="rounded-md border px-3 py-1.5 text-sm hover:bg-gray-50">Déconnexion</button>
            </form>
        @else
            <x-nav-link to="login">Connexion</x-nav-link>
            <x-nav-link to="register">Inscription</x-nav-link>
        @endauth
    </nav>
</header>
