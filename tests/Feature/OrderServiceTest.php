<?php

namespace Tests\Feature;

use App\Exceptions\OrderReferenceAlreadyGeneratedException;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $orderService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->orderService = new OrderService();

        $this->seed();
    }

    public function testOrderCreation()
    {
        $order = $this->orderService->create([])->getModel();

        $this->assertTrue($order->is_draft);

        $this->assertNotNull($order->reference);
    }

    public function testRegenerateReference()
    {
        $this->expectException(OrderReferenceAlreadyGeneratedException::class);

        $order = $this->orderService->create([])->getModel();

        $this->orderService = new OrderService($order);

        $this->orderService->generateReference();
    }
}
