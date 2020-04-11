<?php

namespace Tests\Feature;

use App\Category;
use App\Services\CategoryService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{

    use RefreshDatabase;

    /**
     * @var CategoryService
     */
    protected $categoryService;


    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryService = new CategoryService();

    }

    public function testCategoryCanNotBeParentToItSelf()
    {
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        try {

            $this->categoryService->update(['category_id' => $category->id]);

            self::assertTrue(false, "true negative! a category can not be parent to it self");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }

    }

    public function testDeleteCategory()
    {
        $categoryId = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel()->id;

        self::assertEquals($categoryId, Category::find($categoryId)->id);

        $this->categoryService->delete();

        self::assertNull(Category::find($categoryId));
    }

    public function testCategoryUpdate()
    {
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $this->categoryService->update(['name' => "Category 1 altered"]);

        self::assertEquals($category->name, "Category 1 altered");
    }

    /**
     * @throws ValidationException
     */
    public function testCategoryCreation()
    {
        $initialCategoryCount = Category::count();
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        self::assertNotNull($category);

        self::assertEquals($initialCategoryCount + 1, Category::count());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     * @throws Exception
     */
    public function testCategoryValidation()
    {
        try {
            $this->categoryService->validate([
                "name" => "",
            ]);

            self::assertTrue(false, "false positive! invalid name passed the test.");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }

        try {
            $this->categoryService->validate([
                "name"        => "test",
                "category_id" => -1,
            ]);

            self::assertTrue(false, "false positive! invalid name passed the test.");
        } catch (ValidationException $e) {
            self::assertTrue(true);
        }


        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        try {
            $this->categoryService->validate([
                "name"        => "test",
                "category_id" => $category->id,
            ]);

            self::assertTrue(true);
        } catch (ValidationException $e) {
            self::assertTrue(false, "false positive! a valid category was not accepted as parent category.");
        }
    }

    public function testParentCategories()
    {

        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $category2 = $this->categoryService->create([
            "name" => "Category 2",
        ])->getModel();

        $this->categoryService->setParentCategory($category);

        $this->assertEquals($category2->category_id, $category->id);

        $this->assertNotNull($category2->category);

        $this->assertNotEquals(0, $category->categories->count());

        $this->categoryService->setAsRootCategory();

        $category2->refresh();

        $this->assertNull($category2->category);

    }
}
