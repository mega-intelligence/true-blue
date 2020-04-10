<?php


namespace App\Services;


use App\Product;
use App\Sellable;
use Exception;
use Illuminate\Validation\ValidationException;

class ProductService extends Service
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

        $this->validationRules = [
            "label"    => "required|string|max:128",
            "price"    => "required|numeric|min:0",
            "quantity" => "nullable|int",
        ];
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

        $product = Product::create($attributes);

        $product->sellable()->create($attributes);

        $this->setModel($product);

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
