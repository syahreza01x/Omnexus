<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;
    public string $type; // 'transaction' or 'action'

    public function __construct(string $status, string $type = 'transaction')
    {
        $this->status = $status;
        $this->type = $type;
    }

    public function getClasses(): string
    {
        if ($this->type === 'action') {
            return match ($this->status) {
                'added' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                'removed' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                default => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            };
        }

        // transaction type
        return match ($this->status) {
            'completed' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
            'pending' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
            'paid' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
            'processing' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
            'shipped' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
            'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
