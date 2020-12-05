<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

abstract class Service
{
    /**
     * @var Model|null
     */
    private $model = null;

    /**
     * @var array|null
     */
    protected $validationRules = null;

    protected $modelClass = null;

    /**
     * Alias for the setModel function
     * @param Model $model
     * @return Service
     * @see Service::setModel()
     */
    public function for(Model $model): Service
    {
        return $this->setModel($model);
    }

    /**
     * selects a model for further model specific operations
     * @param Model $model
     * @return Service
     */
    public function setModel(Model $model): Service
    {
        $this->model = $model;

        return $this;
    }

    /**
     * get the currently selected model
     * @return Model|null
     */
    public function getModel(): ?Model
    {
        return $this->model;
    }

    /**
     * creates and selects a new model
     * @param array $attributes
     * @return Service
     * @throws ValidationException
     * @throws Exception
     */
    public function create(array $attributes): Service
    {
        $this->modelClassOrFail();

        $validated = $this->validate($attributes);

        $model = resolve($this->modelClass);

        $this->setModel($model->create($validated));

        return $this;
    }

    /**
     * updates currently selected model
     * @param array $attributes
     * @return Service
     * @throws Exception
     */
    public function update(array $attributes): Service
    {
        $this->modelOrFail();

        $validated = $this->validate($attributes);

        $this->model->update($validated);

        return $this;
    }

    /**
     * deletes the currently selected model
     * @return Service
     * @throws Exception
     */
    public function delete(): Service
    {
        $this->modelOrFail();

        $this->model->delete();

        return $this;
    }

    /**
     * @param array $attributes
     * @return array
     * @throws ValidationException|Exception
     */
    public function validate(array $attributes): array
    {
        $this->validationRulesOrFail();

        $validator = validator()->make($attributes, $this->validationRules);

        if ($validator->fails())
            throw new ValidationException($validator, null, $validator->errors());

        return $validator->validated();
    }

    /**
     * @return array|null
     */
    public function getValidationRules(): ?array
    {
        return $this->validationRules;
    }

    /**
     * @param array|null $validationRules
     */
    public function setValidationRules(?array $validationRules): void
    {
        $this->validationRules = $validationRules;
    }

    /**
     * extends the validation rules that were defined in the parent class
     * @param array $otherValidationRules
     * @return Service
     */
    public function appendValidationRules(array $otherValidationRules): Service
    {
        if (is_null($this->validationRules)) {
            $this->validationRules = $otherValidationRules;
            return $this;
        }

        $this->setValidationRules(array_merge($this->validationRules, $otherValidationRules));

        return $this;
    }

    /**
     * Checks if a model is selected before any model specific operation, if not, it throws an exception
     * @throws Exception
     */
    protected function modelOrFail()
    {
        if (is_null($this->model))
            throw new Exception('Model for this service is not set, use for($model) or setModel($model) to set a target model.');
    }

    /**
     * Checks if a model is selected before any model specific operation, if not, it throws an exception
     * @throws Exception
     */
    protected function modelClassOrFail()
    {
        if (is_null($this->modelClass))
            throw new Exception('ModelClass for this service is not set, Set the protected property $modelClass to the full class name for the targeted model');
    }

    /**
     * Checks if there is any validation rules defined for the current model
     * @throws Exception
     */
    protected function validationRulesOrFail()
    {
        if (is_null($this->validationRules))
            throw new Exception('No validation rules has been defined, before validation attempt, override "protected $validationRules" or
             setValidationRules(array $validationRules) or initialize it explicitly with empty array');
    }
}
