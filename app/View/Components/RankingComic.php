<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RankingComic extends Component
{
    private $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function render(): View|Closure|string
    {
        return view('components.ranking-comic', ['data' => $this->data]);
    }
}
