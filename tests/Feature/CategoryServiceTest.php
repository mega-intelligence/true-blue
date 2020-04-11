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

        $this->expectException(ValidationException::class);

        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $this->categoryService->update(['category_id' => $category->id]);

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


    public function testNameIsRequired()
    {
        $this->expectException(ValidationException::class);

        $this->categoryService->validate([
            "name" => "",
        ]);
    }

    public function testCategoryIdPositive()
    {
        $this->expectException(ValidationException::class);

        $this->categoryService->validate([
            "name"        => "test",
            "category_id" => -1,
        ]);
    }

    public function testCategoryParentExists()
    {
        $this->expectException(ValidationException::class);

        $NON_EXISTING_CATEGORY_ID = 10;

        $this->categoryService->validate([
            "name"        => "test",
            "category_id" => $NON_EXISTING_CATEGORY_ID,
        ]);

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

    public function testAttachCategory()
    {
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $category2 = $this->categoryService->create([
            "name" => "Category 2",
        ])->getModel();

        $this->categoryService->for($category)->attachCategory($category2);

        $this->assertEquals($category2->category_id, $category->id);

        $this->assertNotNull($category2->category);

        $this->assertNotEquals(0, $category->categories->count());

        $this->categoryService->for($category2)->setAsRootCategory();

        $category2->refresh();

        $this->assertNull($category2->category);
    }

    public function testValidationRulesExistsForParentCategory()
    {
        $this->expectException(Exception::class);
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $category2 = $this->categoryService->create([
            "name" => "Category 2",
        ])->getModel();

        $this->categoryService->setValidationRules([]);

        $this->categoryService->setParentCategory($category);

        $this->categoryService = new CategoryService();
    }

    public function testCategoryExistsForParentCategory()
    {
        $this->expectException(ValidationException::class);
        $category = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $category2 = $this->categoryService->create([
            "name" => "Category 2",
        ])->getModel();
        $NON_EXISTING_CATEGORY_ID = 10;

        $category->id = $NON_EXISTING_CATEGORY_ID;

        $this->categoryService->setParentCategory($category);
    }
}
