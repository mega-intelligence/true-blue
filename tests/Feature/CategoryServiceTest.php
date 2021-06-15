<?php

namespace Tests\Feature;

use App\Exceptions\CanNotAssignCategoryAsParentToItSelfException;
use App\Models\Category;
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

    public function testSetsCategoryAsRootWhenNoParentIsProvided()
    {
        $rootCategoryService = $this->categoryService->create([
            "name" => "Category 1",
        ]);

        $this->categoryService = new CategoryService();

        $childCategoryService = $this->categoryService->create([
            "name" => "Category 2",
        ]);

        $childCategoryService->setParentCategory($rootCategoryService->getModel());

        self::assertEquals($rootCategoryService->getModel()->id, $childCategoryService->getModel()->category_id);

        $childCategoryService->setParentCategory(null);

        self::assertNull($childCategoryService->getModel()->category_id);
    }

    public function testCanNotAssignCategoryAsParentOfItSelf()
    {
        $this->expectException(CanNotAssignCategoryAsParentToItSelfException::class);

        $rootCategoryService = $this->categoryService->create([
            "name" => "Category 1",
        ]);

        $rootCategoryService->setParentCategory($rootCategoryService->getModel());

    }

    public function testGetAllRootCategories()
    {
        $rootCategory1 = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $rootCategory2 = $this->categoryService->create([
            "name" => "Category 2",
        ])->getModel();

        $this->categoryService = new CategoryService();

        $childCategoryService = $this->categoryService->create([
            "name" => "Category 3",
        ]);

        $this->categoryService->setParentCategory($rootCategory1);

        $this->assertEquals(2, $this->categoryService->getRootCategories()->count());

    }

    public function testDeleteAndKeepChildrenAsRootCategories()
    {
        $rootCategory = $this->categoryService->create([
            "name" => "Category 1",
        ])->getModel();

        $firstChildCategory = $this->categoryService->create([
            "name" => "Category 1.1",
        ])->getModel();

        $secondLevelChildCategory = $this->categoryService->create([
            "name" => "Category 1.1.1",
        ])->getModel();

        $this->categoryService->for($firstChildCategory)->setParentCategory($rootCategory);
        $this->categoryService->for($secondLevelChildCategory)->setParentCategory($firstChildCategory);

        $this->categoryService->for($firstChildCategory)->deleteAndKeepChildren();

        $secondLevelChildCategory = Category::find($secondLevelChildCategory->id);

        self::assertEquals($rootCategory->id, $secondLevelChildCategory->category_id);

        $this->categoryService->for($rootCategory)->deleteAndKeepChildren();

        $secondLevelChildCategory = Category::find($secondLevelChildCategory->id);

        self::assertNull($secondLevelChildCategory->category_id);
    }
}
