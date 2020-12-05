<?php


namespace App\Services;


use App\Exceptions\OrderReferenceAlreadyGeneratedException;
use App\Models\Order;
use Illuminate\Validation\ValidationException;

class OrderService extends Service
{
    protected $validationRules = [
        "is_draft" => "nullable|boolean",
    ];

    /**
     * OrderService constructor.
     * @param Order|null $order
     */
    public function __construct(Order $order = null)
    {
        if ($order) {
            $this->setModel($order);
        }

    }

    /**
     * Creates and selects a new model
     * @param array $attributes
     * @return Service
     * @throws ValidationException
     * @throws OrderReferenceAlreadyGeneratedException
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $newOrder = Order::create($validated);

        $this->setModel($newOrder);

        $this->generateReference();

        return $this;
    }

    /**
     * Generates a new reference for newly created order, and prevents regenerating a new one if already exists
     * @return OrderService
     * @throws OrderReferenceAlreadyGeneratedException
     */
    public function generateReference(): OrderService
    {
        $this->modelOrFail();

        if ($this->getModel()->reference !== null)
            throw new OrderReferenceAlreadyGeneratedException();

        $randomReference = substr(strrev(md5($this->getModel()->id . time())), 0, 10);

        $this->getModel()->reference = $randomReference;

        $this->getModel()->save();

        $this->getModel()->refresh();

        return $this;
    }
}
