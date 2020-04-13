<?php

namespace Tests\Feature;

use App\Exceptions\NoDefaultVatException;
use App\Services\VatService;
use App\Vat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class VatServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $vatService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vatService = new VatService();

        $this->seed();
    }

    public function testGetDefaultVat()
    {
        $vat = $this->vatService->create(["value" => 0.3])->getModel();

        $this->vatService->setAsDefault();

        $default = $this->vatService->getDefaultVat();

        $this->assertEquals($vat->id, $default->id);
    }

    public function testCreateDefaultVat()
    {
        $initialCount = Vat::count();

        $this->vatService->create(["value" => .3, "is_default" => true]);

        self::assertEquals($initialCount + 1, Vat::count());

        self::assertEquals($this->vatService->getModel()->id, $this->vatService->getDefaultVat()->id);

        $this->assertEquals(1, Vat::whereIsDefault(true)->count());
    }

    public function testUpdateVat()
    {
        $initialCount = Vat::count();

        $this->vatService->create(["value" => .3, "is_default" => true])->getModel();

        self::assertEquals($initialCount + 1, Vat::count());

        $this->vatService->update(["value" => .4]);

        self::assertEquals(0.4, $this->vatService->getModel()->value);

        $this->vatService->update(["value" => .4, "is_default" => true]);

        self::assertEquals($this->vatService->getModel()->id, $this->vatService->getDefaultVat()->id);

        $this->assertEquals(1, Vat::whereIsDefault(true)->count());
    }

    public function testValidateVatValue()
    {
        $this->expectException(ValidationException::class);
        $this->vatService->validate(["value" => 2]);
    }

    public function testDefaultVatExists()
    {
        $this->expectException(NoDefaultVatException::class);
        $vat = $this->vatService->create(["value" => .3, "is_default" => true])->getModel();

        $vat->is_default = false;
        $vat->save();

        $this->vatService->getDefaultVat();
    }
}
