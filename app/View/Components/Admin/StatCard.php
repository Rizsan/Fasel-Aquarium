<?php

namespace App\View\Components\Admin;

use Illuminate\View\Component;
use Illuminate\View\View;

class StatCard extends Component
{
    public function __construct(
        public string     $label,
        public int|string $value,
        public ?string    $icon = null, // ✅ tidak wajib
        public string     $color = 'blue',
        public string     $sub   = '',
    ) {
        // ✅ default icon kalau tidak dikirim
        $this->icon = $this->icon ?? 'M5 13l4 4L19 7';
    }

    public function colorClasses(): array
    {
        return match ($this->color) {
            'emerald' => ['icon' => 'bg-emerald-600', 'text' => 'text-emerald-600'],
            'purple'  => ['icon' => 'bg-purple-600',  'text' => 'text-purple-600'],
            'amber'   => ['icon' => 'bg-amber-500',   'text' => 'text-amber-600'],
            default   => ['icon' => 'bg-blue-600',    'text' => 'text-blue-600'],
        };
    }

    public function render(): View
    {
        return view('components.admin.stat-card');
    }
}