<x-layout>
    <x-slot:title>Catégories - Mini-CMS</x-slot:title>
    <x-page-header title="Catégories" subtitle="Gérez les catégories des articles." />
    <a href="{{ route('admin.categories.create') }}" class="inline-block rounded-md bg-indigo-600 px-4 py-2 text-white">Nouvelle catégorie</a>
    <div class="mt-6 divide-y rounded-md border bg-white">
        @foreach ($categories as $category)
            <div class="flex items-center justify-between px-4 py-3">
                <div><strong>{{ $category->name }}</strong><span class="ml-2 text-sm text-gray-500">{{ $category->posts_count }} articles</span></div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600">Modifier</a>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Supprimer cette catégorie ?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600" type="submit">Supprimer</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $categories->links() }}</div>
</x-layout>
