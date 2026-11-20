<x-layout>
    <x-slot:title>Modifier un tag - Mini-CMS</x-slot:title>
    <x-page-header title="Modifier le tag" />
    <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="max-w-xl space-y-4">
        @csrf @method('PUT')
        <div><label for="name" class="block text-sm font-medium">Nom</label><input id="name" name="name" value="{{ old('name', $tag->name) }}" class="mt-1 w-full rounded-md border px-3 py-2">@error('name')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
        <div><label for="slug" class="block text-sm font-medium">Slug</label><input id="slug" name="slug" value="{{ old('slug', $tag->slug) }}" class="mt-1 w-full rounded-md border px-3 py-2">@error('slug')<p class="text-sm text-red-600">{{ $message }}</p>@enderror</div>
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-white">Mettre à jour</button>
    </form>
</x-layout>
