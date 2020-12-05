<?php


namespace App\Services;


use App\Exceptions\NegativeWarehouseQuantityException;
use App\Models\Product;
use App\Models\Warehouse;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class WarehouseService extends Service
{

    protected $validationRules = [
        "name" => "required|string|max:128",
    ];

    protected $modelClass = Warehouse::class;


    /**
     * Gets the available quantity of a product in the selected warehouse
     * @param Product $product
     * @return int
     * @throws Exception
     */
    public function getProductQuantity(Product $product): int
    {
        $this->modelOrFail();

        $warehouse = $this->getModel();

        $warehouseProduct = $warehouse->products()->where("product_id", $product->id)->first();

        if (!$warehouseProduct)
            return 0;

        return $warehouseProduct->pivot->quantity;
    }

    /**
     * Adds a product quantity to the selected warehouse
     * @param Product $product
     * @param int $quantity
     * @return WarehouseService
     * @throws Exception
     */
    public function addProduct(Product $product, int $quantity = 1): WarehouseService
    {
        $this->modelOrFail();

        $existingQuantity = $this->getProductQuantity($product);

        $this->getModel()->products()->syncwithoutdetaching([$product->id => ["quantity" => $existingQuantity + $quantity]]);

        return $this;
    }

    /**
     * Removes a quantity of a product from the selected warehouse
     * @param Product $product
     * @param int $quntity
     * @return WarehouseService
     * @throws NegativeWarehouseQuantityException if negative quantities is not allowed
     * @throws Exception
     */
    public function removeProduct(Product $product, int $quntity = 1): WarehouseService
    {
        $this->modelOrFail();

        $existingQuantity = $this->getProductQuantity($product);

        if (!config("trueblue.allow_negative_warehouse_quantities") && $existingQuantity < $quntity)
            throw new NegativeWarehouseQuantityException();

        $this->getModel()->products()->syncwithoutdetaching([$product->id => ["quantity" => $existingQuantity - $quntity]]);

        return $this;
    }

    /**
     * Gets the list of all the product in the warehouse
     * @param bool $availableOnly
     * @return Collection|null
     * @throws Exception
     */
    public function getProducts(bool $availableOnly = false): ?Collection
    {
        $this->modelOrFail();

        $query = $this->getModel()->products();

        if ($availableOnly) {
            $query->wherePivot("quantity", ">", 0);
        }

        return $query->get();
    }
}
