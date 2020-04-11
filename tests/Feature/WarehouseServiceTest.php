<?php

namespace Tests\Feature;

use App\Exceptions\NegativeWarehouseQuantityException;
use App\Services\ProductService;
use App\Services\WarehouseService;
use App\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WarehouseServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var Warehouse
     */
    protected $warehouseService;

    /**
     * @var ProductService
     */
    protected $productService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->warehouseService = new WarehouseService();
        $this->productService = new ProductService();
    }

    public function testCreateWarehouse()
    {
        $initialCount = Warehouse::count();
        $this->warehouseService->create(["name" => "warehouse 1"]);
        $this->assertEquals($initialCount + 1, Warehouse::count());
    }

    public function testUpdateWarehouse()
    {
        $warehouse = $this->warehouseService->create(["name" => "warehouse 1"])->getModel();
        $this->warehouseService->update(["name" => "warehouse 1 updated"]);
        $warehouse->refresh();
        $this->assertEquals("warehouse 1 updated", $warehouse->name);
    }

    public function testNameIsRequired()
    {
        $this->expectException(ValidationException::class);
        $this->warehouseService->create(["name" => ""]);
    }

    public function testDeleteWarehouse()
    {
        $initialCount = Warehouse::count();
        $this->warehouseService->create(["name" => "warehouse 1"]);
        $this->warehouseService->delete();
        $this->assertEquals($initialCount, Warehouse::count());
    }

    public function testGetProductQuantity()
    {
        $product = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();

        $product2 = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();

        $warehouse = $this->warehouseService->create(["name" => "warehouse A"])->getModel();

        $warehouse->products()->syncwithoutdetaching([$product->id => ["quantity" => 12]]);

        $this->assertEquals(12, $this->warehouseService->getProductQuantity($product));

        $this->assertEquals(0, $this->warehouseService->getProductQuantity($product2));
    }

    public function testAddProductQuantity()
    {

        $product = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();

        $this->warehouseService->create(["name" => "warehouse A"])->getModel();

        $this->assertEquals(10, $this->warehouseService->addProduct($product, 10)->getProductQuantity($product));

        $this->assertEquals(20, $this->warehouseService->addProduct($product, 10)->getProductQuantity($product));
    }

    public function testRemoveProductQuantity()
    {

        $product = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();

        $this->warehouseService->create(["name" => "warehouse A"])->getModel();

        $this->assertEquals(10, $this->warehouseService->addProduct($product, 10)->getProductQuantity($product));

        $this->assertEquals(0, $this->warehouseService->removeProduct($product, 10)->getProductQuantity($product));
    }

    public function testNegativeQuantitiesConfiguration()
    {
        $this->expectException(NegativeWarehouseQuantityException::class);

        $product = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();

        $this->warehouseService->create(["name" => "warehouse A"])->getModel();

        Config::set("trueblue.allow_negative_warehouse_quantities", false);

        $this->assertEquals(-10, $this->warehouseService->removeProduct($product, 10)->getProductQuantity($product));
        $this->assertEquals(0, $this->warehouseService->addProduct($product, 10)->getProductQuantity($product));


        Config::set("trueblue.allow_negative_warehouse_quantities", true);
        $this->assertEquals(-10, $this->warehouseService->removeProduct($product, 10)->getProductQuantity($product));
    }

    public function testGetProducts()
    {
        $product = $this->productService->create(["label" => "Product 1", "price" => 10])->getModel();
        $product2 = $this->productService->create(["label" => "Product 2", "price" => 10])->getModel();

        $this->warehouseService->create(["name" => "warehouse A"])->getModel();

        $this->assertEquals(10, $this->warehouseService->addProduct($product, 10)->getProductQuantity($product));
        $this->assertEquals(0, $this->warehouseService->addProduct($product2, 0)->getProductQuantity($product2));

        $this->assertEquals(2, $this->warehouseService->getProducts()->count());
        $this->assertEquals(1, $this->warehouseService->getProducts(true)->count());
    }
}
