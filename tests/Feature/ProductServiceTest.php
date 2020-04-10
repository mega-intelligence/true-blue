<?php

namespace Tests\Feature;

use App\Product;
use App\Sellable;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ProductService
     */
    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = new ProductService();

    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws ValidationException
     */
    public function testProductValidation()
    {

        self::assertIsArray($this->productService->validate([
            "label"    => "Product 1",
            "price"    => 10.0,
            "quantity" => 0,
        ]));

        try {
            $this->productService->validate([
                "label"    => "",
                "price"    => 10.0,
                "quantity" => 0,
            ]);
            self::assertTrue(false, "false positive! invalid label passed the test.");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }

        try {
            $this->productService->validate([
                "label"    => "Product",
                "price"    => -1,
                "quantity" => 0,
            ]);

            self::assertTrue(false, "false positive! invalid price passed the test.");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }

        try {
            $this->productService->validate([
                "label"    => "Product",
                "price"    => -1,
                "quantity" => "text",
            ]);

            self::assertTrue(false, "false positive! invalid quantity passed the test.");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }
    }

    /**
     * @throws ValidationException
     */
    public function testProductCreation()
    {
        $count = Product::count();

        self::assertEquals($count + 1, $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
            "quantity" => 2,
        ])->count());
    }

    public function testSellableValues()
    {
        $product = $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
            "quantity" => 2,
        ])->getModel();

        self::assertEquals("Product 1", $product->sellable->label);
        self::assertEquals(10.0, $product->sellable->price);
        self::assertEquals(2, $product->quantity);

    }

    public function testDeleteProduct()
    {

        $product = $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
            "quantity" => 2,
        ])->getModel();
        $sellable = $product->sellable;

        $sellableId = $sellable->id;
        $productId = $product->id;

        self::assertEquals($sellableId, Sellable::find($sellableId)->id);
        self::assertEquals($productId, product::find($productId)->id);

        $this->productService->delete();

        self::assertNull(Sellable::find($sellableId));
        self::assertNull(Product::find($productId));
    }
}
