<?php


namespace App\Services;


use App\Category;
use App\Vat;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

abstract class SellableService extends Service
{
    protected $validationRules = [
        "label"       => "required|string|max:128",
        "price"       => "required|numeric|min:0",
        "category_id" => "nullable|exists:categories,id",
        "vat_id"      => "nullable|exists:vats,id",
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

    public function setVat(Vat $vat): SellableService
    {
        if (!$vat->exists)
            throw  new ModelNotFoundException("VAT value does not exits");

        $this->getModel()->sellable->update(["vat_id" => $vat->id]);

        return $this;
    }

    public function getVat(): Vat
    {
        return $this->getModel()->sellable->vat;
    }
}
