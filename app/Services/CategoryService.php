<?php


namespace App\Services;


use App\Exceptions\CanNotAssignCategoryAsParentToItSelfException;
use App\Models\Category;
use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class CategoryService extends Service
{
    protected $validationRules = [
        "name"        => "required|string|max:128",
        "category_id" => "nullable|different:id|exists:categories,id",
    ];

    protected $modelClass = Category::class;

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
     * @param Product $product
     * @return CategoryService
     * @throws Exception
     */
    public function attachProduct(Product $product): CategoryService
    {
        (new ProductService($product))->addToCategory($this->getModel());

        return $this;
    }

    /**
     * @param Product $product
     * @return CategoryService
     * @throws Exception
     */
    public function detachProduct(Product $product): CategoryService
    {
        (new ProductService($product))->clearCategory();

        return $this;
    }


    /**
     * Sets the parent category for the currently targeted category
     * @param Category $parentCategory
     * @return CategoryService
     * @throws ValidationException
     * @throws Exception
     */
    public function setParentCategory(?Category $parentCategory): CategoryService
    {
        $this->modelOrFail();

        if (is_null($parentCategory)) {
            $this->setAsRootCategory();
            return $this;
        }

        if ($this->getModel()->id === $parentCategory->id) {
            throw new CanNotAssignCategoryAsParentToItSelfException();
        }

        $parentCategory = $this->validateParentCategory($parentCategory);

        $this->getModel()->category_id = $parentCategory->id;

        $this->getModel()->save();

        return $this;
    }

    /**
     * Clears the parent category
     * @return CategoryService
     * @throws Exception
     */
    public function setAsRootCategory(): CategoryService
    {
        $this->modelOrFail();

        $this->getModel()->update(["category_id" => null]);

        return $this;
    }

    /**
     * @param Category $category
     * @return CategoryService
     * @throws ValidationException
     */
    public function attachCategory(Category $category): CategoryService
    {
        (new CategoryService($category))->setParentCategory($this->getModel());

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRootCategories(): ?Collection
    {
        return Category::whereNull('category_id')->ordered()->get();
    }

    /**
     * Validates only the category id
     * @param Category $parentCategory
     * @return Category
     * @throws ValidationException
     * @throws Exception
     */
    protected function validateParentCategory(Category $parentCategory): Category
    {
        $this->validationRulesOrFail();

        if (array_key_exists("category_id", $this->validationRules)) {
            $validator = validator()->make(["category_id" => $parentCategory->id], ["category_id" => $this->validationRules["category_id"]]);
            if ($validator->fails()) {
                throw new ValidationException($validator, null, $validator->errors());
            }

            return $parentCategory;
        }

        throw new Exception('No validation rules were specified for the "category_id" in the $validationRules');
    }
}
