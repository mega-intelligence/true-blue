<?php


namespace App\Services;


use App\Product;
use App\Sellable;
use Exception;
use Illuminate\Validation\ValidationException;

class ProductService extends SellableService
{
    /**
     * ProductService constructor.
     * @param Product|null $product
     */
    public function __construct(Product $product = null)
    {
        if ($product) {
            $this->setModel($product);
        }
    }

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return ProductService
     * @throws ValidationException
     * @throws Exception
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $product = Product::create($validated);

        if (!key_exists("vat_id", $validated)) {
            $defaultVat = (new VatService())->getDefaultVat();
            $validated["vat_id"] = $defaultVat->id;
        }

        $product->sellable()->create($validated);

        $this->setModel($product);

        return $this;
    }

    /**
     * @return ProductService
     * @throws Exception
     */
    public function delete(): Service
    {
        $this->modelOrFail();

        optional($this->getModel()->sellable)->delete();

        $this->getModel()->delete();

        return $this;
    }

    /**
     * @param array $attributes
     * @return ProductService
     * @throws ValidationException|Exception
     */
    public function update(array $attributes): Service
    {
        $this->modelOrFail();

        $validated = $this->validate($attributes);

        $product = $this->getModel();

        $product->update($validated);

        optional($product->sellable)->update($validated);

        return $this;
    }

    /**
     * returns the number of available products
     * @return int
     */
    public function count(): int
    {
        return Product::count();
    }
}
