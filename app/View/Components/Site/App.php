<?php

namespace App\View\Components\Site;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class App extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $currentPage = '',
        public bool $forcedScrolled = false,
    ) {}

    public function render(): View|Closure|string
    {
        return view('site.layouts.app');
    }
}
