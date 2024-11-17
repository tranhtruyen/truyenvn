<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Comment extends Component
{

    public $data;
    public $total;
    /**
     * Create a new component instance.
     */
    public function __construct($data, $total)
    {
        $this->data = $data;
        $this->total = $total;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.comment', ['data' => $this->data, 'total' => $this->total]);
    }
}
