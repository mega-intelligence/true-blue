<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Sellable;
use App\Services\ProductService;
use App\Services\VatService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    protected $vatService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = new ProductService();
        $this->vatService = new VatService();

        $this->seed();
    }

    public function testLabelIsRequired()
    {
        $this->expectException(ValidationException::class);

        $this->productService->validate([
            "label"    => "",
            "price"    => 10.0,
        ]);
    }

    public function testPriceIsPositive()
    {
        $this->expectException(ValidationException::class);

        $this->productService->validate([
            "label"    => "Product",
            "price"    => -1,
        ]);

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
        ])->count());
    }

    public function testSellableValues()
    {
        $product = $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
        ])->getModel();

        self::assertEquals("Product 1", $product->sellable->label);
        self::assertEquals(10.0, $product->sellable->price);

    }

    public function testDeleteProduct()
    {

        $product = $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
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

    public function testUpdateProduct()
    {
        $this->expectException(ValidationException::class);

        $product = $this->productService->create([
            "label"    => "Product 1",
            "price"    => 10.0,
        ])->getModel();

        $this->productService->update([
            "label"    => "Product 2",
            "price"    => 11.0,
        ]);

        self::assertEquals($product->sellable->label, "Product 2");
        self::assertEquals($product->sellable->price, 11.0);


        $this->productService->update([
            "label"    => "",
            "price"    => 11.0,
        ]);
    }

    public function testSetVatValue()
    {
        $this->expectException(ModelNotFoundException::class);

        $vat = $this->vatService->create(["value" => .7])->getModel();
        $this->productService->create([
            "label" => "Product 1",
            "price" => 10.0,
        ])->getModel();

        $this->productService->setVat($vat);

        $this->assertEquals($vat->id, $this->productService->getVat()->id);

        $this->productService->create([
            "label" => "Product 2",
            "price" => 10.0,
        ])->getModel();

        $vat->delete();

        $this->productService->setVat($vat);

    }
}
