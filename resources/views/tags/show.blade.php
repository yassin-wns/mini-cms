<x-layout>
    <x-slot:title>Tag : {{ $tag->name }} - Mini-CMS</x-slot:title>

    <x-page-header
        :title="'Tag : ' . $tag->name"
        subtitle="Articles publiés portant ce tag."
    />

    @if ($posts->isEmpty())
        <x-alert type="info" title="Aucun article">
            Aucun article publié avec le tag <strong>{{ $tag->name }}</strong> pour l'instant.
        </x-alert>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>
        <div class="mt-8">{{ $posts->links() }}</div>
    @endif
</x-layout>
