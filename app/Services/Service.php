<?php


namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Exception;

abstract class Service
{
    /**
     * @var Model|null
     */
    private $model = null;

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
     */
    abstract public function create(array $attributes): Service;

    /**
     * updates currently selected model
     * @param array $attributes
     * @return Service
     * @throws Exception
     */
    public function update(array $attributes): Service
    {
        $this->modelOrFail();

        $this->model->update($attributes);

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
     * Checks if a model is selected before any model specific operation, if not, it throws an exception
     * @throws Exception
     */
    private function modelOrFail()
    {
        if (is_null($this->model))
            throw new Exception('Model for this service is not set, use for($model) or setModel($model) to set a target model.');
    }
}
