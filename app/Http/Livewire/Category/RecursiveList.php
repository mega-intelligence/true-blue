<?php

namespace App\Http\Livewire\Category;

use App\Constants\OrderDirection;
use App\Models\Category;
use App\Services\CategoryService;
use Livewire\Component;

class RecursiveList extends Component
{
    public $message = '';

    public function render()
    {
        return view('livewire.category.recursive-list');
    }

    public function changeParentCategory(Category $category, $newParentCategory, CategoryService $categoryService)
    {
        $newParentCategory = Category::find($newParentCategory);

        $categoryService->for($category)->setParentCategory($newParentCategory);
    }

    public function changeCategoryOrder(Category $category, string $direction)
    {
        switch ($direction) {
            case OrderDirection::UP:
                $category->moveOrderUp();
                break;
            case OrderDirection::DOWN:
                $category->moveOrderDown();
                break;
        }

        $this->message = "moving $direction";
    }

    public function saveCategoryNameChange(Category $category, $newName, CategoryService $categoryService)
    {
        if ($category->name === $newName) {
            return;
        }

        $categoryService->for($category)->update(['name' => $newName]);
        $category->fresh();
        $this->message = "changed category {$category->id} name to {$category->name} " . time();
        $this->emit('saved');
    }

    public function createNewCategory(CategoryService $categoryService)
    {
        $categoryService->create(['name' => config('trueblue.default_new_category_name')]);

        $categoryService->getModel()->moveToStart();

        $this->emit('saved');
    }

    public function removeCategory(Category $category, CategoryService $categoryService)
    {
        $categoryService->for($category)->deleteAndKeepChildren();

        $this->emit('saved');
    }
}
