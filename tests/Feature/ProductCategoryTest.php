<?php

namespace Tests\Feature;

use App\Services\CategoryService;
use App\Services\ProductService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CategoryService
     */
    protected $categoryService;

    /**
     * @var ProductService
     */
    protected $productService;


    protected function setUp(): void
    {
        parent::setUp();

        $this->productService = new ProductService();

        $this->categoryService = new CategoryService();

    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws Exception
     */
    public function testAttachAndDetachProductToCategory()
    {
        $this->productService->create([
            'label'    => 'Product 1',
            'price'    => 10.00,
        ]);

        $this->categoryService->create(["name" => "Category 1"]);

        $this->categoryService->attachProduct($this->productService->getModel());

        self::assertEquals($this->categoryService->getModel()->id, $this->productService->getModel()->sellable->category_id);

        $this->categoryService->detachProduct($this->productService->getModel());

        self::assertNull($this->productService->getModel()->sellable->category);
    }

    public function testNullWhenCategoryDeleted()
    {
        $this->productService->create([
            'label' => 'Product 1',
            'price' => 10.00,
        ]);

        $this->categoryService->create(["name" => "Category 1"]);

        $this->categoryService->attachProduct($this->productService->getModel());

        self::assertEquals($this->categoryService->getModel()->id, $this->productService->getModel()->sellable->category_id);

        $this->categoryService->delete();

        self::assertNull($this->productService->getModel()->sellable->category);
    }
}
