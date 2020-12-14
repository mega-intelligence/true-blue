@props(['category'])
<div class="px-3 py-1 border-l ">
    <div class="border rounded shadow p-2"><span class="text-gray-400">&ndash;</span> {{ $category->name }}</div>
    <div class="ml-5">
        @foreach($category->categories as $category)
            <x-category.recursive-list-item :category="$category"/>
        @endforeach
    </div>
</div>
