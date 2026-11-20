<x-layout>
    <x-slot:title>Tags - Mini-CMS</x-slot:title>
    <x-page-header title="Tags" subtitle="Gérez les tags des articles." />
    <a href="{{ route('admin.tags.create') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-white">Nouveau tag</a>
    <div class="mt-6 divide-y rounded-md border bg-white">
        @foreach ($tags as $tag)
            <div class="flex items-center justify-between px-4 py-3">
                <div><strong>{{ $tag->name }}</strong><span class="ml-2 text-sm text-gray-500">{{ $tag->posts_count }} articles</span></div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.tags.edit', $tag) }}" class="text-indigo-600">Modifier</a>
                    <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}" onsubmit="return confirm('Supprimer ce tag ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $tags->links() }}</div>
</x-layout>
