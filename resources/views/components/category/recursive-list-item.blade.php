@props(['category'])
<div class="pl-4  py-1">
    <div class="border rounded shadow p-2 flex justify-between">
        <span>
            {{ $category->name }} <sup>({{ $category->id }})</sup>
        </span>
        <div class="flex items-center">
            <label for="parent-category" class="text-xs">
                <span>Parent category</span>
            </label>
            <select class="text-xs rounded border m-1 px-1 cursor-pointer" id="parent-category"
                    wire:change="changeParentCategory({{$category->id}},$event.target.value)">
                <option {{ is_null($category->category_id) ? 'selected' : '' }}
                        value="{{ \App\Models\Category::ROOT_CATEGORY_VALUE }}">- Root -
                </option>
                @foreach (App\Models\Category::all() as $inLoopCategory)
                    <option {{ $category->category_id === $inLoopCategory->id ? 'selected' : '' }}
                            value="{{ $inLoopCategory->id }}">{{ $inLoopCategory->name }}</option>
                @endforeach
            </select>

            @if (!$category->isLastInOrder())
                <button class="px-2 border rounded text-gray-100 bg-gray-800"
                        wire:click="changeCategoryOrder({{$category->id}}, '{{ App\Constants\OrderDirection::DOWN }}')">
                    &downarrow;
                </button>
            @endif

            @if (!$category->isFirstInOrder())
                <button class="px-2 border rounded text-gray-100 bg-gray-800"
                        wire:click="changeCategoryOrder({{$category->id}}, '{{ App\Constants\OrderDirection::UP }}')">
                    &uparrow;
                </button>
            @endif
        </div>
    </div>
    @foreach($category->categories as $subCategory)
        <x-category.recursive-list-item :category="$subCategory"/>
    @endforeach
</div>
