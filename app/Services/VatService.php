<?php


namespace App\Services;


use App\Exceptions\NoDefaultVatException;
use App\Vat;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VatService extends Service
{
    protected $validationRules = [
        "value"      => "required|numeric|min:0|max:1",
        "is_default" => "nullable|boolean",
    ];

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return VatService
     * @throws ValidationException
     */
    public function create(array $attributes): Service
    {
        $validated = $this->validate($attributes);

        $vat = Vat::create($validated);

        $this->setModel($vat);

        if (array_key_exists("is_default", $validated) && $validated["is_default"] === true) {
            $this->setAsDefault();
        }

        return $this;
    }

    /**
     * @param array $attributes
     * @return Service
     * @throws Exception
     */
    public function update(array $attributes): Service
    {
        parent::update($attributes);

        if (array_key_exists("is_default", $attributes) && $attributes["is_default"] === true) {
            $this->setAsDefault();
        }

        return $this;
    }

    /**
     * sets the selected VAT as default value
     * @return VatService
     * @throws Exception
     */
    public function setAsDefault(): VatService
    {
        $this->modelOrFail();

        DB::table("vats")->update(["is_default" => false]);

        $vat = $this->getModel();

        $vat->refresh();

        $vat->is_default = true;

        $vat->save();


        return $this;
    }

    /**
     * returns the default Vat or throw exception
     * @return Vat
     * @throws NoDefaultVatException
     */
    public function getDefaultVat(): Vat
    {
        $defaultVat = Vat::where("is_default", true)->first();

        if (!$defaultVat) {
            throw new NoDefaultVatException();
        }

        return $defaultVat;
    }

}
