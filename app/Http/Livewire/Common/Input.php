<?php

namespace App\Http\Livewire\Common;

use App\Services\Service;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Validation\Validator;
use Livewire\Component;

class Input extends Component
{
    public $type = 'text';
    public $name = null;
    public $value = '';
    public $service = null;

    public function mount($name = '', $value = '', $type = 'text', $service)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->service = $service;
    }

    public function updated($field, $value)
    {
        $rules = ['value' => app()->make($this->service)->getValidationRules()[ $this->name ]];
        $this->validate($rules);
    }

    public function render()
    {
        return view('livewire.common.input');
    }

}
