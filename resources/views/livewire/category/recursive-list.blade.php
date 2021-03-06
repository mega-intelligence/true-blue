<div class="p-4">
    <div class="flex justify-between mb-3">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-2">{{ __('Manage categories') }}</h2>
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <button class="px-2 border rounded text-gray-100 bg-gray-800" wire:click="createNewCategory()">
            {{ __('Create New') }} &plus;
        </button>
    </div>

    @foreach((new \App\Services\CategoryService())->getRootCategories() as $category)
        <x-category.recursive-list-item :category="$category"/>
    @endforeach
</div>
