<?php

namespace App\Http\Livewire\Common;

use Livewire\Component;

class Input extends Component
{
    public $text = 0;

    public function render()
    {
        return view('livewire.input');
    }

    public function goUp()
    {
        $this->text++;
    }
}
