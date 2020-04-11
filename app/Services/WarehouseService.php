<?php


namespace App\Services;


use App\Exceptions\NegativeWarehouseQuantityException;
use App\Product;
use App\Warehouse;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\ValidationException;

class WarehouseService extends Service
{

    protected $validationRules = [
        "name" => "required|string|max:128",
    ];

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return WarehouseService
     * @throws ValidationException
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $warehouse = Warehouse::create($validated);

        $this->setModel($warehouse);

        return $this;
    }

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
     * @param int $quntity
     * @return WarehouseService
     * @throws Exception
     */
    public function addProduct(Product $product, int $quntity = 1): WarehouseService
    {
        $this->modelOrFail();

        $existingQuantity = $this->getProductQuantity($product);

        $this->getModel()->products()->syncwithoutdetaching([$product->id => ["quantity" => $existingQuantity + $quntity]]);

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
