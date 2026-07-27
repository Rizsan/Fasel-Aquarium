@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'bg-blue-600',    'text' => 'text-blue-600'],
        'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-600', 'text' => 'text-emerald-600'],
        'purple'  => ['bg' => 'bg-purple-50',  'icon' => 'bg-purple-600',  'text' => 'text-purple-600'],
        'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-500',   'text' => 'text-amber-600'],
    ];

    $c = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition">
    <div class="flex items-center justify-between mb-4">
        <p class="text-gray-500 text-sm font-medium">{{ $label }}</p>

        <div class="{{ $c['icon'] }} w-10 h-10 rounded-xl flex items-center justify-center shadow-sm">
            @if($icon)
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                </svg>
            @endif
        </div>
    </div>

    <p class="text-3xl font-extrabold text-gray-800 mb-1">{{ $value }}</p>

    @if($sub)
        <p class="text-xs {{ $c['text'] }} font-medium">{{ $sub }}</p>
    @endif
</div>