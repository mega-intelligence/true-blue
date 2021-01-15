<div class="p-4">
    <p>
        wire message: {{ $message }}
    </p>
    @foreach((new \App\Services\CategoryService())->getRootCategories() as $category)
        <x-category.recursive-list-item :category="$category"/>
    @endforeach
</div>
