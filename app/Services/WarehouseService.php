<?php


namespace App\Services;


use App\Warehouse;
use Illuminate\Validation\ValidationException;

class WarehouseService extends Service
{

    protected $validationRules = [
        "name" => "required|string|max:128",
    ];

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return WarehouseService
     * @throws ValidationException
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $warehouse = Warehouse::create($validated);

        $this->setModel($warehouse);

        return $this;
    }

}
