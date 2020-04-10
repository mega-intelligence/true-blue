<?php


namespace App\Services;


use App\Category;
use Illuminate\Validation\ValidationException;

class CategoryService extends Service
{
    protected $validationRules = [
        "name"        => "required|string|max:128",
        "category_id" => "nullable|exists:categories,id",
    ];

    /**
     * CategoryService constructor.
     * @param Category|null $category
     */
    public function __construct(Category $category = null)
    {
        if ($category) {
            $this->setModel($category);
        }
    }

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return CategoryService
     * @throws ValidationException
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $category = Category::create($validated);

        $this->setModel($category);

        return $this;
    }
}
