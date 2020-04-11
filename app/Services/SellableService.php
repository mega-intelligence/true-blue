<?php


namespace App\Services;


use App\Category;
use Exception;

abstract class SellableService extends Service
{
    protected $validationRules = [
        "label"       => "required|string|max:128",
        "price"       => "required|numeric|min:0",
        "category_id" => "nullable|exists:categories,id",
    ];


    /**
     * @param Category $category
     * @return SellableService
     * @throws Exception
     */
    public function addToCategory(Category $category): SellableService
    {
        $this->modelOrFail();

        $this->getModel()->sellable()->update(["category_id" => $category->id]);

        return $this;
    }

    /**
     * @return SellableService
     * @throws Exception
     */
    public function clearCategory(): SellableService
    {
        $this->modelOrFail();

        $this->getModel()->sellable->update(["category_id" => null]);

        return $this;
    }
}
