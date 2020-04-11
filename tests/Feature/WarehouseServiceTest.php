<?php

namespace Tests\Feature;

use App\Services\WarehouseService;
use App\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class WarehouseServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var Warehouse
     */
    protected $warehouseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->warehouseService = new WarehouseService();
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
}
