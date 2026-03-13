<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QuantityChange extends Component
{
    public int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getClasses(): string
    {
        return match (true) {
            $this->value > 0 => 'text-green-600 dark:text-green-400',
            $this->value < 0 => 'text-red-600 dark:text-red-400',
            default => 'text-gray-600 dark:text-gray-400',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.quantity-change');
    }
}
